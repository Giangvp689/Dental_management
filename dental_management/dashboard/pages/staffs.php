<?php
require_once __DIR__ . "/../../api/db.php";

/* ===== USER INFO ===== */
$role = $_SESSION['user']['role'] ?? '';

/* ===== SEARCH ===== */
$keyword = $_GET['keyword'] ?? '';
$params  = [];

/* ===== QUERY ===== */
$sql = "SELECT * FROM staffs WHERE status='active'";

if ($keyword !== '') {
    $sql .= " AND (
        full_name LIKE ? OR
        phone LIKE ? OR
        email LIKE ? OR
        position LIKE ?
    )";
    $params = array_fill(0, 4, "%$keyword%");
}

$sql .= " ORDER BY staff_id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$staffs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="page-header">
    <h2>👥 Quản lý Nhân viên</h2>

    <div class="header-actions">
        <form class="search-form">
            <input type="hidden" name="page" value="staffs">
            <input name="keyword"
                   placeholder="Tên, SĐT, email, chức vụ..."
                   value="<?= htmlspecialchars($keyword) ?>">
            <button>🔍</button>
        </form>

        <a href="dashboard.php?page=staff_add" class="btn-add">
            + Nhân viên mới
        </a>
    </div>
</div>

<div class="table-box">
    <table>
        <thead>
        <tr>
            <th>Họ tên</th>
            <th>Chức vụ</th>
            <th>SĐT</th>
            <th>Email</th>
            <th>Hành động</th>
        </tr>
        </thead>
        <tbody>

        <?php if (empty($staffs)): ?>
            <tr>
                <td colspan="5" align="center">Không có nhân viên</td>
            </tr>
        <?php endif; ?>

        <?php foreach ($staffs as $s): ?>
            <tr>
                <td><b><?= htmlspecialchars($s['full_name']) ?></b></td>
                <td><?= htmlspecialchars($s['position']) ?></td>
                <td><?= $s['phone'] ?></td>
                <td><?= $s['email'] ?></td>
                <td>
                    <div class="actions">
                        <a class="btn edit"
                           href="dashboard.php?page=staff_edit&id=<?= $s['staff_id'] ?>">
                            ✏️
                        </a>

                        <a class="btn delete"
                           onclick="return confirm('Ngưng nhân viên này?')"
                           href="dashboard.php?page=staff_delete&id=<?= $s['staff_id'] ?>">
                            ⛔
                        </a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>

        </tbody>
    </table>
</div>
