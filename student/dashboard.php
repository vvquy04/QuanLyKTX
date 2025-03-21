<?php
session_start();
if (!isset($_SESSION['mataikhoan']) || $_SESSION['vaitro'] != 'sinhvien') {
    header("Location: ../login.php");
    exit();
}

include '../includes/config.php';
$page_title = "Trang chủ Sinh viên"; // Tiêu đề trang
include '../includes/header.php';

// Lấy thông tin sinh viên từ session
$masinhvien = $_SESSION['user'];
$student_query = mysqli_query($con, "
    SELECT s.hoten, s.khoa, s.lop, p.sophong, hd.ngayketthuc
    FROM sinhvien s
    LEFT JOIN hopdong hd ON s.masinhvien = hd.masinhvien AND hd.trangthai = 'Hiệu lực'
    LEFT JOIN phong p ON hd.maphong = p.maphong
    WHERE s.masinhvien = '$masinhvien'
");
$student_info = mysqli_fetch_array($student_query);

// Tính số hóa đơn chưa thanh toán
$unpaid_invoices_query = mysqli_query($con, "
    SELECT COUNT(*) AS unpaid_count, SUM(tongtien) AS unpaid_total
    FROM hoadon
    WHERE masinhvien = '$masinhvien' AND trangthaithanhtoan = 'Chưa thanh toán'
");
$unpaid_info = mysqli_fetch_array($unpaid_invoices_query);
$unpaid_count = $unpaid_info['unpaid_count'] ?? 0;
$unpaid_total = $unpaid_info['unpaid_total'] ?? 0;

// Tính số thông báo chưa đọc (giả định bảng thongbao có cột trangthai = 'Chưa đọc')
$notifications_query = mysqli_query($con, "
    SELECT COUNT(*) AS unread_count
    FROM thongbao
    WHERE dadoc = 0
");
$notifications_info = mysqli_fetch_array($notifications_query);
$unread_count = $notifications_info['unread_count'] ?? 0;
?>

<?php include 'includes/sidebar.php'; ?>
<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
        <?php include 'includes/topbar.php'; ?>
        
        <!-- Begin Page Content -->
        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Trang chủ </h1>
            </div>

            <!-- Content Row -->
            <div class="row">

                <!-- Thông tin cá nhân -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Thông tin cá nhân</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo htmlspecialchars($student_info['hoten']); ?></div>
                                    <small><?php echo htmlspecialchars($masinhvien); ?></small>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-user fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Trạng thái hợp đồng -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Phòng hiện tại</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?php echo $student_info['sophong'] ? htmlspecialchars($student_info['sophong']) : 'Chưa có phòng'; ?>
                                    </div>
                                    <small>Hết hạn: <?php echo $student_info['ngayhethan'] ?? 'N/A'; ?></small>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-building fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hóa đơn chưa thanh toán -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Hóa đơn chưa thanh toán</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $unpaid_count; ?></div>
                                    <small>Tổng: <?php echo number_format($unpaid_total, 2); ?> VNĐ</small>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-file-invoice-dollar fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Thông báo chưa đọc -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Thông báo chưa đọc</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $unread_count; ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-bell fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Row -->
            <div class="row">
                <!-- Thông tin bổ sung hoặc liên kết nhanh -->
                <div class="col-lg-6 mb-4">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Hành động nhanh</h6>
                        </div>
                        <div class="card-body">
                            <a href="invoices/index.php" class="btn btn-primary btn-icon-split mb-2">
                                <span class="icon text-white-50"><i class="fas fa-file-invoice"></i></span>
                                <span class="text">Hóa đơn</span>
                            </a>
                            <a href="services/index.php" class="btn btn-success btn-icon-split mb-2">
                                <span class="icon text-white-50"><i class="fa fa-cogs" aria-hidden="true"></i></span>
                                <span class="text">Dịch vụ</span>
                            </a>
                            <a href="rooms/index.php" class="btn btn-info btn-icon-split mb-2">
                                <span class="icon text-white-50"><i class="fas fa-question-circle"></i></span>
                                <span class="text">Gửi yêu cầu sinh viên</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Trạng thái phòng (nếu có phòng) -->
                <?php if ($student_info['sophong']): ?>
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Trạng thái phòng <?php echo htmlspecialchars($student_info['sophong']); ?></h6>
                            </div>
                            <div class="card-body">
                                <?php
                                $room_status_query = mysqli_query($con, "
                                    SELECT p.succhua, COUNT(hd.masinhvien) AS so_sinhvien
                                    FROM phong p
                                    LEFT JOIN hopdong hd ON p.maphong = hd.maphong AND hd.trangthai = 'Hiệu lực'
                                    WHERE p.sophong = '{$student_info['sophong']}'
                                    GROUP BY p.maphong, p.succhua
                                ");
                                $room_status = mysqli_fetch_array($room_status_query);
                                $succhua = $room_status['succhua'] ?? 0;
                                $so_sinhvien = $room_status['so_sinhvien'] ?? 0;
                                $percent_occupied = $succhua > 0 ? ($so_sinhvien / $succhua) * 100 : 0;
                                ?>
                                <p>Sức chứa: <?php echo $succhua; ?> | Đang ở: <?php echo $so_sinhvien; ?></p>
                                <div class="progress">
                                    <div class="progress-bar bg-success" role="progressbar" 
                                        style="width: <?php echo $percent_occupied; ?>%" 
                                        aria-valuenow="<?php echo $percent_occupied; ?>" 
                                        aria-valuemin="0" aria-valuemax="100">
                                        <?php echo round($percent_occupied); ?>%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- End of Main Content -->
</div>

<?php include '../includes/footer.php'; ?>