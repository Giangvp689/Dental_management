<?php
require_once __DIR__ . "/../../api/db.php";

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $stmt = $pdo->prepare("
        INSERT INTO doctors (full_name, specialty, phone, email)
        VALUES (?,?,?,?)
    ");
    $stmt->execute([
        $_POST['full_name'],
        $_POST['specialty'],
        $_POST['phone'],
        $_POST['email']
    ]);
    header("Location: dashboard.php?page=doctors");
}
?>

<div class="form-box">
    <h3>➕ Thêm bác sĩ</h3>

    <form method="post">
        <label>Tên bác sĩ</label>
        <input name="full_name" required>

        <label>Chuyên khoa</label>
        <input name="specialty">

        <label>Số điện thoại</label>
        <input name="phone">

        <label>Email</label>
        <input type="email" name="email">

        <button class="btn-add">Lưu bác sĩ</button>
    </form>
</div>
