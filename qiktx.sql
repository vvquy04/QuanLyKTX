

-- Bảng sinh viên
CREATE TABLE sinhvien (
    masinhvien VARCHAR(10) PRIMARY KEY,
    hoten VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL,
    ngaysinh DATE NOT NULL,
    gioitinh ENUM('Nam', 'Nữ'),
    cccd VARCHAR(12) UNIQUE NOT NULL,
    sodienthoai VARCHAR(15),
    email VARCHAR(100),
    diachi VARCHAR(255) CHARACTER SET utf8mb4,
    khoa VARCHAR(100) CHARACTER SET utf8mb4,
    lop VARCHAR(50) CHARACTER SET utf8mb4
);

-- Bảng quản lý
CREATE TABLE quanly (
    maquanly VARCHAR(10) PRIMARY KEY,
    hoten VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL,
    sodienthoai VARCHAR(15),
    email VARCHAR(100)
);

-- Bảng tài khoản
CREATE TABLE taikhoan (
    tentaikhoan VARCHAR(50) PRIMARY KEY,
    matkhau VARCHAR(100) NOT NULL,
    vaitro ENUM('admin', 'sinhvien') NOT NULL,
    masinhvien VARCHAR(10) UNIQUE,
    maquanly VARCHAR(10) UNIQUE,
    FOREIGN KEY (masinhvien) REFERENCES sinhvien(masinhvien) ON DELETE CASCADE,
    FOREIGN KEY (maquanly) REFERENCES quanly(maquanly) ON DELETE CASCADE
);

-- Bảng phòng
CREATE TABLE phong (
    maphong VARCHAR(10) PRIMARY KEY,
    sophong VARCHAR(10) NOT NULL UNIQUE,
    succhua INT NOT NULL CHECK (succhua > 0),
    loaiphong VARCHAR(50) CHARACTER SET utf8mb4,
    trangthai VARCHAR(20) CHARACTER SET utf8mb4,
    giathue DECIMAL(15,2) NOT NULL CHECK (giathue >= 0),
    sothanhvienhientai INT DEFAULT 0
);

-- Bảng đợt đăng ký nội trú
CREATE TABLE dotdangky (
    madot VARCHAR(10) PRIMARY KEY,
    tendon VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL,
    ngaybatdau DATE NOT NULL,
    ngayketthuc DATE NOT NULL,
    ghichu VARCHAR(255) CHARACTER SET utf8mb4,
    CHECK (ngaybatdau <= ngayketthuc)
);

-- Bảng hợp đồng
CREATE TABLE hopdong (
    mahopdong VARCHAR(10) PRIMARY KEY,
    masinhvien VARCHAR(10),
    maphong VARCHAR(10),
    madot VARCHAR(10),
    ngaybatdau DATE NOT NULL,
    ngayketthuc DATE NOT NULL,
    trangthai VARCHAR(20) CHARACTER SET utf8mb4,
    trangthaigiahan VARCHAR(20) CHARACTER SET utf8mb4 DEFAULT NULL,
    ngaybatdaugiahan DATE,
    ngayketthucgiahan DATE,
    lichsugiahan TEXT CHARACTER SET utf8mb4,
    FOREIGN KEY (masinhvien) REFERENCES sinhvien(masinhvien) ON DELETE CASCADE,
    FOREIGN KEY (maphong) REFERENCES phong(maphong) ON DELETE CASCADE,
    FOREIGN KEY (madot) REFERENCES dotdangky(madot) ON DELETE CASCADE,
    CHECK (ngaybatdau <= ngayketthuc),
    CHECK (ngaybatdaugiahan IS NULL OR ngaybatdaugiahan <= ngayketthucgiahan)
);

-- Bảng hóa đơn
CREATE TABLE hoadon (
    mahoadon VARCHAR(10) PRIMARY KEY,
    masinhvien VARCHAR(10),
    maphong VARCHAR(10),
    tongtien DECIMAL(15,2) NOT NULL CHECK (tongtien >= 0),
    ngaytao DATE NOT NULL,
    hanthanhtoan DATE NOT NULL,
    trangthaithanhtoan VARCHAR(20) CHARACTER SET utf8mb4 DEFAULT 'Chưa thanh toán',
    FOREIGN KEY (masinhvien) REFERENCES sinhvien(masinhvien) ON DELETE CASCADE,
    FOREIGN KEY (maphong) REFERENCES phong(maphong) ON DELETE CASCADE,
    CHECK (ngaytao <= hanthanhtoan)
);

-- Bảng dịch vụ
CREATE TABLE dichvu (
    madichvu VARCHAR(10) PRIMARY KEY,
    tendichvu VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL,
    giadichvu DECIMAL(15,2) NOT NULL CHECK (giadichvu >= 0),
    mota VARCHAR(255) CHARACTER SET utf8mb4,
    trangthai VARCHAR(20) CHARACTER SET utf8mb4 DEFAULT 'Hoạt động'
);

-- Bảng đăng ký dịch vụ
CREATE TABLE dangkydichvu (
    madangky VARCHAR(10) PRIMARY KEY,
    masinhvien VARCHAR(10),
    madichvu VARCHAR(10),
    ngaydangky DATE NOT NULL,
    trangthai VARCHAR(20) CHARACTER SET utf8mb4 DEFAULT 'Đăng ký',
    FOREIGN KEY (masinhvien) REFERENCES sinhvien(masinhvien) ON DELETE CASCADE,
    FOREIGN KEY (madichvu) REFERENCES dichvu(madichvu) ON DELETE CASCADE
);

-- Bảng thông báo
CREATE TABLE thongbao (
    mathongbao VARCHAR(10) PRIMARY KEY,
    maquanly VARCHAR(10),
    tieude VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL,
    noidung TEXT CHARACTER SET utf8mb4 NOT NULL,
    ngaygui DATE NOT NULL,
    FOREIGN KEY (maquanly) REFERENCES quanly(maquanly) ON DELETE CASCADE
);

-- Bảng yêu cầu chuyển phòng
CREATE TABLE yeucauchuyenphong (
    mayeucau VARCHAR(10) PRIMARY KEY,
    masinhvien VARCHAR(10),
    phonghientai VARCHAR(10),
    phongmuonchuyen VARCHAR(10),
    lydoyeucau VARCHAR(255) CHARACTER SET utf8mb4,
    ngayyeucau DATE NOT NULL,
    trangthai VARCHAR(20) CHARACTER SET utf8mb4 DEFAULT 'Chờ',
    FOREIGN KEY (masinhvien) REFERENCES sinhvien(masinhvien) ON DELETE CASCADE,
    FOREIGN KEY (phonghientai) REFERENCES phong(maphong) ON DELETE CASCADE,
    FOREIGN KEY (phongmuonchuyen) REFERENCES phong(maphong) ON DELETE CASCADE,
    CHECK (phonghientai <> phongmuonchuyen)
);

-- Bảng báo cáo
CREATE TABLE baocao (
    mabaocao VARCHAR(10) PRIMARY KEY,
    maquanly VARCHAR(10),
    loaibaocao VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL,
    tieuchiloc VARCHAR(255) CHARACTER SET utf8mb4,
    ngaytao DATE NOT NULL,
    FOREIGN KEY (maquanly) REFERENCES quanly(maquanly) ON DELETE CASCADE
);
