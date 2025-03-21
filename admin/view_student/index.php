<?php
session_start();
if (!isset($_SESSION['mataikhoan']) || $_SESSION['vaitro'] != 'quanly') {
    header("Location: ../../login.php");
    exit();
}

include '../../includes/config.php';
$page_title = "Danh sách sinh viên";
include '../../includes/header.php';

// Truy vấn danh sách tất cả sinh viên
$query = mysqli_query($con, "SELECT masinhvien, hoten, khoa, lop, email, sodienthoai FROM sinhvien ORDER BY masinhvien ASC");
?>

<?php include '../includes/sidebar.php'; ?>
<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
        <?php include '../includes/topbar.php'; ?>
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">Danh sách sinh viên</h1>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tất cả sinh viên</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Mã sinh viên</th>
                                    <th>Họ tên</th>
                                    <th>Khoa</th>
                                    <th>Lớp</th>
                                    <th>Email</th>
                                    <th>Số điện thoại</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_array($query)): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['masinhvien']); ?></td>
                                        <td><?php echo htmlspecialchars($row['hoten']); ?></td>
                                        <td><?php echo htmlspecialchars($row['khoa']); ?></td>
                                        <td><?php echo htmlspecialchars($row['lop']); ?></td>
                                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                                        <td><?php echo htmlspecialchars($row['sodienthoai']); ?></td>
                                        <td>
                                            <a href="detail_student.php?masinhvien=<?php echo urlencode($row['masinhvien']); ?>" 
                                               class="btn btn-primary btn-sm">
                                                <i class="fas fa-eye"></i> Xem chi tiết
                                            </a>
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

