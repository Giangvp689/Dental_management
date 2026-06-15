<?php
require_once __DIR__ . "/../../api/db.php";

$id = (int)$_GET['id'];

$check = $pdo->prepare("
    SELECT COUNT(*) FROM prescription_items WHERE medicine_id=?
");
$check->execute([$id]);

if ($check->fetchColumn() > 0) {
    echo "<p>Thuốc đã dùng trong đơn thuốc, không thể xóa</p>
          <a href='dashboard.php?page=medicines'>← Quay lại</a>";
    return;
}

$pdo->prepare("DELETE FROM medicines WHERE medicine_id=?")->execute([$id]);
header("Location: dashboard.php?page=medicines");
