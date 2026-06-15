<?php
require_once __DIR__ . "/../../api/db.php";

/* ===== DATE ===== */
$from = $_GET['from'] ?? date('Y-m-01');
$to   = $_GET['to']   ?? date('Y-m-d');

/* ===== DATA ===== */
$totalAppointments = $pdo->prepare("
    SELECT COUNT(*) FROM appointments
    WHERE appointment_date BETWEEN ? AND ?
");
$totalAppointments->execute([$from, $to]);

$doneAppointments = $pdo->prepare("
    SELECT COUNT(*) FROM appointments
    WHERE status = 'Hoàn thành'
    AND appointment_date BETWEEN ? AND ?
");
$doneAppointments->execute([$from, $to]);

$totalRecords = $pdo->prepare("
    SELECT COUNT(*) FROM medical_records
    WHERE visit_date BETWEEN ? AND ?
");
$totalRecords->execute([$from, $to]);

$totalRevenue = $pdo->prepare("
    SELECT IFNULL(SUM(total_amount),0)
    FROM invoices
    WHERE status = 'Đã thanh toán'
    AND invoice_date BETWEEN ? AND ?
");
$totalRevenue->execute([$from, $to]);

$doctorRevenueStmt = $pdo->prepare("
    SELECT d.full_name, IFNULL(SUM(i.total_amount),0) revenue
    FROM medical_records m
    JOIN doctors d ON m.doctor_id = d.doctor_id
    JOIN invoices i ON m.patient_id = i.patient_id
    WHERE i.status = 'Đã thanh toán'
    AND i.invoice_date BETWEEN ? AND ?
    GROUP BY d.doctor_id
");
$doctorRevenueStmt->execute([$from, $to]);
$doctorRevenue = $doctorRevenueStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- ===== HEADER ===== -->
<div class="page-header">
    <h2>📊 Báo cáo – Thống kê</h2>

    <form method="get" class="header-actions">
        <input type="hidden" name="page" value="reports">

        <input type="date" name="from" value="<?= $from ?>">
        <input type="date" name="to" value="<?= $to ?>">

        <button class="btn-add">Xem báo cáo</button>
    </form>
</div>

<!-- ===== STATS ===== -->
<div class="cards">
    <div class="card">
        <h3>Tổng lịch hẹn</h3>
        <p><?= $totalAppointments->fetchColumn() ?></p>
    </div>

    <div class="card">
        <h3>Lịch hoàn thành</h3>
        <p><?= $doneAppointments->fetchColumn() ?></p>
    </div>

    <div class="card">
        <h3>Tổng bệnh án</h3>
        <p><?= $totalRecords->fetchColumn() ?></p>
    </div>

    <div class="card">
        <h3>Doanh thu</h3>
        <p><?= number_format($totalRevenue->fetchColumn()) ?> đ</p>
    </div>
</div>

<!-- ===== TABLE ===== -->
<div class="table-box">
    <h3>💰 Doanh thu theo bác sĩ</h3>

    <table>
        <thead>
            <tr>
                <th>Bác sĩ</th>
                <th>Doanh thu</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!$doctorRevenue): ?>
            <tr>
                <td colspan="2">Không có dữ liệu</td>
            </tr>
        <?php else: foreach ($doctorRevenue as $d): ?>
            <tr>
                <td><?= htmlspecialchars($d['full_name']) ?></td>
                <td><?= number_format($d['revenue']) ?> đ</td>
            </tr>
        <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>
