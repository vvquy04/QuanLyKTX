<?php
session_start();
if (!isset($_SESSION['mataikhoan']) || $_SESSION['vaitro'] != 'sinhvien') {
    header("Location: ../../login.php");
    exit();
}
include '../../includes/config.php';
$page_title = "Xin gia hạn hợp đồng";

// Lấy thông tin hợp đồng
$mahopdong = isset($_GET['mahopdong']) ? mysqli_real_escape_string($con, $_GET['mahopdong']) : '';
$masinhvien = $_SESSION['user'];

if (empty($mahopdong)) {
    header("Location: index.php?error=" . urlencode("Không tìm thấy hợp đồng"));
    exit();
}

// Kiểm tra hợp đồng
$query = mysqli_query($con, "
    SELECT hd.*, p.sophong 
    FROM hopdong hd 
    JOIN phong p ON hd.maphong = p.maphong 
    WHERE hd.mahopdong='$mahopdong' AND hd.masinhvien='$masinhvien'
");
$contract = mysqli_fetch_array($query);

if (!$contract || $contract['trangthai'] != 'Hết hạn') {
    header("Location: index.php?error=" . urlencode("Hợp đồng không hợp lệ hoặc không thể gia hạn"));
    exit();
}

// Xử lý yêu cầu gia hạn
if (isset($_POST['renew_contract'])) {
    $new_end_date = mysqli_real_escape_string($con, $_POST['new_end_date']);
    if (strtotime($new_end_date) <= strtotime($contract['ngayketthuc'])) {
        header("Location: index.php?error=" . urlencode("Ngày kết thúc mới phải sau ngày kết thúc hiện tại"));
        exit();
    }

    $sql = "UPDATE hopdong SET trangthai='Đang chờ gia hạn', ngayketthuc='$new_end_date' WHERE mahopdong='$mahopdong'";
    if (mysqli_query($con, $sql)) {
        header("Location: index.php?success=" . urlencode("Yêu cầu gia hạn đã được gửi. Hợp đồng sẽ được gia hạn đến $new_end_date sau khi quản lý xác nhận."));
        exit();
    } else {
        header("Location: index.php?error=" . urlencode("Có lỗi xảy ra khi gửi yêu cầu: " . mysqli_error($con)));
        exit();
    }
}

// Nếu không phải POST, chuyển hướng về index
header("Location: index.php?error=" . urlencode("Không có hành động nào được thực hiện"));
exit();
?>