<?php
require_once __DIR__ . "/../../api/db.php";

$record_id = (int)($_GET['record_id'] ?? 0);
if (!$record_id) die("Thiếu bệnh án");

/* ==== BỆNH ÁN ==== */
$stmt = $pdo->prepare("
    SELECT 
        m.*, 
        p.full_name AS patient_name,
        p.gender, 
        p.phone,
        u.full_name AS doctor_name
    FROM medical_records m
    JOIN patients p ON m.patient_id = p.patient_id
    JOIN users u ON m.doctor_id = u.user_id
    WHERE m.record_id = ?
");
$stmt->execute([$record_id]);
$record = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$record) {
    die('Bệnh án không tồn tại');
}

/* ==== DỊCH VỤ ==== */
$services = $pdo->prepare("
    SELECT s.service_name, ms.quantity, s.price
    FROM medical_services ms
    JOIN services s ON ms.service_id = s.service_id
    WHERE ms.record_id = ?
");
$services->execute([$record_id]);
$services = $services->fetchAll();

/* ==== THUỐC ==== */
$medicines = $pdo->prepare("
    SELECT m.medicine_name, pi.dosage, pi.frequency, pi.duration
    FROM prescription_items pi
    JOIN prescriptions p ON pi.prescription_id = p.prescription_id
    JOIN medicines m ON pi.medicine_id = m.medicine_id
    WHERE p.record_id = ?
");
$medicines->execute([$record_id]);
$medicines = $medicines->fetchAll();
?>

<div class="page-header">
    <h2>📄 Chi tiết bệnh án</h2>
    <a href="javascript:history.back()" class="btn-add">← Quay lại</a>
</div>

<div class="medical-card">
    <p><b><?= htmlspecialchars($record['patient_name']) ?></b></p>
    <p><?= $record['gender'] ?> | <?= $record['phone'] ?></p>
    <p>Bác sĩ: <?= htmlspecialchars($record['doctor_name']) ?></p>
    <p>Ngày khám: <?= date('d/m/Y', strtotime($record['visit_date'])) ?></p>
</div>

<div class="medical-card">
    <h3>📝 Chẩn đoán</h3>
    <p><?= nl2br(htmlspecialchars($record['diagnosis'])) ?></p>

    <h3>📋 Điều trị</h3>
    <p><?= nl2br(htmlspecialchars($record['treatment_plan'])) ?></p>
</div>

<div class="medical-card full">
    <h3>🦷 Dịch vụ</h3>

    <?php if (!$services): ?>
        <p>Không có dịch vụ</p>
    <?php else: ?>
    <table class="medical-table">
        <tr>
            <th>Dịch vụ</th>
            <th>SL</th>
            <th>Đơn giá</th>
            <th>Tiền</th>
        </tr>
        <?php foreach ($services as $s): ?>
        <tr>
            <td><?= htmlspecialchars($s['service_name']) ?></td>
            <td><?= $s['quantity'] ?></td>
            <td><?= number_format($s['price']) ?></td>
            <td><?= number_format($s['price'] * $s['quantity']) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>
</div>

<div class="medical-card full">
    <h3>💊 Đơn thuốc</h3>

    <?php if (!$medicines): ?>
        <p>Không có thuốc</p>
    <?php else: ?>
    <table class="medical-table">
        <tr>
            <th>Thuốc</th>
            <th>Liều</th>
            <th>Lần/ngày</th>
            <th>Số ngày</th>
        </tr>
        <?php foreach ($medicines as $m): ?>
        <tr>
            <td><?= htmlspecialchars($m['medicine_name']) ?></td>
            <td><?= $m['dosage'] ?></td>
            <td><?= $m['frequency'] ?></td>
            <td><?= $m['duration'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>
</div>
