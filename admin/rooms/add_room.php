<?php
include '../../includes/config.php';
include 'index.php';

$query = mysqli_query($con, "SELECT MAX(maphong) AS max_ma FROM phong");
$row = mysqli_fetch_assoc($query);
$maxMaPhong = $row['max_ma'];

if ($maxMaPhong) {
    $num = (int) substr($maxMaPhong, 1) + 1; // Lấy số và tăng lên 1
    $newMaPhong = 'P' . str_pad($num, 3, '0', STR_PAD_LEFT); // Định dạng lại "P001"
} else {
    $newMaPhong = "P001"; // Nếu chưa có phòng nào, bắt đầu từ P001
}
if (isset($_POST['add_room'])) {
    $sophong = $_POST['sophong'];
    $succhua = $_POST['succhua'];
    $loaiphong = $_POST['loaiphong'];
    $trangthai = $_POST['trangthai'];
    $giathue = $_POST['giathue'];
    $sothanhvienhientai = $_POST['sothanhvienhientai'];

    $query = "INSERT INTO phong (maphong, sophong, succhua, loaiphong, trangthai, giathue, sothanhvienhientai) 
              VALUES ('$newMaPhong','$sophong', '$succhua', '$loaiphong', '$trangthai', '$giathue', '$sothanhvienhientai')";
    
    if (mysqli_query($con, $query)) {
        echo "<script>alert('Thêm phòng thành công!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Lỗi khi thêm phòng!'); window.history.back();</script>";
    }
}
?>
