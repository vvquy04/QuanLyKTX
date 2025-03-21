<?php
session_start();
if (!isset($_SESSION['mataikhoan']) || $_SESSION['vaitro'] != 'sinhvien') {
    header("Location: ../../login.php");
    exit();
}

include '../../includes/config.php';

$masinhvien = $_SESSION['user'];

if (isset($_POST['register_service'])) {
    $madichvu = mysqli_real_escape_string($con, $_POST['madichvu']);
    $newMaDangKy = 'DKDV' . str_pad((int)substr(mysqli_fetch_array(mysqli_query($con, "SELECT MAX(madangky) AS max_dk FROM dangkydichvu"))['max_dk'] ?? 'DKDV000', 4) + 1, 3, '0', STR_PAD_LEFT);

    $query = "INSERT INTO dangkydichvu (madangky, masinhvien, madichvu, ngaydangky, trangthai) 
              VALUES ('$newMaDangKy', '$masinhvien', '$madichvu', CURDATE(), 'Đăng ký')";
    
    if (mysqli_query($con, $query)) {
        header("Location: index.php?success=" . urlencode("Đăng ký dịch vụ thành công"));
    } else {
        header("Location: index.php?error=" . urlencode("Lỗi: " . mysqli_error($con)));
    }
}
?>