-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 26, 2025 at 09:08 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `qlktx`
--

-- --------------------------------------------------------

--
-- Table structure for table `baocao`
--

CREATE TABLE `baocao` (
  `mabaocao` varchar(10) NOT NULL,
  `maquanly` varchar(10) DEFAULT NULL,
  `loaibaocao` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `tieuchiloc` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `ngaytao` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `baocao`
--

INSERT INTO `baocao` (`mabaocao`, `maquanly`, `loaibaocao`, `tieuchiloc`, `ngaytao`) VALUES
('BC001', 'QL001', 'Tình trạng phòng', 'Kiểm tra phòng sạch sẽ', '2025-02-20'),
('BC002', 'QL001', 'Doanh thu hóa đơn', 'Doanh thu từ hóa đơn: từ 2024-11-19 đến 2026-11-19', '2025-03-18'),
('BC003', 'QL001', 'Doanh thu hóa đơn', 'Doanh thu từ hóa đơn: từ 2025-01-23 đến 2025-04-19', '2025-03-18'),
('BC004', 'QL001', 'Doanh thu hóa đơn', 'Doanh thu từ hóa đơn: từ 2025-01-23 đến 2025-04-19', '2025-03-18'),
('BC005', 'QL001', 'Tình trạng phòng', 'Tình trạng phòng: phòng: 101A', '2025-03-21'),
('BC006', 'QL001', 'Tình trạng phòng', 'Tình trạng phòng: phòng: 101A', '2025-03-21'),
('BC007', 'QL001', 'Tình trạng phòng', 'Tình trạng phòng: phòng: 101A', '2025-03-21'),
('BC008', 'QL001', 'Số lượng sinh viên', 'Số lượng sinh viên: ', '2025-03-21'),
('BC009', 'QL001', 'Số lượng sinh viên', 'Số lượng sinh viên: ', '2025-03-21'),
('BC010', 'QL001', 'Tình trạng phòng', 'Tình trạng phòng: ', '2025-03-21'),
('BC011', 'QL001', 'Số lượng sinh viên', 'Số lượng sinh viên: ', '2025-03-21'),
('BC012', 'QL001', 'Số lượng sinh viên', 'Số lượng sinh viên: ', '2025-03-21'),
('BC013', 'QL001', 'Tình trạng phòng', 'Tình trạng phòng: ', '2025-03-21'),
('BC014', 'QL001', 'Số lượng sinh viên', 'Số lượng sinh viên: ', '2025-03-21');

-- --------------------------------------------------------

--
-- Table structure for table `chitiethoadon`
--

CREATE TABLE `chitiethoadon` (
  `machitiethoadon` varchar(10) NOT NULL,
  `mahoadon` varchar(10) NOT NULL,
  `maphong` varchar(10) DEFAULT NULL,
  `madichvu` varchar(10) DEFAULT NULL,
  `sotien` float NOT NULL CHECK (`sotien` >= 0),
  `soluong` float NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chitiethoadon`
--

INSERT INTO `chitiethoadon` (`machitiethoadon`, `mahoadon`, `maphong`, `madichvu`, `sotien`, `soluong`) VALUES
('CT006', 'HDN007', 'P003', NULL, 1200000, 1),
('CTHD001', 'HDN001', 'P001', 'DV001', 100000, 0),
('CTHD002', 'HDN001', 'P001', 'DV002', 200000, 0),
('CTHD003', 'HDN002', 'P002', 'DV003', 150000, 0),
('CTHD004', 'HDN003', 'P003', 'DV004', 50000, 0),
('CTHD005', 'HDN004', 'P004', 'DV005', 80000, 0),
('CTHD006', 'HDN005', 'P005', 'DV001', 100000, 0),
('CTHD007', 'HDN005', 'P005', 'DV002', 200000, 0),
('CTHD008', 'HDN005', 'P005', 'DV003', 150000, 0),
('CTHD009', 'HDN003', 'P003', 'DV001', 100000, 0),
('CTHD010', 'HDN002', 'P002', 'DV005', 80000, 0),
('CTHD011', 'HDN007', 'P003', 'DV001', 100000, 1),
('CTHD013', 'HDN007', 'P003', 'DV002', 200000, 50),
('CTHD016', 'HDN007', 'P003', 'DV003', 200000, 10),
('CTHD020', 'HDN007', 'P003', 'DV004', 50000, 1),
('CTHD025', 'HDN007', 'P003', 'DV005', 80000, 1);

--
-- Triggers `chitiethoadon`
--
DELIMITER $$
CREATE TRIGGER `update_tongtien` AFTER INSERT ON `chitiethoadon` FOR EACH ROW BEGIN
    UPDATE hoadon
    SET tongtien = (
        SELECT COALESCE(SUM(sotien), 0)
        FROM chitiethoadon
        WHERE mahoadon = NEW.mahoadon
    )
    WHERE mahoadon = NEW.mahoadon;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_tongtien_on_delete` AFTER DELETE ON `chitiethoadon` FOR EACH ROW BEGIN
    UPDATE hoadon
    SET tongtien = (
        SELECT COALESCE(SUM(sotien), 0)
        FROM chitiethoadon
        WHERE mahoadon = OLD.mahoadon
    )
    WHERE mahoadon = OLD.mahoadon;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_tongtien_on_update` AFTER UPDATE ON `chitiethoadon` FOR EACH ROW BEGIN
    UPDATE hoadon
    SET tongtien = (
        SELECT COALESCE(SUM(sotien), 0)
        FROM chitiethoadon
        WHERE mahoadon = NEW.mahoadon
    )
    WHERE mahoadon = NEW.mahoadon;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `dangkydichvu`
--

CREATE TABLE `dangkydichvu` (
  `madangky` varchar(10) NOT NULL,
  `masinhvien` varchar(10) DEFAULT NULL,
  `madichvu` varchar(10) DEFAULT NULL,
  `ngaydangky` date NOT NULL,
  `trangthai` enum('Đăng ký','Hủy') DEFAULT 'Đăng ký'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dangkydichvu`
--

INSERT INTO `dangkydichvu` (`madangky`, `masinhvien`, `madichvu`, `ngaydangky`, `trangthai`) VALUES
('DKDV001', 'SV001', 'DV001', '2025-02-05', 'Đăng ký'),
('DKDV002', 'SV002', 'DV002', '2025-02-06', 'Đăng ký'),
('DKDV003', 'SV003', 'DV003', '2025-06-05', 'Đăng ký'),
('DKDV004', 'SV004', 'DV004', '2025-09-06', 'Đăng ký'),
('DKDV005', 'SV005', 'DV005', '2026-01-07', 'Đăng ký'),
('DKDV006', 'SV001', 'DV002', '2025-03-21', 'Đăng ký'),
('DKDV007', 'SV001', 'DV003', '2025-03-21', 'Đăng ký'),
('DKDV008', 'SV001', 'DV004', '2025-03-21', 'Đăng ký'),
('DKDV009', 'SV003', 'DV002', '2025-03-21', 'Đăng ký'),
('DKDV010', 'SV003', 'DV001', '2025-03-21', 'Đăng ký');

-- --------------------------------------------------------

--
-- Table structure for table `dichvu`
--

CREATE TABLE `dichvu` (
  `madichvu` varchar(10) NOT NULL,
  `tendichvu` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `giadichvu` float NOT NULL CHECK (`giadichvu` >= 0),
  `mota` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `trangthai` enum('Hoạt động','Ngừng hoạt động') DEFAULT 'Hoạt động'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dichvu`
--

INSERT INTO `dichvu` (`madichvu`, `tendichvu`, `giadichvu`, `mota`, `trangthai`) VALUES
('DV001', 'Internet', 100000, 'Mạng internet tốc độ cao', 'Hoạt động'),
('DV002', 'Điện', 4000, 'Tiền điện một số', 'Hoạt động'),
('DV003', 'Nước', 20000, 'Tiền nước một khối', 'Hoạt động'),
('DV004', 'Giặt ủi', 50000, 'Dịch vụ giặt ủi trong KTX', 'Hoạt động'),
('DV005', 'Gửi xe', 80000, 'Dịch vụ gửi xe', 'Hoạt động');

-- --------------------------------------------------------

--
-- Table structure for table `dotdangky`
--

CREATE TABLE `dotdangky` (
  `madot` varchar(10) NOT NULL,
  `tendon` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `ngaybatdau` date NOT NULL,
  `ngayketthuc` date NOT NULL,
  `ghichu` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL
) ;

--
-- Dumping data for table `dotdangky`
--

INSERT INTO `dotdangky` (`madot`, `tendon`, `ngaybatdau`, `ngayketthuc`, `ghichu`) VALUES
('', 'thang 5', '2025-03-19', '2025-03-28', ''),
('DK001', 'Đợt ĐK Kỳ I', '2025-02-01', '2025-02-10', 'Ký túc xá kỳ I'),
('DK002', 'Đợt ĐK Kỳ II', '2025-06-01', '2025-06-10', 'Ký túc xá kỳ II'),
('DK003', 'Đợt ĐK Kỳ III', '2025-09-01', '2025-09-10', 'Ký túc xá kỳ III'),
('DK004', 'Đợt ĐK Kỳ IV', '2026-01-01', '2026-01-10', 'Ký túc xá kỳ IV'),
('DK005', 'Đợt ĐK Kỳ V', '2026-06-01', '2026-06-10', 'Ký túc xá kỳ V');

-- --------------------------------------------------------

--
-- Table structure for table `hoadon`
--

CREATE TABLE `hoadon` (
  `mahoadon` varchar(10) NOT NULL,
  `masinhvien` varchar(10) DEFAULT NULL,
  `maquanly` varchar(10) DEFAULT NULL,
  `tongtien` float NOT NULL CHECK (`tongtien` >= 0),
  `ngaytao` date NOT NULL,
  `hanthanhtoan` date NOT NULL,
  `trangthaithanhtoan` enum('Chưa thanh toán','Đã thanh toán','Chờ xác nhận','Quá hạn') DEFAULT 'Chưa thanh toán'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hoadon`
--

INSERT INTO `hoadon` (`mahoadon`, `masinhvien`, `maquanly`, `tongtien`, `ngaytao`, `hanthanhtoan`, `trangthaithanhtoan`) VALUES
('HDN001', 'SV001', 'QL001', 1500000, '2025-02-10', '2025-03-10', 'Quá hạn'),
('HDN002', 'SV002', 'QL001', 1600000, '2025-02-11', '2025-03-11', 'Đã thanh toán'),
('HDN003', 'SV003', 'QL002', 1200000, '2025-06-10', '2025-07-10', 'Đã thanh toán'),
('HDN004', 'SV004', 'QL002', 1800000, '2025-09-12', '2025-10-12', 'Đã thanh toán'),
('HDN005', 'SV005', 'QL002', 1400000, '2026-01-15', '2026-02-15', 'Chưa thanh toán'),
('HDN006', 'SV002', 'QL001', 2230000, '2025-03-21', '2025-03-31', 'Chưa thanh toán'),
('HDN007', 'SV003', 'QL001', 1830000, '2025-03-21', '2025-03-31', 'Chưa thanh toán');

-- --------------------------------------------------------

--
-- Table structure for table `hopdong`
--

CREATE TABLE `hopdong` (
  `mahopdong` varchar(10) NOT NULL,
  `masinhvien` varchar(10) DEFAULT NULL,
  `maphong` varchar(10) DEFAULT NULL,
  `madot` varchar(10) DEFAULT NULL,
  `ngaybatdau` date NOT NULL,
  `ngayketthuc` date NOT NULL,
  `trangthai` enum('Hiệu lực','Đang chờ','Hết hạn','Đang chờ gia hạn') NOT NULL
) ;

--
-- Dumping data for table `hopdong`
--

INSERT INTO `hopdong` (`mahopdong`, `masinhvien`, `maphong`, `madot`, `ngaybatdau`, `ngayketthuc`, `trangthai`) VALUES
('HD001', 'SV001', 'P001', 'DK001', '2025-02-05', '2025-09-19', 'Hiệu lực'),
('HD002', 'SV002', 'P002', 'DK001', '2025-02-06', '2025-07-06', 'Hiệu lực'),
('HD003', 'SV003', 'P003', 'DK002', '2025-06-05', '2025-09-18', 'Hiệu lực'),
('HD004', 'SV004', 'P004', 'DK003', '2025-09-06', '2025-09-18', 'Hiệu lực'),
('HD005', 'SV005', 'P005', 'DK004', '2026-01-07', '2026-06-07', 'Đang chờ gia hạn'),
('HD0371', 'SV006', 'P001', '', '2025-03-19', '2025-09-19', 'Đang chờ');

-- --------------------------------------------------------

--
-- Table structure for table `phong`
--

CREATE TABLE `phong` (
  `maphong` varchar(10) NOT NULL,
  `sophong` varchar(10) NOT NULL,
  `succhua` int(11) NOT NULL CHECK (`succhua` > 0),
  `loaiphong` enum('Nam','Nữ') NOT NULL,
  `giathue` float NOT NULL CHECK (`giathue` >= 0),
  `sothanhvienhientai` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `phong`
--

INSERT INTO `phong` (`maphong`, `sophong`, `succhua`, `loaiphong`, `giathue`, `sothanhvienhientai`) VALUES
('P001', '101A', 4, 'Nam', 1500000, 2),
('P002', '102B', 4, 'Nữ', 1600000, 2),
('P003', '103C', 6, 'Nam', 1200000, 3),
('P004', '104D', 3, 'Nữ', 1800000, 1),
('P005', '105E', 5, 'Nữ', 1500000, 2);

-- --------------------------------------------------------

--
-- Table structure for table `quanly`
--

CREATE TABLE `quanly` (
  `maquanly` varchar(10) NOT NULL,
  `hoten` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `sodienthoai` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quanly`
--

INSERT INTO `quanly` (`maquanly`, `hoten`, `sodienthoai`, `email`) VALUES
('QL001', 'Nguyễn Quản Lý', '0912345678', 'quanly1@example.com'),
('QL002', 'Trần Quản Lý', '0923456789', 'quanly2@example.com'),
('QL003', 'Hoàng Quản Lý', '0934567890', 'quanly3@example.com'),
('QL004', 'Lê Quản Lý', '0945678901', 'quanly4@example.com'),
('QL005', 'Phạm Quản Lý', '0956789012', 'quanly5@example.com');

-- --------------------------------------------------------

--
-- Table structure for table `sinhvien`
--

CREATE TABLE `sinhvien` (
  `masinhvien` varchar(10) NOT NULL,
  `hoten` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `ngaysinh` date NOT NULL,
  `gioitinh` enum('Nam','Nữ','Khác') NOT NULL,
  `cccd` varchar(12) NOT NULL,
  `sodienthoai` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `diachi` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `khoa` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `lop` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sinhvien`
--

INSERT INTO `sinhvien` (`masinhvien`, `hoten`, `ngaysinh`, `gioitinh`, `cccd`, `sodienthoai`, `email`, `diachi`, `khoa`, `lop`) VALUES
('SV001', 'Nguyễn Văn A', '2002-05-10', 'Nam', '123456789012', '0987654321', 'nguyenvana@gmail.com', 'Hà Nội', 'Công nghệ thông tin', 'CNTT01'),
('SV002', 'Trần Thị B', '2003-02-15', 'Nữ', '223456789012', '0987612345', 'tranthib@gmail.com', 'Hải Phòng', 'Kinh tế', 'KT01'),
('SV003', 'Hoàng Văn C', '2001-09-20', 'Nam', '323456789012', '0978123456', 'hoangvanc@gmail.com', 'Đà Nẵng', 'Công nghệ thông tin', 'CNTT02'),
('SV004', 'Lê Thị D', '2002-12-25', 'Nữ', '423456789012', '0967234567', 'lethid@gmail.com', 'Hồ Chí Minh', 'Luật', 'LUAT01'),
('SV005', 'Phạm Văn E', '2000-07-05', 'Nam', '523456789012', '0945345678', 'phamvane@gmail.com', 'Hà Nam', 'Kỹ thuật xây dựng', 'XD01'),
('SV006', 'Nguyễn Văn F', '2001-08-20', 'Nam', '623456789012', '0987656789', 'nguyenvanf@gmail.com', 'Hải Dương', 'Công nghệ thông tin', 'CNTT03');

-- --------------------------------------------------------

--
-- Table structure for table `taikhoan`
--

CREATE TABLE `taikhoan` (
  `tentaikhoan` varchar(50) NOT NULL,
  `matkhau` varchar(100) NOT NULL,
  `vaitro` enum('quanly','sinhvien') NOT NULL,
  `masinhvien` varchar(10) DEFAULT NULL,
  `maquanly` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `taikhoan`
--

INSERT INTO `taikhoan` (`tentaikhoan`, `matkhau`, `vaitro`, `masinhvien`, `maquanly`) VALUES
('admin1', 'admin', 'quanly', NULL, 'QL001'),
('admin2', 'admin', 'quanly', NULL, 'QL002'),
('user1', '12345', 'sinhvien', 'SV001', NULL),
('user2', '12345', 'sinhvien', 'SV002', NULL),
('user3', '12345', 'sinhvien', 'SV003', NULL),
('user4', '12345', 'sinhvien', 'SV004', NULL),
('user6', '12345', 'sinhvien', 'SV006', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `thongbao`
--

CREATE TABLE `thongbao` (
  `mathongbao` varchar(10) NOT NULL,
  `maquanly` varchar(10) DEFAULT NULL,
  `tieude` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `noidung` varchar(1000) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `ngaygui` date NOT NULL,
  `dadoc` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `thongbao`
--

INSERT INTO `thongbao` (`mathongbao`, `maquanly`, `tieude`, `noidung`, `ngaygui`, `dadoc`) VALUES
('TB001', 'QL001', 'Thông báo đóng tiền', 'Nhắc nhở đóng tiền đúng hạn', '2025-02-10', 0),
('TB002', 'QL002', 'Bảo trì hệ thống nước', 'Thông báo bảo trì hệ thống nước vào ngày 15/03', '2025-03-01', 0),
('TB003', 'QL003', 'Vệ sinh khu vực chung', 'Nhắc nhở vệ sinh KTX', '2025-03-05', 0),
('TB004', 'QL004', 'Lịch kiểm tra KTX', 'Kiểm tra phòng ở vào ngày 20/04', '2025-04-10', 0),
('TB005', 'QL005', 'Quy định mới về KTX', 'Thông báo về quy định mới', '2025-05-01', 1),
('TB006', 'QL001', 'sjdfshs', 'àhasj', '2025-03-19', 1);

-- --------------------------------------------------------

--
-- Table structure for table `yeucau`
--

CREATE TABLE `yeucau` (
  `mayeucau` varchar(10) NOT NULL,
  `masinhvien` varchar(10) NOT NULL,
  `loaiyeucau` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `noidung` text NOT NULL,
  `ngayyeucau` date NOT NULL,
  `trangthai` enum('Chờ','Đã xử lý','Từ chối') DEFAULT 'Chờ',
  `lydotuchoi` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `yeucau`
--

INSERT INTO `yeucau` (`mayeucau`, `masinhvien`, `loaiyeucau`, `noidung`, `ngayyeucau`, `trangthai`, `lydotuchoi`) VALUES
('YC001', 'SV001', 'Sửa chữa', 'Bóng đèn trong phòng bị hỏng, cần thay mới', '2025-02-15', 'Đã xử lý', NULL),
('YC002', 'SV002', 'Vệ sinh', 'Phòng cần được dọn dẹp', '2025-03-01', 'Đã xử lý', NULL),
('YC003', 'SV003', 'Phản ánh', 'Đường nước trong phòng bị rò rỉ', '2025-03-10', 'Chờ', NULL),
('YC004', 'SV004', 'Hỗ trợ đăng ký', 'Không thể đăng ký dịch vụ internet', '2025-04-05', 'Từ chối', 'Dịch vụ đã đầy'),
('YC005', 'SV005', 'Phản ánh', 'Quạt trần trong phòng không hoạt động', '2025-05-12', 'Chờ', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `baocao`
--
ALTER TABLE `baocao`
  ADD PRIMARY KEY (`mabaocao`),
  ADD KEY `maquanly` (`maquanly`);

--
-- Indexes for table `chitiethoadon`
--
ALTER TABLE `chitiethoadon`
  ADD PRIMARY KEY (`machitiethoadon`),
  ADD KEY `mahoadon` (`mahoadon`),
  ADD KEY `maphong` (`maphong`),
  ADD KEY `madichvu` (`madichvu`);

--
-- Indexes for table `dangkydichvu`
--
ALTER TABLE `dangkydichvu`
  ADD PRIMARY KEY (`madangky`),
  ADD KEY `masinhvien` (`masinhvien`),
  ADD KEY `madichvu` (`madichvu`);

--
-- Indexes for table `dichvu`
--
ALTER TABLE `dichvu`
  ADD PRIMARY KEY (`madichvu`);

--
-- Indexes for table `dotdangky`
--
ALTER TABLE `dotdangky`
  ADD PRIMARY KEY (`madot`);

--
-- Indexes for table `hoadon`
--
ALTER TABLE `hoadon`
  ADD PRIMARY KEY (`mahoadon`),
  ADD KEY `masinhvien` (`masinhvien`),
  ADD KEY `maquanly` (`maquanly`);

--
-- Indexes for table `hopdong`
--
ALTER TABLE `hopdong`
  ADD PRIMARY KEY (`mahopdong`),
  ADD KEY `masinhvien` (`masinhvien`),
  ADD KEY `maphong` (`maphong`),
  ADD KEY `madot` (`madot`);

--
-- Indexes for table `phong`
--
ALTER TABLE `phong`
  ADD PRIMARY KEY (`maphong`),
  ADD UNIQUE KEY `sophong` (`sophong`);

--
-- Indexes for table `quanly`
--
ALTER TABLE `quanly`
  ADD PRIMARY KEY (`maquanly`);

--
-- Indexes for table `sinhvien`
--
ALTER TABLE `sinhvien`
  ADD PRIMARY KEY (`masinhvien`),
  ADD UNIQUE KEY `cccd` (`cccd`);

--
-- Indexes for table `taikhoan`
--
ALTER TABLE `taikhoan`
  ADD PRIMARY KEY (`tentaikhoan`),
  ADD UNIQUE KEY `masinhvien` (`masinhvien`),
  ADD UNIQUE KEY `maquanly` (`maquanly`);

--
-- Indexes for table `thongbao`
--
ALTER TABLE `thongbao`
  ADD PRIMARY KEY (`mathongbao`),
  ADD KEY `maquanly` (`maquanly`);

--
-- Indexes for table `yeucau`
--
ALTER TABLE `yeucau`
  ADD PRIMARY KEY (`mayeucau`),
  ADD KEY `masinhvien` (`masinhvien`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `baocao`
--
ALTER TABLE `baocao`
  ADD CONSTRAINT `baocao_ibfk_1` FOREIGN KEY (`maquanly`) REFERENCES `quanly` (`maquanly`);

--
-- Constraints for table `chitiethoadon`
--
ALTER TABLE `chitiethoadon`
  ADD CONSTRAINT `chitiethoadon_ibfk_1` FOREIGN KEY (`mahoadon`) REFERENCES `hoadon` (`mahoadon`) ON DELETE CASCADE,
  ADD CONSTRAINT `chitiethoadon_ibfk_2` FOREIGN KEY (`maphong`) REFERENCES `phong` (`maphong`) ON DELETE SET NULL,
  ADD CONSTRAINT `chitiethoadon_ibfk_3` FOREIGN KEY (`madichvu`) REFERENCES `dichvu` (`madichvu`) ON DELETE SET NULL;

--
-- Constraints for table `dangkydichvu`
--
ALTER TABLE `dangkydichvu`
  ADD CONSTRAINT `dangkydichvu_ibfk_1` FOREIGN KEY (`masinhvien`) REFERENCES `sinhvien` (`masinhvien`),
  ADD CONSTRAINT `dangkydichvu_ibfk_2` FOREIGN KEY (`madichvu`) REFERENCES `dichvu` (`madichvu`);

--
-- Constraints for table `hoadon`
--
ALTER TABLE `hoadon`
  ADD CONSTRAINT `hoadon_ibfk_1` FOREIGN KEY (`masinhvien`) REFERENCES `sinhvien` (`masinhvien`);

--
-- Constraints for table `hopdong`
--
ALTER TABLE `hopdong`
  ADD CONSTRAINT `hopdong_ibfk_1` FOREIGN KEY (`masinhvien`) REFERENCES `sinhvien` (`masinhvien`),
  ADD CONSTRAINT `hopdong_ibfk_2` FOREIGN KEY (`maphong`) REFERENCES `phong` (`maphong`),
  ADD CONSTRAINT `hopdong_ibfk_3` FOREIGN KEY (`madot`) REFERENCES `dotdangky` (`madot`);

--
-- Constraints for table `taikhoan`
--
ALTER TABLE `taikhoan`
  ADD CONSTRAINT `taikhoan_ibfk_1` FOREIGN KEY (`masinhvien`) REFERENCES `sinhvien` (`masinhvien`),
  ADD CONSTRAINT `taikhoan_ibfk_2` FOREIGN KEY (`maquanly`) REFERENCES `quanly` (`maquanly`);

--
-- Constraints for table `thongbao`
--
ALTER TABLE `thongbao`
  ADD CONSTRAINT `thongbao_ibfk_1` FOREIGN KEY (`maquanly`) REFERENCES `quanly` (`maquanly`);

--
-- Constraints for table `yeucau`
--
ALTER TABLE `yeucau`
  ADD CONSTRAINT `yeucau_ibfk_1` FOREIGN KEY (`masinhvien`) REFERENCES `sinhvien` (`masinhvien`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
