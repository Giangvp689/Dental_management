<?php
require_once __DIR__ . "/../../api/db.php";

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $stmt = $pdo->prepare("
        INSERT INTO patients (full_name, gender, dob, phone, address)
        VALUES (?,?,?,?,?)
    ");
    $stmt->execute([
        $_POST['full_name'],
        $_POST['gender'],
        $_POST['dob'],
        $_POST['phone'],
        $_POST['address']
    ]);
    header("Location: dashboard.php?page=patients");
}
?>

<div class="form-box">
    <h3>➕ Thêm bệnh nhân (Walk-in)</h3>

    <form method="post">
        <label>Họ tên</label>
        <input name="full_name" required>

        <label>Giới tính</label>
        <select name="gender">
            <option>Nam</option>
            <option>Nữ</option>
            <option>Khác</option>
        </select>

        <label>Ngày sinh</label>
        <input type="date" name="dob">

        <label>Số điện thoại</label>
        <input name="phone" required>

        <label>Địa chỉ</label>
        <input name="address" required>

        <button class="btn-add">Lưu bệnh nhân</button>
    </form>
</div>
