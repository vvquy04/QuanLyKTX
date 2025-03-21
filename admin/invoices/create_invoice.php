<?php
session_start();
if (!isset($_SESSION['mataikhoan']) || $_SESSION['vaitro'] != 'quanly') {
    header("Location: ../../login.php");
    exit();
}

include '../../includes/config.php';
$maquanly = $_SESSION['user'];

if (isset($_POST['add_invoice'])) {
    $maphong = mysqli_real_escape_string($con, $_POST['maphong']);
    $masinhvien = mysqli_real_escape_string($con, $_POST['masinhvien']);
    $tongtien = floatval($_POST['tongtien']);
    $hanthanhtoan = mysqli_real_escape_string($con, $_POST['hanthanhtoan']);
    $madichvu = $_POST['madichvu'];
    $soluong = $_POST['soluong'];
    $sotien = $_POST['sotien'];
    $room_price = floatval($_POST['room_price']); // Lấy tiền phòng từ form
    $ngaytao = date('Y-m-d');

    // Kiểm tra dữ liệu
    if (empty($maphong) || empty($masinhvien) || empty($hanthanhtoan) || $tongtien <= 0) {
        header("Location: index.php?error=" . urlencode("Vui lòng nhập đầy đủ thông tin"));
        exit();
    }

    // Tạo mã hóa đơn mới
    $max_hd_query = mysqli_query($con, "SELECT MAX(mahoadon) AS max_hd FROM hoadon");
    $new_mahoadon = 'HDN' . str_pad((int)substr(mysqli_fetch_array($max_hd_query)['max_hd'] ?? 'HDN000', 3) + 1, 3, '0', STR_PAD_LEFT);

    // Thêm hóa đơn
    $query = "INSERT INTO hoadon (mahoadon, masinhvien, maquanly, tongtien, ngaytao, hanthanhtoan, trangthaithanhtoan) 
              VALUES ('$new_mahoadon', '$masinhvien', '$maquanly', '$tongtien', '$ngaytao', '$hanthanhtoan', 'Chưa thanh toán')";
    if (!mysqli_query($con, $query)) {
        header("Location: index.php?error=" . urlencode("Lỗi khi thêm hóa đơn: " . mysqli_error($con)));
        exit();
    }

    // Thêm chi tiết hóa đơn (dịch vụ)
    $index = 0;
    foreach ($madichvu as $mdv) {
        $sl = floatval($soluong[$mdv]);
        $tt = floatval($sotien[$mdv]);
        if ($tt > 0) {
            $new_machitiet = 'CTHD' . str_pad((int)substr(mysqli_fetch_array(mysqli_query($con, "SELECT MAX(machitiethoadon) AS max_ct FROM chitiethoadon"))['max_ct'] ?? 'CTHD000', 4) + 1 + $index, 3, '0', STR_PAD_LEFT);
            $ct_query = "INSERT INTO chitiethoadon (machitiethoadon, mahoadon, maphong, madichvu, sotien, soluong) 
                         VALUES ('$new_machitiet', '$new_mahoadon', '$maphong', '$mdv', '$tt', '$sl')";
            if (!mysqli_query($con, $ct_query)) {
                header("Location: index.php?error=" . urlencode("Lỗi khi thêm chi tiết hóa đơn: " . mysqli_error($con)));
                exit();
            }
            $index++;
        }
    }

    // Thêm chi tiết hóa đơn (tiền phòng)
    if ($room_price > 0) {
        $new_machitiet = 'CT' . str_pad((int)substr(mysqli_fetch_array(mysqli_query($con, "SELECT MAX(machitiethoadon) AS max_ct FROM chitiethoadon"))['max_ct'] ?? 'CT000', 2) + 1 + $index, 3, '0', STR_PAD_LEFT);
        $ct_query = "INSERT INTO chitiethoadon (machitiethoadon, mahoadon, maphong, sotien, soluong) 
                     VALUES ('$new_machitiet', '$new_mahoadon', '$maphong', '$room_price', 1)";
        if (!mysqli_query($con, $ct_query)) {
            header("Location: index.php?error=" . urlencode("Lỗi khi thêm chi tiết hóa đơn: " . mysqli_error($con)));
            exit();
        }
    }

    header("Location: index.php?success=" . urlencode("Thêm hóa đơn thành công"));
    exit();
}
?>