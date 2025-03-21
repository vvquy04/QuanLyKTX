<?php
session_start();
if (!isset($_SESSION['mataikhoan']) || $_SESSION['vaitro'] != 'quanly') {
    header("Location: ../../login.php");
    exit();
}

include '../../includes/config.php';

if (isset($_GET['madichvu'])) {
    $madichvu = mysqli_real_escape_string($con, $_GET['madichvu']);
    $query = "DELETE FROM dichvu WHERE madichvu='$madichvu'";
    
    if (mysqli_query($con, $query)) {
        echo "<script>alert('Xóa dịch vụ thành công!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Lỗi khi xóa dịch vụ: " . mysqli_error($con) . "'); window.history.back();</script>";
    }
}
?>