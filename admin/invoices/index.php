<?php
session_start();
if (!isset($_SESSION['mataikhoan']) || $_SESSION['vaitro'] != 'quanly') {
    header("Location: ../../login.php");
    exit();
}

include '../../includes/config.php';
$page_title = "Quản lý phí ký túc xá";
include '../../includes/header.php';

$maquanly = $_SESSION['user'];

// Xử lý xác nhận thanh toán
if (isset($_GET['confirm']) && !empty($_GET['mahoadon'])) {
    $mahoadon = mysqli_real_escape_string($con, $_GET['mahoadon']);
    $sql = "UPDATE hoadon SET trangthaithanhtoan='Đã thanh toán' 
            WHERE mahoadon='$mahoadon' AND trangthaithanhtoan='Chờ xác nhận'";
    if (mysqli_query($con, $sql) && mysqli_affected_rows($con) > 0) {
        header("Location: index.php?success=" . urlencode("Xác nhận thanh toán thành công"));
        exit();
    } else {
        header("Location: index.php?error=" . urlencode("phí ký túc xá không hợp lệ hoặc đã được xử lý"));
        exit();
    }
}

// Lấy tất cả sinh viên theo phòng để dùng trong JavaScript
$student_data = [];
$students_query = mysqli_query($con, "
    SELECT s.masinhvien, s.hoten, hd.maphong
    FROM sinhvien s
    JOIN hopdong hd ON s.masinhvien = hd.masinhvien AND hd.trangthai = 'Hiệu lực'
");
while ($row = mysqli_fetch_array($students_query)) {
    $student_data[] = [
        'masinhvien' => $row['masinhvien'],
        'hoten' => $row['hoten'],
        'maphong' => $row['maphong']
    ];
}

// Lấy dữ liệu dịch vụ
$dichvu = [];
$dv_query = mysqli_query($con, "SELECT * FROM dichvu WHERE trangthai='Hoạt động'");
while ($dv = mysqli_fetch_array($dv_query)) {
    $dichvu[$dv['madichvu']] = [
        'tendichvu' => $dv['tendichvu'],
        'giadichvu' => $dv['giadichvu'],
        'unit' => ($dv['tendichvu'] == 'Điện') ? 'kWh' : (($dv['tendichvu'] == 'Nước') ? 'm³' : 'lần')
    ];
}

// Lấy dữ liệu tiền phòng
$phong = [];
$phong_query = mysqli_query($con, "SELECT maphong, sophong, giathue FROM phong");
while ($p = mysqli_fetch_array($phong_query)) {
    $phong[$p['maphong']] = [
        'sophong' => $p['sophong'],
        'giathue' => $p['giathue']
    ];
}
?>

<?php include '../includes/sidebar.php'; ?>
<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
        <?php include '../includes/topbar.php'; ?>
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">Quản lý phí ký túc xá</h1>

            <!-- Hiển thị thông báo -->
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
            <?php elseif (isset($_GET['error'])): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
            <?php endif; ?>

            <!-- Nút mở modal thêm phí ký túc xá -->
            <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addInvoiceModal">
                <i class="fas fa-plus mr-2"></i>Thêm phí ký túc xá
            </button>

            <!-- Bảng danh sách phí ký túc xá -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Danh sách phí ký túc xá</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Mã phí ký túc xá</th>
                                    <th>Sinh viên</th>
                                    <th>Phòng</th>
                                    <th>Tổng tiền</th>
                                    <th>Ngày tạo</th>
                                    <th>Hạn thanh toán</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = mysqli_query($con, "
                                    SELECT h.*, s.hoten, p.sophong 
                                    FROM hoadon h 
                                    JOIN sinhvien s ON h.masinhvien = s.masinhvien 
                                    LEFT JOIN hopdong hd ON h.masinhvien = hd.masinhvien AND hd.trangthai = 'Hiệu lực'
                                    LEFT JOIN phong p ON hd.maphong = p.maphong
                                    ORDER BY h.ngaytao DESC
                                ");
                                while ($row = mysqli_fetch_array($query)) {
                                    $trangthai = $row['trangthaithanhtoan'];
                                    if ($trangthai == 'Chưa thanh toán' && strtotime($row['hanthanhtoan']) < time()) {
                                        $trangthai = 'Quá hạn';
                                        mysqli_query($con, "UPDATE hoadon SET trangthaithanhtoan='Quá hạn' WHERE mahoadon='{$row['mahoadon']}'");
                                    }
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['mahoadon']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['hoten']) . " (" . htmlspecialchars($row['masinhvien']) . ")</td>";
                                    echo "<td>" . htmlspecialchars($row['sophong'] ?? 'Chưa có') . "</td>";
                                    echo "<td>" . number_format($row['tongtien'], 0, ',', '.') . "</td>";
                                    echo "<td>" . htmlspecialchars($row['ngaytao']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['hanthanhtoan']) . "</td>";
                                    echo "<td>" . htmlspecialchars($trangthai) . "</td>";
                                    echo "<td>";
                                    if ($trangthai == 'Chờ xác nhận') {
                                        echo "<a href='index.php?confirm=1&mahoadon=" . urlencode($row['mahoadon']) . "' 
                                               class='btn btn-success btn-sm' 
                                               onclick='return confirm(\"Xác nhận sinh viên đã thanh toán?\");'>
                                                <i class='fa fa-check'></i> Xác nhận
                                              </a>";
                                    }
                                    echo "</td>";
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

<!-- Modal Thêm phí ký túc xá -->
<div class="modal fade" id="addInvoiceModal" tabindex="-1" role="dialog" aria-labelledby="addInvoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm phí ký túc xá</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="create_invoice.php" method="POST">
                <div class="modal-body">
                    <!-- Chọn phòng -->
                    <div class="form-group">
                        <label>Chọn phòng:</label>
                        <select name="maphong" id="maphong" class="form-control"  onchange="updateStudents(); updateRoomPrice();">
                            <option value="">Chọn phòng</option>
                            <?php
                            $phong_query = mysqli_query($con, "SELECT maphong, sophong FROM phong");
                            while ($p = mysqli_fetch_array($phong_query)) {
                                echo "<option value='{$p['maphong']}'>{$p['sophong']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Chọn sinh viên thanh toán -->
                    <div class="form-group">
                        <label>Chọn sinh viên thanh toán:</label>
                        <select name="masinhvien" id="masinhvien" class="form-control" >
                            <option value="">Chọn sinh viên</option>
                        </select>
                    </div>

                    <!-- Ngày hạn thanh toán -->
                    <div class="form-group">
                        <label>Hạn thanh toán:</label>
                        <input type="date" name="hanthanhtoan" class="form-control"  min="<?php echo date('Y-m-d'); ?>">
                    </div>

                    <!-- Bảng chi tiết dịch vụ -->
                    <div class="form-group">
                        <label>Chi tiết dịch vụ:</label>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Tên dịch vụ</th>
                                    <th>Số lượng</th>
                                    <th>Đơn giá</th>
                                    <th>Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody id="serviceDetails">
                                <!-- Tiền phòng -->
                                <tr id="roomPriceRow" style="display: none;">
                                    <td>Tiền phòng<input type="hidden" name="room_price" id="room_price_value" value="0"></td>
                                    <td><input type="number" class="form-control" value="1" readonly></td>
                                    <td id="roomPriceUnit">0 VNĐ</td>
                                    <td><input type="number" id="room_price_total" class="form-control thanhtien" value="0" readonly></td>
                                </tr>
                                <!-- Dịch vụ -->
                                <?php
                                foreach ($dichvu as $madichvu => $dv) {
                                    echo "<tr>";
                                    echo "<td>{$dv['tendichvu']}<input type='hidden' name='madichvu[]' value='$madichvu'></td>";
                                    echo "<td>";
                                    if ($dv['tendichvu'] == 'Điện' || $dv['tendichvu'] == 'Nước') {
                                        echo "<input type='number' name='soluong[$madichvu]' class='form-control soluong' min='0' step='0.1' oninput='calculateTotal(this)' value='0'>";
                                    } else {
                                        echo "<input type='number' name='soluong[$madichvu]' class='form-control soluong' value='1' readonly>";
                                    }
                                    echo "</td>";
                                    echo "<td data-giadichvu='{$dv['giadichvu']}'>" . number_format($dv['giadichvu'], 0, ',', '.') . " VNĐ/{$dv['unit']}</td>";
                                    echo "<td><input type='number' name='sotien[$madichvu]' class='form-control thanhtien' value='" . ($dv['tendichvu'] == 'Điện' || $dv['tendichvu'] == 'Nước' ? '0' : $dv['giadichvu']) . "' readonly></td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3">Tổng tiền</th>
                                    <th><input type="number" name="tongtien" id="tongtien" class="form-control" value="0" readonly></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" name="add_invoice" class="btn btn-primary">Thêm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>

<!-- Script xử lý -->
<script>
// Dữ liệu sinh viên và phòng từ PHP
const studentsData = <?php echo json_encode($student_data); ?>;
const phongData = <?php echo json_encode($phong); ?>;

// Cập nhật danh sách sinh viên khi chọn phòng
function updateStudents() {
    const maphong = document.getElementById('maphong').value;
    const masinhvienSelect = document.getElementById('masinhvien');
    masinhvienSelect.innerHTML = '<option value="">Chọn sinh viên</option>';

    if (maphong) {
        const filteredStudents = studentsData.filter(student => student.maphong === maphong);
        if (filteredStudents.length === 0) {
            alert('Không có sinh viên nào trong phòng này.');
        } else {
            filteredStudents.forEach(student => {
                const option = document.createElement('option');
                option.value = student.masinhvien;
                option.text = `${student.hoten} (${student.masinhvien})`;
                masinhvienSelect.appendChild(option);
            });
        }
    }
}

// Cập nhật tiền phòng khi chọn phòng
function updateRoomPrice() {
    const maphong = document.getElementById('maphong').value;
    const roomPriceRow = document.getElementById('roomPriceRow');
    const roomPriceUnit = document.getElementById('roomPriceUnit');
    const roomPriceTotal = document.getElementById('room_price_total');
    const roomPriceValue = document.getElementById('room_price_value');

    if (maphong && phongData[maphong]) {
        const giathue = phongData[maphong].giathue;
        roomPriceUnit.textContent = `${giathue.toLocaleString('vi-VN')} VNĐ`;
        roomPriceTotal.value = giathue;
        roomPriceValue.value = giathue;
        roomPriceRow.style.display = 'table-row';
        calculateTotal(null);
    } else {
        roomPriceUnit.textContent = '0 VNĐ';
        roomPriceTotal.value = 0;
        roomPriceValue.value = 0;
        roomPriceRow.style.display = 'none';
        calculateTotal(null);
    }
}

// Tính tổng tiền khi nhập số lượng
function calculateTotal(input) {
    let total = 0;
    const rows = document.querySelectorAll('#serviceDetails tr');

    rows.forEach(row => {
        const soluongInput = row.querySelector('.soluong');
        const giadichvu = parseFloat(row.cells[2].getAttribute('data-giadichvu')) || 0;
        const thanhtienInput = row.querySelector('.thanhtien');

        if (soluongInput && thanhtienInput) {
            let soluong = parseFloat(soluongInput.value) || 0;
            if (soluong < 0) {
                alert('Số lượng không được âm!');
                soluongInput.value = 0;
                soluong = 0;
            }
            const thanhtien = soluong * giadichvu;
            thanhtienInput.value = thanhtien;
            total += thanhtien;
        } else if (thanhtienInput) { // Trường hợp tiền phòng
            total += parseFloat(thanhtienInput.value) || 0;
        }
    });

    document.getElementById('tongtien').value = total;
}

// Kiểm tra trước khi submit form
document.querySelector('form').addEventListener('submit', function(e) {
    const maphong = document.getElementById('maphong').value;
    const masinhvien = document.getElementById('masinhvien').value;
    const hanthanhtoan = document.getElementById('hanthanhtoan').value;
    const tongtien = parseFloat(document.getElementById('tongtien').value);

    if (!maphong) {
        alert('Vui lòng chọn phòng!');
        e.preventDefault();
        return;
    }
    if (!masinhvien) {
        alert('Vui lòng chọn sinh viên!');
        e.preventDefault();
        return;
    }
    if (!hanthanhtoan) {
        alert('Vui lòng nhập hạn thanh toán!');
        e.preventDefault();
        return;
    }
    if (new Date(hanthanhtoan) < new Date()) {
        alert('Hạn thanh toán không hợp lệ!');
        e.preventDefault();
        return;
    }
    if (tongtien <= 0) {
        alert('Tổng tiền phải lớn hơn 0!');
        e.preventDefault();
        return;
    }
});
</script>