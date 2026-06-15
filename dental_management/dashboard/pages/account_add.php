<?php
require_once __DIR__ . "/../../api/db.php";

/* Lấy nhân viên chưa có tài khoản */
$staffs = $pdo->query("
    SELECT s.staff_id, s.full_name
    FROM staffs s
    LEFT JOIN users u ON s.staff_id = u.staff_id
    WHERE u.user_id IS NULL
")->fetchAll();

/* Lấy bác sĩ chưa có tài khoản */
$doctors = $pdo->query("
    SELECT d.doctor_id, d.full_name
    FROM doctors d
    LEFT JOIN users u ON d.doctor_id = u.doctor_id
    WHERE u.user_id IS NULL
")->fetchAll();

if ($_SERVER['REQUEST_METHOD']==='POST') {

    $password = $_POST['password'];

    $stmt = $pdo->prepare("
        INSERT INTO users (username, password, full_name, role, staff_id, doctor_id)
        VALUES (?,?,?,?,?,?)
    ");

    $stmt->execute([
        $_POST['username'],
        $password,
        $_POST['full_name'],
        $_POST['role'],
        $_POST['staff_id'] ?: null,
        $_POST['doctor_id'] ?: null
    ]);

    header("Location: dashboard.php?page=accounts");
}
?>

<div class="form-box">
<h3>➕ Tạo tài khoản</h3>

<form method="post">

<label>Loại người dùng</label>
<select id="type" onchange="toggleUser()" required>
    <option value="">-- Chọn --</option>
    <option value="staff">Nhân viên</option>
    <option value="doctor">Bác sĩ</option>
</select>

<div id="staff-box" style="display:none">
<label>Nhân viên</label>
<select name="staff_id" onchange="fillName(this)">
    <option value="">-- Chọn nhân viên --</option>
    <?php foreach ($staffs as $s): ?>
        <option value="<?= $s['staff_id'] ?>">
            <?= $s['full_name'] ?>
        </option>
    <?php endforeach; ?>
</select>
</div>

<div id="doctor-box" style="display:none">
<label>Bác sĩ</label>
<select name="doctor_id" onchange="fillName(this)">
    <option value="">-- Chọn bác sĩ --</option>
    <?php foreach ($doctors as $d): ?>
        <option value="<?= $d['doctor_id'] ?>">
            <?= $d['full_name'] ?>
        </option>
    <?php endforeach; ?>
</select>
</div>

<label>Họ tên</label>
<input name="full_name" id="full_name" readonly>

<label>Username</label>
<input name="username" required>

<label>Password</label>
<input type="password" name="password" required>

<label>Quyền</label>
<select name="role">
    <option>ADMIN</option>
    <option>RECEPTIONIST</option>
    <option>DOCTOR</option>
</select>

<button class="btn-add">Tạo tài khoản</button>
</form>
</div>

<script>
function toggleUser() {
    let type = document.getElementById('type').value;
    document.getElementById('staff-box').style.display = type==='staff'?'block':'none';
    document.getElementById('doctor-box').style.display = type==='doctor'?'block':'none';
}

function fillName(select) {
    document.getElementById('full_name').value =
        select.options[select.selectedIndex].text;
}
</script>
