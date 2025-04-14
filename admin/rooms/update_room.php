<?php
session_start();
if (!isset($_SESSION['mataikhoan']) || $_SESSION['vaitro'] != 'quanly') {
    header("Location: ../../login.php");
    exit();
}

include '../../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_room'])) {
    $maphong = mysqli_real_escape_string($con, $_POST['maphong']);
    $sophong = mysqli_real_escape_string($con, $_POST['sophong']);
    $succhua = (int)($_POST['succhua'] ?? 0);
    $loaiphong = mysqli_real_escape_string($con, $_POST['loaiphong']);
    $giathue = (float)($_POST['giathue'] ?? -1);
    $sothanhvienhientai = (int)($_POST['sothanhvienhientai'] ?? -1);

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
        echo "<script>alert('Giá thuê là bắt buộc và không được âm!'); window.history.back();</script>";
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

    // Kiểm tra số phòng đã tồn tại (trừ phòng đang cập nhật)
    $check_query = mysqli_query($con, "SELECT sophong FROM phong WHERE sophong='$sophong' AND maphong != '$maphong'");
    if (mysqli_num_rows($check_query) > 0) {
        echo "<script>alert('Số phòng đã tồn tại!'); window.history.back();</script>";
        exit();
    }

    // Cập nhật phòng
    $query = "UPDATE phong 
              SET sophong = '$sophong', succhua = '$succhua', loaiphong = '$loaiphong', 
                  giathue = '$giathue', sothanhvienhientai = '$sothanhvienhientai'
              WHERE maphong = '$maphong'";

    if (mysqli_query($con, $query)) {
        echo "<script>alert('Cập nhật phòng thành công!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Lỗi khi cập nhật phòng: " . mysqli_error($con) . "'); window.history.back();</script>";
    }
}
?>