CREATE TABLE sinhvien (
    masinhvien VARCHAR(10) PRIMARY KEY,
    hoten NVARCHAR(100) NOT NULL,
    ngaysinh DATE NOT NULL,
    gioitinh ENUM('Nam', 'Nữ', 'Khác') NOT NULL,
    cccd VARCHAR(12) UNIQUE NOT NULL,
    sodienthoai VARCHAR(15),
    email VARCHAR(100),
    diachi NVARCHAR(255),
    khoa NVARCHAR(100),
    lop NVARCHAR(50)
);

CREATE TABLE quanly (
    maquanly VARCHAR(10) PRIMARY KEY,
    hoten NVARCHAR(100) NOT NULL,
    sodienthoai VARCHAR(15),
    email VARCHAR(100)
);

CREATE TABLE taikhoan (
    tentaikhoan VARCHAR(50) PRIMARY KEY,
    matkhau VARCHAR(100) NOT NULL,
    vaitro ENUM('quanly', 'sinhvien') NOT NULL,
    masinhvien VARCHAR(10) UNIQUE,
    maquanly VARCHAR(10) UNIQUE,
    FOREIGN KEY (masinhvien) REFERENCES sinhvien(masinhvien),
    FOREIGN KEY (maquanly) REFERENCES quanly(maquanly)
);

CREATE TABLE phong (
    maphong VARCHAR(10) PRIMARY KEY,
    sophong VARCHAR(10) NOT NULL UNIQUE,
    succhua INT NOT NULL CHECK (succhua > 0),
    loaiphong ENUM('Nam', 'Nữ') NOT NULL,
    giathue FLOAT NOT NULL CHECK (giathue >= 0),
    sothanhvienhientai INT DEFAULT 0 
);

CREATE TABLE dotdangky (
    madot VARCHAR(10) PRIMARY KEY,
    tendon NVARCHAR(100) NOT NULL,
    ngaybatdau DATE NOT NULL,
    ngayketthuc DATE NOT NULL,
    ghichu NVARCHAR(255),
    CHECK (ngaybatdau <= ngayketthuc)
);

CREATE TABLE hopdong (
    mahopdong VARCHAR(10) PRIMARY KEY,
    masinhvien VARCHAR(10),
    maphong VARCHAR(10),
    madot VARCHAR(10),
    ngaybatdau DATE NOT NULL,
    ngayketthuc DATE NOT NULL,
    trangthai ENUM('Hiệu lực', 'Đang chờ', 'Hết hạn', 'Đang chờ gia hạn') NOT NULL,
    CHECK (ngaybatdau <= ngayketthuc),
    FOREIGN KEY (masinhvien) REFERENCES sinhvien(masinhvien),
    FOREIGN KEY (maphong) REFERENCES phong(maphong),
    FOREIGN KEY (madot) REFERENCES dotdangky(madot)
);

CREATE TABLE hoadon (
    mahoadon VARCHAR(10) PRIMARY KEY,
    masinhvien VARCHAR(10),
    maquanly VARCHAR(10),
    tongtien FLOAT NOT NULL CHECK (tongtien >= 0),
    ngaytao DATE NOT NULL,
    hanthanhtoan DATE NOT NULL,
    trangthaithanhtoan ENUM('Chưa thanh toán', 'Đã thanh toán', 'Chờ xác nhận', 'Quá hạn') DEFAULT 'Chưa thanh toán',
    --CHECK (ngaytao <= hanthanhtoan),
    FOREIGN KEY (masinhvien) REFERENCES sinhvien(masinhvien),
    FOREIGN KEY (maquanly) REFERENCES quanly(maquanly),
);

CREATE TABLE dichvu (
    madichvu VARCHAR(10) PRIMARY KEY,
    tendichvu NVARCHAR(100) NOT NULL,
    giadichvu FLOAT NOT NULL CHECK (giadichvu >= 0),
    mota NVARCHAR(255),
    trangthai ENUM('Hoạt động', 'Ngừng hoạt động') DEFAULT 'Hoạt động'
);

