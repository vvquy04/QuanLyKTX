<?php
session_start();

// Kiểm tra đăng nhập và vai trò sinh viên
if (!isset($_SESSION['mataikhoan']) || $_SESSION['vaitro'] != 'sinhvien') {
    header("Location: ../../login.php");
    exit();
}

include '../../includes/config.php'; // Kết nối MySQLi
$page_title = "Lịch sử yêu cầu";
include '../../includes/header.php';

$masinhvien = $_SESSION['user'];

// Lấy lịch sử yêu cầu của sinh viên
$query = "SELECT mayeucau, loaiyeucau, noidung, ngayyeucau, trangthai, lydotuchoi 
          FROM yeucau 
          WHERE masinhvien = '$masinhvien' 
          ORDER BY ngayyeucau DESC";
$result = mysqli_query($con, $query);
?>

<?php include '../includes/sidebar.php'; ?>
<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
        <?php include '../includes/topbar.php'; ?>
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">Lịch sử yêu cầu</h1>
            <p class="mb-4">Xem danh sách các yêu cầu bạn đã gửi. <a href="request_student.php">Gửi yêu cầu mới</a></p>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Danh sách yêu cầu</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Mã yêu cầu</th>
                                    <th>Loại yêu cầu</th>
                                    <th>Nội dung</th>
                                    <th>Ngày gửi</th>
                                    <th>Trạng thái</th>
                                    <th>Lý do từ chối</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (mysqli_num_rows($result) > 0): ?>
                                    <?php while ($row = mysqli_fetch_array($result)): ?>
                                        <tr>
                                            <td><?php echo $row['mayeucau']; ?></td>
                                            <td><?php echo $row['loaiyeucau']; ?></td>
                                            <td><?php echo $row['noidung']; ?></td>
                                            <td><?php echo $row['ngayyeucau']; ?></td>
                                            <td><?php echo $row['trangthai']; ?></td>
                                            <td><?php echo $row['lydotuchoi'] ?: 'Không có'; ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center">Không có yêu cầu nào.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>