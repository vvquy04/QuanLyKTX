<?php
session_start();
if (!isset($_SESSION['mataikhoan']) || $_SESSION['vaitro'] != 'quanly') {
    header("Location: ../../login.php");
    exit();
}

include '../../includes/config.php';

if (isset($_POST['update_service'])) {
    $madichvu = mysqli_real_escape_string($con, $_POST['madichvu']);
    $tendichvu = mysqli_real_escape_string($con, $_POST['tendichvu']);
    $giadichvu = (float)$_POST['giadichvu'];
    $mota = mysqli_real_escape_string($con, $_POST['mota']);
    $trangthai = mysqli_real_escape_string($con, $_POST['trangthai']);

    $query = "UPDATE dichvu 
              SET tendichvu = '$tendichvu', giadichvu = '$giadichvu', mota = '$mota', trangthai = '$trangthai'
              WHERE madichvu = '$madichvu'";

    if (mysqli_query($con, $query)) {
        echo "<script>alert('Cập nhật dịch vụ thành công!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Lỗi khi cập nhật dịch vụ: " . mysqli_error($con) . "'); window.history.back();</script>";
    }
}
?>