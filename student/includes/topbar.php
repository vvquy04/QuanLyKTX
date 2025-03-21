
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

    <div class="ktx-title mx-auto">Quản lý ký túc xá Đại học Thủy lợi</div>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">
        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
        <li class="nav-item dropdown no-arrow d-sm-none">
            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search">
                    <div class="input-group">
                        <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li>

       <!-- Nav Item - Alerts -->
<li class="nav-item dropdown no-arrow mx-1">
    <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-bell fa-fw"></i>
        <?php
        // Đếm số thông báo chưa đọc
        $unread_query = "SELECT COUNT(*) AS unread FROM thongbao WHERE dadoc = 0";
        $unread_result = mysqli_query($con, $unread_query);
        $unread = mysqli_fetch_assoc($unread_result)['unread'];
        // Đếm tổng số thông báo
        $total_query = "SELECT COUNT(*) AS total FROM thongbao";
        $total_result = mysqli_query($con, $total_query);
        $total = mysqli_fetch_assoc($total_result)['total'];
        // Hiển thị badge
        if ($unread > 0) {
            echo '<span class="badge badge-danger badge-counter">' . ($unread > 9 ? '9+' : $unread) . '</span>';
        } elseif ($total > 0) {
            echo '<span class="badge badge-danger badge-counter">' . ($total > 9 ? '9+' : $total) . '</span>';
        }
        ?>
    </a>
    <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
        <h6 class="dropdown-header">Thông báo</h6>
        <?php
        // Lấy 5 thông báo mới nhất
        $notifications_query = "SELECT mathongbao, tieude, ngaygui FROM thongbao ORDER BY ngaygui DESC LIMIT 5";
        $notifications_result = mysqli_query($con, $notifications_query);
        if (mysqli_num_rows($notifications_result) > 0) {
            while ($row = mysqli_fetch_assoc($notifications_result)) {
                echo '<a class="dropdown-item d-flex align-items-center" href="/student/view_notification.php?mathongbao=' . htmlspecialchars($row['mathongbao']) . '" data-mathongbao="' . htmlspecialchars($row['mathongbao']) . '">';
                echo '<div class="mr-3">';
                echo '<div class="icon-circle bg-primary">';
                echo '<i class="fas fa-bell text-white"></i>';
                echo '</div>';
                echo '</div>';
                echo '<div>';
                echo '<div class="small text-gray-500">' . htmlspecialchars($row['ngaygui']) . '</div>';
                echo '<span class="font-weight-bold">' . htmlspecialchars($row['tieude']) . '</span>';
                echo '</div>';
                echo '</a>';
            }
        } else {
            echo '<a class="dropdown-item d-flex align-items-center" href="#">';
            echo '<div class="mr-3">';
            echo '<div class="icon-circle bg-secondary">';
            echo '<i class="fas fa-info text-white"></i>';
            echo '</div>';
            echo '</div>';
            echo '<div>';
            echo '<span class="font-weight-bold">Không có thông báo nào</span>';
            echo '</div>';
            echo '</a>';
        }
        ?>
        <a class="dropdown-item text-center small text-gray-500" href="/student/view_notification.php">Xem tất cả thông báo</a>
    </div>
</li>

       

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Trong topbar.php -->
<!-- Trong topbar.php -->
<li class="nav-item dropdown no-arrow">
    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $_SESSION['user']; ?></span>
        <img class="img-profile rounded-circle" src="/images/userstudent.png">
    </a>
    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
        
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
            Đăng xuất
        </a>
    </div>
</li>
    </ul>
</nav>