<?php
    require_once __DIR__ . "/../../api/db.php";

    $role    = $_SESSION['user']['role'] ?? '';
    $user_id = $_SESSION['user']['user_id'] ?? 0;
    require_once __DIR__ . "/../../dental_login/auth.php";
    requireRole(['ADMIN', 'DOCTOR']);

    $sql = "
        SELECT 
            p.patient_id,
            p.full_name,
            p.gender,
            p.phone,
            mr.record_id,
            mr.doctor_id AS treating_doctor
        FROM patients p
        LEFT JOIN medical_records mr
            ON mr.patient_id = p.patient_id
        AND mr.status = 'Đang khám'
        WHERE p.status = 'Hoạt động'
        ORDER BY p.created_at DESC
    ";

    $stmt = $pdo->query($sql);
    $patients = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="page-header">
    <h2>🩺 Khám chữa bệnh</h2>
</div>

<div class="table-box">
    <table>
        <thead>
            <tr>
                <th>Bệnh nhân</th>
                <th>Giới tính</th>
                <th>SĐT</th>
                <th>Thao tác</th>
            </tr>
        </thead>
    <tbody>

        <?php if (empty($patients)): ?>
        <tr>
            <td colspan="4" align="center">Không có bệnh nhân</td>
        </tr>

        <?php else: foreach ($patients as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p['full_name']) ?></td>
                <td><?= htmlspecialchars($p['gender']) ?></td>
                <td><?= htmlspecialchars($p['phone']) ?></td>
                <td class="actions">

                    <!-- KHÁM / TIẾP TỤC KHÁM -->
                    <?php if ($p['record_id'] && $p['treating_doctor'] == $user_id): ?>

                        <!-- Chính bác sĩ này đang khám -->
                        <a class="btn edit"
                        href="dashboard.php?page=medical_view&record_id=<?= $p['record_id'] ?>">
                        ▶ Tiếp tục khám
                        </a>

                    <?php elseif ($p['record_id'] === null): ?>

                        <!-- Chưa ai khám -->
                        <a class="btn edit"
                        href="dashboard.php?page=medical_view&patient_id=<?= $p['patient_id'] ?>">
                        ➕ Khám
                        </a>

                    <?php else: ?>

                        <!-- Đang được bác sĩ khác khám -->
                        <span style="color:#dc2626;font-size:13px;font-weight:600">
                            ⛔ Đang được bác sĩ khác khám 
                        </span>

                    <?php endif; ?>

                    <!-- HỒ SƠ BỆNH NHÂN (LUÔN CÓ) -->
                    <a class="btn edit"
                    href="dashboard.php?page=patient_view&patient_id=<?= $p['patient_id'] ?>">
                    👤 Hồ sơ
                    </a>

                </td>

            </tr>
        <?php endforeach; endif; ?>

        </tbody>
    </table>
</div>
