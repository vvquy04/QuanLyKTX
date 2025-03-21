<?php
session_start();

// Kiểm tra đăng nhập và vai trò quản lý
if (!isset($_SESSION['mataikhoan']) || $_SESSION['vaitro'] != 'quanly') {
    header("Location: ../login.php");
    exit();
}

include '../includes/config.php';
$page_title = "Xử lý yêu cầu sinh viên";
include '../includes/header.php';

// Xử lý cập nhật trạng thái yêu cầu
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mayeucau = mysqli_real_escape_string($con, $_POST['mayeucau'] ?? '');
    $trangthai = mysqli_real_escape_string($con, $_POST['trangthai'] ?? '');
    $lydotuchoi = mysqli_real_escape_string($con, $_POST['lydotuchoi'] ?? '');

    if ($trangthai === 'Từ chối' && empty($lydotuchoi)) {
        echo "<script>alert('Vui lòng nhập lý do từ chối.'); window.location='process_student_requests.php';</script>";
    } else {
        $lydotuchoi = $lydotuchoi ?: NULL;
        $query = "UPDATE yeucau SET trangthai = '$trangthai', lydotuchoi = " . ($lydotuchoi ? "'$lydotuchoi'" : "NULL") . " WHERE mayeucau = '$mayeucau'";
        if (mysqli_query($con, $query)) {
            echo "<script>alert('Yêu cầu đã được xử lý thành công!'); window.location='process_student_requests.php';</script>";
        } else {
            echo "<script>alert('Lỗi: " . mysqli_error($con) . "'); window.location='process_student_requests.php';</script>";
        }
    }
}

// Lấy danh sách yêu cầu đang chờ
$query = "SELECT y.mayeucau, y.masinhvien, s.hoten, y.loaiyeucau, y.noidung, y.ngayyeucau
          FROM yeucau y
          JOIN sinhvien s ON y.masinhvien = s.masinhvien
          WHERE y.trangthai = 'Chờ'";
$result = mysqli_query($con, $query);

?>

<?php include 'includes/sidebar.php'; ?>
<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
        <?php include 'includes/topbar.php'; ?>
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">Xử lý yêu cầu sinh viên</h1>
            <p class="mb-4">Xem và xử lý các yêu cầu từ sinh viên.</p>

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
                                    <th>Sinh viên</th>
                                    <th>Loại yêu cầu</th>
                                    <th>Nội dung</th>
                                    <th>Ngày yêu cầu</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (mysqli_num_rows($result) > 0): ?>
                                    <?php while ($row = mysqli_fetch_array($result)): ?>
                                        <tr>
                                            <td><?php echo $row['mayeucau']; ?></td>
                                            <td><?php echo $row['hoten'] . " (" . $row['masinhvien'] . ")"; ?></td>
                                            <td><?php echo $row['loaiyeucau']; ?></td>
                                            <td><?php echo $row['noidung']; ?></td>
                                            <td><?php echo $row['ngayyeucau']; ?></td>
                                            <td>
                                                <form method="POST" style="display:inline;">
                                                    <input type="hidden" name="mayeucau" value="<?php echo $row['mayeucau']; ?>">
                                                    <select name="trangthai" class="form-control d-inline w-auto">
                                                        <option value="Đã xử lý">Đã xử lý</option>
                                                        <option value="Từ chối">Từ chối</option>
                                                    </select>
                                                    <input type="text" name="lydotuchoi" class="form-control d-inline w-auto" placeholder="Lý do từ chối (nếu có)">
                                                    <button type="submit" class="btn btn-primary btn-sm m-2">Xác nhận</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center">Không có yêu cầu nào đang chờ xử lý.</td>
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

<?php include '../includes/footer.php'; ?>