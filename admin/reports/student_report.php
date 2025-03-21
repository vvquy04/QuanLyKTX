<?php
session_start();
if (!isset($_SESSION['mataikhoan']) || $_SESSION['vaitro'] != 'quanly') {
    header("Location: ../../login.php");
    exit();
}

include '../../includes/config.php';
$page_title = "Báo cáo số lượng sinh viên";
include '../../includes/header.php';

// Khởi tạo biến lọc
$khoa = $_POST['khoa'] ?? '';
$sophong = $_POST['sophong'] ?? '';

$maquanly = $_SESSION['user']; // Lấy mã quản lý từ session

// Truy vấn lịch sử báo cáo loại "Số lượng sinh viên"
$history_query = mysqli_query($con, "
    SELECT b.*, q.hoten AS tenquanly
    FROM baocao b
    JOIN quanly q ON b.maquanly = q.maquanly
    WHERE b.loaibaocao = 'Số lượng sinh viên'
    ORDER BY b.ngaytao DESC
");

// Xử lý xuất báo cáo
$report_data = [];
$khoa_stats = [];
$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['export_report'])) {
    // Xây dựng câu truy vấn (chỉ lấy sinh viên đang ở nội trú)
    $sql = "
        SELECT s.masinhvien, s.hoten, s.khoa, s.lop, p.sophong
        FROM sinhvien s
        INNER JOIN hopdong hd ON s.masinhvien = hd.masinhvien AND hd.trangthai = 'Hiệu lực'
        INNER JOIN phong p ON hd.maphong = p.maphong
    ";

    // Thêm điều kiện lọc
    $conditions = [];
    if (!empty($khoa)) {
        $conditions[] = "s.khoa = '$khoa'";
    }
    if (!empty($sophong)) {
        $conditions[] = "p.sophong = '$sophong'";
    }

    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }

    $sql .= " ORDER BY s.masinhvien ASC";

    // Thực thi truy vấn
    $result = mysqli_query($con, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
            $report_data[] = $row;
            $khoa_stats[$row['khoa']] = ($khoa_stats[$row['khoa']] ?? 0) + 1;
        }

        // Lưu báo cáo vào bảng baocao
        $tieuchiloc = "Số lượng sinh viên: " . 
                      (!empty($khoa) ? "khoa: $khoa" : "") . 
                      (!empty($sophong) ? ", phòng: $sophong" : "");
        $ngaytao = date('Y-m-d');

        // Tạo mã báo cáo mới
        $query_max_bc = "SELECT MAX(mabaocao) AS max_bc FROM baocao";
        $result_max_bc = mysqli_query($con, $query_max_bc);
        $row_max_bc = mysqli_fetch_array($result_max_bc);
        $max_bc = $row_max_bc['max_bc'] ?? 'BC000';
        $mabaocao = 'BC' . str_pad((int)substr($max_bc, 2) + 1, 3, '0', STR_PAD_LEFT);

        $insert_bc = "INSERT INTO baocao (mabaocao, maquanly, loaibaocao, tieuchiloc, ngaytao) 
                      VALUES ('$mabaocao', '$maquanly', 'Số lượng sinh viên', '$tieuchiloc', '$ngaytao')";
        mysqli_query($con, $insert_bc);
    } else {
        $error = "Không có dữ liệu phù hợp với tiêu chí đã chọn.";
    }

    // // Xuất Excel nếu yêu cầu
    // if (isset($_POST['export_excel'])) {
    //     header('Content-Type: application/vnd.ms-excel');
    //     header('Content-Disposition: attachment;filename="BaoCao_SoLuongSinhVien_' . date('Ymd') . '.xls"');
    //     echo "<table border='1'>
    //         <tr><th>Mã sinh viên</th><th>Họ tên</th><th>Khoa</th><th>Lớp</th><th>Số phòng</th></tr>";
    //     foreach ($report_data as $row) {
    //         echo "<tr><td>{$row['masinhvien']}</td><td>{$row['hoten']}</td><td>{$row['khoa']}</td><td>{$row['lop']}</td><td>{$row['sophong']}</td></tr>";
    //     }
    //     echo "</table>";
    //     exit();
    // }
}
?>

