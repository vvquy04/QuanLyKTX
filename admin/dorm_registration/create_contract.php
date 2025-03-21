<?php
session_start();
if (!isset($_SESSION['mataikhoan']) || $_SESSION['vaitro'] != 'quanly') {
    header("Location: ../../login.php");
    exit();
}
include '../../includes/config.php';

// Lấy mã hợp đồng lớn nhất hiện có (dùng để kiểm tra, nhưng không cần gán lại)
$result = mysqli_query($con, "SELECT MAX(mahopdong) AS max_ma FROM hopdong");
$row = mysqli_fetch_assoc($result);
$max_mahopdong = $row['max_ma'];

if ($max_mahopdong) {
    // Lấy phần số cuối cùng của mã hợp đồng (VD: HD001 -> lấy '001')
    $so_moi = (int)substr($max_mahopdong, 2) + 1;
    // Định dạng lại mã hợp đồng thành HD + số có 3 chữ số (VD: HD002, HD010, HD100)
    $mahopdong = "HD" . str_pad($so_moi, 3, '0', STR_PAD_LEFT);
} else {
    // Nếu chưa có hợp đồng nào, bắt đầu từ HD001
    $mahopdong = "HD001";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Chỉ lấy các trường cần thiết từ POST, bỏ qua mahopdong vì đã tự tạo
    $masinhvien = mysqli_real_escape_string($con, $_POST['masinhvien']);
    $maphong = mysqli_real_escape_string($con, $_POST['maphong']);
    $madot = mysqli_real_escape_string($con, $_POST['madot']);
    $ngaybatdau = mysqli_real_escape_string($con, $_POST['ngaybatdau']);
    $ngayketthuc = mysqli_real_escape_string($con, $_POST['ngayketthuc']);

    // Kiểm tra hợp đồng có tồn tại và đang ở trạng thái 'Đang chờ' không
    $check_contract = mysqli_query($con, "SELECT mahopdong FROM hopdong WHERE masinhvien = '$masinhvien' AND trangthai = 'Đang chờ'");
    if (mysqli_num_rows($check_contract) == 0) {
        echo "<script>alert('Hợp đồng không tồn tại hoặc không ở trạng thái Đang chờ.'); window.location='index.php';</script>";
        exit();
    }
    $existing_contract = mysqli_fetch_assoc($check_contract);
    $existing_mahopdong = $existing_contract['mahopdong']; // Lấy mã hợp đồng hiện có

    // Kiểm tra phòng có còn chỗ không
    $check_room = mysqli_query($con, "SELECT succhua, sothanhvienhientai FROM phong WHERE maphong = '$maphong'");
    $room = mysqli_fetch_assoc($check_room);
    if (!$room || $room['sothanhvienhientai'] >= $room['succhua']) {
        echo "<script>alert('Phòng đã đầy hoặc không tồn tại. Không thể phê duyệt hợp đồng.'); window.location='index.php';</script>";
        exit();
    }

    // Bắt đầu giao dịch để đảm bảo đồng bộ
    mysqli_begin_transaction($con);

    try {
        // Cập nhật hợp đồng với mã hợp đồng hiện có, không gán lại mahopdong
        $query = "UPDATE hopdong 
                  SET maphong = '$maphong', madot = '$madot', 
                      ngaybatdau = '$ngaybatdau', ngayketthuc = '$ngayketthuc', trangthai = 'Hiệu lực' 
                  WHERE mahopdong = '$existing_mahopdong' AND masinhvien = '$masinhvien' AND trangthai = 'Đang chờ'";
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
        // Nếu có lỗi, hủy giao dịch
        mysqli_rollback($con);
        echo "<script>alert('Lỗi: " . $e->getMessage() . "'); window.location='index.php';</script>";
    }
} else {
    header("Location: index.php");
    exit();
}

mysqli_close($con);
?>