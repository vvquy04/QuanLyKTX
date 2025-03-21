
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/student/dashboard.php">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fa fa-graduation-cap" aria-hidden="true"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Sinh viên</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
        <a class="nav-link" href="/student/dashboard.php">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Trang chủ</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Đăng ký nội trú -->
    <li class="nav-item <?php echo strpos($_SERVER['PHP_SELF'], 'contracts') !== false ? 'active' : ''; ?>">
        <a class="nav-link" href="/student/contracts/index.php">
            <i class="fa fa-home" aria-hidden="true"></i>
            <span>Đăng ký nội trú</span>
        </a>
    </li>

    

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Hóa đơn -->
    <li class="nav-item <?php echo strpos($_SERVER['PHP_SELF'], 'invoices') !== false ? 'active' : ''; ?>">
        <a class="nav-link" href="/student/invoices/index.php">
        <i class="fa-solid fa-receipt"></i>
            <span>Hóa đơn</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Dịch vụ -->
    <li class="nav-item <?php echo strpos($_SERVER['PHP_SELF'], 'services') !== false ? 'active' : ''; ?>">
        <a class="nav-link" href="/student/services/index.php">
            <i class="fa fa-cogs" aria-hidden="true"></i>
            <span>Dịch vụ</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Xem thông báo -->
    <li class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'view_notification.php' ? 'active' : ''; ?>">
        <a class="nav-link" href="/student/view_notification.php">
            <i class="fas fa-fw fa-bell"></i>
            <span>Xem thông báo</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Xem thông tin sinh viên -->
    <li class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'view_profile.php' ? 'active' : ''; ?>">
        <a class="nav-link" href="/student/view_profile.php">
            <i class="fa fa-info-circle" aria-hidden="true"></i>
            <span>Xem thông tin sinh viên</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Phòng -->
    <li class="nav-item <?php echo strpos($_SERVER['PHP_SELF'], 'rooms') !== false ? 'active' : ''; ?>">
        <a class="nav-link" href="/student/rooms/index.php">
            <i class="fa fa-bed" aria-hidden="true"></i>
            <span>Gửi yêu cầu sinh viên</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
<!-- End of Sidebar -->