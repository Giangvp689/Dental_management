<?php
require_once __DIR__ . "/../../api/db.php";

$keyword = $_GET['keyword'] ?? '';

$sql = "SELECT * FROM doctors";
$params = [];

if ($keyword !== '') {
    $sql .= "
        AND (
            full_name LIKE ?
            OR specialty LIKE ?
            OR phone LIKE ?
            OR email LIKE ?
        )
    ";
    $params = [
        "%$keyword%",
        "%$keyword%",
        "%$keyword%",
        "%$keyword%"
    ];
}

$sql .= " ORDER BY doctor_id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$doctors = $stmt->fetchAll();
?>

<div class="page-header">
    <h2>🧑‍⚕️ Quản lý bác sĩ</h2>

    <div class="header-actions">
        <form class="search-form">
            <input type="hidden" name="page" value="doctors">
            <input name="keyword"
                   placeholder="Tên, chuyên khoa, SĐT, email..."
                   value="<?= htmlspecialchars($keyword) ?>">
            <button>🔍</button>
        </form>

        <a href="dashboard.php?page=doctor_add" class="btn-add">
            + Thêm bác sĩ
        </a>
    </div>
</div>

<div class="table-box">
    <table>
        <thead>
        <tr>
            <th>Tên bác sĩ</th>
            <th>Chuyên khoa</th>
            <th>SĐT</th>
            <th>Email</th>
            <th>Hành động</th>
            <th>Trạng thái</th>

        </tr>
        </thead>
        <tbody>
        <?php if (!$doctors): ?>
            <tr><td colspan="5" align="center">Chưa có bác sĩ</td></tr>
        <?php endif; ?>

        <?php foreach ($doctors as $d): ?>
            <tr>
                <td><b><?= htmlspecialchars($d['full_name']) ?></b></td>
                <td><?= htmlspecialchars($d['specialty']) ?></td>
                <td><?= $d['phone'] ?></td>
                <td><?= $d['email'] ?></td>
                <td><?= $d['status'] ?></td>
                <td>
                    <div class="actions">
                        <a class="btn edit"
                           href="dashboard.php?page=doctor_edit&id=<?= $d['doctor_id'] ?>">
                            ✏️
                        </a>
                        <a class="btn delete"
                           onclick="return confirm('Ngưng hoạt động bác sĩ này?')"
                           href="dashboard.php?page=doctor_delete&id=<?= $d['doctor_id'] ?>">
                            ⛔
                        </a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
