<?php
session_start();
if (!isset($_SESSION['mataikhoan']) || $_SESSION['vaitro'] != 'quanly') {
    header("Location: ../../login.php");
    exit();
}
include '../../includes/config.php';
$page_title = "Quản lý phòng";
include '../../includes/header.php';
?>

<?php include '../includes/sidebar.php'; ?>
<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
        <?php include '../includes/topbar.php'; ?>
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">Quản lý phòng</h1>

            <!-- Nút Thêm Phòng -->
            <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addRoomModal">
                <i class="fas fa-plus mr-2"></i>Thêm Phòng
            </button>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Danh sách phòng</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Mã phòng</th>
                                    <th>Số phòng</th>
                                    <th>Sức chứa</th>
                                    <th>Loại phòng</th>
                                    <th>Giá Thuê</th>
                                    <th>Số thành viên hiện tại</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = mysqli_query($con, "SELECT * FROM phong");
                                while ($row = mysqli_fetch_array($query)) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['maphong']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['sophong']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['succhua']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['loaiphong']) . "</td>";
                                    echo "<td>" . number_format($row['giathue'], 0, ',', '.') . " VNĐ</td>";
                                    echo "<td>" . htmlspecialchars($row['sothanhvienhientai']) . "</td>";
                                    echo "<td>
                                            <button class='btn btn-warning btn-sm' onclick=\"editRoom(
                                                '" . addslashes($row['maphong']) . "',
                                                '" . addslashes($row['sophong']) . "',
                                                '" . addslashes($row['succhua']) . "',
                                                '" . addslashes($row['loaiphong']) . "',
                                                '" . addslashes($row['giathue']) . "',
                                                '" . addslashes($row['sothanhvienhientai']) . "'
                                            )\">
                                                <i class='fa fa-pen' aria-hidden='true'></i>
                                            </button>
                                            <a href='delete_room.php?maphong=" . urlencode($row['maphong']) . "' 
                                               class='btn btn-danger btn-sm' 
                                               onclick='return confirm(\"Bạn có chắc muốn xóa phòng này?\");'>
                                                <i class='fa fa-trash' aria-hidden='true'></i>
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
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>

<!-- Modal Thêm Phòng -->
<div class="modal fade" id="addRoomModal" tabindex="-1" role="dialog" aria-labelledby="addRoomModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm Phòng</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="add_room.php" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Số Phòng:</label>
                        <input type="text" name="sophong" class="form-control" >
                    </div>
                    <div class="form-group">
                        <label>Sức Chứa:</label>
                        <input type="number" name="succhua" class="form-control" min="1" >
                    </div>
                    <div class="form-group">
                        <label>Loại Phòng:</label>
                        <select name="loaiphong" class="form-control" >
                            <option value="Nam">Nam</option>
                            <option value="Nữ">Nữ</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Giá Thuê:</label>
                        <input type="number" name="giathue" class="form-control" min="0" step="1000" >
                    </div>
                    <div class="form-group">
                        <label>Số Thành Viên Hiện Tại:</label>
                        <input type="number" name="sothanhvienhientai" class="form-control" min="0" >
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary" name="add_room">Thêm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Cập Nhật Phòng -->
<div class="modal fade" id="editRoomModal" tabindex="-1" role="dialog" aria-labelledby="editRoomModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cập Nhật Phòng</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="update_room.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="maphong" id="edit_maphong">
                    <div class="form-group">
                        <label>Số Phòng:</label>
                        <input type="text" name="sophong" id="edit_sophong" class="form-control" >
                    </div>
                    <div class="form-group">
                        <label>Sức Chứa:</label>
                        <input type="number" name="succhua" id="edit_succhua" class="form-control" min="1" >
                    </div>
                    <div class="form-group">
                        <label>Loại Phòng:</label>
                        <select name="loaiphong" id="edit_loaiphong" class="form-control" >
                            <option value="Nam">Nam</option>
                            <option value="Nữ">Nữ</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Giá Thuê:</label>
                        <input type="number" name="giathue" id="edit_giathue" class="form-control" min="0" step="1000" >
                    </div>
                    <div class="form-group">
                        <label>Số Thành Viên Hiện Tại:</label>
                        <input type="number" name="sothanhvienhientai" id="edit_sothanhvienhientai" class="form-control" min="0" >
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary" name="update_room">Cập Nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editRoom(maphong, sophong, succhua, loaiphong, giathue, sothanhvienhientai) {
    document.getElementById('edit_maphong').value = maphong;
    document.getElementById('edit_sophong').value = sophong;
    document.getElementById('edit_succhua').value = succhua;
    document.getElementById('edit_loaiphong').value = loaiphong;
    document.getElementById('edit_giathue').value = giathue;
    document.getElementById('edit_sothanhvienhientai').value = sothanhvienhientai;
    $('#editRoomModal').modal('show');
}

// Kiểm tra form thêm phòng
document.querySelector('#addRoomModal form').addEventListener('submit', function(e) {
    const sophong = document.querySelector('#addRoomModal [name="sophong"]').value;
    const succhua = parseInt(document.querySelector('#addRoomModal [name="succhua"]').value);
    const giathue = parseFloat(document.querySelector('#addRoomModal [name="giathue"]').value);
    const sothanhvienhientai = parseInt(document.querySelector('#addRoomModal [name="sothanhvienhientai"]').value);

    if (!sophong) {
        alert('Số phòng là bắt buộc!');
        e.preventDefault();
        return;
    }
    if (isNaN(succhua) || succhua <= 0) {
        alert('Sức chứa phải là số dương!');
        e.preventDefault();
        return;
    }
    if (isNaN(giathue) || giathue < 0) {
        alert('Giá thuê không được âm!');
        e.preventDefault();
        return;
    }
    if (isNaN(sothanhvienhientai) || sothanhvienhientai < 0) {
        alert('Số thành viên hiện tại phải là số không âm!');
        e.preventDefault();
        return;
    }
    if (sothanhvienhientai > succhua) {
        alert('Số thành viên hiện tại không được vượt quá sức chứa!');
        e.preventDefault();
        return;
    }
});

// Kiểm tra form cập nhật phòng
document.querySelector('#editRoomModal form').addEventListener('submit', function(e) {
    const sophong = document.querySelector('#edit_sophong').value;
    const succhua = parseInt(document.querySelector('#edit_succhua').value);
    const giathue = parseFloat(document.querySelector('#edit_giathue').value);
    const sothanhvienhientai = parseInt(document.querySelector('#edit_sothanhvienhientai').value);

    if (!sophong) {
        alert('Số phòng là bắt buộc!');
        e.preventDefault();
        return;
    }
    if (isNaN(succhua) || succhua <= 0) {
        alert('Sức chứa phải là số dương!');
        e.preventDefault();
        return;
    }
    if (isNaN(giathue) || giathue < 0) {
        alert('Giá thuê không được âm!');
        e.preventDefault();
        return;
    }
    if (isNaN(sothanhvienhientai) || sothanhvienhientai < 0) {
        alert('Số thành viên hiện tại phải là số không âm!');
        e.preventDefault();
        return;
    }
    if (sothanhvienhientai > succhua) {
        alert('Số thành viên hiện tại không được vượt quá sức chứa!');
        e.preventDefault();
        return;
    }
});
</script>