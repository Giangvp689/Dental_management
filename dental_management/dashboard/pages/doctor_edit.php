<?php
require_once __DIR__ . "/../../api/db.php";

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM doctors WHERE doctor_id=?");
$stmt->execute([$id]);
$d = $stmt->fetch();

if (!$d) header("Location: dashboard.php?page=doctors");

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $stmt = $pdo->prepare("
        UPDATE doctors
        SET full_name=?, specialty=?, phone=?, email=?
        WHERE doctor_id=?
    ");
    $stmt->execute([
        $_POST['full_name'],
        $_POST['specialty'],
        $_POST['phone'],
        $_POST['email'],
        $id
    ]);
    header("Location: dashboard.php?page=doctors");
}
?>

<div class="form-box">
    <h3>✏️ Cập nhật bác sĩ</h3>

    <form method="post">
        <label>Tên bác sĩ</label>
        <input name="full_name" value="<?= $d['full_name'] ?>" required>

        <label>Chuyên khoa</label>
        <input name="specialty" value="<?= $d['specialty'] ?>">

        <label>SĐT</label>
        <input name="phone" value="<?= $d['phone'] ?>">

        <label>Email</label>
        <input name="email" value="<?= $d['email'] ?>">

        <button class="btn-add">Cập nhật</button>
    </form>
</div>
