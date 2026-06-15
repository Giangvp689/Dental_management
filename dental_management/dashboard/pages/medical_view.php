<?php
require_once __DIR__ . "/../../api/db.php";
require_once __DIR__ . "/../../dental_login/auth.php";

requireRole(['ADMIN', 'DOCTOR']);

$user     = $_SESSION['user'];
$user_id  = (int)$user['user_id'];
$role     = $user['role'];

$doctor_id = $user_id; // QUY ƯỚC: doctor_id = user_id

$action     = $_POST['action'] ?? '';
$patient_id = (int)($_GET['patient_id'] ?? 0);
$record_id  = (int)($_GET['record_id'] ?? 0);

/* ==================================================
   1. LẤY / TẠO BỆNH ÁN
================================================== */

if ($record_id > 0) {

    // Lấy bệnh án theo record_id
    $stmt = $pdo->prepare("SELECT * FROM medical_records WHERE record_id=?");
    $stmt->execute([$record_id]);
    $record = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$record) {
        die("Bệnh án không tồn tại");
    }

    // Bác sĩ chỉ được xem bệnh án của mình
    if ($role === 'DOCTOR' && (int)$record['doctor_id'] !== $doctor_id) {
        die("Bệnh án đang do bác sĩ khác phụ trách");
    }

    $patient_id = (int)$record['patient_id'];

} elseif ($patient_id > 0) {

    // Kiểm tra xem bệnh nhân đã có bệnh án đang khám chưa
    $stmt = $pdo->prepare("
        SELECT * FROM medical_records
        WHERE patient_id=? AND status='Đang khám'
        LIMIT 1
    ");
    $stmt->execute([$patient_id]);
    $record = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($record) {
        // Nếu đang khám bởi bác sĩ khác
        if ($role === 'DOCTOR' && (int)$record['doctor_id'] !== $doctor_id) {
            die("Bệnh nhân đang được bác sĩ khác khám");
        }
    } else {
        // Tạo bệnh án mới
        $stmt = $pdo->prepare("
            INSERT INTO medical_records (patient_id, doctor_id, visit_date, status)
            VALUES (?, ?, CURDATE(), 'Đang khám')
        ");
        $stmt->execute([$patient_id, $doctor_id]);

        $record_id = $pdo->lastInsertId();

        $stmt = $pdo->prepare("SELECT * FROM medical_records WHERE record_id=?");
        $stmt->execute([$record_id]);
        $record = $stmt->fetch(PDO::FETCH_ASSOC);
    }

} else {
    die("Thiếu thông tin bệnh nhân");
}

$is_locked = ($record['status'] === 'Hoàn tất');

/* ==================================================
   2. ACTION
================================================== */

if ($is_locked && $action) {
    die("Bệnh án đã hoàn tất, không thể chỉnh sửa");
}

/* ---- Thêm dịch vụ ---- */
if ($action === 'add_service') {
    $pdo->prepare("
        INSERT INTO medical_services (record_id, service_id, quantity)
        VALUES (?,?,?)
    ")->execute([
        $record['record_id'],
        $_POST['service_id'],
        (int)($_POST['quantity'] ?? 1)
    ]);
}

/* ---- Thêm thuốc ---- */
if ($action === 'add_medicine') {

        $quantity = (int)$_POST['quantity'];

    $pdo->beginTransaction();
        try {
                // Kiểm tra tồn kho
                $stmt = $pdo->prepare("SELECT stock FROM medicines WHERE medicine_id=?");
                $stmt->execute([$_POST['medicine_id']]);
                $stock = (int)$stmt->fetchColumn();

                if ($stock < $quantity) {
                    throw new Exception("Thuốc không đủ tồn kho");
                }

                // Lấy hoặc tạo đơn thuốc
                $stmt = $pdo->prepare("
                    SELECT prescription_id FROM prescriptions WHERE record_id=?
                ");
                $stmt->execute([$record['record_id']]);
                $prescription_id = $stmt->fetchColumn();

                if (!$prescription_id) {
                    $pdo->prepare("
                        INSERT INTO prescriptions (record_id) VALUES (?)
                    ")->execute([$record['record_id']]);
                    $prescription_id = $pdo->lastInsertId();
                }

                // Thêm thuốc
                $pdo->prepare("
                    INSERT INTO prescription_items
                    (prescription_id, medicine_id, dosage, frequency, duration, quantity)
                    VALUES (?,?,?,?,?,?)
                ")->execute([
                    $prescription_id,
                    $_POST['medicine_id'],
                    $_POST['dosage'],
                    $_POST['frequency'],
                    $_POST['duration'],
                    $quantity
                ]);

                // Trừ kho
                $pdo->prepare("
                    UPDATE medicines SET stock = stock - ? WHERE medicine_id=?
                ")->execute([$quantity, $_POST['medicine_id']]);

        $pdo->commit();

    } catch (Exception $e) {
        $pdo->rollBack();
        echo "<script>alert('".$e->getMessage()."')</script>";
    }
}

/* ---- Hoàn tất khám ---- */
if ($action === 'finish') {

    $pdo->beginTransaction();
    try {
        // Cập nhật bệnh án
        $pdo->prepare("
            UPDATE medical_records
            SET diagnosis=?, treatment_plan=?, status='Hoàn tất'
            WHERE record_id=?
        ")->execute([
            $_POST['diagnosis'],
            $_POST['treatment_plan'],
            $record['record_id']
        ]);

        // Tạo hóa đơn
        $pdo->prepare("
            INSERT INTO invoices (patient_id, record_id, invoice_date, status)
            VALUES (?, ?, CURDATE(), 'Chưa thanh toán')
        ")->execute([
            $record['patient_id'],
            $record['record_id']
        ]);

        $invoice_id = $pdo->lastInsertId();
        $total = 0;

        // Dịch vụ
        $stmt = $pdo->prepare("
            SELECT ms.quantity, s.price, ms.service_id
            FROM medical_services ms
            JOIN services s ON ms.service_id=s.service_id
            WHERE ms.record_id=?
        ");
        $stmt->execute([$record['record_id']]);

        foreach ($stmt as $s) {
            $line = $s['price'] * $s['quantity'];
            $total += $line;

            $pdo->prepare("
                INSERT INTO invoice_details
                (invoice_id, service_id, quantity, price)
                VALUES (?,?,?,?)
            ")->execute([
                $invoice_id,
                $s['service_id'],
                $s['quantity'],
                $s['price']
            ]);
        }

        // Thuốc
        $stmt = $pdo->prepare("
            SELECT pi.quantity, m.price
            FROM prescription_items pi
            JOIN prescriptions p ON pi.prescription_id=p.prescription_id
            JOIN medicines m ON pi.medicine_id=m.medicine_id
            WHERE p.record_id=?
        ");
        $stmt->execute([$record['record_id']]);

        foreach ($stmt as $m) {
            $total += $m['price'] * $m['quantity'];
        }

        $pdo->prepare("
            UPDATE invoices SET total_amount=? WHERE invoice_id=?
        ")->execute([$total, $invoice_id]);

        $pdo->commit();

        header("Location: dashboard.php?page=invoices&invoice_id=".$invoice_id);
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        die("Lỗi hoàn tất khám: ".$e->getMessage());
    }
}

/* ---- Xóa dịch vụ ---- */
if ($action === 'delete_service') {
    $pdo->prepare("
        DELETE FROM medical_services
        WHERE id=? AND record_id=?
    ")->execute([
        $_POST['id'],
        $record['record_id']
    ]);
}

/* ---- Xóa thuốc ---- */
if ($action === 'delete_medicine') {

    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare("
            SELECT medicine_id, quantity
            FROM prescription_items
            WHERE item_id=?
        ");
        $stmt->execute([$_POST['item_id']]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$item) {
            throw new Exception("Thuốc không tồn tại");
        }

        $pdo->prepare("
            UPDATE medicines SET stock = stock + ?
            WHERE medicine_id=?
        ")->execute([$item['quantity'], $item['medicine_id']]);

        $pdo->prepare("
            DELETE FROM prescription_items WHERE item_id=?
        ")->execute([$_POST['item_id']]);

        $pdo->commit();

    } catch (Exception $e) {
        $pdo->rollBack();
        echo "<script>alert('".$e->getMessage()."')</script>";
    }
}

/* ==================================================
   3. LOAD DATA
================================================== */

$stmt = $pdo->prepare("
    SELECT 
        m.*, 
        p.full_name AS patient,
        p.gender,
        p.dob,
        u.full_name AS doctor
    FROM medical_records m
    JOIN patients p ON m.patient_id=p.patient_id
    JOIN users u ON m.doctor_id=u.user_id
    WHERE m.record_id=?
");
$stmt->execute([$record['record_id']]);
$record = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$record) {
    die("Không tải được thông tin bệnh án");
}

$services = $pdo->query("SELECT * FROM services")->fetchAll(PDO::FETCH_ASSOC);
$medicines = $pdo->query("
    SELECT medicine_id, medicine_name, stock FROM medicines
")->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("
    SELECT ms.*, s.service_name, s.price
    FROM medical_services ms
    JOIN services s ON ms.service_id=s.service_id
    WHERE ms.record_id=?
");
$stmt->execute([$record['record_id']]);
$used_services = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("
    SELECT pi.*, m.medicine_name
    FROM prescription_items pi
    JOIN prescriptions p ON pi.prescription_id=p.prescription_id
    JOIN medicines m ON pi.medicine_id=m.medicine_id
    WHERE p.record_id=?
");
$stmt->execute([$record['record_id']]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<div class="page-header">
    <h2>🩺 Bệnh án khám chữa bệnh</h2>
    <a href="dashboard.php?page=medical" class="btn-add">← Quay lại</a>
</div>

<?php if ($is_locked): ?>
<div class="medical-card full" style="background:#fef3c7;color:#92400e">
    🔒 Bệnh án đã hoàn tất – chỉ xem
</div>
<?php endif; ?>

<div class="medical-layout">

    <!-- ===== THÔNG TIN BỆNH NHÂN ===== -->
    <div class="medical-card">
        <h3>👤 Bệnh nhân</h3>
        <p><b><?= htmlspecialchars($record['patient']) ?></b></p>
        <p>Giới tính: <?= $record['gender'] ?></p>
        <p>Ngày sinh: <?= date('d/m/Y', strtotime($record['dob'])) ?></p>
        <p>Bác sĩ: <b><?= htmlspecialchars($record['doctor']) ?></b></p>
    </div>

    <!-- ===== CHẨN ĐOÁN + ĐIỀU TRỊ ===== -->
    <?php if (!$is_locked): ?>
            <form method="post" class="medical-card full" onsubmit="return confirm('Hoàn tất khám?')">
                <?php else: ?>
                <div class="medical-card full">
                <?php endif; ?>

                    <h3>📝 Chẩn đoán</h3>
                    <textarea name="diagnosis" rows="4" <?= $is_locked?'readonly':'' ?> required>
                    <?= htmlspecialchars($record['diagnosis']) ?>
                    </textarea>

                    <h3 style="margin-top:15px">📋 Phác đồ điều trị</h3>
                    <textarea name="treatment_plan" rows="4" <?= $is_locked?'readonly':'' ?> required>
                    <?= htmlspecialchars($record['treatment_plan']) ?>
                    </textarea>

                <?php if (!$is_locked): ?>
            </form> 
        <?php else: ?>
        </div>
    <?php endif; ?>

    <!-- ===== DỊCH VỤ ===== -->
    <div class="medical-card full">
        <h3>🦷 Dịch vụ sử dụng</h3>

        <?php if (!$is_locked): ?>
        <form method="post" style="display:flex;gap:10px;flex-wrap:wrap">
            <select name="service_id">
                <?php foreach ($services as $s): ?>
                    <option value="<?= $s['service_id'] ?>">
                        <?= htmlspecialchars($s['service_name']) ?> (<?= number_format($s['price']) ?>đ)
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="number" name="quantity" value="1" min="1" style="width:80px">
            <button name="action" value="add_service" class="btn-add">+ Thêm</button>
        </form>
        <?php endif; ?>

        <?php if ($used_services): ?>
        <table class="medical-table" style="margin-top:15px">
            <tr>
                 <th>Dịch vụ</th>
                <th>Số lượng</th>
                <th>Đơn giá</th>
                <?php if (!$is_locked): ?><th></th><?php endif; ?>
            </tr>
                <?php foreach ($used_services as $us): ?>
                <tr>
                    <td><?= htmlspecialchars($us['service_name']) ?></td>
                    <td><?= $us['quantity'] ?></td>
                    <td><?= number_format($us['price']) ?>đ</td>

                    <?php if (!$is_locked): ?>
                    <td>
                        <form method="post" style="display:inline"
                            onsubmit="return confirm('Xóa dịch vụ này?')">
                            <input type="hidden" name="id" value="<?= $us['id'] ?>">
                            <button name="action" value="delete_service"
                                    class="btn-add"
                                    style="background:#dc2626">❌</button>
                        </form>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>

        </table>
        <?php endif; ?>
    </div>

    <!-- ===== ĐƠN THUỐC ===== -->
    <div class="medical-card full">
        <h3>💊 Đơn thuốc</h3>

        <?php if (!$is_locked): ?>
        <form method="post"
            style="display:grid;
                    grid-template-columns:2fr 1fr 1fr 1fr 1fr auto;
                    gap:10px">

            <select name="medicine_id" required>
                <?php foreach ($medicines as $m): ?>
                <option value="<?= $m['medicine_id'] ?>">
                    <?= htmlspecialchars($m['medicine_name']) ?>
                    (Tồn: <?= $m['stock'] ?>)
                </option>
                <?php endforeach; ?>
            </select>

            <input name="dosage" placeholder="Liều" required>
            <input name="frequency" placeholder="Lần/ngày" required>
            <input name="duration" placeholder="Số ngày" required>

            <input type="number" name="quantity" min="1" value="1" required>

            <button name="action" value="add_medicine" class="btn-add">+</button>
        </form>

        <?php endif; ?>

        <?php if ($items): ?>
        <table class="medical-table" style="margin-top:15px">
            <tr>
                <th>Thuốc</th>
                <th>Liều</th>
                <th>Lần/ngày</th>
                <th>Số ngày</th>
                <th>Số lượng</th>
                <?php if (!$is_locked): ?><th></th><?php endif; ?>
            </tr>

            <?php foreach ($items as $i): ?>
            <tr>
                <td><?= htmlspecialchars($i['medicine_name']) ?></td>
                <td><?= htmlspecialchars($i['dosage']) ?></td>
                <td><?= htmlspecialchars($i['frequency']) ?></td>
                <td><?= htmlspecialchars($i['duration']) ?></td>
                <td><?= (int)$i['quantity'] ?></td>

                <?php if (!$is_locked): ?>
                <td>
                    <form method="post" style="display:inline"
                        onsubmit="return confirm('Xóa thuốc này?')">
                        <input type="hidden" name="item_id" value="<?= $i['item_id'] ?>">
                        <button name="action" value="delete_medicine"
                                class="btn-add"
                                style="background:#dc2626">❌</button>
                    </form>
                </td>
                <?php endif; ?>
            </tr>
            <?php endforeach; ?>

        </table>
        <?php endif; ?>
        
                    <?php if (!$is_locked): ?>
                    <div style="text-align:right;margin-top:15px">
                        <button name="action" value="finish" class="btn-add">
                            ✔ Hoàn tất khám & lưu bệnh án
                        </button>
                    </div>
                    <?php endif; ?>

    </div>

</div>
