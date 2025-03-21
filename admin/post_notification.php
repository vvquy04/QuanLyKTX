<?php
session_start();
if (!isset($_SESSION['mataikhoan']) || $_SESSION['vaitro'] != 'quanly') {
    header("Location: ../../login.php");
    exit();
}
include '../includes/config.php';

// Tạo mã thông báo tự động (mathongbao)
$result = mysqli_query($con, "SELECT MAX(mathongbao) AS max_ma FROM thongbao");
$row = mysqli_fetch_assoc($result);
$max_mathongbao = $row['max_ma'];

if ($max_mathongbao) {
    // Lấy phần số cuối cùng của mã thông báo (VD: TB0001 -> lấy '0001')
    $so_moi = (int)substr($max_mathongbao, 2) + 1;
    // Định dạng lại mã thông báo thành TB + số có 4 chữ số (VD: TB0002, TB0010)
    $mathongbao = "TB" . str_pad($so_moi, 3, '0', STR_PAD_LEFT);
} else {
    // Nếu chưa có thông báo nào, bắt đầu từ TB0001
    $mathongbao = "TB001";
}

$page_title = "Đăng thông báo";
include '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy dữ liệu từ form
    $tieude = mysqli_real_escape_string($con, $_POST['tieude']);
    $noidung = mysqli_real_escape_string($con, $_POST['noidung']);
    $maquanly = $_SESSION['user']; // Lấy mã quản lý từ session
    $ngaygui = date('Y-m-d H:i:s'); // Ngày gửi là thời gian hiện tại

    // Kiểm tra dữ liệu đầu vào
    if (empty($tieude) || empty($noidung)) {
        $error = "Tiêu đề và nội dung không được để trống.";
    } else {
        // Thêm thông báo vào cơ sở dữ liệu
        $query = "INSERT INTO thongbao (mathongbao, maquanly, tieude, noidung, ngaygui) 
                  VALUES ('$mathongbao', '$maquanly', '$tieude', '$noidung', '$ngaygui')";
        if (mysqli_query($con, $query)) {
            $success = "Đăng thông báo thành công!";
        } else {
            $error = "Lỗi: " . mysqli_error($con);
        }
    }
}

?>

<?php include 'includes/sidebar.php'; ?>
<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
        <?php include 'includes/topbar.php'; ?>
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">Đăng thông báo</h1>
            <p class="mb-4">Tạo thông báo mới cho sinh viên.</p>

            <!-- Hiển thị thông báo thành công hoặc lỗi -->
            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <!-- Form đăng thông báo -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Form đăng thông báo</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="post_notification.php">
                        <div class="form-group">
                            <label for="tieude">Tiêu đề</label>
                            <input type="text" class="form-control" id="tieude" name="tieude" required>
                        </div>
                        <div class="form-group">
                            <label for="noidung">Nội dung</label>
                            <textarea class="form-control" id="noidung" name="noidung" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Đăng thông báo</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>