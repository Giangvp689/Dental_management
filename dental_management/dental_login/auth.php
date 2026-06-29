<?php
if (!isset($_SESSION['user'])) {
    header("Location: /dental_login/index.php");
    exit;
}

function requireRole(array $roles) {
    if (!in_array($_SESSION['user']['role'], $roles)) {
        http_response_code(403);
        die("Bạn không có quyền truy cập chức năng này");
    }
}
