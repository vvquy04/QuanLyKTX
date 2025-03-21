<?php
session_start();
if (!isset($_SESSION['mataikhoan']) || $_SESSION['vaitro'] != 'quanly') {
    header("Location: ../../login.php");
    exit();
}
include '../../includes/config.php';
// Lấy mã đợt đăng ký lớn nhất hiện có
$result = mysqli_query($con, "SELECT MAX(madot) AS max_madot FROM dotdangky");
$row = mysqli_fetch_assoc($result);
$max_madot = $row['max_madot'];

if ($max_madot) {
    // Lấy phần số (VD: DK001 -> lấy '001')
    $so_moi = (int)substr($max_madot, 2) + 1;

    // Định dạng lại mã đợt đăng ký (VD: DK002, DK010, DK100)
    $madot = "DK" . str_pad($so_moi, 3, '0', STR_PAD_LEFT);
} else {
    // Nếu chưa có đợt nào, bắt đầu từ DK001
    $madot = "DK001";
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $madot = mysqli_real_escape_string($con, $_POST['madot']);
    $tendon = mysqli_real_escape_string($con, $_POST['tendon']);
    $ngaybatdau = mysqli_real_escape_string($con, $_POST['ngaybatdau']);
    $ngayketthuc = mysqli_real_escape_string($con, $_POST['ngayketthuc']);
    $ghichu = mysqli_real_escape_string($con, $_POST['ghichu']);

    $query = "INSERT INTO dotdangky (madot, tendon, ngaybatdau, ngayketthuc, ghichu) 
              VALUES ('$madot', '$tendon', '$ngaybatdau', '$ngayketthuc', '$ghichu')";
    if (mysqli_query($con, $query)) {
        echo "<script>alert('Đã mở đợt đăng ký thành công'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Lỗi: " . mysqli_error($con) . "'); window.location='index.php';</script>";
    }
} else {
    header("Location: index.php");
    exit();
}
?>