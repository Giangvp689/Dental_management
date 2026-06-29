<?php
require_once __DIR__ . "/../../api/db.php";

$q = $_GET['q'] ?? '';

$stmt = $pdo->prepare("
    SELECT * FROM medicines
    WHERE medicine_name LIKE ?
    ORDER BY medicine_name
");
$stmt->execute(["%$q%"]);
$medicines = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="page-header">
    <h2>💊 Thuốc</h2>

    <form class="search-form" method="get">
        <input type="hidden" name="page" value="medicines">
        <input type="text" name="q" placeholder="Tìm thuốc..." value="<?= htmlspecialchars($q) ?>">
        <button>🔍</button>
    </form>

    <a href="dashboard.php?page=medicine_add" class="btn-add">+ Thêm thuốc</a>
</div>

<div class="table-box">
<table>
    <thead>
        <tr>
            <th>Tên thuốc</th>
            <th>Đơn vị</th>
            <th>Giá</th>
            <th>Tồn kho</th>
            <th>Mô tả</th>
            <th></th>
        </tr>
    </thead>
    <tbody>

        <?php if (!$medicines): ?>
        <tr>
            <td colspan="6">Chưa có thuốc</td>
        </tr>
        <?php else: foreach ($medicines as $m): ?>
        <tr>
            <td><?= htmlspecialchars($m['medicine_name']) ?></td>
            <td><?= htmlspecialchars($m['unit']) ?></td>
            <td><?= number_format($m['price']) ?> đ</td>
            <td>
                <?= $m['stock'] ?>
                <?php if ($m['stock'] <= 10): ?>
                    <span style="color:red;font-weight:bold">⚠</span>
                <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($m['description']) ?></td>
            <td class="actions">
                <a class="btn edit"
                href="dashboard.php?page=medicine_edit&id=<?= $m['medicine_id'] ?>">Sửa</a>
                <a class="btn delete"
                onclick="return confirm('Xóa thuốc này?')"
                href="dashboard.php?page=medicine_delete&id=<?= $m['medicine_id'] ?>">Xóa</a>
            </td>
        </tr>
        <?php endforeach; endif; ?>

    </tbody>
</table>
</div>
