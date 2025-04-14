<?php
session_start();
if (!isset($_SESSION['mataikhoan']) || $_SESSION['vaitro'] != 'sinhvien') {
    header("Location: ../../login.php");
    exit();
}
include '../../includes/config.php';
$page_title = "Danh sách phí ký túc xá";
include '../../includes/header.php';

$masinhvien = $_SESSION['user'];

// Truy vấn chỉ lấy thông tin phí ký túc xá, không lặp chi tiết
$query = mysqli_query($con, "
    SELECT DISTINCT h.mahoadon, h.tongtien, h.ngaytao, h.hanthanhtoan, h.trangthaithanhtoan, p.sophong
    FROM hoadon h 
    LEFT JOIN hopdong hd ON h.masinhvien = hd.masinhvien AND hd.trangthai = 'Hiệu lực'
    LEFT JOIN phong p ON hd.maphong = p.maphong
    WHERE h.masinhvien = '$masinhvien'
    ORDER BY h.ngaytao DESC
");
?>

<?php include '../includes/sidebar.php'; ?>
<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
        <?php include '../includes/topbar.php'; ?>
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">Danh sách phí ký túc xá của bạn</h1>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">phí ký túc xá</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Mã phí ký túc xá</th>
                                    <th>Phòng</th>
                                    <th>Tổng tiền</th>
                                    <th>Ngày tạo</th>
                                    <th>Hạn thanh toán</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_array($query)): ?>
                                    <?php
                                    $trangthai = $row['trangthaithanhtoan'];
                                    if ($trangthai == 'Chưa thanh toán' && strtotime($row['hanthanhtoan']) < time()) {
                                        $trangthai = 'Quá hạn';
                                        mysqli_query($con, "UPDATE hoadon SET trangthaithanhtoan='Quá hạn' WHERE mahoadon='{$row['mahoadon']}'");
                                    }
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['mahoadon']); ?></td>
                                        <td><?php echo htmlspecialchars($row['sophong'] ?? 'Chưa có'); ?></td>
                                        <td><?php echo number_format($row['tongtien'], 0, ',', '.'); ?></td>
                                        <td><?php echo htmlspecialchars($row['ngaytao']); ?></td>
                                        <td><?php echo htmlspecialchars($row['hanthanhtoan']); ?></td>
                                        <td><?php echo htmlspecialchars($trangthai); ?></td>
                                        <td>
                                            <?php if ($trangthai == 'Chưa thanh toán'): ?>
                                                <a href="pay_invoice.php?mahoadon=<?php echo urlencode($row['mahoadon']); ?>" class="btn btn-success btn-sm">
                                                    <i class="fa fa-money-bill" aria-hidden="true"></i> Thanh toán
                                                </a>
                                            <?php elseif ($trangthai == 'Chờ xác nhận'): ?>
                                                <span class="text-warning">Đang chờ xác nhận</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>

