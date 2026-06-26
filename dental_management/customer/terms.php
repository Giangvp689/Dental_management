<?php
// terms.php - Trang điều khoản dịch vụ
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Điều khoản dịch vụ - Nha Khoa Sunshine</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Tahoma, sans-serif; 
            line-height: 1.6; 
            color: #333; 
            padding: 20px;
            background: linear-gradient(135deg, #f8fdff 0%, #e3f2fd 100%);
            min-height: 100vh;
        }
        .container { 
            max-width: 800px; 
            margin: 40px auto; 
            background: white; 
            padding: 50px; 
            border-radius: 20px; 
            box-shadow: 0 15px 40px rgba(0,0,0,0.1); 
        }
        h1 { 
            color: #0288d1; 
            margin-bottom: 25px; 
            text-align: center;
            font-size: 32px;
        }
        h2 { 
            color: #0277bd; 
            margin: 35px 0 18px; 
            font-size: 22px;
            border-bottom: 2px solid #e3f2fd;
            padding-bottom: 8px;
        }
        p { 
            margin-bottom: 18px; 
            text-align: justify;
        }
        ul { 
            margin-left: 25px; 
            margin-bottom: 25px; 
        }
        li {
            margin-bottom: 10px;
        }
        .back-btn { 
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-top: 40px; 
            padding: 14px 28px; 
            background: #0288d1; 
            color: white; 
            text-decoration: none; 
            border-radius: 10px; 
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .back-btn:hover {
            background: #0277bd;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(2, 119, 189, 0.3);
        }
        .highlight {
            background: #e3f2fd;
            padding: 20px;
            border-radius: 12px;
            border-left: 4px solid #0288d1;
            margin: 25px 0;
        }
        @media (max-width: 768px) {
            .container {
                padding: 30px 20px;
                margin: 20px auto;
            }
            h1 {
                font-size: 26px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📋 Điều Khoản Dịch Vụ & Chính Sách Bảo Mật</h1>
        
        <div class="highlight">
            <p><strong>Thông báo quan trọng:</strong> Bằng việc đăng ký lịch hẹn, bạn đồng ý với tất cả các điều khoản và điều kiện dưới đây.</p>
        </div>
        
        <h2>1. Cam kết bảo mật thông tin</h2>
        <p>Nha Khoa Sunshine cam kết bảo mật mọi thông tin cá nhân và y tế của bệnh nhân theo quy định của pháp luật Việt Nam. Thông tin của bạn sẽ chỉ được sử dụng cho mục đích:</p>
        <ul>
            <li>Khám chữa bệnh và điều trị</li>
            <li>Quản lý hồ sơ bệnh án điện tử</li>
            <li>Liên hệ xác nhận lịch hẹn và tư vấn sau điều trị</li>
            <li>Cải thiện chất lượng dịch vụ</li>
        </ul>
        
        <h2>2. Quy trình đăng ký và hủy lịch hẹn</h2>
        <p><strong>Đăng ký lịch hẹn:</strong></p>
        <ul>
            <li>Thông tin đăng ký phải chính xác và trung thực</li>
            <li>Lịch hẹn sẽ được xác nhận qua điện thoại/email trong vòng 24 giờ</li>
            <li>Vui lòng đến trước giờ hẹn ít nhất 15 phút để làm thủ tục</li>
        </ul>
        
        <p><strong>Hủy/dời lịch hẹn:</strong></p>
        <ul>
            <li>Có thể hủy hoặc dời lịch hẹn trước ít nhất 2 giờ làm việc</li>
            <li>Liên hệ hotline (028) 1234 5678 để thông báo hủy lịch</li>
            <li>Khách hàng hủy lịch quá 3 lần có thể bị hạn chế đặt lịch</li>
        </ul>
        
        <h2>3. Quyền lợi của bệnh nhân</h2>
        <ul>
            <li>Được tư vấn miễn phí trước và sau khi khám</li>
            <li>Được cung cấp hồ sơ bệnh án điện tử và phiếu điều trị chi tiết</li>
            <li>Được bảo mật thông tin y tế cá nhân</li>
            <li>Được lựa chọn bác sĩ điều trị (nếu có yêu cầu)</li>
            <li>Được giải đáp mọi thắc mắc về phác đồ điều trị</li>
        </ul>
        
        <h2>4. Trách nhiệm của bệnh nhân</h2>
        <ul>
            <li>Cung cấp thông tin chính xác về tiền sử bệnh, dị ứng thuốc</li>
            <li>Tuân thủ chỉ định và hướng dẫn của bác sĩ</li>
            <li>Thanh toán chi phí dịch vụ theo quy định</li>
            <li>Thông báo kịp thời khi không thể đến đúng hẹn</li>
        </ul>
        
        <h2>5. Chính sách thanh toán</h2>
        <ul>
            <li>Thanh toán sau khi hoàn tất dịch vụ</li>
            <li>Chấp nhận thanh toán bằng tiền mặt, chuyển khoản, thẻ tín dụng/ghi nợ</li>
            <li>Xuất hóa đơn VAT theo yêu cầu</li>
            <li>Áp dụng bảo hiểm y tế theo quy định của nhà nước</li>
        </ul>
        
        <h2>6. Liên hệ khiếu nại</h2>
        <p>Nếu có bất kỳ thắc mắc hoặc khiếu nại nào về dịch vụ, vui lòng liên hệ:</p>
        <p><strong>Nha Khoa Sunshine</strong><br>
        📞 Hotline: (028) 1234 5678<br>
        📧 Email: info@nhakhoasunshine.vn<br>
        🏢 Địa chỉ: 123 Đường ABC, Quận XYZ, TP.HCM<br>
        ⏰ Giờ làm việc: Thứ 2 - Thứ 7: 7:30 - 19:30, Chủ nhật: 7:30 - 17:00</p>
        
        <p style="color: #666; font-style: italic; margin-top: 30px;">
            * Các điều khoản có thể được cập nhật theo thời gian. Phiên bản hiệu lực: 01/2024
        </p>
        
        <a href="register.php" class="back-btn">
            <i class="fas fa-arrow-left"></i> Quay lại đăng ký
        </a>
        <a href="index.php" class="back-btn" style="background: #666; margin-left: 15px;">
            <i class="fas fa-home"></i> Trang chủ
        </a>
    </div>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</body>
</html>