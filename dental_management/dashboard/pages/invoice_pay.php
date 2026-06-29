<?php
require_once __DIR__ . "/../../api/db.php";

$id = (int)($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $method = $_POST['payment_method'];
    $amount = $_POST['amount'];

    $pdo->prepare("
        INSERT INTO payments (invoice_id, payment_date, payment_method, amount)
        VALUES (?, CURDATE(), ?, ?)
    ")->execute([$id, $method, $amount]);

    $pdo->prepare("
        UPDATE invoices SET status='Đã thanh toán'
        WHERE invoice_id=?
    ")->execute([$id]);

    header("Location: dashboard.php?page=invoice_detail&id=$id");
    exit;
}
?>

<div class="medical-card">
    <h3>💰 Thanh toán hóa đơn</h3>

    <form method="post">
        <label>Phương thức</label>
        <select name="payment_method" required>
            <option>Tiền mặt</option>
            <option>Chuyển khoản</option>
            <option>Thẻ</option>
        </select>

        <label>Số tiền</label>
        <input type="number" name="amount" required>

        <button class="btn-add">Xác nhận</button>
    </form>
</div>
