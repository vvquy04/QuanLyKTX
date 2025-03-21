<?php
session_start();
if (!isset($_SESSION['mataikhoan']) || $_SESSION['vaitro'] != 'sinhvien') {
    header("Location: ../../login.php");
    exit();
}

include '../../includes/config.php';
$page_title = "Đăng ký nội trú";
include '../../includes/header.php';

$masinhvien = $_SESSION['user'];

// Kiểm tra xem sinh viên đã có hợp đồng chưa
$check_contract = mysqli_query($con, "SELECT * FROM hopdong WHERE masinhvien = '$masinhvien' AND trangthai = 'Approved'");
$has_contract = mysqli_num_rows($check_contract) > 0;

// Kiểm tra đợt đăng ký hiện tại
$dot_query = mysqli_query($con, "SELECT * FROM dotdangky WHERE ngaybatdau <= CURDATE() AND ngayketthuc >= CURDATE()");
$dot_active = mysqli_num_rows($dot_query) > 0 ? mysqli_fetch_array($dot_query) : null;

// Xử lý đăng ký
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$has_contract && $dot_active) {
    $maphong = mysqli_real_escape_string($con, $_POST['maphong']);
    $madot = $dot_active['madot'];
    $ngaybatdau = mysqli_real_escape_string($con, $_POST['ngaybatdau']);
    $ngayketthuc = date('Y-m-d', strtotime($ngaybatdau . ' +6 months'));

    // Kiểm tra phòng còn chỗ không
    $room_query = mysqli_query($con, "SELECT succhua, sothanhvienhientai FROM phong WHERE maphong = '$maphong'");
    $room = mysqli_fetch_array($room_query);
    if ($room['sothanhvienhientai'] < $room['succhua']) {
        $query = "INSERT INTO hopdong (mahopdong, masinhvien, maphong, madot, ngaybatdau, ngayketthuc, trangthai) 
                  VALUES (CONCAT('HD', LPAD(FLOOR(RAND() * 10000), 4, '0')), '$masinhvien', '$maphong', '$madot', '$ngaybatdau', '$ngayketthuc', 'Đang chờ')";
        if (mysqli_query($con, $query)) {
            echo "<script>alert('Đăng ký nội trú thành công. Vui lòng chờ quản lý duyệt.'); window.location='index.php';</script>";
        } else {
            echo "<script>alert('Lỗi: " . mysqli_error($con) . "'); window.location='register_dorm.php';</script>";
        }
    } else {
        echo "<script>alert('Phòng đã đầy. Vui lòng chọn phòng khác.'); window.location='register_dorm.php';</script>";
    }
}
?>

<?php include '../includes/sidebar.php'; ?>
<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
        <?php include '../includes/topbar.php'; ?>
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">Đăng ký nội trú</h1>
            <p class="mb-4">Đăng ký nội trú nếu có đợt đang mở và bạn chưa có hợp đồng.</p>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Form đăng ký nội trú</h6>
                </div>
                <div class="card-body">
                    <?php if ($has_contract): ?>
                        <p class="text-center">Bạn đã có hợp đồng nội trú. Xem chi tiết tại <a href="index.php">Quản lý hợp đồng</a>.</p>
                    <?php elseif (!$dot_active): ?>
                        <p class="text-center">Hiện tại không có đợt đăng ký nội trú nào đang mở.</p>
                    <?php else: ?>
                        <div class="alert alert-info">
                            Đợt đăng ký đang mở: <?php echo $dot_active['tendon']; ?><br>
                            Từ: <?php echo $dot_active['ngaybatdau']; ?> đến <?php echo $dot_active['ngayketthuc']; ?>
                        </div>
                        <form method="POST">
                            <div class="form-group">
                                <label for="maphong">Chọn phòng</label>
                                <select class="form-control" id="maphong" name="maphong" required>
                                    <?php
                                    $room_query = mysqli_query($con, "SELECT maphong, sophong, succhua, sothanhvienhientai 
                                                                      FROM phong 
                                                                      WHERE sothanhvienhientai < succhua");
                                    if ($room_query) {
                                        if (mysqli_num_rows($room_query) > 0) {
                                            while ($room = mysqli_fetch_array($room_query)) {
                                                $slots_left = $room['succhua'] - $room['sothanhvienhientai'];
                                                echo "<option value='{$room['maphong']}'>{$room['sophong']} (Còn {$slots_left} chỗ)</option>";
                                            }
                                        } else {
                                            echo "<option value='' disabled selected>Không có phòng nào còn chỗ</option>";
                                        }
                                    } else {
                                        echo "<option value='' disabled selected>Lỗi truy vấn: " . mysqli_error($con) . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="ngaybatdau">Ngày bắt đầu</label>
                                <input type="date" class="form-control" id="ngaybatdau" name="ngaybatdau" min="<?php echo $dot_active['ngaybatdau']; ?>" required onchange="updateNgayKetThuc()">
                            </div>
                            <div class="form-group">
                                <label for="ngayketthuc">Ngày kết thúc</label>
                                <input type="date" class="form-control" id="ngayketthuc" name="ngayketthuc" readonly max="<?php echo $dot_active['ngayketthuc']; ?>" >
                            </div>
                            <button type="submit" class="btn btn-primary" <?php echo mysqli_num_rows($room_query) == 0 ? 'disabled' : ''; ?>>Đăng ký</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function updateNgayKetThuc() {
    let ngayBatDau = document.getElementById("ngaybatdau").value;
    if (ngayBatDau) {
        let ngayKetThuc = new Date(ngayBatDau);
        ngayKetThuc.setMonth(ngayKetThuc.getMonth() + 6); // Cộng 6 tháng
        document.getElementById("ngayketthuc").value = ngayKetThuc.toISOString().split('T')[0];
    }
}
</script>

<?php include '../../includes/footer.php'; ?>