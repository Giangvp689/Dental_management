<?php
require_once __DIR__ . "/../../api/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("
        INSERT INTO staffs (full_name, position, phone, email)
        VALUES (?,?,?,?)
    ");
    $stmt->execute([
        $_POST['full_name'],
        $_POST['position'],
        $_POST['phone'],
        $_POST['email']
    ]);
    header("Location: dashboard.php?page=staffs");
}
?>

<div class="form-box">
    <h3>➕ Thêm Nhân viên</h3>

    <form method="post">
        <label>Họ tên</label>
        <input name="full_name" required>

        <label>Chức vụ</label>
        <input name="position" placeholder="Lễ tân / Y tá / Admin">

        <label>Số điện thoại</label>
        <input name="phone">

        <label>Email</label>
        <input name="email">

        <button class="btn-add">Lưu nhân viên</button>
    </form>
</div>