CREATE TABLE dangkydichvu (
    madangky VARCHAR(10) PRIMARY KEY,
    masinhvien VARCHAR(10),
    madichvu VARCHAR(10),
    ngaydangky DATE NOT NULL,
    trangthai ENUM('Đăng ký', 'Hủy') DEFAULT 'Đăng ký',
    FOREIGN KEY (masinhvien) REFERENCES sinhvien(masinhvien),
    FOREIGN KEY (madichvu) REFERENCES dichvu(madichvu)
);

CREATE TABLE thongbao (
    mathongbao VARCHAR(10) PRIMARY KEY,
    maquanly VARCHAR(10),
    tieude NVARCHAR(100) NOT NULL,
    noidung NVARCHAR(1000) NOT NULL,
    ngaygui DATE NOT NULL,
    dadoc TINYINT DEFAULT 0,
    FOREIGN KEY (maquanly) REFERENCES quanly(maquanly)
);

CREATE TABLE yeucau (
    mayeucau VARCHAR(10) PRIMARY KEY,
    masinhvien VARCHAR(10) NOT NULL,
    loaiyeucau NVARCHAR(50) NOT NULL,
    noidung TEXT NOT NULL,
    ngayyeucau DATE NOT NULL,
    trangthai ENUM('Chờ', 'Đã xử lý', 'Từ chối') DEFAULT 'Chờ',
    lydotuchoi NVARCHAR(255) DEFAULT NULL,
    FOREIGN KEY (masinhvien) REFERENCES sinhvien(masinhvien) ON DELETE CASCADE
);

CREATE TABLE baocao (
    mabaocao VARCHAR(10) PRIMARY KEY,
    maquanly VARCHAR(10),
    loaibaocao NVARCHAR(50) NOT NULL,
    tieuchiloc NVARCHAR(255),
    ngaytao DATE NOT NULL,
    FOREIGN KEY (maquanly) REFERENCES quanly(maquanly)
);

CREATE TABLE chitiethoadon (
    machitiethoadon VARCHAR(10) PRIMARY KEY,
    mahoadon VARCHAR(10) NOT NULL,
    maphong VARCHAR(10), 
    madichvu VARCHAR(10), 
    sotien FLOAT NOT NULL CHECK (sotien >= 0),
    FOREIGN KEY (mahoadon) REFERENCES hoadon(mahoadon) ON DELETE CASCADE,
    FOREIGN KEY (maphong) REFERENCES phong(maphong) ON DELETE SET NULL,
    FOREIGN KEY (madichvu) REFERENCES dichvu(madichvu) ON DELETE SET NULL
);
ALTER TABLE chitiethoadon
ADD COLUMN soluong FLOAT NOT NULL DEFAULT 0 CHECK (soluong >= 0);


-- Thêm dữ liệu vào bảng sinhvien
INSERT INTO sinhvien VALUES
('SV001', N'Nguyễn Văn A', '2002-05-10', 'Nam', '123456789012', '0987654321', 'nguyenvana@gmail.com', N'Hà Nội', N'Công nghệ thông tin', 'CNTT01'),
('SV002', N'Trần Thị B', '2003-02-15', 'Nữ', '223456789012', '0987612345', 'tranthib@gmail.com', N'Hải Phòng', N'Kinh tế', 'KT01'),
('SV003', N'Hoàng Văn C', '2001-09-20', 'Nam', '323456789012', '0978123456', 'hoangvanc@gmail.com', N'Đà Nẵng', N'Công nghệ thông tin', 'CNTT02'),
('SV004', N'Lê Thị D', '2002-12-25', 'Nữ', '423456789012', '0967234567', 'lethid@gmail.com', N'Hồ Chí Minh', N'Luật', 'LUAT01'),
('SV005', N'Phạm Văn E', '2000-07-05', 'Nam', '523456789012', '0945345678', 'phamvane@gmail.com', N'Hà Nam', N'Kỹ thuật xây dựng', 'XD01');

