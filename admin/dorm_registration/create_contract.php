<?php
session_start();
if (!isset($_SESSION['mataikhoan']) || $_SESSION['vaitro'] != 'quanly') {
    header("Location: ../../login.php");
    exit();
}
include '../../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy dữ liệu từ form
    $mahopdong = mysqli_real_escape_string($con, $_POST['mahopdong'] ?? '');
    $masinhvien = mysqli_real_escape_string($con, $_POST['masinhvien'] ?? '');
    $maphong = mysqli_real_escape_string($con, $_POST['maphong'] ?? '');
    $madot = mysqli_real_escape_string($con, $_POST['madot'] ?? '');
    $ngaybatdau = mysqli_real_escape_string($con, $_POST['ngaybatdau'] ?? '');
    $ngayketthuc = mysqli_real_escape_string($con, $_POST['ngayketthuc'] ?? '');

    // Kiểm tra dữ liệu đầu vào
    if (empty($mahopdong) || empty($masinhvien) || empty($maphong) || empty($madot) || empty($ngaybatdau) || empty($ngayketthuc)) {
        echo "<script>alert('Vui lòng nhập đầy đủ thông tin'); window.location='index.php';</script>";
        exit;
    }

    // Kiểm tra hợp đồng có tồn tại và ở trạng thái 'Đang chờ'
    $check_contract = mysqli_query($con, "SELECT * FROM hopdong WHERE mahopdong = '$mahopdong' AND masinhvien = '$masinhvien' AND trangthai = 'Đang chờ'");
    if (mysqli_num_rows($check_contract) == 0) {
        echo "<script>alert('Hợp đồng không tồn tại hoặc không ở trạng thái Đang chờ.'); window.location='index.php';</script>";
        exit;
    }

    // Kiểm tra sinh viên đã có hợp đồng 'Hiệu lực' trong cùng đợt chưa
    $check_existing = mysqli_query($con, "SELECT * FROM hopdong WHERE masinhvien = '$masinhvien' AND madot = '$madot' AND trangthai = 'Hiệu lực'");
    if (mysqli_num_rows($check_existing) > 0) {
        echo "<script>alert('Sinh viên đã có hợp đồng hiệu lực trong đợt này.'); window.location='index.php';</script>";
        exit;
    }

    // Kiểm tra phòng có còn chỗ không
    $check_room = mysqli_query($con, "SELECT succhua, sothanhvienhientai FROM phong WHERE maphong = '$maphong'");
    $room = mysqli_fetch_assoc($check_room);
    if (!$room || $room['sothanhvienhientai'] >= $room['succhua']) {
        echo "<script>alert('Phòng đã đầy hoặc không tồn tại. Không thể phê duyệt hợp đồng.'); window.location='index.php';</script>";
        exit;
    }

    // Bắt đầu giao dịch
    mysqli_begin_transaction($con);
    try {
        // Cập nhật hợp đồng
        $query = "UPDATE hopdong 
                  SET maphong = '$maphong', madot = '$madot', 
                      ngaybatdau = '$ngaybatdau', ngayketthuc = '$ngayketthuc', trangthai = 'Hiệu lực' 
                  WHERE mahopdong = '$mahopdong' AND masinhvien = '$masinhvien'";
        if (!mysqli_query($con, $query)) {
            throw new Exception("Lỗi khi cập nhật hợp đồng: " . mysqli_error($con));
        }

        // Cập nhật số thành viên hiện tại của phòng
        $query_update_room = "UPDATE phong 
                              SET sothanhvienhientai = sothanhvienhientai + 1 
                              WHERE maphong = '$maphong'";
        if (!mysqli_query($con, $query_update_room)) {
            throw new Exception("Lỗi khi cập nhật phòng: " . mysqli_error($con));
        }

        // Xác nhận giao dịch
        mysqli_commit($con);
        echo "<script>alert('Hợp đồng đã được tạo thành công'); window.location='index.php';</script>";
    } catch (Exception $e) {
        mysqli_rollback($con);
        echo "<script>alert('Lỗi: " . $e->getMessage() . "'); window.location='index.php';</script>";
    }
} else {
    header("Location: index.php");
    exit();
}

mysqli_close($con);
?>