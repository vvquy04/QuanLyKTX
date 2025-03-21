<?php
session_start();
if (!isset($_SESSION['mataikhoan']) || $_SESSION['vaitro'] != 'sinhvien') {
    header("Location: ../../login.php");
    exit();
}
include '../../includes/config.php';
$page_title = "Thanh toán hóa đơn";
include '../../includes/header.php';

// Lấy thông tin hóa đơn
$mahoadon = isset($_GET['mahoadon']) ? mysqli_real_escape_string($con, $_GET['mahoadon']) : '';
$masinhvien = $_SESSION['user'];

if (empty($mahoadon)) {
    header("Location: index.php?error=" . urlencode("Không tìm thấy hóa đơn"));
    exit();
}

// Truy vấn thông tin hóa đơn và chi tiết (bao gồm soluong)
$query = mysqli_query($con, "
    SELECT h.*, p.sophong, cthd.machitiethoadon, cthd.maphong AS cthd_maphong, cthd.madichvu, cthd.sotien, cthd.soluong, dv.tendichvu
    FROM hoadon h 
    LEFT JOIN hopdong hd ON h.masinhvien = hd.masinhvien AND hd.trangthai = 'Hiệu lực'
    LEFT JOIN phong p ON hd.maphong = p.maphong
    LEFT JOIN chitiethoadon cthd ON h.mahoadon = cthd.mahoadon
    LEFT JOIN dichvu dv ON cthd.madichvu = dv.madichvu
    WHERE h.mahoadon = '$mahoadon' AND h.masinhvien = '$masinhvien'
    ORDER BY cthd.machitiethoadon
");

$invoice = mysqli_fetch_array($query);
if (!$invoice || $invoice['trangthaithanhtoan'] != 'Chưa thanh toán') {
    header("Location: index.php?error=" . urlencode("Hóa đơn không hợp lệ hoặc không thể thanh toán"));
    exit();
}

// Thu thập chi tiết hóa đơn
$details = [];
mysqli_data_seek($query, 0); // Reset con trỏ về đầu kết quả
while ($row = mysqli_fetch_array($query)) {
    if ($row['machitiethoadon']) {
        // Nếu có madichvu và tendichvu, đây là dịch vụ
        if ($row['madichvu'] && $row['tendichvu']) {
            $unit = ($row['tendichvu'] == 'Điện') ? 'kWh' : (($row['tendichvu'] == 'Nước') ? 'm³' : 'lần');
            // Chỉ hiển thị số lượng nếu soluong > 0
            $khoanmuc = ($row['soluong'] > 0) 
                ? "Dịch vụ ({$row['tendichvu']}): {$row['soluong']} $unit" 
                : "Dịch vụ ({$row['tendichvu']})";
        } else {
            // Nếu không, đây là tiền phòng
            $khoanmuc = "Tiền phòng ({$row['sophong']})";
        }
        $details[] = [
            'khoanmuc' => $khoanmuc,
            'sotien' => $row['sotien']
        ];
    }
}

// Xử lý thanh toán
if (isset($_POST['pay_invoice'])) {
    $sql = "UPDATE hoadon SET trangthaithanhtoan='Chờ xác nhận' WHERE mahoadon='$mahoadon'";
    if (mysqli_query($con, $sql)) {
        header("Location: index.php?success=" . urlencode("Thanh toán thành công. Hóa đơn đang chờ xác nhận."));
        exit();
    } else {
        $error = "Có lỗi xảy ra khi gửi yêu cầu. Vui lòng thử lại.";
    }
}
?>

<?php include '../includes/sidebar.php'; ?>
<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
        <?php include '../includes/topbar.php'; ?>
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">Thanh toán hóa đơn</h1>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin hóa đơn</h6>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>
                    <div class="row">
                        <!-- Cột chứa mã QR -->
                        <div class="col-md-4 text-center">
                            <h5>Mã QR thanh toán:</h5>
                            <img src="../../images/qr.jpg" class="img-fluid rounded shadow" style="width: 200px; height: auto; border: 1px solid #ddd; padding: 10px; border-radius: 10px;" alt="QR Code">
                        </div>
                        <!-- Cột chứa thông tin hóa đơn -->
                        <div class="col-md-8">
                            <h3>Thanh toán hóa đơn</h3>
                            <p><strong>Mã hóa đơn:</strong> <?php echo htmlspecialchars($invoice['mahoadon']); ?></p>
                            <p><strong>Phòng:</strong> <?php echo htmlspecialchars($invoice['sophong'] ?? 'Chưa có phòng'); ?></p>
                            <p><strong>Chi tiết khoản tiền:</strong></p>
                            <ul>
                                <?php foreach ($details as $detail): ?>
                                    <li><?php echo htmlspecialchars($detail['khoanmuc']) . ": " . number_format($detail['sotien'], 0, ',', '.') . " VNĐ"; ?></li>
                                <?php endforeach; ?>
                                <?php if (empty($details)): ?>
                                    <li>Không có chi tiết</li>
                                <?php endif; ?>
                            </ul>
                            <p><strong>Tổng tiền:</strong> <?php echo number_format($invoice['tongtien'], 0, ',', '.'); ?> VNĐ</p>
                            <p><strong>Ngày tạo:</strong> <?php echo htmlspecialchars($invoice['ngaytao']); ?></p>
                            <p><strong>Hạn thanh toán:</strong> <?php echo htmlspecialchars($invoice['hanthanhtoan']); ?></p>
                            <p><strong>Trạng thái:</strong> 
                                <?php 
                                if (strtotime($invoice['hanthanhtoan']) < time()) {
                                    echo '<span class="text-danger">Quá hạn</span>';
                                    mysqli_query($con, "UPDATE hoadon SET trangthaithanhtoan='Quá hạn' WHERE mahoadon='$mahoadon'");
                                } else {
                                    echo '<span class="text-info">Chưa thanh toán</span>';
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                    <div style="display: flex; justify-content: center; margin-top: 20px;">
                        <form method="POST" onsubmit="return confirm('Bạn đã thanh toán hóa đơn này qua QR Code? Nhấn OK để gửi yêu cầu xác nhận đến quản lý.');">
                            <button type="submit" name="pay_invoice" class="btn btn-success">Xác nhận thanh toán</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>