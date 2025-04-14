<!-- includes/sidebar.php -->
<?php
if (!isset($_SESSION['mataikhoan']) || $_SESSION['vaitro'] != 'quanly') {
    header("Location: ../login.php");
    exit();
}
?>

<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="../admin/dashboard.php">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fa fa-graduation-cap" aria-hidden="true"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Quản lý</div>
    </a>
    <hr class="sidebar-divider my-0">
    <li class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
        <a class="nav-link" href="/admin/dashboard.php">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Trang chủ</span>
        </a>
    </li>
    <hr class="sidebar-divider">
    <li class="nav-item <?php echo strpos($_SERVER['PHP_SELF'], 'dorm_registration') !== false ? 'active' : ''; ?>">
        <a class="nav-link" href="/admin/dorm_registration/index.php">
            <i class="fa fa-home" aria-hidden="true"></i>
            <span>Đăng ký nội trú</span>
        </a>
    </li>
    <hr class="sidebar-divider">
    <li class="nav-item <?php echo strpos($_SERVER['PHP_SELF'], 'rooms') !== false ? 'active' : ''; ?>">
        <a class="nav-link" href="/admin/rooms/index.php">
            <i class="fa fa-bed" aria-hidden="true"></i>
            <span>Phòng</span>
        </a>
    </li>
    <hr class="sidebar-divider">
    <li class="nav-item <?php echo strpos($_SERVER['PHP_SELF'], 'invoices') !== false ? 'active' : ''; ?>">
        <a class="nav-link" href="/admin/invoices/index.php">
            <i class="fa fa-file" aria-hidden="true"></i>
            <span>Phí ký túc xá</span>
        </a>
    </li>
    <hr class="sidebar-divider">
    <li class="nav-item <?php echo strpos($_SERVER['PHP_SELF'], 'contracts') !== false ? 'active' : ''; ?>">
        <a class="nav-link" href="/admin/contracts/index.php">
        <i class="fa-solid fa-receipt"></i>
            <span>Hồ sơ nội trú</span>
        </a>
    </li>
    <hr class="sidebar-divider">
    <li class="nav-item <?php echo strpos($_SERVER['PHP_SELF'], 'services') !== false ? 'active' : ''; ?>">
        <a class="nav-link" href="/admin/services/index.php">
            <i class="fa fa-cogs" aria-hidden="true"></i>
            <span>Dịch vụ</span>
        </a>
    </li>
    <hr class="sidebar-divider">
    <li class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'post_notification.php' ? 'active' : ''; ?>">
        <a class="nav-link" href="/admin/post_notification.php">
            <i class="fas fa-fw fa-bell"></i>
            <span>Đăng thông báo</span>
        </a>
    </li>
    <hr class="sidebar-divider">
    <li class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'process_student_requests.php' ? 'active' : ''; ?>">
        <a class="nav-link" href="/admin/process_student_requests.php">
            <i class="fas fa-fw fa-tasks"></i>
            <span>Xử lý yêu cầu sinh viên</span>
        </a>
    </li>
    <hr class="sidebar-divider">
    <li class="nav-item <?php echo strpos($_SERVER['PHP_SELF'], 'view_student') !== false ? 'active' : ''; ?>">
        <a class="nav-link" href="/admin/view_student/index.php">
            <i class="fa fa-info-circle" aria-hidden="true"></i>
            <span>Xem thông tin sinh viên</span>
        </a>
    </li>
    <hr class="sidebar-divider">
    <li class="nav-item <?php echo strpos($_SERVER['PHP_SELF'], 'reports') !== false ? 'active' : ''; ?>">
    <a class="nav-link collapsed" href="reports/index.php" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Báo cáo</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Xem báo cáo:</h6>
                        <a class="collapse-item" href="/admin/reports/revenue_report.php">Doanh thu từ hóa đơn</a>
                        <a class="collapse-item" href="/admin/reports/student_report.php">Số lượng sinh viên nội trú</a>
                        <a class="collapse-item" href="/admin/reports/room_report.php">Tình trạng phòng</a>
                    </div>
                </div>
    </li>
    <hr class="sidebar-divider d-none d-md-block">
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>