<?php
require_once __DIR__ . "/../../api/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo->prepare("
        INSERT INTO services(service_name, price, description)
        VALUES (?,?,?)
    ")->execute([
        $_POST['service_name'],
        $_POST['price'],
        $_POST['description']
    ]);

    header("Location: dashboard.php?page=services");
    exit;
}
?>

<div class="page-header">
    <h2>➕ Thêm dịch vụ</h2>
</div>

<form method="post" class="form-box">
    <label>Tên dịch vụ</label>
    <input type="text" name="service_name" required>

    <label>Giá</label>
    <input type="number" name="price" required>

    <label>Mô tả</label>
    <textarea name="description"></textarea>

    <button class="btn-add">Lưu</button>
</form>
