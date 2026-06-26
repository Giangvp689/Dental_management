<?php
require_once __DIR__ . "/../../api/db.php";

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("
    SELECT * FROM users WHERE user_id=?
");
$stmt->execute([$id]);
$u = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$u) header("Location: dashboard.php?page=accounts");

if ($_SERVER['REQUEST_METHOD']==='POST') {

    if (!empty($_POST['password'])) {
     $password = $_POST['password'];
        $stmt = $pdo->prepare("
            UPDATE users
            SET username=?, password=?, role=?, status=?
            WHERE user_id=?
        ");
        $stmt->execute([
            $_POST['username'],
            $password,
            $_POST['role'],
            $_POST['status'],
            $id
        ]);
    } else {
        $stmt = $pdo->prepare("
            UPDATE users
            SET username=?, role=?, status=?
            WHERE user_id=?
        ");
        $stmt->execute([
            $_POST['username'],
            $_POST['role'],
            $_POST['status'],
            $id
        ]);
    }

    header("Location: dashboard.php?page=accounts");
}
?>

<div class="form-box">
<h3>✏️ Cập nhật tài khoản</h3>

<form method="post">
    <label>Họ tên</label>
    <input value="<?= htmlspecialchars($u['full_name']) ?>" disabled>

    <label>Username</label>
    <input name="username" value="<?= $u['username'] ?>" required>

    <label>Mật khẩu mới</label>
    <input type="password" name="password"
           placeholder="Để trống nếu không đổi">

    <label>Quyền</label>
    <select name="role">
        <option <?= $u['role']=='ADMIN'?'selected':'' ?>>ADMIN</option>
        <option <?= $u['role']=='RECEPTIONIST'?'selected':'' ?>>RECEPTIONIST</option>
        <option <?= $u['role']=='DOCTOR'?'selected':'' ?>>DOCTOR</option>
    </select>

    <label>Trạng thái</label>
    <select name="status">
        <option value="1" <?= $u['status']==1?'selected':'' ?>>Hoạt động</option>
        <option value="0" <?= $u['status']==0?'selected':'' ?>>Khóa</option>
    </select>

    <button class="btn-add">Lưu thay đổi</button>
</form>
</div>
