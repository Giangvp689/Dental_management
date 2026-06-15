<?php
require_once __DIR__ . "/../../api/db.php";

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("
    UPDATE users SET status=0 WHERE user_id=?
");
$stmt->execute([$id]);

header("Location: dashboard.php?page=accounts");
