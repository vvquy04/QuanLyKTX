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
            <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addRoomModal">Thêm Phòng</button>

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
                                    echo "<td>{$row['maphong']}</td>";
                                    echo "<td>{$row['sophong']}</td>";
                                    echo "<td>{$row['succhua']}</td>";
                                    echo "<td>{$row['loaiphong']}</td>";
                                    echo "<td>{$row['giathue']}</td>";
                                    echo "<td>{$row['sothanhvienhientai']}</td>";
                                    echo "<td>
                                           <button class='btn btn-warning btn-sm' onclick=\"editRoom(
        '".addslashes($row['maphong'])."',
        '".addslashes($row['sophong'])."',
        '".addslashes($row['succhua'])."',
        '".addslashes($row['loaiphong'])."',
        '".addslashes($row['giathue'])."',
        '".addslashes($row['sothanhvienhientai'])."'
    )\">
        <i class='fa fa-pen' aria-hidden='true'></i>
    </button>
                                            <a href='delete_room.php?maphong={$row['maphong']}' class='btn btn-danger btn-sm' 
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
                <h5 class="modal-title">Thêm Phòng </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="add_room.php" method="POST">
                <div class="modal-body">
                    <label>Số Phòng:</label>
                    <input type="text" name="sophong" class="form-control" required>
                    <label>Sức Chứa:</label>
                    <input type="number" name="succhua" class="form-control" required>
                    <label>Loại Phòng:</label>
                    <select name="loaiphong" class="form-control">
                        <option value="Nam">Nam</option>
                        <option value="Nữ">Nữ</option>
                    </select>
                    <label>Giá Thuê:</label>
                    <input type="text" name="giathue" class="form-control" required>
                    <label>Số Thành Viên Hiện Tại:</label>
                    <input type="number" name="sothanhvienhientai" class="form-control" required>
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
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="update_room.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="maphong" id="edit_maphong">
                    <label>Số Phòng:</label>
                    <input type="text" name="sophong" id="edit_sophong" class="form-control" required>
                    <label>Sức Chứa:</label>
                    <input type="number" name="succhua" id="edit_succhua" class="form-control" required>
                    <label>Loại Phòng:</label>
                    <select name="loaiphong" id="edit_loaiphong" class="form-control">
                        <option value="Nam">Nam</option>
                        <option value="Nữ">Nữ</option>
                    </select>
                    <label>Giá Thuê:</label>
                    <input type="text" name="giathue" id="edit_giathue" class="form-control" required>
                    <label>Số Thành Viên Hiện Tại:</label>
                    <input type="number" name="sothanhvienhientai" id="edit_sothanhvienhientai" class="form-control" required>
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
        $('#editRoomModal').modal('show'); // Hiển thị modal
    }
</script>
