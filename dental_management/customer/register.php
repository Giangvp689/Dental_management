<?php
/* =====================================================
   REGISTER.PHP – FORM ĐẶT LỊCH (DÙNG TRONG MODAL)
   ===================================================== */

/* ===== KẾT NỐI DB ===== */
if (!isset($pdo)) {
    require_once __DIR__ . "/../api/db.php";
}

/* ===== LẤY DANH SÁCH ===== */
$doctors = $pdo->query("
    SELECT doctor_id, full_name, specialty
    FROM doctors
    WHERE status='Hoạt động'
")->fetchAll();

$services = $pdo->query("
    SELECT service_id, service_name, price
    FROM services
")->fetchAll();

/* ===== XỬ LÝ SUBMIT (KHÔNG REDIRECT) ===== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $phone = trim($_POST['phone']);
    $date  = $_POST['appointment_date'];
    $time  = $_POST['appointment_time'];

    /* --- CHECK TRÙNG --- */
    $check = $pdo->prepare("
        SELECT COUNT(*) 
        FROM appointment_requests
        WHERE phone = ?
          AND appointment_date = ?
          AND appointment_time = ?
    ");
    $check->execute([$phone, $date, $time]);

    if ($check->fetchColumn() > 0) {
        echo "<script>
            alert('⚠ Lịch hẹn này đã tồn tại!');
            if (typeof closeModal === 'function') closeModal();
        </script>";
        return;
    }

    /* --- INSERT --- */
    $stmt = $pdo->prepare("
        INSERT INTO appointment_requests
        (full_name, phone, email, gender, dob,
         appointment_date, appointment_time,
         doctor_id, service_id, note,
         source, status)
        VALUES (?,?,?,?,?,?,?,?,?,?, 'Website', 'Chờ xác nhận')
    ");

    $stmt->execute([
        $_POST['full_name'],
        $phone,
        $_POST['email'],
        $_POST['gender'],
        $_POST['dob'],
        $date,
        $time,
        $_POST['doctor_id'],
        $_POST['service_id'],
        $_POST['note']
    ]);

    echo "<script>
        alert('✅ Đặt lịch thành công! Phòng khám sẽ liên hệ với bạn.');
        document.querySelector('.form-box form').reset();
        if (typeof closeModal === 'function') closeModal();
    </script>";
    return;
}
?>

<style>
.form-box{
    width:100%;
    max-width:520px;
    margin:0 auto;
    background:#fff;
    padding:26px;
    border-radius:20px;
    box-shadow:0 15px 40px rgba(0,0,0,.08);
    font-family:"Segoe UI",Tahoma,sans-serif
}
.form-box h2{
    text-align:center;
    color:#0ea5e9;
    margin-bottom:18px
}
label{
    font-weight:600;
    font-size:14px;
    display:block;
    margin-top:12px
}
input,select,textarea{
    width:100%;
    padding:12px;
    margin-top:6px;
    border-radius:8px;
    border:1px solid #cbd5e1;
    font-size:14px
}
textarea{resize:none}
button{
    width:100%;
    margin-top:22px;
    padding:14px;
    background:#0ea5e9;
    color:#fff;
    border:none;
    border-radius:30px;
    font-size:16px;
    font-weight:700;
    cursor:pointer
}
button:disabled{opacity:.6}
</style>

<div class="form-box">
<h2>📅 Đặt lịch khám nha khoa</h2>

<form method="post">

<label>Họ và tên</label>
<input name="full_name" required>

<label>Số điện thoại</label>
<input name="phone" required>

<label>Email</label>
<input name="email">

<label>Giới tính</label>
<select name="gender">
    <option>Nam</option>
    <option>Nữ</option>
    <option>Khác</option>
</select>

<label>Ngày sinh</label>
<input type="date" name="dob">

<label>Dịch vụ</label>
<select name="service_id" required>
<?php foreach ($services as $s): ?>
<option value="<?= $s['service_id'] ?>">
<?= htmlspecialchars($s['service_name']) ?> (<?= number_format($s['price']) ?>đ)
</option>
<?php endforeach; ?>
</select>

<label>Bác sĩ</label>
<select name="doctor_id" required>
<?php foreach ($doctors as $d): ?>
<option value="<?= $d['doctor_id'] ?>">
<?= htmlspecialchars($d['full_name']) ?> – <?= htmlspecialchars($d['specialty']) ?>
</option>
<?php endforeach; ?>
</select>

<label>Ngày khám</label>
<input type="date" name="appointment_date" required>

<label>Giờ khám</label>
<input type="time" name="appointment_time" required>

<label>Ghi chú</label>
<textarea name="note" rows="3"></textarea>

<button onclick="this.disabled=true;this.form.submit();">
    Gửi yêu cầu đặt lịch
</button>

</form>
</div>
