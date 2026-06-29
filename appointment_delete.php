<?php
require_once __DIR__ . "/../../api/db.php";

$id = $_GET['id'] ?? 0;
$action = $_GET['action'] ?? 'cancel';

if (!$id) {
    header("Location: dashboard.php?page=appointments");
    exit;
}

if ($action === 'cancel') {

    // 🔹 HỦY LỊCH
    $stmt = $pdo->prepare("
        UPDATE appointment_requests 
        SET status = 'Hủy' 
        WHERE request_id = ?
    ");
    $stmt->execute([$id]);

} elseif ($action === 'delete') {

    // 🔥 XÓA LỊCH KHÁM TRƯỚC (do FK)
    $stmt = $pdo->prepare("
        DELETE FROM appointments 
        WHERE request_id = ?
    ");
    $stmt->execute([$id]);

    // 🔥 XÓA YÊU CẦU ĐẶT LỊCH
    $stmt = $pdo->prepare("
        DELETE FROM appointment_requests 
        WHERE request_id = ?
    ");
    $stmt->execute([$id]);
}

header("Location: dashboard.php?page=appointments");
exit;
