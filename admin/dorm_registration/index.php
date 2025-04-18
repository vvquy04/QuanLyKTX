<?php
session_start();
if (!isset($_SESSION['mataikhoan']) || $_SESSION['vaitro'] != 'quanly') {
    header("Location: ../../login.php");
    exit();
}
include '../../includes/config.php';
$page_title = "Quản lý đăng ký nội trú";
include '../../includes/header.php';

// Kiểm tra trạng thái đợt đăng ký hiện tại
$dot_query = mysqli_query($con, "SELECT * FROM dotdangky WHERE ngaybatdau <= CURDATE() AND ngayketthuc >= CURDATE()");
$dot_active = mysqli_num_rows($dot_query) > 0 ? mysqli_fetch_array($dot_query) : null;

// Tạo mã đợt tự động
$result = mysqli_query($con, "SELECT MAX(madot) AS max_madot FROM dotdangky");
$row = mysqli_fetch_assoc($result);
$max_madot = $row['max_madot'];
$madot = $max_madot ? "DK" . str_pad((int)substr($max_madot, 2) + 1, 3, '0', STR_PAD_LEFT) : "DK001";
?>

<?php include '../includes/sidebar.php'; ?>
<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
        <?php include '../includes/topbar.php'; ?>
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">Quản lý đăng ký nội trú</h1>
            <p class="mb-4">Danh sách yêu cầu đăng ký nội trú từ sinh viên.
                
            <!-- Nút điều hướng -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <?php if ($dot_active): ?>
                        <div class="alert alert-info">
                            Đợt đăng ký đang mở: <?php echo htmlspecialchars($dot_active['tendon']); ?><br>
                            Từ: <?php echo $dot_active['ngaybatdau']; ?> đến <?php echo $dot_active['ngayketthuc']; ?>
                        </div>
                    <?php else: ?>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#openRegistrationModal">Mở đợt đăng ký</button>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Bảng danh sách đăng ký -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Danh sách yêu cầu đăng ký nội trú</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Mã hợp đồng</th>
                                    <th>Mã sinh viên</th>
                                    <th>Họ tên</th>
                                    <th>Mã phòng</th>
                                    <th>Ngày bắt đầu</th>
                                    <th>Ngày kết thúc</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = mysqli_query($con, "SELECT hd.mahopdong, hd.masinhvien, sv.hoten, hd.maphong, hd.ngaybatdau, hd.ngayketthuc, hd.trangthai 
                                                             FROM hopdong hd 
                                                             JOIN sinhvien sv ON hd.masinhvien = sv.masinhvien 
                                                             WHERE hd.trangthai = 'Đang chờ'");
                                while ($row = mysqli_fetch_array($query)) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['mahopdong']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['masinhvien']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['hoten']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['maphong']) . "</td>";
                                    echo "<td>" . $row['ngaybatdau'] . "</td>";
                                    echo "<td>" . $row['ngayketthuc'] . "</td>";
                                    echo "<td>";
                                    if ($row['trangthai'] == 'Hiệu lực') {
                                        echo '<span class="text-success">Hiệu lực</span>';
                                    } elseif ($row['trangthai'] == 'Hết hạn') {
                                        echo '<span class="text-danger">Hết hạn</span>';
                                    } elseif ($row['trangthai'] == 'Đang chờ') {
                                        echo '<span class="text-warning">Đang chờ</span>';
                                    } elseif ($row['trangthai'] == 'Đang chờ gia hạn') {
                                        echo '<span class="text-warning">Đang chờ gia hạn</span>';
                                    }
                                    echo "</td>";
                                    echo "<td>
                                        <button class='btn btn-success btn-sm' data-toggle='modal' data-target='#contractModal' 
                                                data-mahopdong='{$row['mahopdong']}' data-masinhvien='{$row['masinhvien']}' data-maphong='{$row['maphong']}'>Tạo hợp đồng</button>
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

<!-- Modal mở đợt đăng ký -->
<div class="modal fade" id="openRegistrationModal" tabindex="-1" role="dialog" aria-labelledby="openRegistrationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="openRegistrationModalLabel">Mở đợt đăng ký</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="open_registration.php" method="POST" id="openRegistrationForm">
                <div class="modal-body">
                    <input type="hidden" name="madot" value="<?php echo $madot; ?>">
                    <div class="form-group">
                        <label for="tendon">Tên đợt</label>
                        <input type="text" class="form-control" id="tendon" name="tendon" >
                    </div>
                    <div class="form-group">
                        <label for="ngaybatdau">Ngày bắt đầu</label>
                        <input type="date" class="form-control" id="ngaybatdau" name="ngaybatdau" >
                    </div>
                    <div class="form-group">
                        <label for="ngayketthuc">Ngày kết thúc</label>
                        <input type="date" class="form-control" id="ngayketthuc" name="ngayketthuc" >
                    </div>
                    <div class="form-group">
                        <label for="ghichu">Ghi chú</label>
                        <textarea class="form-control" id="ghichu" name="ghichu"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Mở đợt</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal tạo hợp đồng -->
<div class="modal fade" id="contractModal" tabindex="-1" role="dialog" aria-labelledby="contractModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="contractModalLabel">Tạo hợp đồng</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="create_contract.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="mahopdong" id="mahopdong">
                    <div class="form-group">
                        <label for="masinhvien">Mã sinh viên</label>
                        <input type="text" class="form-control" id="masinhvien" name="masinhvien" readonly>
                    </div>
                    <div class="form-group">
                        <label for="maphong">Mã phòng</label>
                        <input type="text" class="form-control" id="maphong" name="maphong" readonly>
                    </div>
                    <div class="form-group">
                        <label for="madot">Mã đợt</label>
                        <select class="form-control" id="madot" name="madot" >
                            <?php
                            $dot_query = mysqli_query($con, "SELECT * FROM dotdangky WHERE ngaybatdau <= CURDATE() AND ngayketthuc >= CURDATE()");
                            while ($dot = mysqli_fetch_array($dot_query)) {
                                echo "<option value='{$dot['madot']}'>" . htmlspecialchars($dot['tendon']) . " ({$dot['madot']})</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="ngaybatdau">Ngày bắt đầu</label>
                        <input type="text" class="form-control" id="ngaybatdau" name="ngaybatdau" value="<?php echo $dot_active ? $dot_active['ngaybatdau'] : ''; ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="ngayketthuc">Ngày kết thúc</label>
                        <input type="text" class="form-control" id="ngayketthuc" name="ngayketthuc" value="<?php echo $dot_active ? $dot_active['ngayketthuc'] : ''; ?>" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Tạo hợp đồng</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>

<script>
    $(document).ready(function() {
        $('#contractModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var mahopdong = button.data('mahopdong');
            var masinhvien = button.data('masinhvien');
            var maphong = button.data('maphong');
            var modal = $(this);
            modal.find('#mahopdong').val(mahopdong);
            modal.find('#masinhvien').val(masinhvien);
            modal.find('#maphong').val(maphong);
        });

        $('#openRegistrationForm').on('submit', function(e) {
            var tendon = $('#tendon').val();
            var ngaybatdau = $('#ngaybatdau').val();
            var ngayketthuc = $('#ngayketthuc').val();

            if (!tendon) {
                e.preventDefault();
                alert('Tên đợt không được bỏ trống');
                return false;
            }
            if (ngaybatdau && ngayketthuc && ngayketthuc <= ngaybatdau) {
                e.preventDefault();
                alert('Ngày kết thúc phải sau ngày bắt đầu');
                return false;
            }
        });
    });
</script>