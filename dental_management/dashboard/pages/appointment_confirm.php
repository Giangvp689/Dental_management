<?php
require_once __DIR__ . "/../../api/db.php";

$id = $_GET['id'] ?? 0;

/* Lấy request */
$stmt = $pdo->prepare("
    SELECT * FROM appointment_requests WHERE request_id=?
");
$stmt->execute([$id]);
$r = $stmt->fetch();
if (!$r) die("Không tìm thấy lịch");

/* Kiểm tra bệnh nhân */
$stmt = $pdo->prepare("SELECT patient_id FROM patients WHERE phone=?");
$stmt->execute([$r['phone']]);
$p = $stmt->fetch();

if (!$p) {
    $stmt = $pdo->prepare("
        INSERT INTO patients(full_name,gender,dob,phone)
        VALUES (?,?,?,?)
    ");
    $stmt->execute([
        $r['full_name'],
        $r['gender'],
        $r['dob'],
        $r['phone']
    ]);
    $patient_id = $pdo->lastInsertId();
} else {
    $patient_id = $p['patient_id'];
}

/* TẠO APPOINTMENT THẬT */
$stmt = $pdo->prepare("
    INSERT INTO appointments
    (patient_id, doctor_id, service_id, request_id,
     appointment_date, appointment_time, status)
    VALUES (?,?,?,?,?,?, 'Đang khám')
");
$stmt->execute([
    $patient_id,
    $r['doctor_id'],
    $r['service_id'],
    $r['request_id'],
    $r['appointment_date'],
    $r['appointment_time']
]);

/* Update request */
$stmt = $pdo->prepare("
    UPDATE appointment_requests 
    SET status='Đã đến'
    WHERE request_id=?
");
$stmt->execute([$id]);

header("Location: dashboard.php?page=appointments");
exit;
