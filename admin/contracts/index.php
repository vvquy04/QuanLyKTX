<?php
session_start();
if (!isset($_SESSION['mataikhoan']) || $_SESSION['vaitro'] != 'quanly') {
    header("Location: ../../login.php");
    exit();
}
include '../../includes/config.php';
$page_title = "Quản lý hợp đồng";
include '../../includes/header.php';

$maquanly = $_SESSION['user'];

// Xử lý xác nhận gia hạn
if (isset($_GET['confirm']) && !empty($_GET['mahopdong'])) {
    $mahopdong = mysqli_real_escape_string($con, $_GET['mahopdong']);
    $new_end_date = date('Y-m-d', strtotime('+6 months')); // Gia hạn thêm 6 tháng (có thể điều chỉnh)

    $sql = "UPDATE hopdong SET trangthai='Hiệu lực', ngayketthuc='$new_end_date' 
            WHERE mahopdong='$mahopdong' AND trangthai='Đang chờ'";
    if (mysqli_query($con, $sql) && mysqli_affected_rows($con) > 0) {
        header("Location: index.php?success=" . urlencode("Gia hạn hợp đồng thành công"));
        exit();
    } else {
        header("Location: index.php?error=" . urlencode("Hợp đồng không hợp lệ hoặc đã được xử lý"));
        exit();
    }
}

// Truy vấn danh sách tất cả hợp đồng
$query = mysqli_query($con, "
    SELECT h.*, p.sophong, s.hoten 
    FROM hopdong h 
    JOIN phong p ON h.maphong = p.maphong 
    JOIN sinhvien s ON h.masinhvien = s.masinhvien 
    ORDER BY h.ngayketthuc DESC
");

// Kiểm tra trạng thái hợp đồng
$current_date = date('Y-m-d');
?>

<?php include '../includes/sidebar.php'; ?>
<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
        <?php include '../includes/topbar.php'; ?>
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">Quản lý hợp đồng</h1>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
            <?php elseif (isset($_GET['error'])): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
            <?php endif; ?>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Danh sách hợp đồng</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Mã hợp đồng</th>
                                    <th>Sinh viên</th>
                                    <th>Phòng</th>
                                    <th>Ngày bắt đầu</th>
                                    <th>Ngày kết thúc</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_array($query)): ?>
                                    <?php
                                    $ngayketthuc = $row['ngayketthuc'];
                                    $trangthai = $row['trangthai'];
                                    if ($ngayketthuc < $current_date && $trangthai == 'Hiệu lực') {
                                        $trangthai = 'Hết hạn';
                                        mysqli_query($con, "UPDATE hopdong SET trangthai='Hết hạn' WHERE mahopdong='{$row['mahopdong']}'");
                                    }
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['mahopdong']); ?></td>
                                        <td><?php echo htmlspecialchars($row['hoten']); ?></td>
                                        <td><?php echo htmlspecialchars($row['sophong']); ?></td>
                                        <td><?php echo htmlspecialchars($row['ngaybatdau']); ?></td>
                                        <td><?php echo htmlspecialchars($row['ngayketthuc']); ?></td>
                                        <td>
                                            <?php
                                            if ($trangthai == 'Hiệu lực') {
                                                echo '<span class="text-success">Hiệu lực</span>';
                                            } elseif ($trangthai == 'Hết hạn') {
                                                echo '<span class="text-danger">Hết hạn</span>';
                                            } elseif ($trangthai == 'Đang chờ') {
                                                echo '<span class="text-warning">Đang chờ đăng ký</span>';
                                            }elseif ($trangthai == 'Đang chờ gia hạn') {
                                                echo '<span class="text-warning">Đang chờ gia hạn</span>';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php if ($trangthai == 'Đang chờ gia hạn'): ?>
                                                <a href="index.php?confirm=1&mahopdong=<?php echo urlencode($row['mahopdong']); ?>" 
                                                   class="btn btn-success btn-sm" 
                                                   onclick="return confirm('Xác nhận gia hạn hợp đồng này?');">
                                                    <i class="fa fa-check" aria-hidden="true"></i> Xác nhận
                                                </a>
                                            <?php endif; ?>
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
