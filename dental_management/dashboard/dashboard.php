<?php
session_start();

/* ===============================
   AUTH
================================ */
if (!isset($_SESSION["user"])) {
    header("Location: ../dental_login/index.php");
    exit;
}

require_once __DIR__ . "/../api/db.php";
/* ===============================
   PERMISSIONS
================================ */
$permissions = require __DIR__ . "/permissions.php";

$role = $_SESSION['user']['role'] ?? '';
$allowModules = $permissions[$role] ?? [];

/**
 * Lấy module từ page
 * medical_view → medical
 * patient_view → patients
 */
function getModule($page) {

    // mapping page con → module cha
    $map = [
        'patient'  => 'patients',
        'patients' => 'patients',

        'medical'  => 'medical',

        'invoice'  => 'invoices',
        'invoices' => 'invoices',

        'appointment'  => 'appointments',
        'appointments' => 'appointments',

        'doctor'  => 'doctors',
        'doctors' => 'doctors',
          
        'service'   => 'services',
        'services'  => 'services',

        'account'   => 'accounts',
        'accounts'  => 'accounts',

        'staff'     => 'staffs',
        'staffs'    => 'staffs',


        'medicine'   => 'medicines',
        'medicines'  => 'medicines',
        
        'report'   => 'reports',
        'reports'  => 'reports'
    ];

    $base = $page;

    if (str_contains($page, '_')) {
        $base = explode('_', $page)[0];
    }

    return $map[$base] ?? $base;
}


function canView($module) {
    global $allowModules;
    return in_array($module, $allowModules);
}

/* ===============================
   ROUTER
================================ */
$page = $_GET['page'] ?? 'dashboard';
$module = getModule($page);

if (!canView($module)) {
    die("❌ Bạn không có quyền truy cập chức năng này");
}


/* ===============================
   DASHBOARD DATA
================================ */
$totalPatients = $pdo->query("SELECT COUNT(*) FROM patients")->fetchColumn();

