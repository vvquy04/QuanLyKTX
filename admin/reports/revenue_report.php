<?php
session_start();
if (!isset($_SESSION['mataikhoan']) || $_SESSION['vaitro'] != 'quanly') {
    header("Location: ../../login.php");
    exit();
}

include '../../includes/config.php';
$page_title = "Báo cáo doanh thu từ hóa đơn";
include '../../includes/header.php';

// Khởi tạo biến lọc
$start_date = $_POST['start_date'] ?? '';
$end_date = $_POST['end_date'] ?? '';
$sophong = $_POST['sophong'] ?? '';

$maquanly = $_SESSION['user']; // Lấy mã quản lý từ session

// Truy vấn lịch sử báo cáo loại "Doanh thu hóa đơn"
$history_query = mysqli_query($con, "
    SELECT b.*, q.hoten AS tenquanly
    FROM baocao b
    JOIN quanly q ON b.maquanly = q.maquanly
    WHERE b.loaibaocao = 'Doanh thu hóa đơn'
    ORDER BY b.ngaytao DESC
");

// Xử lý xuất báo cáo
$report_data = [];
$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['export_report'])) {
    // Xây dựng câu truy vấn
    $sql = "
        SELECT h.mahoadon, h.tongtien, h.ngaytao, h.hanthanhtoan, h.trangthaithanhtoan, 
               s.hoten, p.sophong
        FROM hoadon h
        JOIN sinhvien s ON h.masinhvien = s.masinhvien
        LEFT JOIN hopdong hd ON h.masinhvien = hd.masinhvien AND hd.trangthai = 'Hiệu lực'
        LEFT JOIN phong p ON hd.maphong = p.maphong
        WHERE h.trangthaithanhtoan = 'Đã thanh toán'
    ";

    // Thêm điều kiện lọc
    $conditions = [];
    if (!empty($start_date)) {
        $conditions[] = "h.ngaytao >= '$start_date'";
    }
    if (!empty($end_date)) {
        $conditions[] = "h.ngaytao <= '$end_date'";
    }
    if (!empty($sophong)) {
        $conditions[] = "p.sophong = '$sophong'";
    }

    if (!empty($conditions)) {
        $sql .= " AND " . implode(" AND ", $conditions);
    }

    $sql .= " ORDER BY h.ngaytao DESC";

    // Thực thi truy vấn
    $result = mysqli_query($con, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
            $report_data[] = $row;
        }

        // Lưu báo cáo vào bảng baocao
        $tieuchiloc = "Doanh thu từ hóa đơn: " . (!empty($start_date) ? "từ $start_date " : "") . 
                      (!empty($end_date) ? "đến $end_date" : "") . 
                      (!empty($sophong) ? ", phòng: $sophong" : "");
        $ngaytao = date('Y-m-d');

        // Tạo mã báo cáo mới
        $query_max_bc = "SELECT MAX(mabaocao) as max_bc FROM baocao";
        $result_max_bc = mysqli_query($con, $query_max_bc);
        $row_max_bc = mysqli_fetch_array($result_max_bc);
        $max_bc = $row_max_bc['max_bc'] ?? 'BC000';
        $mabaocao = 'BC' . str_pad((int)substr($max_bc, 2) + 1, 3, '0', STR_PAD_LEFT);

        $insert_bc = "INSERT INTO baocao (mabaocao, maquanly, loaibaocao, tieuchiloc, ngaytao) 
                      VALUES ('$mabaocao', '$maquanly', 'Doanh thu hóa đơn', '$tieuchiloc', '$ngaytao')";
        mysqli_query($con, $insert_bc);
    } else {
        $error = "Không có dữ liệu phù hợp với tiêu chí đã chọn.";
    }
}
?>

<?php include '../includes/sidebar.php'; ?>
<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
        <?php include '../includes/topbar.php'; ?>
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">Báo cáo doanh thu từ hóa đơn</h1>

            <!-- Form lọc báo cáo -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Lọc báo cáo</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label>Ngày bắt đầu:</label>
                                <input type="date" name="start_date" class="form-control" value="<?php echo htmlspecialchars($start_date); ?>">
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Ngày kết thúc:</label>
                                <input type="date" name="end_date" class="form-control" value="<?php echo htmlspecialchars($end_date); ?>">
                            </div>
                            <div class="col-md-4 form-group">
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
                            <div class="table-responsive">
                                <table class="table table-bordered" id="reportTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Mã hóa đơn</th>
                                            <th>Sinh viên</th>
                                            <th>Phòng</th>
                                            <th>Tổng tiền</th>
                                            <th>Ngày tạo</th>
                                            <th>Hạn thanh toán</th>
                                            <th>Trạng thái</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $total_revenue = 0;
                                        foreach ($report_data as $row) {
                                            $total_revenue += $row['tongtien'];
                                            echo "<tr>";
                                            echo "<td>" . htmlspecialchars($row['mahoadon']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['hoten']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['sophong'] ?? 'Chưa có phòng') . "</td>";
                                            echo "<td>" . number_format($row['tongtien'], 2) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['ngaytao']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['hanthanhtoan']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['trangthaithanhtoan']) . "</td>";
                                            echo "</tr>";
                                        }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" class="text-right">Tổng doanh thu:</th>
                                            <th><?php echo number_format($total_revenue, 2); ?></th>
                                            <th colspan="3"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Lịch sử báo cáo -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Lịch sử báo cáo doanh thu</h6>
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
        "order": [[4, "desc"]] // Sắp xếp theo Ngày tạo
    });
    $('#historyTable').DataTable({
        "order": [[3, "desc"]] // Sắp xếp theo Ngày tạo
    });
});
</script>