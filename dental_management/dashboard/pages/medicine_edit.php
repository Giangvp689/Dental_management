<?php
require_once __DIR__ . "/../../api/db.php";

$id = (int)$_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM medicines WHERE medicine_id=?");
$stmt->execute([$id]);
$medicine = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$medicine) {
    echo "Không tìm thấy thuốc";
    return;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $pdo->prepare("
        UPDATE medicines
        SET medicine_name=?, unit=?, price=?, stock=?, description=?
        WHERE medicine_id=?
    ")->execute([
        $_POST['medicine_name'],
        $_POST['unit'],
        $_POST['price'],
        $_POST['stock'],
        $_POST['description'],
        $id
    ]);

    header("Location: dashboard.php?page=medicines");
    exit;
}
?>

<div class="page-header">
    <h2>✏️ Sửa thuốc</h2>
</div>

<form method="post" class="form-box">

    <label>Tên thuốc</label>
    <input type="text" name="medicine_name"
           value="<?= htmlspecialchars($medicine['medicine_name']) ?>" required>

    <label>Đơn vị</label>
    <input type="text" name="unit"
           value="<?= htmlspecialchars($medicine['unit']) ?>">

    <label>Giá</label>
    <input type="number" name="price"
           value="<?= $medicine['price'] ?>" min="0">

    <label>Số lượng tồn kho</label>
    <input type="number" name="stock"
           value="<?= $medicine['stock'] ?>" min="0" required>

    <label>Mô tả</label>
    <textarea name="description"><?= htmlspecialchars($medicine['description']) ?></textarea>

    <button class="btn-add">Cập nhật</button>
</form>
