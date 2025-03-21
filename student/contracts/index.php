<?php
session_start();
if (!isset($_SESSION['mataikhoan']) || $_SESSION['vaitro'] != 'sinhvien') {
    header("Location: ../../login.php");
    exit();
}
include '../../includes/config.php';
$page_title = "Quản lý hợp đồng";
include '../../includes/header.php';

$masinhvien = $_SESSION['user']; // Giả định session lưu mã sinh viên
$current_date = date('Y-m-d');
?>

<?php include '../includes/sidebar.php'; ?>
<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
        <?php include '../includes/topbar.php'; ?>
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">Quản lý hợp đồng</h1>
            <p class="mb-4">Danh sách hợp đồng nội trú của bạn. Nếu bạn chưa đăng ký nội trú, hãy kiểm tra mục đăng ký.</p>

            <!-- Thông báo -->
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
            <?php elseif (isset($_GET['error'])): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
            <?php endif; ?>

            <!-- Bảng danh sách hợp đồng -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Danh sách hợp đồng</h6>
                </div>
                <div class="card-body">
                    <?php
                    $query = mysqli_query($con, "
                        SELECT hd.*, p.sophong 
                        FROM hopdong hd 
                        JOIN phong p ON hd.maphong = p.maphong 
                        WHERE hd.masinhvien = '$masinhvien'
                    ");
                    if (mysqli_num_rows($query) > 0) {
                        echo '<div class="table-responsive">';
                        echo '<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">';
                        echo '<thead>';
                        echo '<tr>';
                        echo '<th>Mã hợp đồng</th>';
                        echo '<th>Số phòng</th>';
                        echo '<th>Ngày bắt đầu</th>';
                        echo '<th>Ngày kết thúc</th>';
                        echo '<th>Trạng thái</th>';
                        echo '<th>Hành động</th>';
                        echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';
                        while ($row = mysqli_fetch_array($query)) {
                            $trangthai = $row['trangthai'];
                            $ngayketthuc = $row['ngayketthuc'];
                            if ($ngayketthuc < $current_date && $trangthai == 'Hiệu lực') {
                                $trangthai = 'Hết hạn';
                                mysqli_query($con, "UPDATE hopdong SET trangthai='Hết hạn' WHERE mahopdong='{$row['mahopdong']}'");
                            }
                            $default_new_end_date = date('Y-m-d', strtotime($row['ngayketthuc'] . ' +6 months')); // Mặc định +6 tháng

                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['mahopdong']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['sophong']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['ngaybatdau']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['ngayketthuc']) . "</td>";
                            echo "<td>";
                            if ($trangthai == 'Hiệu lực') {
                                echo '<span class="text-success">Hiệu lực</span>';
                            } elseif ($trangthai == 'Hết hạn') {
                                echo '<span class="text-danger">Hết hạn</span>';
                            } elseif ($trangthai == 'Đang chờ') {
                                echo '<span class="text-warning">Đang chờ gia hạn</span>';
                            }
                            echo "</td>";
                            echo "<td>";
                            if ($trangthai == 'Hết hạn') {
                                echo '<button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#renewModal' . $row['mahopdong'] . '">';
                                echo '<i class="fa fa-sync" aria-hidden="true"></i> Xin gia hạn';
                                echo '</button>';
                            } elseif ($trangthai == 'Đang chờ') {
                                echo '<span class="text-warning">Đang chờ xác nhận</span>';
                            }
                            echo "</td>";
                            echo "</tr>";
                            ?>

                            <!-- Modal Gia hạn Hợp đồng -->
                            <div class="modal fade" id="renewModal<?php echo $row['mahopdong']; ?>" tabindex="-1" role="dialog" aria-labelledby="renewModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Xin gia hạn hợp đồng <?php echo $row['mahopdong']; ?></h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <form method="POST" action="renew_contract.php?mahopdong=<?php echo urlencode($row['mahopdong']); ?>">
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Mã hợp đồng:</label>
                                                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($row['mahopdong']); ?>" readonly>
                                                </div>
                                                <div class="form-group">
                                                    <label>Số phòng:</label>
                                                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($row['sophong']); ?>" readonly>
                                                </div>
                                                <div class="form-group">
                                                    <label>Ngày bắt đầu:</label>
                                                    <input type="date" class="form-control" value="<?php echo htmlspecialchars($row['ngaybatdau']); ?>" readonly>
                                                </div>
                                                <div class="form-group">
                                                    <label>Ngày kết thúc hiện tại:</label>
                                                    <input type="date" class="form-control" value="<?php echo htmlspecialchars($row['ngayketthuc']); ?>" readonly>
                                                </div>
                                                <div class="form-group">
                                                    <label>Ngày kết thúc mới (đề xuất):</label>
                                                    <input type="date" name="new_end_date" class="form-control" 
                                                           value="<?php echo $default_new_end_date; ?>" 
                                                           min="<?php echo date('Y-m-d', strtotime($row['ngayketthuc'] . ' +1 day')); ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                                                <button type="submit" name="renew_contract" class="btn btn-primary">
                                                    <i class="fa fa-sync mr-2"></i>Gửi yêu cầu
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        echo '</tbody>';
                        echo '</table>';
                        echo '</div>';
                    } else {
                        echo '<p class="text-center">Bạn chưa có hợp đồng nội trú nào. <a href="register_dorm.php">Đăng ký nội trú ngay</a>.</p>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
