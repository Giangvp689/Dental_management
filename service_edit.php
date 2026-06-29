<?php
require_once __DIR__ . "/../../api/db.php";

$id = (int)$_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM services WHERE service_id=?");
$stmt->execute([$id]);
$service = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$service) {
    echo "Không tìm thấy dịch vụ";
    return;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo->prepare("
        UPDATE services
        SET service_name=?, price=?, description=?
        WHERE service_id=?
    ")->execute([
        $_POST['service_name'],
        $_POST['price'],
        $_POST['description'],
        $id
    ]);

    header("Location: dashboard.php?page=services");
    exit;
}
?>

<div class="page-header">
    <h2>✏️ Sửa dịch vụ</h2>
</div>

<form method="post" class="form-box">
    <label>Tên dịch vụ</label>
    <input type="text" name="service_name"
           value="<?= htmlspecialchars($service['service_name']) ?>" required>

    <label>Giá</label>
    <input type="number" name="price"
           value="<?= $service['price'] ?>" required>

    <label>Mô tả</label>
    <textarea name="description"><?= htmlspecialchars($service['description']) ?></textarea>

    <button class="btn-add">Cập nhật</button>
</form>
