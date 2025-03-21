<?php
session_start();
if (!isset($_SESSION['mataikhoan']) || $_SESSION['vaitro'] != 'quanly') {
    header("Location: ../../login.php");
    exit();
}
include '../../includes/config.php';
$page_title = "Quản lý dịch vụ";
include '../../includes/header.php';
?>

<?php include '../includes/sidebar.php'; ?>
<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
        <?php include '../includes/topbar.php'; ?>
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">Quản lý dịch vụ</h1>

            <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addServiceModal">
                <i class="fas fa-plus mr-2"></i>Thêm Dịch Vụ
            </button>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Danh sách dịch vụ</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Mã dịch vụ</th>
                                    <th>Tên dịch vụ</th>
                                    <th>Giá đơn vị</th>
                                    <th>Mô tả</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = mysqli_query($con, "SELECT * FROM dichvu");
                                while ($row = mysqli_fetch_array($query)) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['madichvu']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['tendichvu']) . "</td>";
                                    echo "<td>" . number_format($row['giadichvu'], 0, ',', '.') . " VNĐ/" . ($row['tendichvu'] == 'Điện' ? 'kWh' : ($row['tendichvu'] == 'Nước' ? 'm³' : 'lần')) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['mota']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['trangthai']) . "</td>";
                                    echo "<td>
                                            <button class='btn btn-warning btn-sm' onclick=\"editService(
                                                '" . addslashes($row['madichvu']) . "',
                                                '" . addslashes($row['tendichvu']) . "',
                                                '" . addslashes($row['giadichvu']) . "',
                                                '" . addslashes($row['mota']) . "',
                                                '" . addslashes($row['trangthai']) . "'
                                            )\">
                                                <i class='fa fa-pen'></i>
                                            </button>
                                            <a href='delete_service.php?madichvu=" . urlencode($row['madichvu']) . "' 
                                               class='btn btn-danger btn-sm' 
                                               onclick='return confirm(\"Bạn có chắc muốn xóa dịch vụ này?\");'>
                                                <i class='fa fa-trash'></i>
                                            </a>
                                          </td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <h1 class="h3 mb-2 text-gray-800">Danh sách đăng ký dịch vụ</h1>
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Sinh viên đăng ký dịch vụ</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable2" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Mã đăng ký</th>
                                    <th>Mã sinh viên</th>
                                    <th>Họ tên</th>
                                    <th>Phòng</th>
                                    <th>Tên dịch vụ</th>
                                    <th>Ngày đăng ký</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query_dk = mysqli_query($con, "
                                    SELECT dk.*, sv.hoten, p.sophong, dv.tendichvu
                                    FROM dangkydichvu dk
                                    JOIN sinhvien sv ON dk.masinhvien = sv.masinhvien
                                    JOIN dichvu dv ON dk.madichvu = dv.madichvu
                                    LEFT JOIN hopdong hd ON dk.masinhvien = hd.masinhvien AND hd.trangthai = 'Hiệu lực'
                                    LEFT JOIN phong p ON hd.maphong = p.maphong
                                ");
                                while ($row_dk = mysqli_fetch_array($query_dk)) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row_dk['madangky']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row_dk['masinhvien']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row_dk['hoten']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row_dk['sophong'] ?? 'Chưa có') . "</td>";
                                    echo "<td>" . htmlspecialchars($row_dk['tendichvu']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row_dk['ngaydangky']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row_dk['trangthai']) . "</td>";
                                    echo "</tr>";
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

<!-- Modal Thêm Dịch Vụ -->
<div class="modal fade" id="addServiceModal" tabindex="-1" role="dialog" aria-labelledby="addServiceModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm Dịch Vụ</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="add_service.php" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Tên Dịch Vụ:</label>
                        <input type="text" name="tendichvu" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Giá Đơn Vị:</label>
                        <input type="number" name="giadichvu" class="form-control" min="0" step="1000" required>
                    </div>
                    <div class="form-group">
                        <label>Mô Tả:</label>
                        <textarea name="mota" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Trạng Thái:</label>
                        <select name="trangthai" class="form-control" required>
                            <option value="Hoạt động">Hoạt động</option>
                            <option value="Ngừng hoạt động">Ngừng hoạt động</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" name="add_service" class="btn btn-primary">Thêm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Cập Nhật Dịch Vụ -->
<div class="modal fade" id="editServiceModal" tabindex="-1" role="dialog" aria-labelledby="editServiceModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cập Nhật Dịch Vụ</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="update_service.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="madichvu" id="edit_madichvu">
                    <div class="form-group">
                        <label>Tên Dịch Vụ:</label>
                        <input type="text" name="tendichvu" id="edit_tendichvu" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Giá Đơn Vị:</label>
                        <input type="number" name="giadichvu" id="edit_giadichvu" class="form-control" min="0" step="1000" required>
                    </div>
                    <div class="form-group">
                        <label>Mô Tả:</label>
                        <textarea name="mota" id="edit_mota" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Trạng Thái:</label>
                        <select name="trangthai" id="edit_trangthai" class="form-control" required>
                            <option value="Hoạt động">Hoạt động</option>
                            <option value="Ngừng hoạt động">Ngừng hoạt động</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" name="update_service" class="btn btn-primary">Cập Nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editService(madichvu, tendichvu, giadichvu, mota, trangthai) {
    document.getElementById('edit_madichvu').value = madichvu;
    document.getElementById('edit_tendichvu').value = tendichvu;
    document.getElementById('edit_giadichvu').value = giadichvu;
    document.getElementById('edit_mota').value = mota || '';
    document.getElementById('edit_trangthai').value = trangthai;
    $('#editServiceModal').modal('show');
}
</script>
