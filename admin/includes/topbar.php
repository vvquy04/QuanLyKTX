<?php
// Kiểm tra vai trò quản lý
if (!isset($_SESSION['mataikhoan']) || $_SESSION['vaitro'] != 'quanly') {
    header("Location: ../login.php");
    exit();
}

// Lấy số lượng và danh sách yêu cầu chưa xử lý
$query_count = "SELECT COUNT(*) as pending FROM yeucau WHERE trangthai = 'Chờ'";
$result_count = mysqli_query($con, $query_count);
$pending_count = mysqli_fetch_array($result_count)['pending'];

$query_requests = "SELECT y.mayeucau, y.loaiyeucau, y.noidung, y.ngayyeucau, s.hoten 
                   FROM yeucau y 
                   JOIN sinhvien s ON y.masinhvien = s.masinhvien 
                   WHERE y.trangthai = 'Chờ' 
                   ORDER BY y.ngayyeucau DESC 
                   LIMIT 5"; // Giới hạn 5 yêu cầu mới nhất
$result_requests = mysqli_query($con, $query_requests);
?>
<style>
    .ktx-title {
    font-size: 30px; /* Điều chỉnh kích thước chữ */
    font-weight: bold; /* Chữ đậm */
    color: #4e73df; /* Màu xanh Bootstrap */
    text-transform: uppercase; /* Viết hoa */
    letter-spacing: 1px; /* Giãn chữ */
}
</style>
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Topbar Search -->
    <div class="ktx-title mx-auto">Quản lý ký túc xá Đại học Thủy lợi</div>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">
        <!-- Nav Item - Messages -->
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-envelope fa-fw"></i>
                <span class="badge badge-danger badge-counter"><?php echo $pending_count; ?></span>
            </a>
            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="messagesDropdown">
                <h6 class="dropdown-header">Yêu cầu từ sinh viên</h6>
                <?php if (mysqli_num_rows($result_requests) > 0): ?>
                    <?php while ($row = mysqli_fetch_array($result_requests)): ?>
                        <a class="dropdown-item d-flex align-items-center" href="../admin/process_student_requests.php#<?php echo $row['mayeucau']; ?>">
                            <div class="dropdown-list-image mr-3">
                                <img class="rounded-circle" src="/images/userstudent.png" alt="...">
                                <div class="status-indicator bg-success"></div>
                            </div>
                            <div class="font-weight-bold">
                                <div class="text-truncate">[<?php echo $row['loaiyeucau']; ?>] <?php echo $row['noidung']; ?></div>
                                <div class="small text-gray-500"><?php echo $row['hoten']; ?> · <?php echo $row['ngayyeucau']; ?></div>
                            </div>
                        </a>
                    <?php endwhile; ?>
                <?php else: ?>
                    <a class="dropdown-item text-center small text-gray-500" href="#">Không có yêu cầu nào</a>
                <?php endif; ?>
                <a class="dropdown-item text-center small text-gray-500" href="../process_student_requests.php">Xem tất cả yêu cầu</a>
            </div>
        </li>

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $_SESSION['user']; ?></span>
                <img class="img-profile rounded-circle" src="/images/userfemale.jpg">
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="../logout.php" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Đăng xuất
                </a>
            </div>
        </li>
    </ul>
</nav>