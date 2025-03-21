<?php
session_start();
if (!isset($_SESSION['mataikhoan']) || $_SESSION['vaitro'] != 'quanly') {
    header("Location: ../../login.php");
    exit();
}

include '../../includes/config.php';
$page_title = "Báo cáo tình trạng phòng";
include '../../includes/header.php';

// Khởi tạo biến lọc
$sophong = $_POST['sophong'] ?? '';
$tinhtrang = $_POST['tinhtrang'] ?? '';

$maquanly = $_SESSION['user']; // Lấy mã quản lý từ session

// Truy vấn lịch sử báo cáo loại "Tình trạng phòng"
$history_query = mysqli_query($con, "
    SELECT b.*, q.hoten AS tenquanly
    FROM baocao b
    JOIN quanly q ON b.maquanly = q.maquanly
    WHERE b.loaibaocao = 'Tình trạng phòng'
    ORDER BY b.ngaytao DESC
");

// Xử lý xuất báo cáo
$report_data = [];
$stats = ['Đầy' => 0, 'Còn trống' => 0, 'Trống hoàn toàn' => 0];
$total_phong = 0;
$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['export_report'])) {
    // Xây dựng câu truy vấn
    $sql = "
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

    // Thêm điều kiện lọc
    $conditions = [];
    if (!empty($sophong)) {
        $conditions[] = "p.sophong = '$sophong'";
    }
    if (!empty($tinhtrang)) {
        $conditions[] = "(
            CASE 
                WHEN COUNT(hd.masinhvien) = p.succhua THEN 'Đầy'
                WHEN COUNT(hd.masinhvien) > 0 THEN 'Còn trống'
                ELSE 'Trống hoàn toàn'
            END = '$tinhtrang'
        )";
    }

    if (!empty($conditions)) {
        $sql .= " HAVING " . implode(" AND ", $conditions);
    }

    $sql .= " ORDER BY p.sophong ASC";

    // Thực thi truy vấn
    $result = mysqli_query($con, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
            $report_data[] = $row;
            $stats[$row['tinhtrang']]++;
        }
        $total_phong = count($report_data);

        // Lưu báo cáo vào bảng baocao
        $tieuchiloc = "Tình trạng phòng: " . 
                      (!empty($sophong) ? "phòng: $sophong" : "") . 
                      (!empty($tinhtrang) ? ", tình trạng: $tinhtrang" : "");
        $ngaytao = date('Y-m-d');

        // Tạo mã báo cáo mới
        $query_max_bc = "SELECT MAX(mabaocao) AS max_bc FROM baocao";
        $result_max_bc = mysqli_query($con, $query_max_bc);
        $row_max_bc = mysqli_fetch_array($result_max_bc);
        $max_bc = $row_max_bc['max_bc'] ?? 'BC000';
        $mabaocao = 'BC' . str_pad((int)substr($max_bc, 2) + 1, 3, '0', STR_PAD_LEFT);

        $insert_bc = "INSERT INTO baocao (mabaocao, maquanly, loaibaocao, tieuchiloc, ngaytao) 
                      VALUES ('$mabaocao', '$maquanly', 'Tình trạng phòng', '$tieuchiloc', '$ngaytao')";
        mysqli_query($con, $insert_bc);
    } else {
        $error = "Không có dữ liệu phù hợp với tiêu chí đã chọn.";
    }

    // // Xuất Excel nếu yêu cầu
    // if (isset($_POST['export_excel'])) {
    //     header('Content-Type: application/vnd.ms-excel');
    //     header('Content-Disposition: attachment;filename="BaoCao_TinhTrangPhong_' . date('Ymd') . '.xls"');
    //     echo "<table border='1'>
    //         <tr><th>Số phòng</th><th>Sức chứa</th><th>Số sinh viên</th><th>Tình trạng</th></tr>";
    //     foreach ($report_data as $row) {
    //         echo "<tr><td>{$row['sophong']}</td><td>{$row['succhua']}</td><td>{$row['so_sinhvien']}</td><td>{$row['tinhtrang']}</td></tr>";
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
            <h1 class="h3 mb-2 text-gray-800">Báo cáo tình trạng phòng</h1>

            <!-- Form lọc báo cáo -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Lọc báo cáo</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="row">
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
                            <div class="col-md-6 form-group">
                                <label>Tình trạng:</label>
                                <select name="tinhtrang" class="form-control">
                                    <option value="">Tất cả</option>
                                    <option value="Đầy" <?php echo $tinhtrang == 'Đầy' ? 'selected' : ''; ?>>Đầy</option>
                                    <option value="Còn trống" <?php echo $tinhtrang == 'Còn trống' ? 'selected' : ''; ?>>Còn trống</option>
                                    <option value="Trống hoàn toàn" <?php echo $tinhtrang == 'Trống hoàn toàn' ? 'selected' : ''; ?>>Trống hoàn toàn</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" name="export_report" class="btn btn-primary">Xuất báo cáo</button>
                        <!-- <button type="submit" name="export_excel" class="btn btn-success">Xuất Excel</button> -->
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
                                <div class="col-md-3"><strong>Tổng số phòng:</strong> <?php echo $total_phong; ?></div>
                                <div class="col-md-3"><strong>Phòng đầy:</strong> <?php echo $stats['Đầy']; ?></div>
                                <div class="col-md-3"><strong>Phòng còn trống:</strong> <?php echo $stats['Còn trống']; ?></div>
                                <div class="col-md-3"><strong>Phòng trống hoàn toàn:</strong> <?php echo $stats['Trống hoàn toàn']; ?></div>
                            </div>
                            <div class="mb-4 chart-container">
                                <canvas id="roomChart"></canvas>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="reportTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Số phòng</th>
                                            <th>Sức chứa</th>
                                            <th>Số sinh viên</th>
                                            <th>Tình trạng</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($report_data as $row): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($row['sophong']); ?></td>
                                                <td><?php echo htmlspecialchars($row['succhua']); ?></td>
                                                <td><?php echo htmlspecialchars($row['so_sinhvien']); ?></td>
                                                <td><?php echo htmlspecialchars($row['tinhtrang']); ?></td>
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
                    <h6 class="m-0 font-weight-bold text-primary">Lịch sử báo cáo tình trạng phòng</h6>
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

<script>
$(document).ready(function() {
    $('#reportTable').DataTable({
        "order": [[0, "asc"]] // Sắp xếp theo Số phòng
    });
    $('#historyTable').DataTable({
        "order": [[3, "desc"]] // Sắp xếp theo Ngày tạo
    });

    <?php if (!empty($report_data)): ?>
        var ctx = document.getElementById('roomChart').getContext('2d');
        var roomChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Đầy', 'Còn trống', 'Trống hoàn toàn'],
                datasets: [{
                    label: 'Số lượng phòng',
                    data: [<?php echo $stats['Đầy']; ?>, <?php echo $stats['Còn trống']; ?>, <?php echo $stats['Trống hoàn toàn']; ?>],
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
    <?php endif; ?>
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