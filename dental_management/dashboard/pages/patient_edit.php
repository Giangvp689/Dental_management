<?php
require_once __DIR__ . "/../../api/db.php";

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM patients WHERE patient_id=?");
$stmt->execute([$id]);
$p = $stmt->fetch();

if (!$p) header("Location: dashboard.php?page=patients");

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $stmt = $pdo->prepare("
        UPDATE patients
        SET full_name=?, gender=?, dob=?, phone=?, address=?
        WHERE patient_id=?
    ");
    $stmt->execute([
        $_POST['full_name'],
        $_POST['gender'],
        $_POST['dob'],
        $_POST['phone'],
        $_POST['address'],
        $id
    ]);
    header("Location: dashboard.php?page=patients");
}
?>

<div class="form-box">
    <h3>✏️ Cập nhật bệnh nhân</h3>

    <form method="post">
        <label>Họ tên</label>
        <input name="full_name" value="<?= htmlspecialchars($p['full_name']) ?>" required>

        <label>Giới tính</label>
        <select name="gender">
            <option <?= $p['gender']=='Nam'?'selected':'' ?>>Nam</option>
            <option <?= $p['gender']=='Nữ'?'selected':'' ?>>Nữ</option>
            <option <?= $p['gender']=='Khác'?'selected':'' ?>>Khác</option>
        </select>

        <label>Ngày sinh</label>
        <input type="date" name="dob" value="<?= $p['dob'] ?>">

        <label>SĐT</label>
        <input name="phone" value="<?= htmlspecialchars($p['phone']) ?>">

        <label>Địa chỉ</label>
        <input name="address" value="<?= htmlspecialchars($p['address']) ?>">

        <button class="btn-add">Cập nhật</button>
    </form>
</div>
