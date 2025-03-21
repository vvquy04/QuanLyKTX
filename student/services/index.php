<?php
session_start();
if (!isset($_SESSION['mataikhoan']) || $_SESSION['vaitro'] != 'sinhvien') {
    header("Location: ../../login.php");
    exit();
}
include '../../includes/config.php';
$page_title = "Quản lý dịch vụ cá nhân";
include '../../includes/header.php';

$masinhvien = $_SESSION['user'];

// Đăng ký mặc định Điện, Nước, Mạng nếu chưa đăng ký
$default_services = ['Điện', 'Nước', 'Internet'];
foreach ($default_services as $service) {
    $check = mysqli_query($con, "SELECT * FROM dangkydichvu dk 
                                 JOIN dichvu dv ON dk.madichvu = dv.madichvu 
                                 WHERE dk.masinhvien='$masinhvien' AND dv.tendichvu='$service' AND dk.trangthai='Đăng ký'");
    if (mysqli_num_rows($check) == 0) {
        $dv_query = mysqli_query($con, "SELECT madichvu FROM dichvu WHERE tendichvu='$service' AND trangthai='Hoạt động'");
        if ($dv = mysqli_fetch_array($dv_query)) {
            $madichvu = $dv['madichvu'];
            $newMaDangKy = 'DKDV' . str_pad((int)substr(mysqli_fetch_array(mysqli_query($con, "SELECT MAX(madangky) AS max_dk FROM dangkydichvu"))['max_dk'] ?? 'DKDV000', 4) + 1, 3, '0', STR_PAD_LEFT);
            mysqli_query($con, "INSERT INTO dangkydichvu (madangky, masinhvien, madichvu, ngaydangky, trangthai) 
                                VALUES ('$newMaDangKy', '$masinhvien', '$madichvu', CURDATE(), 'Đăng ký')");
        }
    }
}
?>

<?php include '../includes/sidebar.php'; ?>
<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
        <?php include '../includes/topbar.php'; ?>
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">Quản lý dịch vụ cá nhân</h1>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
            <?php elseif (isset($_GET['error'])): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
            <?php endif; ?>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Dịch vụ của tôi</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Mã đăng ký</th>
                                    <th>Tên dịch vụ</th>
                                    <th>Giá đơn vị</th>
                                    <th>Mô tả</th>
                                    <th>Ngày đăng ký</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = mysqli_query($con, "
                                    SELECT dk.*, dv.tendichvu, dv.giadichvu, dv.mota
                                    FROM dangkydichvu dk
                                    JOIN dichvu dv ON dk.madichvu = dv.madichvu
                                    WHERE dk.masinhvien='$masinhvien' AND dk.trangthai='Đăng ký'
                                ");
                                while ($row = mysqli_fetch_array($query)) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['madangky']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['tendichvu']) . "</td>";
                                    echo "<td>" . number_format($row['giadichvu'], 0, ',', '.') . " VNĐ/" . ($row['tendichvu'] == 'Điện' ? 'kWh' : ($row['tendichvu'] == 'Nước' ? 'm³' : 'lần')) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['mota']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['ngaydangky']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['trangthai']) . "</td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Dịch vụ có sẵn để đăng ký</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable2" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Mã dịch vụ</th>
                                    <th>Tên dịch vụ</th>
                                    <th>Giá đơn vị</th>
                                    <th>Mô tả</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = mysqli_query($con, "
                                    SELECT dv.*
                                    FROM dichvu dv
                                    LEFT JOIN dangkydichvu dk ON dv.madichvu = dk.madichvu AND dk.masinhvien='$masinhvien' AND dk.trangthai='Đăng ký'
                                    WHERE dv.trangthai='Hoạt động' AND dk.madangky IS NULL
                                ");
                                while ($row = mysqli_fetch_array($query)) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['madichvu']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['tendichvu']) . "</td>";
                                    echo "<td>" . number_format($row['giadichvu'], 0, ',', '.') . " VNĐ/" . ($row['tendichvu'] == 'Điện' ? 'kWh' : ($row['tendichvu'] == 'Nước' ? 'm³' : 'lần')) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['mota']) . "</td>";
                                    echo "<td>";
                                    if (!in_array($row['tendichvu'], $default_services)) {
                                        echo "<button class='btn btn-primary btn-sm' data-toggle='modal' data-target='#registerModal" . $row['madichvu'] . "'>
                                                <i class='fas fa-check mr-2'></i>Đăng ký
                                              </button>";
                                    }
                                    echo "</td>";
                                    echo "</tr>";
                                    if (!in_array($row['tendichvu'], $default_services)) {
                                        ?>
                                        <div class="modal fade" id="registerModal<?php echo $row['madichvu']; ?>" tabindex="-1" role="dialog">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Xác nhận đăng ký dịch vụ</h5>
                                                        <button type="button" class="close" data-dismiss="modal">
                                                            <span aria-hidden="true">×</span>
                                                        </button>
                                                    </div>
                                                    <form action="register_service.php" method="POST">
                                                        <div class="modal-body">
                                                            <input type="hidden" name="madichvu" value="<?php echo $row['madichvu']; ?>">
                                                            <p><strong>Tên dịch vụ:</strong> <?php echo htmlspecialchars($row['tendichvu']); ?></p>
                                                            <p><strong>Giá:</strong> <?php echo number_format($row['giadichvu'], 0, ',', '.'); ?> VNĐ</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                                                            <button type="submit" name="register_service" class="btn btn-primary">Xác nhận</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>

