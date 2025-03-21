<?php
session_start();

// Kiểm tra đăng nhập và vai trò sinh viên
if (!isset($_SESSION['mataikhoan']) || $_SESSION['vaitro'] != 'sinhvien') {
    header("Location: ../../login.php");
    exit();
}

include '../../includes/config.php'; // Kết nối MySQLi
$page_title = "Gửi yêu cầu";
include '../../includes/header.php';

$masinhvien = $_SESSION['user'];

// Xử lý gửi yêu cầu
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $loaiyeucau = mysqli_real_escape_string($con, $_POST['loaiyeucau'] ?? '');
    $noidung = mysqli_real_escape_string($con, $_POST['noidung'] ?? '');

    // Kiểm tra dữ liệu đầu vào
    if (empty($loaiyeucau) || empty($noidung)) {
        echo "<script>alert('Vui lòng nhập đầy đủ thông tin.'); window.location='request_student.php';</script>";
    } else {
        // Tạo mã yêu cầu mới
        $query = "SELECT MAX(mayeucau) as max_id FROM yeucau";
        $result = mysqli_query($con, $query);
        $row = mysqli_fetch_array($result);
        $max_id = $row['max_id'] ?? 'YC000';
        $new_id = 'YC' . str_pad((int)substr($max_id, 2) + 1, 3, '0', STR_PAD_LEFT);

        // Thêm yêu cầu vào bảng yeucau
        $ngayyeucau = date('Y-m-d');
        $query = "INSERT INTO yeucau (mayeucau, masinhvien, loaiyeucau, noidung, ngayyeucau, trangthai) 
                  VALUES ('$new_id', '$masinhvien', '$loaiyeucau', '$noidung', '$ngayyeucau', 'Chờ')";
        if (mysqli_query($con, $query)) {
            echo "<script>alert('Yêu cầu đã được gửi thành công. Vui lòng chờ quản lý xử lý.'); window.location='index.php';</script>";
        } else {
            echo "<script>alert('Lỗi: " . mysqli_error($con) . "'); window.location='request_student.php';</script>";
        }
    }
}
?>

<?php include '../includes/sidebar.php'; ?>
<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
        <?php include '../includes/topbar.php'; ?>
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">Gửi yêu cầu</h1>
            <p class="mb-4">Gửi yêu cầu tới quản lý ký túc xá (sửa chữa, vệ sinh, chuyển phòng, v.v.).</p>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Form gửi yêu cầu</h6>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="form-group">
                            <label for="loaiyeucau">Loại yêu cầu</label>
                            <select class="form-control" id="loaiyeucau" name="loaiyeucau" required>
                                <option value="Sửa chữa">Sửa chữa</option>
                                <option value="Vệ sinh">Vệ sinh</option>
                                <option value="Phản ánh">Phản ánh</option>
                                <option value="Hỗ trợ đăng ký">Hỗ trợ đăng ký</option>
                                <option value="Chuyển phòng">Chuyển phòng</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="noidung">Nội dung</label>
                            <textarea class="form-control" id="noidung" name="noidung" rows="5" required placeholder="Mô tả chi tiết yêu cầu của bạn"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Gửi yêu cầu</button>
                        <a href="index.php" class="btn btn-secondary">Quay lại</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>