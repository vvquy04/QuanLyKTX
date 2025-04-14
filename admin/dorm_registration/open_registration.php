<?php
session_start();
if (!isset($_SESSION['mataikhoan']) || $_SESSION['vaitro'] != 'quanly') {
    header("Location: ../../login.php");
    exit();
}
include '../../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy dữ liệu từ form
    $tendon = trim($_POST['tendon'] ?? '');
    $ngaybatdau = $_POST['ngaybatdau'] ?? '';
    $ngayketthuc = $_POST['ngayketthuc'] ?? '';
    $ghichu = trim($_POST['ghichu'] ?? '');

    // Kiểm tra dữ liệu đầu vào
    if (empty($tendon)) {
        echo "<script>alert('Tên đợt không được bỏ trống'); window.location='index.php';</script>";
        exit;
    }
    if (empty($ngaybatdau) || empty($ngayketthuc)) {
        echo "<script>alert('Vui lòng nhập đầy đủ ngày bắt đầu và ngày kết thúc'); window.location='index.php';</script>";
        exit;
    }
    if ($ngayketthuc <= $ngaybatdau) {
        echo "<script>alert('Ngày kết thúc phải sau ngày bắt đầu'); window.location='index.php';</script>";
        exit;
    }

    // Tạo mã đợt tự động
    $result = mysqli_query($con, "SELECT MAX(madot) AS max_madot FROM dotdangky");
    $row = mysqli_fetch_assoc($result);
    $max_madot = $row['max_madot'];
    $madot = $max_madot ? "DK" . str_pad((int)substr($max_madot, 2) + 1, 3, '0', STR_PAD_LEFT) : "DK001";

    // Làm sạch dữ liệu
    $tendon = mysqli_real_escape_string($con, $tendon);
    $ngaybatdau = mysqli_real_escape_string($con, $ngaybatdau);
    $ngayketthuc = mysqli_real_escape_string($con, $ngayketthuc);
    $ghichu = mysqli_real_escape_string($con, $ghichu);

    // Thêm dữ liệu vào bảng dotdangky
    mysqli_begin_transaction($con);
    try {
        $query = "INSERT INTO dotdangky (madot, tendon, ngaybatdau, ngayketthuc, ghichu) 
                  VALUES ('$madot', '$tendon', '$ngaybatdau', '$ngayketthuc', '$ghichu')";
        if (!mysqli_query($con, $query)) {
            throw new Exception("Lỗi khi thêm đợt đăng ký: " . mysqli_error($con));
        }
        mysqli_commit($con);
        echo "<script>alert('Đã mở đợt đăng ký thành công'); window.location='index.php';</script>";
    } catch (Exception $e) {
        mysqli_rollback($con);
        echo "<script>alert('Lỗi: " . $e->getMessage() . "'); window.location='index.php';</script>";
    }
} else {
    header("Location: index.php");
    exit();
}

mysqli_close($con);
?>