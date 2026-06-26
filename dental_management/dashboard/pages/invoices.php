<?php
require_once __DIR__ . "/../../api/db.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete_invoice') {

    $invoice_id = (int)$_POST['invoice_id'];

    $pdo->beginTransaction();

        try {
            // 1. Xóa chi tiết hóa đơn
            $pdo->prepare("
                DELETE FROM invoice_details
                WHERE invoice_id=?
            ")->execute([$invoice_id]);

            // 2. Xóa hóa đơn
            $pdo->prepare("
                DELETE FROM invoices
                WHERE invoice_id=?
            ")->execute([$invoice_id]);

        $pdo->commit();

        header("Location: dashboard.php?page=invoices");
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        die("Lỗi xóa hóa đơn: " . $e->getMessage());
    }
}

$sql = "
SELECT
    i.invoice_id,
    p.full_name AS patient,
    i.invoice_date,
    i.total_amount,
    i.status
FROM invoices i
JOIN patients p ON i.patient_id = p.patient_id
ORDER BY i.invoice_date DESC
";

$invoices = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="page-header">
    <h2>🧾 Hóa đơn</h2>
</div>

<div class="table-box">
    <table>
        <thead>
            <tr>
                <th>Mã HĐ</th>
                <th>Bệnh nhân</th>
                <th>Ngày</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($invoices as $i): ?>
            <tr>
                <td>#<?= $i['invoice_id'] ?></td>
                <td><?= htmlspecialchars($i['patient']) ?></td>
                <td><?= date('d/m/Y', strtotime($i['invoice_date'])) ?></td>
                <td><?= number_format($i['total_amount'] ?? 0) ?> đ</td>
                <td>
                    <span class="status <?= $i['status']=='Đã thanh toán'?'done':'pending' ?>">
                        <?= $i['status'] ?>
                    </span>
                </td>
                <td class="actions">
                    <a class="btn edit"
                    href="dashboard.php?page=invoice_detail&id=<?= $i['invoice_id'] ?>">
                    Xem
                    </a>

                    <?php if ($i['status'] !== 'Đã thanh toán'): ?>
                    <form method="post" style="display:inline"
                        onsubmit="return confirm('Xóa toàn bộ hóa đơn này?')">
                        <input type="hidden" name="invoice_id"
                            value="<?= $i['invoice_id'] ?>">
                        <button name="action"
                                value="delete_invoice"
                                class="btn edit"
                                style="background:#dc2626">
                            ❌ Xóa
                        </button>
                    </form>
                    <?php endif; ?>
                </td>

            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
