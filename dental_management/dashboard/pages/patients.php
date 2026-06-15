<?php

require_once __DIR__ . "/../../api/db.php";

/* ===== USER INFO ===== */
$role    = $_SESSION['user']['role'] ?? '';
$user_id = $_SESSION['user']['user_id'] ?? 0;

/* ===== SEARCH ===== */
$keyword = $_GET['keyword'] ?? '';
$params  = [];

/* ===== QUERY ===== */
$sql = "SELECT * FROM patients WHERE status='Hoạt động'";

if ($keyword !== '') {
    $sql .= " AND (
        full_name LIKE ? OR
        phone LIKE ? OR
        address LIKE ?
    )";
    $params = ["%$keyword%", "%$keyword%", "%$keyword%"];
}


$sql .= " ORDER BY patient_id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$patients = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="page-header">
    <h2>👤 Quản lý bệnh nhân</h2>

    <div class="header-actions">
        <form class="search-form">
            <input type="hidden" name="page" value="patients">
            <input name="keyword"
                   placeholder="Tên, SĐT, địa chỉ..."
                   value="<?= htmlspecialchars($keyword) ?>">
            <button>🔍</button>
        </form>

        <?php if ($role !== 'DOCTOR'): ?>
            <a href="dashboard.php?page=patient_add" class="btn-add">
                + Bệnh nhân mới
            </a>
        <?php endif; ?>
    </div>
</div>

<div class="table-box">
    <table>
        <thead>
            <tr>
                <th>Họ tên</th>
                <th>Giới tính</th>
                <th>Ngày sinh</th>
                <th>SĐT</th>
                <th>Địa chỉ</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>

            <?php if (empty($patients)): ?>
                <tr>
                    <td colspan="6" align="center">Không có bệnh nhân</td>
                </tr>
            <?php endif; ?>

            <?php foreach ($patients as $p): ?>
                <tr>
                    <td><b><?= htmlspecialchars($p['full_name']) ?></b></td>
                    <td><?= $p['gender'] ?></td>
                    <td><?= date('d/m/Y', strtotime($p['dob'])) ?></td>
                    <td><?= $p['phone'] ?></td>
                    <td><?= htmlspecialchars($p['address']) ?></td>
                    <td>
                        <div class="actions">
                            <?php if ($role !== 'DOCTOR'): ?>
                                <a class="btn edit"
                                href="dashboard.php?page=patient_edit&id=<?= $p['patient_id'] ?>">
                                    ✏️
                                </a>

                                <a class="btn delete"
                                onclick="return confirm('Ngưng hồ sơ bệnh nhân này?')"
                                href="dashboard.php?page=patient_delete&id=<?= $p['patient_id'] ?>">
                                    ⛔
                                </a>
                            <?php else: ?>
                                <span style="color:#888;font-size:13px;">
                                        Chỉ xem
                                </span>    
                            <?php endif; ?>

                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>

        </tbody>
    </table>
</div>
