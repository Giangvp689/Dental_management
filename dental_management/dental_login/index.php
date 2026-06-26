<?php
session_start();
require_once __DIR__ . "/../api/db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = trim($_POST["password"] ?? "");

    if ($username === "" || $password === "") {
        $error = "Vui lòng nhập đầy đủ thông tin!";
    } else {
        $sql = "
            SELECT user_id, username, password, full_name, role, status
            FROM users
            WHERE username = ?
            LIMIT 1
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            $error = "Tài khoản không tồn tại!";
        } elseif ((int)$user['status'] !== 1) {
            $error = "Tài khoản đã bị khóa!";
        }
        elseif ($password !== $user['password']) {
            $error = "Sai mật khẩu!";
        } else {
            // Đăng nhập thành công
            $_SESSION['user'] = [
                'user_id'   => $user['user_id'],
                'username'  => $user['username'],
                'full_name' => $user['full_name'],
                'role'      => $user['role'] // ADMIN | DOCTOR | RECEPTIONIST
            ];

            // Nếu là bác sĩ → lấy doctor_id
            if ($user['role'] === 'DOCTOR') {
              $stmt = $pdo->prepare("
                    SELECT doctor_id 
                    FROM doctors 
                    WHERE full_name = ?
                ");
                $stmt->execute([$user['full_name']]);
                $doctor = $stmt->fetch(PDO::FETCH_ASSOC);

                $_SESSION['user']['doctor_id'] = $doctor['doctor_id'] ?? null;
            }
        

            header("Location: /dental_management/dashboard/dashboard.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Dental Management System - Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="login-container">
    <div class="login-card">
        <h1>Dental Management</h1>
        <p class="subtitle">Clinic Management System</p>

        <form method="POST">
            <div class="input-group">
                <label>Username</label>
                <input type="text" name="username" required>
            </div>

            <div class="input-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>

            <button type="submit">Login</button>

            <?php if ($error): ?>
                <p class="error"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
        </form>
    </div>
</div>

</body>
</html>
