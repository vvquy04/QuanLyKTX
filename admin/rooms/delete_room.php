<?php
include '../../includes/config.php';
include 'index.php';
if (isset($_GET['maphong'])) {
    $maphong = $_GET['maphong'];
    $query = "DELETE FROM phong WHERE maphong='$maphong'";
    
    if (mysqli_query($con, $query)) {
        echo "<script>alert('Xóa phòng thành công!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Lỗi khi xóa phòng!'); window.history.back();</script>";
    }
}
?>
