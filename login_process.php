<?php
session_start();

// Kết nối database từ file config
include('includes/config.php');

// Kiểm tra nếu form được submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form và làm sạch để tránh SQL Injection
    $user_unsafe = $_POST['username']; // Đây sẽ là masinhvien hoặc maquanly
    $pass_unsafe = $_POST['password'];

    $user = mysqli_real_escape_string($con, $user_unsafe);
    $pass = mysqli_real_escape_string($con, $pass_unsafe);

    // Truy vấn kiểm tra thông tin đăng nhập
    // Kiểm tra xem user nhập vào khớp với masinhvien hoặc maquanly
    $query = mysqli_query($con, "SELECT * FROM taikhoan WHERE (masinhvien = '$user' OR maquanly = '$user') AND matkhau = '$pass'") or die(mysqli_error($con));
    $row = mysqli_fetch_array($query);

    // Lấy thông tin từ kết quả truy vấn
    $mataikhoan = $row['tentaikhoan'];
    $vaitro = $row['vaitro']; // Lấy vaitro để phân quyền ('admin' hoặc 'sinhvien')
    $counter = mysqli_num_rows($query);

    // Kiểm tra kết quả đăng nhập
    if ($counter == 0) {
        // Thông tin đăng nhập sai
        echo "<script type='text/javascript'>
                alert('Thông tin đăng nhập không hợp lệ');
                document.location='login.php';
              </script>";
    } else {
        // Đăng nhập thành công, lưu thông tin vào session
        $_SESSION['mataikhoan'] = $mataikhoan;
        $_SESSION['vaitro'] = $vaitro; // Lưu vai trò để phân quyền
        $_SESSION['user'] = $user;   // Lưu mã sinh viên hoặc mã quản lý mà người dùng nhập

        // Phân quyền dựa trên vaitro
        if ($vaitro == 'sinhvien') {
            echo "<script type='text/javascript'>
                    document.location='student/dashboard.php';
                  </script>";
        } elseif ($vaitro == 'quanly') {
            echo "<script type='text/javascript'>
                    document.location='admin/dashboard.php';
                  </script>";
        }
    }
}
?>