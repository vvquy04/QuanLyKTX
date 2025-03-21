<?php
session_start();
if (!isset($_SESSION['mataikhoan']) || $_SESSION['vaitro'] != 'quanly') {
    header("Location: ../../login.php");
    exit();
}

include '../../includes/config.php';

// Tạo mã dịch vụ mới
$query = mysqli_query($con, "SELECT MAX(madichvu) AS max_ma FROM dichvu");
$row = mysqli_fetch_assoc($query);
$maxMaDichVu = $row['max_ma'];
$newMaDichVu = $maxMaDichVu ? 'DV' . str_pad((int)substr($maxMaDichVu, 2) + 1, 3, '0', STR_PAD_LEFT) : 'DV001';

if (isset($_POST['add_service'])) {
    $tendichvu = mysqli_real_escape_string($con, $_POST['tendichvu']);
    $giadichvu = (float)$_POST['giadichvu'];
    $mota = mysqli_real_escape_string($con, $_POST['mota']);
    $trangthai = mysqli_real_escape_string($con, $_POST['trangthai']);

    $query = "INSERT INTO dichvu (madichvu, tendichvu, giadichvu, mota, trangthai) 
              VALUES ('$newMaDichVu', '$tendichvu', '$giadichvu', '$mota', '$trangthai')";
    
    if (mysqli_query($con, $query)) {
        echo "<script>alert('Thêm dịch vụ thành công!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Lỗi khi thêm dịch vụ: " . mysqli_error($con) . "'); window.history.back();</script>";
    }
}
?>