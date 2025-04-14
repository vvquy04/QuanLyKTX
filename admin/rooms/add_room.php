<?php
session_start();
if (!isset($_SESSION['mataikhoan']) || $_SESSION['vaitro'] != 'quanly') {
    header("Location: ../../login.php");
    exit();
}

include '../../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_room'])) {
    $sophong = mysqli_real_escape_string($con, $_POST['sophong']);
    $succhua = (int)($_POST['succhua'] ?? 0);
    $loaiphong = mysqli_real_escape_string($con, $_POST['loaiphong']);
    $giathue = (float)($_POST['giathue'] ?? 0);
    $sothanhvienhientai = (int)($_POST['sothanhvienhientai'] ?? 0);

    // Kiểm tra dữ liệu đầu vào
    if (empty($sophong)) {
        echo "<script>alert('Số phòng là bắt buộc!'); window.history.back();</script>";
        exit();
    }
    if ($succhua <= 0) {
        echo "<script>alert('Sức chứa phải là số dương!'); window.history.back();</script>";
        exit();
    }
    if ($giathue < 0) {
        echo "<script>alert('Giá thuê không được âm!'); window.history.back();</script>";
        exit();
    }
    if ($sothanhvienhientai < 0) {
        echo "<script>alert('Số thành viên hiện tại phải là số không âm!'); window.history.back();</script>";
        exit();
    }
    if ($sothanhvienhientai > $succhua) {
        echo "<script>alert('Số thành viên hiện tại không được vượt quá sức chứa!'); window.history.back();</script>";
        exit();
    }

    // Kiểm tra số phòng đã tồn tại
    $check_query = mysqli_query($con, "SELECT sophong FROM phong WHERE sophong='$sophong'");
    if (mysqli_num_rows($check_query) > 0) {
        echo "<script>alert('Số phòng đã tồn tại!'); window.history.back();</script>";
        exit();
    }

    // Tạo mã phòng mới
    $query = mysqli_query($con, "SELECT MAX(maphong) AS max_ma FROM phong");
    $row = mysqli_fetch_assoc($query);
    $maxMaPhong = $row['max_ma'];
    $newMaPhong = $maxMaPhong ? 'P' . str_pad((int)substr($maxMaPhong, 1) + 1, 3, '0', STR_PAD_LEFT) : 'P001';

    // Thêm phòng
    $query = "INSERT INTO phong (maphong, sophong, succhua, loaiphong, giathue, sothanhvienhientai) 
              VALUES ('$newMaPhong', '$sophong', '$succhua', '$loaiphong', '$giathue', '$sothanhvienhientai')";
    
    if (mysqli_query($con, $query)) {
        echo "<script>alert('Thêm phòng thành công!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Lỗi khi thêm phòng: " . mysqli_error($con) . "'); window.history.back();</script>";
    }
}
?>