
<?php
require_once __DIR__ . "/../../api/db.php";

$sql = "
    SELECT u.*, 
           s.full_name AS staff_name,
           d.full_name AS doctor_name
    FROM users u
    LEFT JOIN staffs s ON u.staff_id = s.staff_id
    LEFT JOIN doctors d ON u.doctor_id = d.doctor_id
    ORDER BY u.user_id DESC
";
$users = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="page-header">
    <h2>🔐 Quản lý Tài khoản</h2>

    <div class="header-actions">
        <a href="dashboard.php?page=account_add" class="btn-add">
            + Tạo tài khoản
        </a>
    </div>
</div>

<div class="table-box">
<table>
    <thead>
        <tr>
            <th>Username</th>
            <th>Người dùng</th>
            <th>Quyền</th>
            <th>Trạng thái</th>
            <th>Hành động</th>
        </tr>
    </thead>

    <tbody>
    <?php foreach ($users as $u): ?>
    <tr>
        <td><b><?= htmlspecialchars($u['username']) ?></b></td>
        <td>
            <?= htmlspecialchars($u['staff_name'] ?? $u['doctor_name']) ?>
        </td>
        <td><?= $u['role'] ?></td>
        <td><?= $u['status'] ?></td>
        <td>
            <a class="btn edit"
            href="dashboard.php?page=account_edit&id=<?= $u['user_id'] ?>">✏️</a>

            <a class="btn delete"
            onclick="return confirm('Khóa tài khoản này?')"
            href="dashboard.php?page=account_delete&id=<?= $u['user_id'] ?>">⛔</a>
        </td>

    </tr>
    <?php endforeach; ?>

    </tbody>
</table>
</div>
