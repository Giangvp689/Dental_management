<?php
require_once __DIR__ . "/../../api/db.php";

$keyword = trim($_GET['keyword'] ?? '');

/* ── Query có WHERE nếu search ── */
$sql    = "SELECT * FROM doctors WHERE 1=1";
$params = [];

if ($keyword !== '') {
    $sql .= " AND (
        full_name  LIKE ?
        OR specialty LIKE ?
        OR phone     LIKE ?
        OR email     LIKE ?
    )";
    $like     = "%$keyword%";
    $params   = [$like, $like, $like, $like];
}

$sql .= " ORDER BY doctor_id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$doctors = $stmt->fetchAll();

/* ── Thông báo từ redirect ── */
$msg = $_GET['msg'] ?? '';
$err = $_GET['err'] ?? '';
$msgText = match($msg) {
    'deleted'   => ['type' => 'success', 'text' => '✅ Đã xóa bác sĩ thành công.'],
    'suspended' => ['type' => 'warning', 'text' => '⛔ Đã ngưng hoạt động bác sĩ.'],
    'activated' => ['type' => 'success', 'text' => '✅ Đã kích hoạt lại bác sĩ.'],
    'not_found' => ['type' => 'danger',  'text' => '❌ Không tìm thấy bác sĩ.'],
    default     => null,
};
?>

<!-- ── Thông báo ── -->
<?php if ($msgText): ?>
<div class="alert alert-<?= $msgText['type'] ?>" style="
    padding:10px 16px; border-radius:6px; margin-bottom:16px; font-size:14px;
    background:<?= $msgText['type']==='success'?'#d1fae5':($msgText['type']==='warning'?'#fef9c3':'#fee2e2') ?>;
    border:1px solid <?= $msgText['type']==='success'?'#6ee7b7':($msgText['type']==='warning'?'#fde047':'#fca5a5') ?>;
    color:#374151;">
    <?= htmlspecialchars($msgText['text']) ?>
</div>
<?php endif; ?>

<?php if ($err): ?>
<div class="alert alert-danger" style="
    padding:10px 16px; border-radius:6px; margin-bottom:16px; font-size:14px;
    background:#fee2e2; border:1px solid #fca5a5; color:#374151;">
    ❌ <?= htmlspecialchars(urldecode($err)) ?>
</div>
<?php endif; ?>

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
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!$doctors): ?>
            <tr><td colspan="6" align="center" style="color:#888;padding:20px">Chưa có bác sĩ nào</td></tr>
        <?php endif; ?>

        <?php foreach ($doctors as $d): ?>
            <tr>
                <td><b><?= htmlspecialchars($d['full_name']) ?></b></td>
                <td><?= htmlspecialchars($d['specialty']) ?></td>
                <td><?= htmlspecialchars($d['phone']) ?></td>
                <td><?= htmlspecialchars($d['email']) ?></td>
                <td>
                    <?php if ($d['status'] === 'Hoạt động'): ?>
                        <span style="color:#16a34a;font-weight:600">● Hoạt động</span>
                    <?php else: ?>
                        <span style="color:#dc2626;font-weight:600">● Ngưng</span>
                    <?php endif; ?>
                </td>
                <td>
                    <div class="actions">
                        <!-- Sửa -->
                        <a class="btn edit"
                           href="dashboard.php?page=doctor_edit&id=<?= $d['doctor_id'] ?>"
                           title="Sửa thông tin">✏️</a>

                        <!-- Ngưng / Kích hoạt -->
                        <?php if ($d['status'] === 'Hoạt động'): ?>
                            <a class="btn delete"
                               href="dashboard.php?page=doctor_delete&id=<?= $d['doctor_id'] ?>&action=suspend"
                               onclick="return confirm('Ngưng hoạt động bác sĩ <?= addslashes(htmlspecialchars($d['full_name'])) ?>?')"
                               title="Ngưng hoạt động">⛔</a>
                        <?php else: ?>
                            <a class="btn"
                               href="dashboard.php?page=doctor_delete&id=<?= $d['doctor_id'] ?>&action=suspend"
                               onclick="return confirm('Kích hoạt lại bác sĩ <?= addslashes(htmlspecialchars($d['full_name'])) ?>?')"
                               title="Kích hoạt lại"
                               style="background:#16a34a;color:#fff;padding:4px 8px;border-radius:4px;text-decoration:none">▶️</a>
                        <?php endif; ?>

                        <!-- Xóa hẳn -->
                        <a class="btn"
                           href="dashboard.php?page=doctor_delete&id=<?= $d['doctor_id'] ?>&action=delete"
                           onclick="return confirm('XÓA VĨNH VIỄN bác sĩ <?= addslashes(htmlspecialchars($d['full_name'])) ?>?\n\nNếu bác sĩ còn lịch hẹn hoặc hồ sơ bệnh, hệ thống sẽ từ chối.\nHành động này không thể hoàn tác!')"
                           title="Xóa vĩnh viễn"
                           style="background:#dc2626;color:#fff;padding:4px 8px;border-radius:4px;text-decoration:none;font-size:13px">🗑️</a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