-- Thêm dữ liệu vào bảng quanly
INSERT INTO quanly VALUES
('QL001', N'Nguyễn Quản Lý', '0912345678', 'quanly1@example.com'),
('QL002', N'Trần Quản Lý', '0923456789', 'quanly2@example.com'),
('QL003', N'Hoàng Quản Lý', '0934567890', 'quanly3@example.com'),
('QL004', N'Lê Quản Lý', '0945678901', 'quanly4@example.com'),
('QL005', N'Phạm Quản Lý', '0956789012', 'quanly5@example.com');

-- Thêm dữ liệu vào bảng taikhoan
INSERT INTO taikhoan VALUES
('user1', 'password123', 'sinhvien', 'SV001', NULL),
('user2', 'password123', 'sinhvien', 'SV002', NULL),
('user3', 'password123', 'sinhvien', 'SV003', NULL),
('admin1', 'adminpass', 'quanly', NULL, 'QL001'),
('admin2', 'adminpass', 'quanly', NULL, 'QL002');

-- Thêm dữ liệu vào bảng phong
INSERT INTO phong VALUES
('P001', '101A', 4, 'Nam', 1500000, 2),
('P002', '102B', 4, 'Nữ', 1600000, 2),
('P003', '103C', 6, 'Nam', 1200000, 3),
('P004', '104D', 3, 'Nữ', 1800000, 1),
('P005', '105E', 5, 'Nam', 1400000, 2);

-- Thêm dữ liệu vào bảng dotdangky
INSERT INTO dotdangky VALUES
('DK001', N'Đợt ĐK Kỳ I', '2025-02-01', '2025-02-10', N'Ký túc xá kỳ I'),
('DK002', N'Đợt ĐK Kỳ II', '2025-06-01', '2025-06-10', N'Ký túc xá kỳ II'),
('DK003', N'Đợt ĐK Kỳ III', '2025-09-01', '2025-09-10', N'Ký túc xá kỳ III'),
('DK004', N'Đợt ĐK Kỳ IV', '2026-01-01', '2026-01-10', N'Ký túc xá kỳ IV'),
('DK005', N'Đợt ĐK Kỳ V', '2026-06-01', '2026-06-10', N'Ký túc xá kỳ V');

-- Thêm dữ liệu vào bảng hopdong
INSERT INTO hopdong VALUES
('HD001', 'SV001', 'P001', 'DK001', '2025-02-05', '2025-07-05', 'Hiệu lực'),
('HD002', 'SV002', 'P002', 'DK001', '2025-02-06', '2025-07-06', 'Hiệu lực'),
('HD003', 'SV003', 'P003', 'DK002', '2025-06-05', '2025-12-05', 'Hiệu lực'),
('HD004', 'SV004', 'P004', 'DK003', '2025-09-06', '2026-02-06', 'Đang chờ'),
('HD005', 'SV005', 'P005', 'DK004', '2026-01-07', '2026-06-07', 'Hiệu lực');

-- Thêm dữ liệu vào bảng hoadon
INSERT INTO hoadon VALUES
('HDN001', 'SV001', 1500000, '2025-02-10', '2025-03-10', 'Chưa thanh toán'),
('HDN002', 'SV002', 1600000, '2025-02-11', '2025-03-11', 'Đã thanh toán'),
('HDN003', 'SV003', 1200000, '2025-06-10', '2025-07-10', 'Chưa thanh toán'),
('HDN004', 'SV004', 1800000, '2025-09-12', '2025-10-12', 'Chưa thanh toán'),
('HDN005', 'SV005', 1400000, '2026-01-15', '2026-02-15', 'Chưa thanh toán');

-- Thêm dữ liệu vào bảng dichvu
INSERT INTO dichvu VALUES
('DV001', N'Internet', 100000, N'Mạng internet tốc độ cao', 'Hoạt động'),
('DV002', N'Điện', 200000, N'Tiền điện hàng tháng', 'Hoạt động'),
('DV003', N'Nước', 150000, N'Tiền nước hàng tháng', 'Hoạt động'),
('DV004', N'Giặt ủi', 50000, N'Dịch vụ giặt ủi trong KTX', 'Hoạt động'),
('DV005', N'Gửi xe', 80000, N'Dịch vụ gửi xe', 'Hoạt động');

