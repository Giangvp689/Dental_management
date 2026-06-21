-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th6 21, 2026 lúc 11:29 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `dental_management`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `appointments`
--

CREATE TABLE `appointments` (
  `appointment_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `service_id` int(11) DEFAULT NULL,
  `request_id` int(11) DEFAULT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `status` enum('Đang khám','Hoàn thành','Hủy') DEFAULT 'Đang khám'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `appointments`
--

INSERT INTO `appointments` (`appointment_id`, `patient_id`, `doctor_id`, `service_id`, `request_id`, `appointment_date`, `appointment_time`, `status`) VALUES
(1, 1, 1, NULL, 1, '2025-01-10', '08:00:00', 'Hoàn thành'),
(2, 2, 2, NULL, 2, '2025-01-11', '09:00:00', 'Hoàn thành'),
(3, 3, 3, NULL, 4, '2025-01-13', '14:00:00', 'Hoàn thành'),
(4, 4, 4, NULL, 6, '2025-01-15', '08:30:00', 'Hoàn thành'),
(5, 5, 5, NULL, 8, '2025-01-17', '10:30:00', 'Hoàn thành'),
(7, 7, 1, NULL, NULL, '2025-02-01', '08:00:00', 'Hoàn thành'),
(8, 8, 2, NULL, NULL, '2025-02-02', '09:00:00', 'Đang khám'),
(9, 9, 3, NULL, NULL, '2025-02-03', '10:00:00', 'Đang khám'),
(10, 10, 4, NULL, NULL, '2025-02-04', '14:00:00', 'Đang khám'),
(11, 11, 1, NULL, 7, '2025-01-16', '09:30:00', 'Đang khám'),
(14, 15, 1, NULL, 14, '2026-01-19', '09:44:00', 'Đang khám'),
(16, 16, 2, 2, 17, '2026-01-19', '10:25:00', 'Đang khám'),
(17, 17, 1, 5, 2902, '2026-01-12', '22:07:00', 'Đang khám'),
(19, 18, 5, 9, 2904, '2026-01-12', '19:30:00', 'Đang khám'),
(20, 19, 5, 8, 2909, '2026-01-13', '00:20:00', 'Đang khám'),
(21, 19, 5, 8, 2909, '2026-01-13', '00:20:00', 'Đang khám'),
(22, 20, 1, 1, 2907, '2026-01-13', '23:36:00', 'Đang khám'),
(23, 21, 3, 5, 2910, '2026-01-16', '15:50:00', 'Đang khám'),
(24, 26, 10, 3, 2911, '2026-04-07', '18:50:00', 'Đang khám'),
(25, 27, 1, 1, 2912, '2026-04-07', '22:36:00', 'Đang khám'),
(26, 13, 1, 1, 2906, '2026-01-13', '19:31:00', 'Đang khám');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `appointment_requests`
--

CREATE TABLE `appointment_requests` (
  `request_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `gender` enum('Nam','Nữ','Khác') DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `source` enum('Website','Điện thoại','Fanpage') DEFAULT 'Website',
  `note` text DEFAULT NULL,
  `status` enum('Chờ xác nhận','Đã xác nhận','Đã đến','Không đến','Hủy') DEFAULT 'Chờ xác nhận',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `doctor_id` int(11) DEFAULT NULL,
  `service_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `appointment_requests`
--

INSERT INTO `appointment_requests` (`request_id`, `full_name`, `phone`, `email`, `gender`, `dob`, `appointment_date`, `appointment_time`, `source`, `note`, `status`, `created_at`, `doctor_id`, `service_id`) VALUES
(1, 'Nguyễn Văn A', '0911111111', 'a@gmail.com', 'Nam', '1995-02-10', '2025-01-10', '08:00:00', 'Website', NULL, 'Đã đến', '2025-12-31 14:44:40', NULL, NULL),
(2, 'Trần Thị B', '0911111112', 'b@gmail.com', 'Nữ', '1998-03-15', '2025-01-11', '09:00:00', 'Website', NULL, 'Đã đến', '2025-12-31 14:44:40', NULL, NULL),
(4, 'Phạm Thị D', '0911111114', 'd@gmail.com', 'Nữ', '1992-06-18', '2025-01-13', '14:00:00', 'Website', NULL, 'Đã đến', '2025-12-31 14:44:40', NULL, NULL),
(5, 'Hoàng Văn E', '0911111115', 'e@gmail.com', 'Nam', '1985-07-22', '2025-01-14', '15:00:00', 'Website', NULL, 'Hủy', '2025-12-31 14:44:40', NULL, NULL),
(6, 'Võ Thị F', '0911111116', 'f@gmail.com', 'Nữ', '2000-01-01', '2025-01-15', '08:30:00', 'Website', NULL, 'Chờ xác nhận', '2025-12-31 14:44:40', 2, 3),
(7, 'Đặng Văn G', '0911111117', 'g@gmail.com', 'Nam', '1996-09-09', '2025-01-16', '09:30:00', 'Website', NULL, 'Đã đến', '2025-12-31 14:44:40', NULL, NULL),
(8, 'Ngô Thị H', '0911111118', 'h@gmail.com', 'Nữ', '1994-11-11', '2025-01-17', '10:30:00', 'Website', NULL, 'Đã đến', '2025-12-31 14:44:40', NULL, NULL),
(14, 'Vũ Kỳ 2', '0987654345', 'vuky123@gmail.com', 'Nữ', '2026-01-28', '2026-01-11', '09:44:00', 'Website', '', 'Hủy', '2026-01-02 10:40:59', 3, 4),
(17, 'A ah auaua ', '1234567890', 'nducthanh111@gmail.com', 'Nam', '2026-01-12', '2026-01-11', '10:25:00', 'Website', '', 'Hủy', '2026-01-02 12:22:26', 2, 2),
(2902, 'Nguyễn Đình Chiến', '0989098998', 'nducthanh111@gmail.com', 'Nam', '2005-01-11', '2026-01-12', '22:07:00', 'Website', '', 'Đã đến', '2026-01-12 10:07:17', 1, 5),
(2904, 'Phạm Thị Liên', '0909888777', 'lientt@gmail.com', 'Nam', '1999-02-13', '2026-01-12', '19:30:00', 'Website', '', 'Đã đến', '2026-01-12 10:25:50', 5, 9),
(2906, 'Đức Thành', '0965899760', 'nducthanh111@gmail.com', 'Nữ', '2026-01-12', '2026-01-13', '19:31:00', 'Website', '', 'Đã đến', '2026-01-12 10:29:47', 1, 1),
(2907, 'Văn  AHhAGhg', '07654341514', 'giagvp123@gmai.com', 'Nam', '2000-12-12', '2026-01-13', '23:36:00', 'Website', '', 'Đã đến', '2026-01-12 10:30:18', 1, 1),
(2908, 'Đức Thành', '0987654345', 'nducthanh111@gmail.com', 'Nam', '2026-01-05', '2026-01-16', '08:40:00', 'Website', '', 'Đã xác nhận', '2026-01-12 10:37:14', 6, 4),
(2909, 'Mai Hoa', '0951425362', 'hoal@gmail.com', 'Nam', '2000-12-12', '2026-01-13', '00:20:00', 'Website', '', 'Đã xác nhận', '2026-01-13 01:19:19', 5, 8),
(2910, 'Linh ', '05432135567', 'linh@gmail.com', 'Nữ', '1999-11-13', '2026-01-16', '15:50:00', 'Website', '', 'Đã xác nhận', '2026-01-16 06:51:01', 3, 5),
(2911, 'Đức Thành', '0987654341', 'nducthanh111@gmail.com', 'Nam', '2026-04-08', '2026-04-07', '18:50:00', 'Website', 'ok', 'Chờ xác nhận', '2026-04-01 08:47:38', 10, 3),
(2912, 'Lan', '0987654343', 'nducthanh111@gmail.com', 'Nam', '1999-04-08', '2026-04-07', '22:36:00', 'Website', '', 'Đã đến', '2026-04-07 01:34:48', 1, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `doctors`
--

CREATE TABLE `doctors` (
  `doctor_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `specialty` varchar(100) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `status` enum('Hoạt động','Ngưng') DEFAULT 'Hoạt động'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `doctors`
--

INSERT INTO `doctors` (`doctor_id`, `full_name`, `specialty`, `phone`, `email`, `status`) VALUES
(1, 'BS Nguyễn An', 'Răng tổng quát', '0901000001', 'an@gmail.com', 'Hoạt động'),
(2, 'BS Trần Bình', 'Chỉnh nha', '0901000002', 'binh@gmail.com', 'Hoạt động'),
(3, 'BS Lê Cường', 'Implant', '0901000003', 'cuong@gmail.com', 'Hoạt động'),
(4, 'BS Phạm Dũng', 'Nha chu', '0901000004', 'dung@gmail.com', 'Hoạt động'),
(5, 'BS Võ Hạnh', 'Răng trẻ em', '0901000005', 'hanh@gmail.com', 'Hoạt động'),
(6, 'BS Đỗ Minh', 'Phẫu thuật', '0901000006', 'minh@gmail.com', 'Hoạt động'),
(7, 'BS Lý Anh', 'Thẩm mỹ', '0901000007', 'anh@gmail.com', 'Hoạt động'),
(8, 'BS Hoàng Long', 'Tổng quát', '0901000008', 'long@gmail.com', 'Hoạt động'),
(9, 'BS Mai Phương', 'Nội nha', '0901000009', 'phuong@gmail.com', 'Ngưng'),
(10, 'BS Trịnh Sơn', 'Răng hàm mặt', '0901000010', 'son@gmail.com', 'Hoạt động'),
(11, 'Thanh Linh', 'Răng hàm mặt', '0987654321', 'giagdz123@gmail.com', 'Hoạt động');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `invoices`
--

CREATE TABLE `invoices` (
  `invoice_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `invoice_date` date DEFAULT curdate(),
  `status` enum('Chưa thanh toán','Đã thanh toán') DEFAULT 'Chưa thanh toán',
  `record_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `invoices`
--

INSERT INTO `invoices` (`invoice_id`, `patient_id`, `total_amount`, `invoice_date`, `status`, `record_id`) VALUES
(1, 1, 300000.00, '2025-01-10', 'Đã thanh toán', 0),
(2, 2, 25000000.00, '2025-01-11', 'Đã thanh toán', 0),
(3, 3, 15000000.00, '2025-01-13', 'Đã thanh toán', 0),
(4, 4, 800000.00, '2025-01-15', 'Đã thanh toán', 0),
(5, 5, 1200000.00, '2025-01-17', 'Đã thanh toán', 0),
(6, 6, 6000000.00, '2025-01-18', 'Đã thanh toán', 0),
(7, 7, 100000.00, '2025-02-01', 'Chưa thanh toán', 0),
(8, 8, 100000.00, '2025-02-02', 'Chưa thanh toán', 0),
(9, 9, 15000000.00, '2025-02-03', 'Chưa thanh toán', 0),
(13, 11, NULL, '2025-12-31', 'Đã thanh toán', 0),
(21, 17, 2003500.00, '2026-01-12', 'Đã thanh toán', 53),
(22, 17, 2000000.00, '2026-01-12', 'Chưa thanh toán', 54),
(23, 19, 0.00, '2026-01-13', 'Đã thanh toán', 58),
(24, 17, 0.00, '2026-01-13', 'Chưa thanh toán', 55),
(25, 7, 4000.00, '2026-01-13', 'Chưa thanh toán', 7),
(26, 18, 828000.00, '2026-01-13', 'Chưa thanh toán', 73),
(28, 19, 4007000.00, '2026-01-14', 'Chưa thanh toán', 74);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `invoice_details`
--

CREATE TABLE `invoice_details` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `invoice_details`
--

INSERT INTO `invoice_details` (`id`, `invoice_id`, `service_id`, `quantity`, `price`) VALUES
(1, 1, 2, 1, 300000.00),
(2, 2, 4, 1, 25000000.00),
(3, 3, 5, 1, 15000000.00),
(4, 4, 9, 1, 800000.00),
(5, 5, 10, 1, 1200000.00),
(6, 6, 6, 1, 6000000.00),
(7, 7, 1, 1, 100000.00),
(8, 8, 1, 1, 100000.00),
(9, 9, 5, 1, 15000000.00),
(24, 21, 8, 1, 2000000.00),
(25, 22, 8, 1, 2000000.00),
(26, 26, 9, 1, 800000.00),
(27, 28, 8, 2, 2000000.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `medical_records`
--

CREATE TABLE `medical_records` (
  `record_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `appointment_id` int(11) DEFAULT NULL,
  `visit_date` date NOT NULL,
  `symptoms` text DEFAULT NULL,
  `diagnosis` text DEFAULT NULL,
  `treatment_plan` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('Đang khám','Hoàn tất') DEFAULT 'Đang khám',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `medical_records`
--

INSERT INTO `medical_records` (`record_id`, `patient_id`, `doctor_id`, `appointment_id`, `visit_date`, `symptoms`, `diagnosis`, `treatment_plan`, `notes`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, '2025-01-10', 'Đau răng', 'Sâu răng', 'Trám răng', NULL, 'Hoàn tất', '2025-12-31 14:44:40', '2025-12-31 14:44:40'),
(2, 2, 2, 2, '2025-01-11', 'Răng lệch', 'Sai khớp cắn', 'Niềng răng', NULL, 'Hoàn tất', '2025-12-31 14:44:40', '2025-12-31 14:44:40'),
(3, 3, 3, 3, '2025-01-13', 'Mất răng', 'Mất răng số 6', 'Cấy Implant', NULL, 'Hoàn tất', '2025-12-31 14:44:40', '2025-12-31 14:44:40'),
(4, 4, 4, 4, '2025-01-15', 'Chảy máu chân răng', 'Viêm nha chu', 'Điều trị nha chu', NULL, 'Hoàn tất', '2025-12-31 14:44:40', '2025-12-31 14:44:40'),
(5, 5, 5, 5, '2025-01-17', 'Đau răng sữa', 'Viêm tủy', 'Điều trị tủy', NULL, 'Hoàn tất', '2025-12-31 14:44:40', '2025-12-31 14:44:40'),
(6, 6, 6, NULL, '2025-01-18', 'Răng gãy', 'Chấn thương', 'Bọc sứ', NULL, 'Hoàn tất', '2025-12-31 14:44:40', '2025-12-31 14:44:40'),
(7, 7, 1, 7, '2025-02-01', 'Đau hàm', 'Viêm hàm        ', 'Theo dõi        ', NULL, 'Hoàn tất', '2025-12-31 14:44:40', '2026-01-13 08:42:02'),
(8, 8, 2, 8, '2025-02-02', 'Răng mọc lệch', 'Sai khớp', 'Tư vấn', NULL, 'Hoàn tất', '2025-12-31 14:44:40', '2026-01-13 09:01:00'),
(9, 9, 3, 9, '2025-02-03', 'Mất răng', NULL, NULL, NULL, 'Hoàn tất', '2025-12-31 14:44:40', '2026-01-12 08:33:52'),
(10, 10, 4, 10, '2025-02-04', 'Viêm nướu', 'Nha chu', 'Điều trị', NULL, 'Hoàn tất', '2025-12-31 14:44:40', '2026-01-13 09:01:30'),
(49, 1, 2, NULL, '2026-01-01', NULL, NULL, NULL, NULL, 'Hoàn tất', '2026-01-01 07:05:49', '2026-01-01 08:17:07'),
(51, 18, 1, NULL, '2026-01-12', NULL, NULL, NULL, NULL, 'Hoàn tất', '2026-01-12 10:26:40', '2026-01-12 13:01:52'),
(52, 18, 1, NULL, '2026-01-12', NULL, '    ok', '    ok', NULL, 'Hoàn tất', '2026-01-12 13:13:47', '2026-01-12 13:14:10'),
(53, 17, 1, NULL, '2026-01-12', NULL, '        U cửa tử cung', 'Thuốc        ', NULL, 'Hoàn tất', '2026-01-12 13:25:09', '2026-01-12 13:25:46'),
(54, 17, 1, NULL, '2026-01-12', NULL, '        OK', 'OK        ', NULL, 'Hoàn tất', '2026-01-12 13:48:06', '2026-01-12 13:54:08'),
(61, 19, 5, NULL, '2026-01-13', NULL, NULL, NULL, NULL, 'Hoàn tất', '2026-01-13 08:17:38', '2026-01-13 09:01:42'),
(62, 18, 10, 5, '2026-01-13', NULL, NULL, NULL, NULL, 'Hoàn tất', '2026-01-13 08:18:21', '2026-01-13 09:02:16'),
(63, 2, 10, NULL, '2026-01-13', NULL, NULL, NULL, NULL, 'Hoàn tất', '2026-01-13 08:18:24', '2026-01-13 09:02:34'),
(64, 5, 10, NULL, '2026-01-13', NULL, NULL, NULL, NULL, 'Hoàn tất', '2026-01-13 08:18:27', '2026-01-13 09:03:21'),
(65, 4, 10, NULL, '2026-01-13', NULL, NULL, NULL, NULL, 'Hoàn tất', '2026-01-13 08:18:34', '2026-01-13 09:03:33'),
(66, 1, 10, NULL, '2026-01-13', NULL, NULL, NULL, NULL, 'Hoàn tất', '2026-01-13 08:18:36', '2026-01-13 09:03:57'),
(67, 3, 10, NULL, '2026-01-13', NULL, NULL, NULL, NULL, 'Hoàn tất', '2026-01-13 08:18:40', '2026-01-13 09:04:05'),
(72, 19, 1, NULL, '2026-01-13', NULL, '        ok', 'ok        ', NULL, 'Hoàn tất', '2026-01-13 09:11:11', '2026-01-13 09:29:34'),
(73, 18, 23, NULL, '2026-01-13', NULL, '        Viêm tủy', 'Viêm tủy răng        ', NULL, 'Hoàn tất', '2026-01-13 09:26:21', '2026-01-13 09:28:05'),
(74, 19, 1, NULL, '2026-01-13', NULL, '        OK', 'OK        ', NULL, 'Hoàn tất', '2026-01-13 13:59:13', '2026-01-14 08:39:47'),
(75, 19, 1, NULL, '2026-01-15', NULL, NULL, NULL, NULL, 'Đang khám', '2026-01-15 08:08:34', '2026-01-15 08:08:34'),
(76, 21, 1, NULL, '2026-01-16', NULL, NULL, NULL, NULL, 'Đang khám', '2026-01-16 07:17:10', '2026-01-16 07:17:10'),
(77, 24, 12, NULL, '2026-04-07', NULL, NULL, NULL, NULL, 'Đang khám', '2026-04-07 01:38:39', '2026-04-07 01:38:39');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `medical_services`
--

CREATE TABLE `medical_services` (
  `id` int(11) NOT NULL,
  `record_id` int(11) DEFAULT NULL,
  `service_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `medical_services`
--

INSERT INTO `medical_services` (`id`, `record_id`, `service_id`, `quantity`) VALUES
(13, 49, 3, 2),
(15, 51, 4, 1),
(17, 10, 3, 1),
(22, 73, 9, 1),
(23, 74, 8, 2),
(24, 75, 1, 1),
(25, 75, 1, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `medicines`
--

CREATE TABLE `medicines` (
  `medicine_id` int(11) NOT NULL,
  `medicine_name` varchar(100) NOT NULL,
  `unit` varchar(50) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `medicines`
--

INSERT INTO `medicines` (`medicine_id`, `medicine_name`, `unit`, `price`, `description`, `stock`) VALUES
(1, 'Paracetamol', 'Viên', 2000.00, '', 200),
(2, 'Amoxicillin', 'Viên', 3000.00, '', 150),
(3, 'Ibuprofen', 'Viên', 2500.00, '', 150),
(4, 'Metronidazole', 'Viên', 3500.00, '', 100),
(5, 'Vitamin C', 'Viên', 1500.00, '', 300),
(6, 'Chlorhexidine', 'Chai', 20000.00, '', 50),
(7, 'Spiramycin', 'Viên', 4000.00, NULL, 100),
(8, 'Alpha Choay', 'Viên', 5000.00, '', 120),
(9, 'Cefalexin', 'Viên', 4500.00, '', 100),
(10, 'Efferalgan', 'Viên', 3000.00, '', 150);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `patients`
--

CREATE TABLE `patients` (
  `patient_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `gender` enum('Nam','Nữ','Khác') DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Hoạt động','Ngưng') DEFAULT 'Hoạt động'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `patients`
--

INSERT INTO `patients` (`patient_id`, `full_name`, `gender`, `dob`, `phone`, `address`, `created_at`, `status`) VALUES
(1, 'Nguyễn Văn A', 'Nam', '1995-02-10', '0911111111', 'Hà Nội', '2025-12-31 14:44:40', 'Hoạt động'),
(2, 'Trần Thị B', 'Nữ', '1998-03-15', '0911111112', 'Hải Phòng', '2025-12-31 14:44:40', 'Hoạt động'),
(3, 'Phạm Thị D', 'Nữ', '1992-06-18', '0911111114', 'Đà Nẵng', '2025-12-31 14:44:40', 'Hoạt động'),
(4, 'Võ Thị F', 'Nữ', '2000-01-01', '0911111116', 'Huế', '2025-12-31 14:44:40', 'Hoạt động'),
(5, 'Ngô Thị H', 'Nữ', '1994-11-11', '0911111118', 'TP HCM', '2025-12-31 14:44:40', 'Hoạt động'),
(6, 'Phan Văn I', 'Nam', '1988-04-04', '0911111119', 'Cần Thơ', '2025-12-31 14:44:40', 'Hoạt động'),
(7, 'Lê Quốc L', 'Nam', '1991-08-08', '0911111130', 'Hà Nội', '2025-12-31 14:44:40', 'Hoạt động'),
(8, 'Trần Văn M', 'Nam', '1987-09-19', '0911111131', 'Nam Định', '2025-12-31 14:44:40', 'Hoạt động'),
(9, 'Nguyễn Thị N', 'Nữ', '1993-10-10', '0911111132', 'Ninh Bình', '2025-12-31 14:44:40', 'Hoạt động'),
(10, 'Hoàng Văn O', 'Nam', '1980-01-01', '0911111133', 'Bắc Ninh', '2025-12-31 14:44:40', 'Hoạt động'),
(11, 'Đặng Văn G', 'Nam', '1996-09-09', '0911111117', NULL, '2025-12-31 14:54:18', 'Ngưng'),
(12, 'Trần Nghĩa Rồ', 'Nam', '2000-12-12', '0359761258', 'Văn Quán, Lập Thạch, Vĩnh Phúc', '2026-01-01 01:52:46', 'Ngưng'),
(13, 'Nguyễn Thanh Bình', 'Nữ', '2026-01-05', '0965899760', '', '2026-01-02 10:28:16', 'Ngưng'),
(14, 'Văn Nam', 'Nam', '2026-01-04', '0377446303', 'Xóm Ngoài, thôn Bùng Dựa', '2026-01-02 10:29:47', 'Ngưng'),
(15, 'Vũ Kỳ 2', 'Nữ', '2026-01-28', '0987654345', NULL, '2026-01-02 10:46:02', 'Ngưng'),
(16, 'A ah auaua ', 'Nam', '2026-01-12', '1234567890', NULL, '2026-01-02 12:22:48', 'Ngưng'),
(17, 'Nguyễn Đình Chiến', 'Nam', '2005-01-11', '0989098998', 'Bắc Giang', '2026-01-12 10:08:02', 'Hoạt động'),
(18, 'Phạm Thị Liên', 'Nam', '1999-02-13', '0909888777', 'Hai Ba Trung', '2026-01-12 10:26:18', 'Hoạt động'),
(19, 'Mai Hoa', 'Nam', '2000-12-12', '0951425362', 'Thanh Lãng Bình Xuyên', '2026-01-13 01:19:38', 'Hoạt động'),
(20, 'Văn  AHhAGhg', 'Nam', '2000-12-12', '07654341514', 'Xóm Ngoài, thôn Bùng Dựa', '2026-01-16 01:53:46', 'Hoạt động'),
(21, 'Linh ', 'Nữ', '1999-11-13', '05432135567', 'Hai Ba Trung', '2026-01-16 06:51:38', 'Hoạt động'),
(24, 'Trần Thanh Nam', 'Nam', '2026-04-01', '0987654349', 'Hai Ba Trung', '2026-04-01 00:57:29', 'Hoạt động'),
(26, 'Đức Thành', 'Nam', '2026-04-08', '0987654341', 'iauau ayaya ', '2026-04-07 02:24:40', 'Hoạt động'),
(27, 'Lan', 'Nam', '1999-04-08', '0987654343', 'ưewewe', '2026-04-07 02:53:49', 'Hoạt động');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `payment_date` date DEFAULT curdate(),
  `payment_method` enum('Tiền mặt','Chuyển khoản','Thẻ') DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `payments`
--

INSERT INTO `payments` (`payment_id`, `invoice_id`, `payment_date`, `payment_method`, `amount`) VALUES
(1, 1, '2025-12-31', 'Tiền mặt', 300000.00),
(2, 2, '2025-12-31', 'Chuyển khoản', 25000000.00),
(3, 3, '2025-12-31', 'Chuyển khoản', 15000000.00),
(4, 4, '2025-12-31', 'Tiền mặt', 800000.00),
(5, 5, '2025-12-31', 'Thẻ', 1200000.00),
(6, 6, '2025-12-31', 'Chuyển khoản', 6000000.00),
(7, 13, '2025-12-31', 'Tiền mặt', 0.00),
(8, 21, '2026-01-12', 'Chuyển khoản', 20350000.00),
(9, 23, '2026-01-13', 'Chuyển khoản', 1.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `prescriptions`
--

CREATE TABLE `prescriptions` (
  `prescription_id` int(11) NOT NULL,
  `record_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `prescriptions`
--

INSERT INTO `prescriptions` (`prescription_id`, `record_id`, `created_at`) VALUES
(1, 1, '2025-12-31 14:44:40'),
(2, 2, '2025-12-31 14:44:40'),
(3, 3, '2025-12-31 14:44:40'),
(4, 4, '2025-12-31 14:44:40'),
(5, 5, '2025-12-31 14:44:40'),
(6, 6, '2025-12-31 14:44:40'),
(7, 7, '2025-12-31 14:44:40'),
(8, 8, '2025-12-31 14:44:40'),
(9, 9, '2025-12-31 14:44:40'),
(10, 10, '2025-12-31 14:44:40'),
(34, 73, '2026-01-13 09:27:50'),
(35, 74, '2026-01-14 08:39:38'),
(36, 76, '2026-01-16 07:23:54');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `prescription_items`
--

CREATE TABLE `prescription_items` (
  `item_id` int(11) NOT NULL,
  `prescription_id` int(11) NOT NULL,
  `medicine_id` int(11) NOT NULL,
  `dosage` varchar(100) DEFAULT NULL,
  `frequency` varchar(100) DEFAULT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `prescription_items`
--

INSERT INTO `prescription_items` (`item_id`, `prescription_id`, `medicine_id`, `dosage`, `frequency`, `duration`, `quantity`) VALUES
(1, 1, 1, '1 viên', '2 lần/ngày', '5 ngày', 1),
(2, 2, 2, '1 viên', '3 lần/ngày', '7 ngày', 1),
(3, 3, 3, '1 viên', '2 lần/ngày', '5 ngày', 1),
(4, 4, 4, '1 viên', '3 lần/ngày', '7 ngày', 1),
(5, 5, 5, '1 viên', '1 lần/ngày', '10 ngày', 1),
(6, 6, 6, 'Súc miệng', '2 lần/ngày', '7 ngày', 1),
(7, 7, 7, '1 viên', '2 lần/ngày', '5 ngày', 1),
(8, 8, 8, '1 viên', '3 lần/ngày', '7 ngày', 1),
(9, 9, 9, '1 viên', '2 lần/ngày', '5 ngày', 1),
(10, 10, 10, '1 viên', '2 lần/ngày', '3 ngày', 1),
(25, 34, 4, '2 viên', 'Ngày 2 lần', '4 ngày', 8),
(26, 35, 4, '2 viên', 'Ngày 2 lần', '4 ngày', 2),
(28, 36, 1, '2 viên', 'Ngày 2 lần', '4 ngày', 55),
(29, 36, 1, '2 viên', 'Ngày 2 lần', '4 ngày', 55);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `services`
--

CREATE TABLE `services` (
  `service_id` int(11) NOT NULL,
  `service_name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `services`
--

INSERT INTO `services` (`service_id`, `service_name`, `price`, `description`) VALUES
(1, 'Khám tổng quát', 100000.00, NULL),
(2, 'Trám răng', 300000.00, NULL),
(3, 'Nhổ răng', 500000.00, NULL),
(4, 'Niềng răng', 25000000.00, NULL),
(5, 'Cấy Implant', 15000000.00, NULL),
(6, 'Bọc răng sứ', 6000000.00, NULL),
(7, 'Lấy cao răng', 200000.00, NULL),
(8, 'Tẩy trắng răng', 2000000.00, NULL),
(9, 'Điều trị nha chu', 800000.00, NULL),
(10, 'Điều trị tủy', 1200000.00, NULL),
(12, 'sdfghjk', 1234567.00, '');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `staffs`
--

CREATE TABLE `staffs` (
  `staff_id` int(11) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `position` varchar(50) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `staffs`
--

INSERT INTO `staffs` (`staff_id`, `full_name`, `position`, `phone`, `email`, `status`) VALUES
(1, 'Nguyễn Thanh Bình', 'Lễ Tân', '0965899760', 'Hhaa', 'active'),
(2, 'Bùi Thị Hồng Yến', 'Lễ Tân', '0987987877', 'nductaa@gmail.com', 'active');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `role` enum('ADMIN','DOCTOR','RECEPTIONIST') NOT NULL,
  `status` tinyint(4) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `staff_id` int(11) DEFAULT NULL,
  `doctor_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `full_name`, `role`, `status`, `created_at`, `staff_id`, `doctor_id`) VALUES
(1, 'admin1', '123', 'Admin One', 'ADMIN', 1, '2025-12-31 14:44:39', NULL, NULL),
(10, 'admin2', '123', 'Admin Two', 'ADMIN', 1, '2025-12-31 14:44:39', NULL, NULL),
(11, 'binh2', '123', 'Nguyễn Thanh Bình', 'RECEPTIONIST', 1, '2026-01-12 08:34:40', 1, NULL),
(12, 'bs.son', '123', 'BS Trịnh Sơn', 'DOCTOR', 1, '2026-01-12 14:27:43', NULL, 10),
(13, 'bs.hanh', '123', 'BS Võ Hạnh', 'DOCTOR', 1, '2026-01-13 01:18:27', NULL, 5),
(16, 'bs.an', '123', 'BS Nguyễn An', 'DOCTOR', 1, '2026-01-13 08:24:13', NULL, 1),
(17, 'bs.dung', '123', 'BS Phạm Dũng', 'DOCTOR', 1, '2026-01-13 08:45:32', NULL, 4),
(18, 'bs.binh', '123', 'BS Trần Bình', 'DOCTOR', 1, '2026-01-13 09:25:03', NULL, 2),
(19, 'bs.cuong', '123', 'BS Lê Cường', 'DOCTOR', 1, '2026-01-13 09:25:18', NULL, 3),
(20, 'bs.minh', '123', 'BS Đỗ Minh', 'DOCTOR', 1, '2026-01-13 09:25:32', NULL, 6),
(21, 'bs.lyanh', '123', 'BS Lý Anh', 'DOCTOR', 0, '2026-01-13 09:25:44', NULL, 7),
(22, 'bs.long', '123', 'BS Hoàng Long', 'DOCTOR', 1, '2026-01-13 09:25:59', NULL, 8),
(23, 'bs.phuong', '123', 'BS Mai Phương', 'DOCTOR', 1, '2026-01-13 09:26:11', NULL, 9),
(24, 'ltyen', '123', 'Bùi Thị Hồng Yến', 'RECEPTIONIST', 1, '2026-04-14 09:05:09', 2, NULL);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`appointment_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `doctor_id` (`doctor_id`),
  ADD KEY `request_id` (`request_id`),
  ADD KEY `fk_app_service` (`service_id`);

--
-- Chỉ mục cho bảng `appointment_requests`
--
ALTER TABLE `appointment_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `fk_req_doctor` (`doctor_id`),
  ADD KEY `fk_req_service` (`service_id`);

--
-- Chỉ mục cho bảng `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`doctor_id`);

--
-- Chỉ mục cho bảng `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`invoice_id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Chỉ mục cho bảng `invoice_details`
--
ALTER TABLE `invoice_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_id` (`invoice_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Chỉ mục cho bảng `medical_records`
--
ALTER TABLE `medical_records`
  ADD PRIMARY KEY (`record_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `doctor_id` (`doctor_id`),
  ADD KEY `fk_medical_appointment` (`appointment_id`);

--
-- Chỉ mục cho bảng `medical_services`
--
ALTER TABLE `medical_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `record_id` (`record_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Chỉ mục cho bảng `medicines`
--
ALTER TABLE `medicines`
  ADD PRIMARY KEY (`medicine_id`);

--
-- Chỉ mục cho bảng `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`patient_id`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- Chỉ mục cho bảng `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `invoice_id` (`invoice_id`);

--
-- Chỉ mục cho bảng `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD PRIMARY KEY (`prescription_id`),
  ADD KEY `record_id` (`record_id`);

--
-- Chỉ mục cho bảng `prescription_items`
--
ALTER TABLE `prescription_items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `prescription_id` (`prescription_id`),
  ADD KEY `medicine_id` (`medicine_id`);

--
-- Chỉ mục cho bảng `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`service_id`);

--
-- Chỉ mục cho bảng `staffs`
--
ALTER TABLE `staffs`
  ADD PRIMARY KEY (`staff_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `appointments`
--
ALTER TABLE `appointments`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT cho bảng `appointment_requests`
--
ALTER TABLE `appointment_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2913;

--
-- AUTO_INCREMENT cho bảng `doctors`
--
ALTER TABLE `doctors`
  MODIFY `doctor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `invoices`
--
ALTER TABLE `invoices`
  MODIFY `invoice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT cho bảng `invoice_details`
--
ALTER TABLE `invoice_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT cho bảng `medical_records`
--
ALTER TABLE `medical_records`
  MODIFY `record_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT cho bảng `medical_services`
--
ALTER TABLE `medical_services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT cho bảng `medicines`
--
ALTER TABLE `medicines`
  MODIFY `medicine_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `patients`
--
ALTER TABLE `patients`
  MODIFY `patient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT cho bảng `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `prescriptions`
--
ALTER TABLE `prescriptions`
  MODIFY `prescription_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT cho bảng `prescription_items`
--
ALTER TABLE `prescription_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT cho bảng `services`
--
ALTER TABLE `services`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `staffs`
--
ALTER TABLE `staffs`
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`patient_id`),
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`doctor_id`),
  ADD CONSTRAINT `appointments_ibfk_3` FOREIGN KEY (`request_id`) REFERENCES `appointment_requests` (`request_id`),
  ADD CONSTRAINT `fk_app_service` FOREIGN KEY (`service_id`) REFERENCES `services` (`service_id`);

--
-- Các ràng buộc cho bảng `appointment_requests`
--
ALTER TABLE `appointment_requests`
  ADD CONSTRAINT `fk_req_doctor` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`doctor_id`),
  ADD CONSTRAINT `fk_req_service` FOREIGN KEY (`service_id`) REFERENCES `services` (`service_id`);

--
-- Các ràng buộc cho bảng `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`patient_id`);

--
-- Các ràng buộc cho bảng `invoice_details`
--
ALTER TABLE `invoice_details`
  ADD CONSTRAINT `invoice_details_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`invoice_id`),
  ADD CONSTRAINT `invoice_details_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`service_id`);

--
-- Các ràng buộc cho bảng `medical_records`
--
ALTER TABLE `medical_records`
  ADD CONSTRAINT `fk_medical_appointment` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`appointment_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `medical_records_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`patient_id`);

--
-- Các ràng buộc cho bảng `medical_services`
--
ALTER TABLE `medical_services`
  ADD CONSTRAINT `medical_services_ibfk_1` FOREIGN KEY (`record_id`) REFERENCES `medical_records` (`record_id`),
  ADD CONSTRAINT `medical_services_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`service_id`);

--
-- Các ràng buộc cho bảng `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`invoice_id`);

--
-- Các ràng buộc cho bảng `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD CONSTRAINT `prescriptions_ibfk_1` FOREIGN KEY (`record_id`) REFERENCES `medical_records` (`record_id`);

--
-- Các ràng buộc cho bảng `prescription_items`
--
ALTER TABLE `prescription_items`
  ADD CONSTRAINT `prescription_items_ibfk_1` FOREIGN KEY (`prescription_id`) REFERENCES `prescriptions` (`prescription_id`),
  ADD CONSTRAINT `prescription_items_ibfk_2` FOREIGN KEY (`medicine_id`) REFERENCES `medicines` (`medicine_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
