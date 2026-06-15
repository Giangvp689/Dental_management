<?php
require_once __DIR__ . "/../../api/db.php";

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("
    UPDATE doctors SET status='Ngưng' WHERE doctor_id=?
");
$stmt->execute([$id]);

header("Location: dashboard.php?page=doctors");
exit;
