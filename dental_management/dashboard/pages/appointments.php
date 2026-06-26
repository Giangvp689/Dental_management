<?php
require_once __DIR__ . "/../../api/db.php";

// 🔐 SESSION
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$role = $_SESSION['user']['role'] ?? '';
$doctorId = $_SESSION['user']['doctor_id'] ?? null;

// 🔎 FILTER
$search = $_GET['q'] ?? '';
$statusFilter = $_GET['status'] ?? '';

// ===============================
// QUERY
// ===============================
$sql = "
    SELECT ar.*,
           d.full_name AS doctor_name,
           s.service_name
    FROM appointment_requests ar
    LEFT JOIN doctors d ON ar.doctor_id = d.doctor_id
    LEFT JOIN services s ON ar.service_id = s.service_id
    WHERE 1=1
";

// 👉 LỌC THEO BÁC SĨ
if ($role === 'DOCTOR') {
    $sql .= " AND ar.doctor_id = :doctor_id";
}

// 👉 SEARCH
$sql .= "
    AND (
        ar.full_name LIKE :search
        OR ar.phone LIKE :search
        OR d.full_name LIKE :search
    )
";

// 👉 LỌC TRẠNG THÁI
switch ($statusFilter) {
    case 'cho_xac_nhan':
        $sql .= " AND ar.status = 'Chờ xác nhận'";
        break;
    case 'da_xac_nhan':
        $sql .= " AND ar.status = 'Đã xác nhận'";
        break;
    case 'hoan_thanh':
        $sql .= " AND ar.status = 'Đã đến'";
        break;
    case 'da_huy':
        $sql .= " AND ar.status = 'Hủy'";
        break;
}

// 👉 SORT
$sql .= " ORDER BY ar.appointment_date DESC, ar.appointment_time DESC";

// ===============================
// EXECUTE
// ===============================
$params = [
    ':search' => "%$search%"
];

if ($role === 'DOCTOR') {
    $params[':doctor_id'] = $doctorId;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$list = $stmt->fetchAll();
?>

<div class="page-header">
    <h2>Quản lý lịch hẹn</h2>

    <form method="get" class="search-form">
        <input type="hidden" name="page" value="appointments">

        <input 
            name="q"
            placeholder="Tìm tên KH, SĐT, bác sĩ..."
            value="<?= htmlspecialchars($search) ?>"
        >

        <select name="status">
            <option value="">-- Tất cả trạng thái --</option>
            <option value="cho_xac_nhan" <?= $statusFilter=='cho_xac_nhan'?'selected':'' ?>>
                Chờ xác nhận
            </option>
            <option value="da_xac_nhan" <?= $statusFilter=='da_xac_nhan'?'selected':'' ?>>
                Đã xác nhận
            </option>
            <option value="hoan_thanh" <?= $statusFilter=='hoan_thanh'?'selected':'' ?>>
                Hoàn thành
            </option>
            <option value="da_huy" <?= $statusFilter=='da_huy'?'selected':'' ?>>
                Đã hủy
            </option>
        </select>

        <button>🔍</button>
    </form>
</div>

<table class="table-box">
<thead>
<tr>
    <th>Họ tên</th>
    <th>SĐT</th>
    <th>Ngày</th>
    <th>Giờ</th>
    <th>Bác sĩ</th>
    <th>Dịch vụ</th>
    <th>Trạng thái</th>
    <th>Thao tác</th>
</tr>
</thead>

<tbody>
<?php if (!$list): ?>
<tr>
    <td colspan="8">Không có dữ liệu</td>
</tr>
<?php else: foreach ($list as $r): ?>
<tr>
    <td><?= htmlspecialchars($r['full_name']) ?></td>
    <td><?= htmlspecialchars($r['phone']) ?></td>
    <td><?= $r['appointment_date'] ?></td>
    <td><?= $r['appointment_time'] ?></td>
    <td><?= htmlspecialchars($r['doctor_name'] ?? '—') ?></td>
    <td><?= htmlspecialchars($r['service_name'] ?? '—') ?></td>

    <td>
        <?php
            if ($r['status'] === 'Đã đến') echo 'Hoàn thành';
            elseif ($r['status'] === 'Hủy') echo 'Đã hủy';
            else echo htmlspecialchars($r['status']);
        ?>
    </td>

    <td>
        <?php if ($r['status'] === 'Đã xác nhận'): ?>
            <a href="dashboard.php?page=appointment_confirm&id=<?= $r['request_id'] ?>">
                ✔ Khách đến
            </a>
        <?php endif; ?>

        <a href="dashboard.php?page=appointment_edit&id=<?= $r['request_id'] ?>">✏️</a>

        <a onclick="return confirm('Hủy lịch hẹn này?')"
           href="dashboard.php?page=appointment_delete&id=<?= $r['request_id'] ?>&action=cancel">
            🚫 Hủy
        </a>

        <a onclick="return confirm('XÓA VĨNH VIỄN lịch này?')"
           href="dashboard.php?page=appointment_delete&id=<?= $r['request_id'] ?>&action=delete">
            🗑 Xóa
        </a>
    </td>
</tr>
<?php endforeach; endif; ?>
</tbody>
</table>