$todayAppointments = $pdo->query("
    SELECT COUNT(*) FROM appointments
    WHERE appointment_date = CURDATE()
")->fetchColumn();

$todayRevenue = $pdo->query("
    SELECT IFNULL(SUM(amount),0)
    FROM payments
    WHERE payment_date = CURDATE()
")->fetchColumn();

$totalServices = $pdo->query("SELECT COUNT(*) FROM services")->fetchColumn();

$appointmentsToday = $pdo->query("
    SELECT 
        p.full_name AS patient,
        d.full_name AS doctor,
        a.appointment_time,
        a.status
    FROM appointments a
    JOIN patients p ON a.patient_id = p.patient_id
    JOIN doctors d ON a.doctor_id = d.doctor_id
    WHERE a.appointment_date = CURDATE()
    ORDER BY a.appointment_time
")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="UTF-8">
        <title>Dental Dashboard</title>
        <link rel="stylesheet" href="dashboard.css">
    </head>
    <body>

        <!-- ===============================
            SIDEBAR
        ================================ -->
        <aside class="sidebar">
            <div class="logo">🦷 Dental Clinic</div>

            <ul class="menu">

                <?php if (canView('dashboard')): ?>
                <li class="<?= $page=='dashboard'?'active':'' ?>">
                    <a href="dashboard.php?page=dashboard">📊 Dashboard</a>
                </li>
                <?php endif; ?>

                <?php if (canView('appointments')): ?>
                <li class="<?= $page=='appointments'?'active':'' ?>">
                    <a href="dashboard.php?page=appointments">🕜Lịch hẹn</a></li>
                <?php endif; ?>

                <?php if (canView('patients')): ?>
                <li class="<?= $page=='patients'?'active':'' ?>">
                    <a href="dashboard.php?page=patients">🦷Bệnh nhân</a></li>
                <?php endif; ?>

                <?php if (canView('doctors')): ?>
                <li class="<?= $page=='doctors'?'active':'' ?>">
                    <a href="dashboard.php?page=doctors">🧑‍⚕️Bác sĩ</a></li>
                <?php endif; ?>

                <?php if (canView('medical')): ?>
                <li class="<?= $page=='medical'?'active':'' ?>">
                    <a href="dashboard.php?page=medical">🕵️Khám chữa bệnh</a></li>
                <?php endif; ?>

                <?php if (canView('services')): ?>
                <li class="<?= $page=='services'?'active':'' ?>">
                    <a href="dashboard.php?page=services">💁Dịch vụ</a></li>
                <?php endif; ?>

                <?php if (canView('medicines')): ?>
                <li class="<?= $page=='medicines'?'active':'' ?>">
                    <a href="dashboard.php?page=medicines">💊Thuốc</a></li>
                <?php endif; ?>

                <?php if (canView('invoices')): ?>
                <li class="<?= $page=='invoices'?'active':'' ?>">
                    <a href="dashboard.php?page=invoices">💰Hóa đơn</a></li>
                <?php endif; ?>
                
                <?php if (canView('staffs')): ?>
                <li class="<?= $page=='staffs'?'active':'' ?>">
                    <a href="dashboard.php?page=staffs">👥 Nhân viên</a></li>
                <?php endif; ?>

                <?php if (canView('accounts')): ?>
                <li class="<?= $page=='accounts'?'active':'' ?>">
                    <a href="dashboard.php?page=accounts">🔐 Tài khoản</a></li>
                <?php endif; ?>

                <?php if (canView('reports')): ?>
                <li class="<?= $page=='reports'?'active':'' ?>">
                    <a href="dashboard.php?page=reports">📊Báo cáo thống kê</a></li>
                <?php endif; ?>

                <li class="logout">
                    <a href="../dental_login/index.php">🚪 Đăng xuất</a>
                </li>
            </ul>
        </aside>

        <!-- ===============================
            MAIN
        ================================ -->
        <main class="main">

            <?php if ($page === 'dashboard'): ?>

                    <!-- TOPBAR -->
                    <header class="topbar">
                        <span>
                            Xin chào, 
                            <b><?= htmlspecialchars($_SESSION["user"]["full_name"] ?? $_SESSION["user"]["username"]) ?></b>
                            (<?= $role ?>)
                        </span>
                        <span><?= date("d/m/Y") ?></span>
                    </header>

                    <!-- CARDS -->
                    <div class="cards">

                        <?php if (canView('patients')): ?>
                        <div class="card">
                            <h3>👤Lượt khám Bệnh nhân</h3>
                            <p><?= $totalPatients ?></p>
                        </div>
                        <?php endif; ?>

                        <?php if (canView('appointments')): ?>
                        <div class="card">
                            <h3>📅 Lịch hôm nay</h3>
                            <p><?= $todayAppointments ?></p>
                        </div>
                        <?php endif; ?>

                        <?php if (canView('invoices')): ?>
                        <div class="card">
                            <h3>💰 Doanh thu</h3>
                            <p><?= number_format($todayRevenue) ?> đ</p>
                        </div>
                        <?php endif; ?>

                        <?php if (canView('services')): ?>
                        <div class="card">
                            <h3>🦷 Dịch vụ</h3>
                            <p><?= $totalServices ?></p>
                        </div>
                        <?php endif; ?>

                    </div>

                    <!-- TABLE -->
                    <?php if (canView('appointments')): ?>
                    <div class="table-box">
                        <h3>Lịch hẹn hôm nay</h3>
                        <table>
                            <thead>
                                <tr>
                                    <th>Bệnh nhân</th>
                                    <th>Bác sĩ</th>
                                    <th>Giờ</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!$appointmentsToday): ?>
                                    <tr>
                                        <td colspan="4">Không có lịch hôm nay</td>
                                    </tr>
                                <?php else: foreach ($appointmentsToday as $a): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($a['patient']) ?></td>
                                        <td><?= htmlspecialchars($a['doctor']) ?></td>
                                        <td><?= substr($a['appointment_time'],0,5) ?></td>
                                        <td><?= $a['status'] ?></td>
                                    </tr>
                                <?php endforeach; endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>

            <?php else: ?>

                <!-- LOAD PAGE -->
                <?php include __DIR__ . "/pages/$page.php"; ?>

            <?php endif; ?>

        </main>

    </body>
</html>
