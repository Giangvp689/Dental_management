<?php
require_once __DIR__ . "/../../api/db.php";

$id     = (int)($_GET['id']     ?? 0);
$action = $_GET['action'] ?? 'suspend'; // suspend | delete

if ($id <= 0) {
    header("Location: dashboard.php?page=doctors");
    exit;
}

/* ── Lấy thông tin bác sĩ (cần status để toggle) ── */
$stmt = $pdo->prepare("SELECT full_name, status FROM doctors WHERE doctor_id = ?");
$stmt->execute([$id]);
$doctor = $stmt->fetch();

if (!$doctor) {
    header("Location: dashboard.php?page=doctors&msg=not_found");
    exit;
}

/* ══════════════════════════════════════════════
   ACTION: TOGGLE TRẠNG THÁI (Hoạt động ↔ Ngưng)
   ══════════════════════════════════════════════ */
if ($action === 'suspend') {
    // Đảo ngược trạng thái hiện tại
    $newStatus = ($doctor['status'] === 'Hoạt động') ? 'Ngưng' : 'Hoạt động';
    $stmt = $pdo->prepare("UPDATE doctors SET status = ? WHERE doctor_id = ?");
    $stmt->execute([$newStatus, $id]);

    $msg = ($newStatus === 'Ngưng') ? 'suspended' : 'activated';
    header("Location: dashboard.php?page=doctors&msg=$msg");
    exit;
}

/* ══════════════════════════════════════════════
   ACTION: XÓA THẬT SỰ
   ══════════════════════════════════════════════ */
if ($action === 'delete') {

    /* ── Kiểm tra ràng buộc dữ liệu ── */
    $checks = [
        'lịch hẹn'              => "SELECT COUNT(*) FROM appointments          WHERE doctor_id = ?",
        'hồ sơ bệnh'            => "SELECT COUNT(*) FROM medical_records       WHERE doctor_id = ?",
        'yêu cầu đặt lịch'      => "SELECT COUNT(*) FROM appointment_requests  WHERE doctor_id = ?",
    ];

    $conflicts = [];
    foreach ($checks as $label => $sql) {
        $s = $pdo->prepare($sql);
        $s->execute([$id]);
        $cnt = (int)$s->fetchColumn();
        if ($cnt > 0) {
            $conflicts[] = "$cnt $label";
        }
    }

    if (!empty($conflicts)) {
        /* Còn ràng buộc → không cho xóa, redirect kèm thông báo */
        $msg = urlencode('Không thể xóa vì còn: ' . implode(', ', $conflicts) . '. Hãy dùng "Ngưng hoạt động" thay thế.');
        header("Location: dashboard.php?page=doctors&err=" . $msg);
        exit;
    }

    /* ── Gỡ liên kết tài khoản users rồi xóa ── */
    $pdo->beginTransaction();
    try {
        $pdo->prepare("UPDATE users SET doctor_id = NULL, status = 0 WHERE doctor_id = ?")->execute([$id]);
        $pdo->prepare("DELETE FROM doctors WHERE doctor_id = ?")->execute([$id]);
        $pdo->commit();
        header("Location: dashboard.php?page=doctors&msg=deleted");
    } catch (Exception $e) {
        $pdo->rollBack();
        $msg = urlencode('Lỗi khi xóa: ' . $e->getMessage());
        header("Location: dashboard.php?page=doctors&err=" . $msg);
    }
    exit;
}

/* fallback */
header("Location: dashboard.php?page=doctors");
exit;
