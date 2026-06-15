<?php
require_once __DIR__ . "/../api/db.php";

/* ===== LẤY DỮ LIỆU ===== */
$services = $pdo->query("SELECT service_id, service_name, price FROM services")->fetchAll();
$doctors  = $pdo->query("SELECT doctor_id, full_name, specialty FROM doctors WHERE status='Hoạt động'")->fetchAll();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Nha khoa Nhóm 5 – Phòng khám răng hàm mặt</title>

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:"Segoe UI",Tahoma,sans-serif}
body{background:#f8fafc;color:#0f172a}

/* ===== HEADER ===== */
header{
    position:sticky;
    top:0;
    z-index:1000;
    background:#ffffffee;
    backdrop-filter:blur(8px);
    padding:16px 70px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    box-shadow:0 6px 30px rgba(0,0,0,.06)
}
.logo{font-size:24px;font-weight:800;color:#0ea5e9}
nav a{
    margin-left:28px;
    text-decoration:none;
    color:#334155;
    font-weight:600
}
nav a:hover{color:#0ea5e9}

/* ===== HERO ===== */
.hero{
    background:
        linear-gradient(90deg,rgba(14,165,233,.9),rgba(14,165,233,.65)),
        url("https://images.unsplash.com/photo-1606813902634-15b8d0a1c6bb?auto=format&fit=crop&w=1600&q=80");
    background-size:cover;
    background-position:center;
    padding:140px 70px 160px;
    color:#fff
}
.hero-box{max-width:600px}
.hero h1{font-size:46px;line-height:1.2}
.hero p{margin-top:18px;font-size:16px;line-height:1.7}
.hero-actions{margin-top:32px;display:flex;gap:16px}
.hero button{
    padding:15px 36px;
    border:none;
    border-radius:30px;
    font-size:15px;
    font-weight:800;
    cursor:pointer
}
.btn-main{background:#fff;color:#0ea5e9}
.btn-outline{background:transparent;border:2px solid #fff;color:#fff}

/* ===== SECTION ===== */
.section{max-width:1100px;margin:90px auto;padding:0 20px}
.section h2{text-align:center;font-size:34px;margin-bottom:50px}

/* ===== SERVICES ===== */
.service-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:28px}
.service{
    background:#fff;
    padding:32px;
    border-radius:26px;
    box-shadow:0 15px 40px rgba(0,0,0,.08)
}
.service span{display:block;margin-top:10px;color:#0ea5e9;font-weight:800}

/* ===== DOCTORS ===== */
.doctor-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:32px}
.doctor{
    background:#fff;
    border-radius:26px;
    overflow:hidden;
    box-shadow:0 15px 40px rgba(0,0,0,.1);
    text-align:center
}
.doctor img{width:100%;height:240px;object-fit:cover}
.doctor h4{margin:18px 0 6px}
.doctor p{font-size:14px;color:#64748b;margin-bottom:16px}

/* ===== CTA ===== */
.cta{
    background:linear-gradient(90deg,#0ea5e9,#38bdf8);
    padding:80px 20px;
    text-align:center;
    color:#fff
}
.cta h2{font-size:34px}
.cta button{
    margin-top:24px;
    padding:16px 42px;
    border:none;
    border-radius:30px;
    font-size:16px;
    font-weight:800;
    background:#fff;
    color:#0ea5e9;
    cursor:pointer
}

/* ===== FOOTER ===== */
footer{
    background:#0f172a;
    color:#cbd5f5;
    padding:26px;
    text-align:center;
    font-size:14px
}

/* ===== MODAL ===== */
.modal{
    position:fixed;
    inset:0;
    display:none;
    z-index:9999
}
.modal.show{display:block}
.modal-overlay{
    position:absolute;
    inset:0;
    background:rgba(0,0,0,.6)
}
.modal-box{
    position:relative;
    width:560px;
    max-height:90vh;
    overflow:auto;
    background:#fff;
    border-radius:26px;
    margin:5vh auto;
    padding:30px
}
.modal-close{
    position:absolute;
    top:14px;
    right:18px;
    font-size:26px;
    cursor:pointer
}

@media(max-width:900px){
    header{padding:16px 20px}
    .hero{padding:120px 20px}
    .service-grid,.doctor-grid{grid-template-columns:1fr}
}
</style>
</head>

<body>

<header>
    <div class="logo">🦷 Nha khoa Nhóm 5</div>
    <nav>
        <a href="#">Trang chủ</a>
        <a href="#services">Dịch vụ</a>
        <a href="#doctors">Bác sĩ</a>
        <a href="#" onclick="openModal()">Đặt lịch</a>
    </nav>
</header>

<section class="hero">
    <div class="hero-box">
        <h1>Phòng khám răng hàm mặt<br>chuẩn y khoa hiện đại</h1>
        <p>Chăm sóc răng miệng an toàn – cá nhân hóa – chuẩn Bộ Y tế.</p>
        <div class="hero-actions">
            <button class="btn-main" onclick="openModal()">📅 Đặt lịch ngay</button>
            <button class="btn-outline" onclick="document.getElementById('services').scrollIntoView({behavior:'smooth'})">
                Xem dịch vụ
            </button>
        </div>
    </div>
</section>

<section class="section" id="services">
<h2>Dịch vụ nổi bật</h2>
<div class="service-grid">
<?php foreach($services as $s): ?>
<div class="service">
    <h4><?= htmlspecialchars($s['service_name']) ?></h4>
    <span><?= number_format($s['price']) ?> VNĐ</span>
</div>
<?php endforeach; ?>
</div>
</section>

<section class="section" id="doctors">
<h2>Đội ngũ bác sĩ</h2>
<div class="doctor-grid">
<?php foreach($doctors as $d): ?>
<div class="doctor">
    <img src="https://nhakhoaviethan.vn/wp-content/uploads/2021/12/Bs-Quan-1024x1024.jpg">
    <h4><?= htmlspecialchars($d['full_name']) ?></h4>
    <p><?= htmlspecialchars($d['specialty']) ?></p>
</div>
<?php endforeach; ?>
</div>
</section>

<section class="cta">
    <h2>Bạn đang gặp vấn đề về răng miệng?</h2>
    <button onclick="openModal()">Đặt lịch tư vấn miễn phí</button>
</section>

<footer>
    © 2026 Nha khoa Nhóm 5 – Chăm sóc nụ cười Việt
</footer>

<!-- ===== MODAL FORM ===== -->
<div class="modal" id="modal">
    <div class="modal-overlay" onclick="closeModal()"></div>
    <div class="modal-box">
        <span class="modal-close" onclick="closeModal()">×</span>

        <?php
        // include chuẩn ngữ cảnh
        include __DIR__ . "/register.php";
        ?>

    </div>
</div>

<script>
function openModal(){
    document.getElementById('modal').classList.add('show');
}
function closeModal(){
    document.getElementById('modal').classList.remove('show');
}
</script>

</body>
<?php if (isset($_GET['status'])): ?>
<script>
<?php if ($_GET['status'] === 'success'): ?>
    alert("✅ Đặt lịch thành công! Phòng khám sẽ liên hệ với bạn.");
<?php elseif ($_GET['status'] === 'duplicate'): ?>
    alert("⚠ Lịch hẹn với số điện thoại này tại thời điểm trên đã tồn tại!");
<?php endif; ?>
</script>
<?php endif; ?>

</html>
