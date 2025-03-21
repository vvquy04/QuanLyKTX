<?php
session_start();
if (!isset($_SESSION['mataikhoan']) || $_SESSION['vaitro'] != 'quanly') {
    header("Location: ../login.php");
    exit();
}

include '../includes/config.php';
$page_title = "Trang chủ Quản lý"; // Tiêu đề trang
include '../includes/header.php';

// Lấy dữ liệu cho báo cáo phòng
$room_stats = ['Đầy' => 0, 'Còn trống' => 0, 'Trống hoàn toàn' => 0];
$total_phong = 0;
$room_sql = "
    SELECT p.sophong, p.succhua, 
           COUNT(hd.masinhvien) AS so_sinhvien,
           CASE 
               WHEN COUNT(hd.masinhvien) = p.succhua THEN 'Đầy'
               WHEN COUNT(hd.masinhvien) > 0 THEN 'Còn trống'
               ELSE 'Trống hoàn toàn'
           END AS tinhtrang
    FROM phong p
    LEFT JOIN hopdong hd ON p.maphong = hd.maphong AND hd.trangthai = 'Hiệu lực'
    GROUP BY p.maphong, p.sophong, p.succhua
";
$room_result = mysqli_query($con, $room_sql);
if ($room_result && mysqli_num_rows($room_result) > 0) {
    while ($row = mysqli_fetch_array($room_result)) {
        $room_stats[$row['tinhtrang']]++;
    }
    $total_phong = mysqli_num_rows($room_result);
}

// Lấy dữ liệu cho báo cáo sinh viên
$khoa_stats = [];
$total_students = 0;
$student_sql = "
    SELECT s.khoa
    FROM sinhvien s
    INNER JOIN hopdong hd ON s.masinhvien = hd.masinhvien AND hd.trangthai = 'Hiệu lực'
    INNER JOIN phong p ON hd.maphong = p.maphong
";
$student_result = mysqli_query($con, $student_sql);
if ($student_result && mysqli_num_rows($student_result) > 0) {
    while ($row = mysqli_fetch_array($student_result)) {
        $khoa_stats[$row['khoa']] = ($khoa_stats[$row['khoa']] ?? 0) + 1;
    }
    $total_students = mysqli_num_rows($student_result);
}

// Lấy dữ liệu doanh thu (tổng tiền từ hóa đơn đã thanh toán)
$total_revenue = 0;
$revenue_sql = "
    SELECT SUM(h.tongtien) AS total_revenue
    FROM hoadon h
    WHERE h.trangthaithanhtoan = 'Đã thanh toán'
";
$revenue_result = mysqli_query($con, $revenue_sql);
if ($revenue_result && mysqli_num_rows($revenue_result) > 0) {
    $revenue_row = mysqli_fetch_array($revenue_result);
    $total_revenue = $revenue_row['total_revenue'] ?? 0;
}
?>

<?php include 'includes/sidebar.php'; ?>
<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
        <?php include 'includes/topbar.php'; ?>
        
        <!-- Begin Page Content -->
        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Trang chủ</h1>
            </div>

            <!-- Content Row -->
            <div class="row">

                <!-- Tổng số phòng -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Tổng số phòng</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_phong; ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-building fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tổng số sinh viên nội trú -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Sinh viên nội trú</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_students; ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-users fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tổng doanh thu -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Tổng doanh thu
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo number_format($total_revenue, 2); ?> VNĐ</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Phòng trống hoàn toàn -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Phòng trống hoàn toàn</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $room_stats['Trống hoàn toàn']; ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-door-open fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Row -->
            <div class="row">

                <!-- Bar Chart (Tình trạng phòng) -->
                <div class="col-xl-8 col-lg-7">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Tình trạng phòng</h6>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="roomChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pie Chart (Phân bố sinh viên theo khoa) -->
                <div class="col-xl-4 col-lg-5">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Phân bố sinh viên theo khoa</h6>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="studentChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- End of Main Content -->
</div>

<?php include '../includes/footer.php'; ?>

<!-- Thêm Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Biểu đồ cột: Tình trạng phòng
var roomCtx = document.getElementById('roomChart').getContext('2d');
var roomChart = new Chart(roomCtx, {
    type: 'bar',
    data: {
        labels: ['Đầy', 'Còn trống', 'Trống hoàn toàn'],
        datasets: [{
            label: 'Số lượng phòng',
            data: [<?php echo $room_stats['Đầy']; ?>, <?php echo $room_stats['Còn trống']; ?>, <?php echo $room_stats['Trống hoàn toàn']; ?>],
            backgroundColor: ['#36b9cc', '#f6c23e', '#e74a3b'],
            borderColor: ['#2c9faf', '#e0b412', '#c53727'],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Số lượng'
                }
            },
            x: {
                title: {
                    display: true,
                    text: 'Tình trạng'
                }
            }
        },
        maintainAspectRatio: false
    }
});

// Biểu đồ tròn: Phân bố sinh viên theo khoa
var studentCtx = document.getElementById('studentChart').getContext('2d');
var studentChart = new Chart(studentCtx, {
    type: 'pie',
    data: {
        labels: [<?php echo "'" . implode("','", array_keys($khoa_stats)) . "'"; ?>],
        datasets: [{
            label: 'Số lượng sinh viên theo khoa',
            data: [<?php echo implode(',', array_values($khoa_stats)); ?>],
            backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
            },
            title: {
                display: true,
                text: 'Phân bố sinh viên theo khoa'
            }
        }
    }
});
</script>

<style>
.chart-container {
    position: relative;
    max-width: 500px; /* Giới hạn chiều rộng biểu đồ */
    height: 300px; /* Giới hạn chiều cao biểu đồ */
    margin: 0 auto; /* Căn giữa biểu đồ */
}
</style>