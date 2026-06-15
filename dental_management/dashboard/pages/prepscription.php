<?php
require_once __DIR__ . "/../../api/db.php";

$record_id = (int)$_GET['record_id'];

$stmt = $pdo->prepare("
    SELECT * FROM prescriptions
    WHERE record_id=?
");
$stmt->execute([$record_id]);
$meds = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="page-header">
    <h2>💊 Đơn thuốc</h2>
    <a href="dashboard.php?page=prescription_add&record_id=<?= $record_id ?>"
       class="btn-add">
       + Thêm thuốc
    </a>
</div>

<div class="table-box">
<table>
<thead>
<tr>
    <th>Thuốc</th>
    <th>Liều dùng</th>
    <th>Thời gian</th>
    <th>Hành động</th>
</tr>
</thead>
<tbody>
<?php foreach ($meds as $m): ?>
<tr>
    <td><?= htmlspecialchars($m['medicine_name']) ?></td>
    <td><?= htmlspecialchars($m['dosage']) ?></td>
    <td><?= htmlspecialchars($m['duration']) ?></td>
    <td>
        <a class="btn delete"
           onclick="return confirm('Xóa thuốc?')"
           href="dashboard.php?page=prescription_delete&id=<?= $m['prescription_id'] ?>&record_id=<?= $record_id ?>">
           Xóa
        </a>
    </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
