<?php
session_start();
if (!isset($_SESSION['mataikhoan']) || $_SESSION['vaitro'] != 'sinhvien') {
    header("Location: ../../login.php");
    exit();
}

include '../includes/config.php';

$page_title = "Thông tin sinh viên";
include '../includes/header.php';

// Lấy thông tin sinh viên từ cơ sở dữ liệu
$masinhvien = $_SESSION['user'];
$query = "SELECT * FROM sinhvien WHERE masinhvien = '$masinhvien'";
$result = mysqli_query($con, $query);
$sinhvien = mysqli_fetch_assoc($result);

if (!$sinhvien) {
    die("Không tìm thấy thông tin sinh viên.");
}
?>


<?php include 'includes/sidebar.php'; ?>
<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
        <?php include 'includes/topbar.php'; ?>
        <div class="container emp-profile">
            <form method="post">
                <div class="row">
                    <div class="col-md-4">
                        <div class="profile-img">
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS52y5aInsxSm31CvHOFHWujqUx_wWTS9iM6s7BAm21oEN_RiGoog" alt=""/>
                            <div class="file btn btn-lg btn-primary">
                              
                                <input type="file" name="file"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="profile-head">
                            <h5>
                                <?php echo htmlspecialchars($sinhvien['hoten']); ?>
                            </h5>
                            <h6>
                                Sinh viên - <?php echo htmlspecialchars($sinhvien['khoa']); ?>
                            </h6>
                            <p class="proile-rating">MÃ SINH VIÊN: <span><?php echo htmlspecialchars($sinhvien['masinhvien']); ?></span></p>
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Thông tin cá nhân</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Thông tin học tập</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="profile-work">
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="tab-content profile-tab" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Mã sinh viên</label>
                                    </div>
                                    <div class="col-md-6">
                                        <p><?php echo htmlspecialchars($sinhvien['masinhvien']); ?></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Họ tên</label>
                                    </div>
                                    <div class="col-md-6">
                                        <p><?php echo htmlspecialchars($sinhvien['hoten']); ?></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Ngày sinh</label>
                                    </div>
                                    <div class="col-md-6">
                                        <p><?php echo htmlspecialchars($sinhvien['ngaysinh']); ?></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Giới tính</label>
                                    </div>
                                    <div class="col-md-6">
                                        <p><?php echo htmlspecialchars($sinhvien['gioitinh']); ?></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Email</label>
                                    </div>
                                    <div class="col-md-6">
                                        <p><?php echo htmlspecialchars($sinhvien['email']); ?></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Số điện thoại</label>
                                    </div>
                                    <div class="col-md-6">
                                        <p><?php echo htmlspecialchars($sinhvien['sodienthoai']); ?></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Địa chỉ</label>
                                    </div>
                                    <div class="col-md-6">
                                        <p><?php echo htmlspecialchars($sinhvien['diachi']); ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Khoa</label>
                                    </div>
                                    <div class="col-md-6">
                                        <p><?php echo htmlspecialchars($sinhvien['khoa']); ?></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Lớp</label>
                                    </div>
                                    <div class="col-md-6">
                                        <p><?php echo htmlspecialchars($sinhvien['lop']); ?></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Trạng thái học tập</label>
                                    </div>
                                    <div class="col-md-6">
                                        <p>Đang học</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Năm nhập học</label>
                                    </div>
                                    <div class="col-md-6">
                                        <p><?php echo date('Y', strtotime($sinhvien['ngaysinh'])) + 18; // Ước tính năm nhập học ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>