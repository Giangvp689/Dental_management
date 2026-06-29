<?php
require_once __DIR__ . "/../../api/db.php";

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("
    UPDATE staffs SET status='inactive' WHERE staff_id=?
");
$stmt->execute([$id]);

header("Location: dashboard.php?page=staffs");
