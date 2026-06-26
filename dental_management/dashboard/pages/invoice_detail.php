<?php
require_once __DIR__ . "/../../api/db.php";

$id = (int)($_GET['id'] ?? 0);

/* ===============================
   1. LẤY HÓA ĐƠN
================================ */
$stmt = $pdo->prepare("
    SELECT
        i.invoice_id,
        i.invoice_date,
        i.total_amount,
        i.status,
        p.full_name,
        p.phone,
        i.record_id
    FROM invoices i
    JOIN patients p ON i.patient_id = p.patient_id
    WHERE i.invoice_id = ?
");
$stmt->execute([$id]);
$invoice = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$invoice) {
    echo "<p>Không tìm thấy hóa đơn</p>";
    return;
}

/* ===============================
   2. DỊCH VỤ
================================ */
$services = $pdo->prepare("
    SELECT
        s.service_name,
        d.quantity,
        d.price,
        (d.quantity * d.price) AS total
    FROM invoice_details d
    JOIN services s ON d.service_id = s.service_id
    WHERE d.invoice_id = ?
");
$services->execute([$id]);
$service_items = $services->fetchAll(PDO::FETCH_ASSOC);

/* ===============================
   3. THUỐC
================================ */
$meds = $pdo->prepare("
    SELECT
        m.medicine_name,
        pi.dosage,
        pi.frequency,
        pi.duration,
        m.price
    FROM prescription_items pi
    JOIN prescriptions p ON pi.prescription_id = p.prescription_id
    JOIN medicines m ON pi.medicine_id = m.medicine_id
    WHERE p.record_id = ?
");
$meds->execute([$invoice['record_id']]);
$medicine_items = $meds->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="page-header">
    <h2>🧾 Hóa đơn #<?= $invoice['invoice_id'] ?></h2>
    <a href="dashboard.php?page=invoices" class="btn-add">← Quay lại</a>
</div>

<div class="medical-layout">

    <!-- ===== THÔNG TIN ===== -->
    <div class="medical-card">
        <p><b>Bệnh nhân:</b> <?= htmlspecialchars($invoice['full_name']) ?></p>
        <p><b>SĐT:</b> <?= htmlspecialchars($invoice['phone']) ?></p>
        <p><b>Ngày lập:</b> <?= date('d/m/Y', strtotime($invoice['invoice_date'])) ?></p>
        <p><b>Trạng thái:</b> <?= htmlspecialchars($invoice['status']) ?></p>
    </div>

    <!-- ===== DỊCH VỤ ===== -->
    <div class="medical-card full">
        <h3>📋 Dịch vụ</h3>

        <?php if ($service_items): ?>
        <table>
            <thead>
                <tr>
                    <th>Dịch vụ</th>
                    <th>Số lượng</th>
                    <th>Giá</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($service_items as $s): ?>
                <tr>
                    <td><?= htmlspecialchars($s['service_name']) ?></td>
                    <td><?= $s['quantity'] ?></td>
                    <td><?= number_format($s['price']) ?> đ</td>
                    <td><?= number_format($s['total']) ?> đ</td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
            <p>Không có dịch vụ</p>
        <?php endif; ?>
    </div>

    <!-- ===== THUỐC ===== -->
    <div class="medical-card full">
        <h3>💊 Thuốc sử dụng</h3>

        <?php if ($medicine_items): ?>
        <table>
            <thead>
                <tr>
                    <th>Thuốc</th>
                    <th>Liều</th>
                    <th>Lần/ngày</th>
                    <th>Số ngày</th>
                    <th>Giá</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($medicine_items as $m): ?>
                <tr>
                    <td><?= htmlspecialchars($m['medicine_name']) ?></td>
                    <td><?= htmlspecialchars($m['dosage']) ?></td>
                    <td><?= htmlspecialchars($m['frequency']) ?></td>
                    <td><?= htmlspecialchars($m['duration']) ?></td>
                    <td><?= number_format($m['price']) ?> đ</td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
            <p>Không có thuốc</p>
        <?php endif; ?>
    </div>

    <!-- ===== TỔNG TIỀN ===== -->
    <div class="medical-card full" style="text-align:right">
        <h3>
            💰 Tổng cộng: <?= number_format($invoice['total_amount']) ?> đ
        </h3>

        <?php if ($invoice['status'] === 'Chưa thanh toán'): ?>
            <a class="btn-add"
               href="dashboard.php?page=invoice_pay&id=<?= $invoice['invoice_id'] ?>">
               💳 Thanh toán
            </a>
        <?php endif; ?>
    </div>

</div>
