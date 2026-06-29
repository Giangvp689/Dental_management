<?php
require_once __DIR__ . "/../../api/db.php";

$id = $_GET['id'] ?? 0;

// lấy lịch hẹn
$stmt = $pdo->prepare("SELECT * FROM appointment_requests WHERE request_id=?");
$stmt->execute([$id]);
$r = $stmt->fetch();
if (!$r) die("Không tìm thấy lịch");

// danh sách bác sĩ & dịch vụ
$doctors  = $pdo->query("
    SELECT doctor_id, full_name 
    FROM doctors 
    WHERE status='Hoạt động'
")->fetchAll();

$services = $pdo->query("
    SELECT service_id, service_name 
    FROM services
")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("
        UPDATE appointment_requests
        SET full_name=?, phone=?, appointment_date=?, appointment_time=?,
            doctor_id=?, service_id=?, status=?
        WHERE request_id=?
    ");
    $stmt->execute([
        $_POST['full_name'],
        $_POST['phone'],
        $_POST['appointment_date'],
        $_POST['appointment_time'],
        $_POST['doctor_id'],
        $_POST['service_id'],
        $_POST['status'],
        $id
    ]);

    header("Location: dashboard.php?page=appointments");
    exit;
}
?>

<div class="form-box">
    <h3>Sửa lịch hẹn</h3>

    <form method="post">
        <label>Họ tên</label>
        <input name="full_name" value="<?= htmlspecialchars($r['full_name']) ?>" required>

        <label>Số điện thoại</label>
        <input name="phone" value="<?= $r['phone'] ?>" required>

        <!-- 🔹 THÊM DỊCH VỤ (GIỮ STYLE CŨ) -->
        <label>Dịch vụ</label>
        <select name="service_id" required>
            <option value="">-- Chọn dịch vụ --</option>
            <?php foreach ($services as $s): ?>
                <option value="<?= $s['service_id'] ?>"
                    <?= ($r['service_id'] == $s['service_id']) ? 'selected' : '' ?>>
                    <?= $s['service_name'] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- 🔹 THÊM BÁC SĨ (GIỮ STYLE CŨ) -->
        <label>Bác sĩ</label>
        <select name="doctor_id" required>
            <option value="">-- Chọn bác sĩ --</option>
            <?php foreach ($doctors as $d): ?>
                <option value="<?= $d['doctor_id'] ?>"
                    <?= ($r['doctor_id'] == $d['doctor_id']) ? 'selected' : '' ?>>
                    <?= $d['full_name'] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Ngày hẹn</label>
        <input type="date" name="appointment_date" value="<?= $r['appointment_date'] ?>">

        <label>Giờ hẹn</label>
        <input type="time" name="appointment_time" value="<?= $r['appointment_time'] ?>">

        <label>Trạng thái</label>
        <select name="status">
            <?php foreach (['Chờ xác nhận','Đã xác nhận','Hủy'] as $st): ?>
                <option <?= ($r['status'] === $st) ? 'selected' : '' ?>>
                    <?= $st ?>
                </option>
            <?php endforeach; ?>
        </select>

        <br><br>
        <button class="btn-add">Lưu thay đổi</button>
    </form>
</div>
