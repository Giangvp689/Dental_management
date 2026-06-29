<?php
require_once __DIR__ . "/../../api/db.php";

$keyword = $_GET['q'] ?? '';

$sql = "
SELECT * FROM services
WHERE service_name LIKE ?
ORDER BY service_name
";
$stmt = $pdo->prepare($sql);
$stmt->execute(["%$keyword%"]);
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="page-header">
    <h2>🦷 Dịch vụ</h2>

    <form method="get" class="search-form">
        <input type="hidden" name="page" value="services">
        <input type="text" name="q" placeholder="Tìm dịch vụ..." value="<?= htmlspecialchars($keyword) ?>">
        <button type="submit">🔍</button>
    </form>

    <a href="dashboard.php?page=service_add" class="btn-add">+ Thêm dịch vụ</a>
</div>

<div class="table-box">
<table>
<thead>
<tr>
    <th>Tên dịch vụ</th>
    <th>Giá</th>
    <th>Mô tả</th>
    <th></th>
</tr>
</thead>
<tbody>
<?php if (!$services): ?>
<tr><td colspan="4">Không có dịch vụ</td></tr>
<?php else: foreach ($services as $s): ?>
<tr>
    <td><?= htmlspecialchars($s['service_name']) ?></td>
    <td><?= number_format($s['price']) ?> đ</td>
    <td><?= htmlspecialchars($s['description']) ?></td>
    <td class="actions">
        <a class="btn edit"
           href="dashboard.php?page=service_edit&id=<?= $s['service_id'] ?>">Sửa</a>

        <a class="btn delete"
           onclick="return confirm('Xóa dịch vụ này?')"
           href="dashboard.php?page=service_delete&id=<?= $s['service_id'] ?>">Xóa</a>
    </td>
</tr>
<?php endforeach; endif; ?>
</tbody>
</table>
</div>
