<?php
require_once __DIR__ . "/../../api/db.php";

$id = (int)$_GET['id'];

/* Kiểm tra đã dùng trong hóa đơn chưa */
$check = $pdo->prepare("
    SELECT COUNT(*) FROM invoice_details WHERE service_id=?
");
$check->execute([$id]);

if ($check->fetchColumn() > 0) {
    echo "<p>Dịch vụ đã dùng trong hóa đơn, không thể xóa</p>
          <a href='dashboard.php?page=services'>← Quay lại</a>";
    return;
}

$pdo->prepare("DELETE FROM services WHERE service_id=?")->execute([$id]);
header("Location: dashboard.php?page=services");
