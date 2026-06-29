<?php
require_once __DIR__ . "/../../api/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $pdo->prepare("
        INSERT INTO medicines
        (medicine_name, unit, price, stock, description)
        VALUES (?,?,?,?,?)
    ")->execute([
        $_POST['medicine_name'],
        $_POST['unit'],
        $_POST['price'],
        $_POST['stock'],
        $_POST['description']
    ]);

    header("Location: dashboard.php?page=medicines");
    exit;
}
?>

<div class="page-header">
    <h2>➕ Thêm thuốc</h2>
</div>

<form method="post" class="form-box">

    <label>Tên thuốc</label>
    <input type="text" name="medicine_name" required>

    <label>Đơn vị</label>
    <input type="text" name="unit" placeholder="viên / chai / ống">

    <label>Giá</label>
    <input type="number" name="price" min="0" required>

    <label>Số lượng tồn kho</label>
    <input type="number" name="stock" min="0" required>

    <label>Mô tả</label>
    <textarea name="description"></textarea>

    <button class="btn-add">Lưu</button>
</form>
