<?php
session_start();
if (!isset($_SESSION['mataikhoan']) || $_SESSION['vaitro'] != 'quanly') {
    header("Location: ../../login.php");
    exit();
}

include '../../includes/config.php';

if (isset($_GET['maphong'])) {
    $maphong = mysqli_real_escape_string($con, $_GET['maphong']);

    // Kiểm tra xem phòng có hợp đồng hiệu lực hoặc đang chờ không
    $check_query = mysqli_query($con, "SELECT * FROM hopdong WHERE maphong='$maphong' AND trangthai IN ('Hiệu lực', 'Đang chờ')");
    if (mysqli_num_rows($check_query) > 0) {
        echo "<script>alert('Không thể xóa phòng vì đang có hợp đồng hiệu lực hoặc đang chờ!'); window.location='index.php';</script>";
        exit();
    }

    // Kiểm tra xem phòng có tồn tại không
    $check_room = mysqli_query($con, "SELECT * FROM phong WHERE maphong='$maphong'");
    if (mysqli_num_rows($check_room) == 0) {
        echo "<script>alert('Phòng không tồn tại!'); window.location='index.php';</script>";
        exit();
    }

    // Xóa phòng
    $query = "DELETE FROM phong WHERE maphong='$maphong'";
    
    if (mysqli_query($con, $query)) {
        echo "<script>alert('Xóa phòng thành công!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Lỗi khi xóa phòng: " . mysqli_error($con) . "'); window.location='index.php';</script>";
    }
} else {
    echo "<script>alert('Không có mã phòng được cung cấp!'); window.location='index.php';</script>";
}
?>