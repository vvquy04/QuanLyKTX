<?php
session_start();
if (!isset($_SESSION['mataikhoan']) || $_SESSION['vaitro'] != 'quanly') {
    header("Location: ../../login.php");
    exit();
}
include '../../includes/config.php';
include 'index.php';
if (isset($_POST['update_room'])) {
    $maphong = $_POST['maphong'];
    $sophong = $_POST['sophong'];
    $succhua = $_POST['succhua'];
    $loaiphong = $_POST['loaiphong'];
    $trangthai = $_POST['trangthai'];
    $giathue = $_POST['giathue'];
    $sothanhvienhientai = $_POST['sothanhvienhientai'];

    $query = "UPDATE phong 
              SET sophong = '$sophong', succhua = '$succhua', loaiphong = '$loaiphong', 
                  trangthai = '$trangthai', giathue = '$giathue', sothanhvienhientai = '$sothanhvienhientai'
              WHERE maphong = '$maphong'";

    if (mysqli_query($con, $query)) {
        echo "<script>alert('Cập nhật phòng thành công!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Lỗi khi cập nhật phòng!'); window.history.back();</script>";
    }
}
?>