<?php include '../includes/sidebar.php'; ?>
<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
        <?php include '../includes/topbar.php'; ?>
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">Báo cáo số lượng sinh viên</h1>

            <!-- Form lọc báo cáo -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Lọc báo cáo</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Khoa:</label>
                                <select name="khoa" class="form-control">
                                    <option value="">Tất cả</option>
                                    <?php
                                    $khoa_query = mysqli_query($con, "SELECT DISTINCT khoa FROM sinhvien ORDER BY khoa");
                                    while ($k = mysqli_fetch_array($khoa_query)) {
                                        echo "<option value='{$k['khoa']}' " . ($khoa == $k['khoa'] ? 'selected' : '') . ">{$k['khoa']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Số phòng:</label>
                                <select name="sophong" class="form-control">
                                    <option value="">Tất cả</option>
                                    <?php
                                    $phong_query = mysqli_query($con, "SELECT sophong FROM phong ORDER BY sophong");
                                    while ($phong = mysqli_fetch_array($phong_query)) {
                                        echo "<option value='{$phong['sophong']}' " . ($sophong == $phong['sophong'] ? 'selected' : '') . ">{$phong['sophong']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <button type="submit" name="export_report" class="btn btn-primary">Xuất báo cáo</button>
                    </form>
                </div>
            </div>

            <!-- Kết quả báo cáo -->
            <?php if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['export_report'])): ?>
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Kết quả báo cáo</h6>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-warning"><?php echo $error; ?></div>
                        <?php elseif (!empty($report_data)): ?>
                            <div class="row mb-3">
                                <div class="col-md-4"><strong>Tổng số sinh viên nội trú:</strong> <?php echo count($report_data); ?></div>
                                <?php foreach ($khoa_stats as $khoa_name => $count): ?>
                                    <div class="col-md-4"><strong><?php echo htmlspecialchars($khoa_name); ?>:</strong> <?php echo $count; ?></div>
                                <?php endforeach; ?>
                            </div>
                            <div class="mb-4 chart-container">
                                <canvas id="studentChart"></canvas>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="reportTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Mã sinh viên</th>
                                            <th>Họ tên</th>
                                            <th>Khoa</th>
                                            <th>Lớp</th>
                                            <th>Số phòng</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($report_data as $row): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($row['masinhvien']); ?></td>
                                                <td><?php echo htmlspecialchars($row['hoten']); ?></td>
                                                <td><?php echo htmlspecialchars($row['khoa']); ?></td>
                                                <td><?php echo htmlspecialchars($row['lop']); ?></td>
                                                <td><?php echo htmlspecialchars($row['sophong']); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Lịch sử báo cáo -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Lịch sử báo cáo số lượng sinh viên</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="historyTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Mã báo cáo</th>
                                    <th>Quản lý</th>
                                    <th>Tiêu chí lọc</th>
                                    <th>Ngày tạo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_array($history_query)): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['mabaocao']); ?></td>
                                        <td><?php echo htmlspecialchars($row['tenquanly']); ?></td>
                                        <td><?php echo htmlspecialchars($row['tieuchiloc']); ?></td>
                                        <td><?php echo htmlspecialchars($row['ngaytao']); ?></td>
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

<!-- Thêm Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    $('#reportTable').DataTable({
        "order": [[0, "asc"]] // Sắp xếp theo Mã sinh viên
    });
    $('#historyTable').DataTable({
        "order": [[3, "desc"]] // Sắp xếp theo Ngày tạo
    });

    <?php if (!empty($report_data)): ?>
        var ctx = document.getElementById('studentChart').getContext('2d');
        var studentChart = new Chart(ctx, {
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
    <?php endif; ?>
});
</script>

<style>
.chart-container {
    position: relative;
    max-width: 400px; /* Giới hạn chiều rộng biểu đồ */
    height: 300px; /* Giới hạn chiều cao biểu đồ */
    margin: 0 auto; /* Căn giữa biểu đồ */
}
</style>