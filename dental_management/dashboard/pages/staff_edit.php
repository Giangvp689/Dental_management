<?php
require_once __DIR__ . "/../../api/db.php";

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM staffs WHERE staff_id=?");
$stmt->execute([$id]);
$s = $stmt->fetch();

if (!$s) header("Location: dashboard.php?page=staffs");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("
        UPDATE staffs
        SET full_name=?, position=?, phone=?, email=?
        WHERE staff_id=?
    ");
    $stmt->execute([
        $_POST['full_name'],
        $_POST['position'],
        $_POST['phone'],
        $_POST['email'],
        $id
    ]);
    header("Location: dashboard.php?page=staffs");
}
?>

<div class="form-box">
    <h3>✏️ Cập nhật Nhân viên</h3>

    <form method="post">
        <label>Họ tên</label>
        <input name="full_name" value="<?= htmlspecialchars($s['full_name']) ?>" required>

        <label>Chức vụ</label>
        <input name="position" value="<?= htmlspecialchars($s['position']) ?>">

        <label>Số điện thoại</label>
        <input name="phone" value="<?= $s['phone'] ?>">

        <label>Email</label>
        <input name="email" value="<?= $s['email'] ?>">

        <button class="btn-add">Cập nhật</button>
    </form>
</div>
