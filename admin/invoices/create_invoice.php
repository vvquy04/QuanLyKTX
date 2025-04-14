<?php
session_start();
if (!isset($_SESSION['mataikhoan']) || $_SESSION['vaitro'] != 'quanly') {
    header("Location: ../../login.php");
    exit();
}

include '../../includes/config.php';
$maquanly = $_SESSION['user'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_invoice'])) {
    $maphong = mysqli_real_escape_string($con, $_POST['maphong']);
    $masinhvien = mysqli_real_escape_string($con, $_POST['masinhvien']);
    $tongtien = floatval($_POST['tongtien']);
    $hanthanhtoan = mysqli_real_escape_string($con, $_POST['hanthanhtoan']);
    $madichvu = $_POST['madichvu'] ?? [];
    $soluong = $_POST['soluong'] ?? [];
    $sotien = $_POST['sotien'] ?? [];
    $room_price = floatval($_POST['room_price']);
    $ngaytao = date('Y-m-d');

    // Kiểm tra dữ liệu đầu vào
    if (empty($maphong)) {
        header("Location: index.php?error=" . urlencode("Vui lòng chọn phòng"));
        exit();
    }
    if (empty($masinhvien)) {
        header("Location: index.php?error=" . urlencode("Vui lòng chọn sinh viên"));
        exit();
    }
    if (empty($hanthanhtoan)) {
        header("Location: index.php?error=" . urlencode("Vui lòng nhập hạn thanh toán"));
        exit();
    }

    // Kiểm tra hạn thanh toán hợp lệ
    if (strtotime($hanthanhtoan) < strtotime(date('Y-m-d'))) {
        header("Location: index.php?error=" . urlencode("Hạn thanh toán không hợp lệ"));
        exit();
    }

    // Kiểm tra sinh viên có hợp đồng hợp lệ với phòng
    $check_contract = mysqli_query($con, "
        SELECT * FROM hopdong 
        WHERE masinhvien='$masinhvien' 
        AND maphong='$maphong' 
        AND trangthai='Hiệu lực'
    ");
    if (mysqli_num_rows($check_contract) == 0) {
        header("Location: index.php?error=" . urlencode("Sinh viên không có hợp đồng hợp lệ với phòng này"));
        exit();
    }

    // Kiểm tra số lượng dịch vụ không âm
    foreach ($madichvu as $mdv) {
        $sl = floatval($soluong[$mdv] ?? 0);
        if ($sl < 0) {
            header("Location: index.php?error=" . urlencode("Số lượng không được âm"));
            exit();
        }
    }

    // Kiểm tra tổng tiền
    if ($tongtien <= 0) {
        header("Location: index.php?error=" . urlencode("Tổng tiền phải lớn hơn 0"));
        exit();
    }

    // Tạo mã hóa đơn mới
    $max_hd_query = mysqli_query($con, "SELECT MAX(mahoadon) AS max_hd FROM hoadon");
    $max_hd = mysqli_fetch_array($max_hd_query)['max_hd'] ?? 'HDN000';
    $new_mahoadon = 'HDN' . str_pad((int)substr($max_hd, 3) + 1, 3, '0', STR_PAD_LEFT);

    // Bắt đầu transaction
    mysqli_begin_transaction($con);

    try {
        // Thêm hóa đơn
        $query = "INSERT INTO hoadon (mahoadon, masinhvien, maquanly, tongtien, ngaytao, hanthanhtoan, trangthaithanhtoan) 
                  VALUES ('$new_mahoadon', '$masinhvien', '$maquanly', '$tongtien', '$ngaytao', '$hanthanhtoan', 'Chưa thanh toán')";
        if (!mysqli_query($con, $query)) {
            throw new Exception("Lỗi khi thêm hóa đơn: " . mysqli_error($con));
        }

        // Thêm chi tiết hóa đơn (dịch vụ)
        $index = 0;
        foreach ($madichvu as $mdv) {
            $sl = floatval($soluong[$mdv] ?? 0);
            $tt = floatval($sotien[$mdv] ?? 0);
            if ($tt > 0 && $sl >= 0) {
                $new_machitiet = 'CTHD' . str_pad((int)substr(mysqli_fetch_array(mysqli_query($con, "SELECT MAX(machitiethoadon) AS max_ct FROM chitiethoadon"))['max_ct'] ?? 'CTHD000', 4) + 1 + $index, 3, '0', STR_PAD_LEFT);
                $ct_query = "INSERT INTO chitiethoadon (machitiethoadon, mahoadon, maphong, madichvu, sotien, soluong) 
                             VALUES ('$new_machitiet', '$new_mahoadon', '$maphong', '$mdv', '$tt', '$sl')";
                if (!mysqli_query($con, $ct_query)) {
                    throw new Exception("Lỗi khi thêm chi tiết hóa đơn: " . mysqli_error($con));
                }
                $index++;
            }
        }

        // Thêm chi tiết hóa đơn (tiền phòng)
        if ($room_price > 0) {
            $new_machitiet = 'CTHD' . str_pad((int)substr(mysqli_fetch_array(mysqli_query($con, "SELECT MAX(machitiethoadon) AS max_ct FROM chitiethoadon"))['max_ct'] ?? 'CTHD000', 4) + 1 + $index, 3, '0', STR_PAD_LEFT);
            $ct_query = "INSERT INTO chitiethoadon (machitiethoadon, mahoadon, maphong, sotien, soluong) 
                         VALUES ('$new_machitiet', '$new_mahoadon', '$maphong', '$room_price', 1)";
            if (!mysqli_query($con, $ct_query)) {
                throw new Exception("Lỗi khi thêm chi tiết hóa đơn: " . mysqli_error($con));
            }
        }

        // Commit transaction
        mysqli_commit($con);
        header("Location: index.php?success=" . urlencode("Thêm hóa đơn thành công"));
        exit();
    } catch (Exception $e) {
        // Rollback transaction
        mysqli_rollback($con);
        header("Location: index.php?error=" . urlencode($e->getMessage()));
        exit();
    }
}
?>