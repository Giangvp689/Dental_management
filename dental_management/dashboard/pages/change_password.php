<?php
require_once __DIR__ . "/../../api/db.php";

$user_id = (int)$_SESSION['user']['user_id'];
$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current  = $_POST['current_password']  ?? '';
    $new      = $_POST['new_password']      ?? '';
    $confirm  = $_POST['confirm_password']  ?? '';

    /* ── Lấy mật khẩu hiện tại từ DB ── */
    $stmt = $pdo->prepare("SELECT password FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $row = $stmt->fetch();

    if (!$row) {
        $error = 'Không tìm thấy tài khoản.';
    } elseif ($current !== $row['password']) {
        $error = 'Mật khẩu hiện tại không đúng.';
    } elseif (strlen($new) < 6) {
        $error = 'Mật khẩu mới phải có ít nhất 6 ký tự.';
    } elseif ($new !== $confirm) {
        $error = 'Xác nhận mật khẩu không khớp.';
    } elseif ($new === $current) {
        $error = 'Mật khẩu mới không được trùng mật khẩu cũ.';
    } else {
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE user_id = ?");
        $stmt->execute([$new, $user_id]);
        $success = 'Đổi mật khẩu thành công!';
    }
}
?>

<div class="form-box" style="max-width:440px">
    <h3>🔑 Đổi mật khẩu</h3>
    <p style="color:#6b7280;font-size:13px;margin-top:-8px;margin-bottom:16px">
        Tài khoản: <b><?= htmlspecialchars($_SESSION['user']['username']) ?></b>
    </p>

    <?php if ($success): ?>
        <div style="background:#d1fae5;border:1px solid #6ee7b7;color:#065f46;
                    padding:10px 14px;border-radius:6px;margin-bottom:16px;font-size:14px">
            ✅ <?= $success ?>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div style="background:#fee2e2;border:1px solid #fca5a5;color:#991b1b;
                    padding:10px 14px;border-radius:6px;margin-bottom:16px;font-size:14px">
            ❌ <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <label>Mật khẩu hiện tại</label>
        <input type="password" name="current_password" required
               placeholder="Nhập mật khẩu hiện tại">

        <label>Mật khẩu mới</label>
        <input type="password" name="new_password" required
               placeholder="Tối thiểu 6 ký tự" id="new_pw">

        <label>Xác nhận mật khẩu mới</label>
        <input type="password" name="confirm_password" required
               placeholder="Nhập lại mật khẩu mới" id="confirm_pw">

        <!-- Thanh độ mạnh mật khẩu -->
        <div style="margin-top:-8px;margin-bottom:12px">
            <div style="height:4px;background:#e5e7eb;border-radius:4px;overflow:hidden">
                <div id="strength-bar"
                     style="height:100%;width:0%;transition:width .3s,background .3s;border-radius:4px">
                </div>
            </div>
            <span id="strength-text"
                  style="font-size:11px;color:#9ca3af"></span>
        </div>

        <button class="btn-add" style="width:100%">🔒 Đổi mật khẩu</button>
    </form>
</div>

<script>
document.getElementById('new_pw').addEventListener('input', function () {
    const val = this.value;
    let score = 0;
    if (val.length >= 6)  score++;
    if (val.length >= 10) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;

    const bar   = document.getElementById('strength-bar');
    const label = document.getElementById('strength-text');
    const levels = [
        { w: '0%',   bg: '',        text: '' },
        { w: '25%',  bg: '#ef4444', text: 'Rất yếu' },
        { w: '50%',  bg: '#f97316', text: 'Yếu' },
        { w: '75%',  bg: '#eab308', text: 'Trung bình' },
        { w: '90%',  bg: '#22c55e', text: 'Mạnh' },
        { w: '100%', bg: '#16a34a', text: 'Rất mạnh' },
    ];
    const lv = val.length === 0 ? 0 : Math.min(score, 5);
    bar.style.width      = levels[lv].w;
    bar.style.background = levels[lv].bg;
    label.textContent    = levels[lv].text;
    label.style.color    = levels[lv].bg;
});
</script>