<?php
session_start();
if (!isset($_SESSION['mataikhoan']) || $_SESSION['vaitro'] != 'sinhvien') {
    header("Location: ../../login.php");
    exit();
}
include '../includes/config.php';

$page_title = "Xem thông báo";
include '../includes/header.php';

// Đánh dấu thông báo đã đọc nếu nhấp từ dropdown
if (isset($_GET['mathongbao'])) {
    $mathongbao = mysqli_real_escape_string($con, $_GET['mathongbao']);
    $query_update = "UPDATE thongbao SET dadoc = 1 WHERE mathongbao = '$mathongbao' AND dadoc = 0";
    mysqli_query($con, $query_update);
}

// Phân trang
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10; // Số thông báo mỗi trang
$offset = ($page - 1) * $limit;

// Lấy tổng số thông báo
$total_query = "SELECT COUNT(*) AS total FROM thongbao";
$total_result = mysqli_query($con, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_notifications = $total_row['total'];
$total_pages = ceil($total_notifications / $limit);

// Lấy danh sách thông báo
$query = "SELECT * FROM thongbao ORDER BY ngaygui DESC LIMIT $limit OFFSET $offset";
$result = mysqli_query($con, $query);

?>

<?php include 'includes/sidebar.php'; ?>
<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
        <?php include 'includes/topbar.php'; ?>
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">Xem thông báo</h1>
            <p class="mb-4">Danh sách các thông báo từ quản lý.</p>

            <!-- Hiển thị danh sách thông báo -->
            <?php if (mysqli_num_rows($result) > 0): ?>
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Danh sách thông báo</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Mã thông báo</th>
                                        <th>Tiêu đề</th>
                                        <th>Nội dung</th>
                                        <th>Ngày gửi</th>
                                        <th>Người gửi</th>
                                        <th>Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($row['mathongbao']); ?></td>
                                            <td><?php echo htmlspecialchars($row['tieude']); ?></td>
                                            <td><?php echo htmlspecialchars($row['noidung']); ?></td>
                                            <td><?php echo htmlspecialchars($row['ngaygui']); ?></td>
                                            <td><?php echo htmlspecialchars($row['maquanly']); ?></td>
                                            <td><?php echo $row['dadoc'] ? 'Đã đọc' : 'Chưa đọc'; ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            <?php else: ?>
                <div class="alert alert-info text-center">Không có thông báo nào để hiển thị.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>