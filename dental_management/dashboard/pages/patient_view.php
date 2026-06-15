<?php
require_once __DIR__ . "/../../api/db.php";

$patient_id = (int)($_GET['patient_id'] ?? 0);

if (!$patient_id) die("Thiếu bệnh nhân");

require_once __DIR__ . "/../../dental_login/auth.php";
requireRole(['ADMIN','DOCTOR','RECEPTIONIST']);

/* info */
$p = $pdo->prepare("SELECT * FROM patients WHERE patient_id=?");
$p->execute([$patient_id]);
$patient = $p->fetch();
if (!$patient) die("Không tồn tại");

/* lịch sử */
$r = $pdo->prepare("
    SELECT record_id, visit_date, diagnosis, status
    FROM medical_records
    WHERE patient_id=?
    ORDER BY visit_date DESC
");
$r->execute([$patient_id]);
$records = $r->fetchAll();
?>

<div class="page-header">
    <h2>👤 Hồ sơ bệnh nhân</h2>
    <a href="dashboard.php?page=medical" class="btn-add">← Quay lại</a>
</div>

<div class="medical-card">
    <p><b><?= htmlspecialchars($patient['full_name']) ?></b></p>
    <p><?= $patient['gender'] ?> | <?= $patient['phone'] ?></p>
</div>

<div class="medical-card full">
    <h3>📜 Lịch sử khám</h3>

    <table class="medical-table">
        <tr>
            <th>Ngày</th>
            <th>Chẩn đoán</th>
            <th>Trạng thái</th>
            <th></th>
        </tr>

        <?php foreach ($records as $r): ?>
        <tr>
            <td><?= date('d/m/Y', strtotime($r['visit_date'])) ?></td>
            <td><?= htmlspecialchars($r['diagnosis']) ?></td>
            <td><?= $r['status'] ?></td>
            <td>
                <a class="btn view"
                   href="dashboard.php?page=medical_history&record_id=<?= $r['record_id'] ?>">
                   Xem
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