-- Thêm dữ liệu vào bảng dangkydichvu
INSERT INTO dangkydichvu VALUES
('DKDV001', 'SV001', 'DV001', '2025-02-05', 'Đăng ký'),
('DKDV002', 'SV002', 'DV002', '2025-02-06', 'Đăng ký'),
('DKDV003', 'SV003', 'DV003', '2025-06-05', 'Đăng ký'),
('DKDV004', 'SV004', 'DV004', '2025-09-06', 'Đăng ký'),
('DKDV005', 'SV005', 'DV005', '2026-01-07', 'Đăng ký');

-- Thêm dữ liệu vào bảng thongbao
INSERT INTO thongbao VALUES
('TB001', 'QL001', N'Thông báo đóng tiền', N'Nhắc nhở đóng tiền đúng hạn', '2025-02-10', 0),
('TB002', 'QL002', N'Bảo trì hệ thống nước', N'Thông báo bảo trì hệ thống nước vào ngày 15/03', '2025-03-01', 0),
('TB003', 'QL003', N'Vệ sinh khu vực chung', N'Nhắc nhở vệ sinh KTX', '2025-03-05', 0),
('TB004', 'QL004', N'Lịch kiểm tra KTX', N'Kiểm tra phòng ở vào ngày 20/04', '2025-04-10', 0),
('TB005', 'QL005', N'Quy định mới về KTX', N'Thông báo về quy định mới', '2025-05-01', 0);

-- Thêm dữ liệu vào bảng yeucau
INSERT INTO yeucau VALUES
('YC001', 'SV001', N'Sửa chữa', N'Bóng đèn trong phòng bị hỏng, cần thay mới', '2025-02-15', 'Chờ', NULL),
('YC002', 'SV002', N'Vệ sinh', N'Phòng cần được dọn dẹp', '2025-03-01', 'Đã xử lý', NULL),
('YC003', 'SV003', N'Phản ánh', N'Đường nước trong phòng bị rò rỉ', '2025-03-10', 'Chờ', NULL),
('YC004', 'SV004', N'Hỗ trợ đăng ký', N'Không thể đăng ký dịch vụ internet', '2025-04-05', 'Từ chối', N'Dịch vụ đã đầy'),
('YC005', 'SV005', N'Phản ánh', N'Quạt trần trong phòng không hoạt động', '2025-05-12', 'Chờ', NULL);

-- Thêm dữ liệu vào bảng baocao
INSERT INTO baocao VALUES
('BC001', 'QL001', N'Tình trạng phòng', N'Kiểm tra phòng sạch sẽ', '2025-02-20'),
('BC002', 'QL002', N'Thu tiền phòng', N'Danh sách sinh viên chưa đóng tiền', '2025-03-10'),
('BC003', 'QL003', N'Bảo trì hệ thống', N'Kiểm tra hệ thống điện nước', '2025-03-25'),
('BC004', 'QL004', N'Hoạt động sinh viên', N'Thống kê đăng ký dịch vụ', '2025-04-15'),
('BC005', 'QL005', N'Vi phạm nội quy', N'Danh sách sinh viên vi phạm nội quy', '2025-05-05');

-- Thêm dữ liệu vào bảng chitiethoadon
INSERT INTO chitiethoadon VALUES
('CTHD001', 'HDN001', 'P001', 'DV001', 100000),
('CTHD002', 'HDN001', 'P001', 'DV002', 200000),
('CTHD003', 'HDN002', 'P002', 'DV003', 150000),
('CTHD004', 'HDN003', 'P003', 'DV004', 50000),
('CTHD005', 'HDN004', 'P004', 'DV005', 80000),
('CTHD006', 'HDN005', 'P005', 'DV001', 100000),
('CTHD007', 'HDN005', 'P005', 'DV002', 200000),
('CTHD008', 'HDN005', 'P005', 'DV003', 150000),
('CTHD009', 'HDN003', 'P003', 'DV001', 100000),
('CTHD010', 'HDN002', 'P002', 'DV005', 80000);
