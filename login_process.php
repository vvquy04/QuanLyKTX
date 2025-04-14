<?php
session_start();

// Kết nối database từ file config
include('includes/config.php');

// Hàm tạo session token
function generateSessionToken() {
    return bin2hex(random_bytes(16));
}

// Kiểm tra nếu form được submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $user_unsafe = trim($_POST['username'] ?? '');
    $pass_unsafe = trim($_POST['password'] ?? '');

    // Kiểm tra dữ liệu đầu vào
    if (empty($user_unsafe)) {
        echo "<script type='text/javascript'>
                alert('Vui lòng nhập mã đăng nhập');
                document.location='login.php';
              </script>";
        exit;
    }
    if (empty($pass_unsafe)) {
        echo "<script type='text/javascript'>
                alert('Vui lòng nhập mật khẩu');
                document.location='login.php';
              </script>";
        exit;
    }

    // Làm sạch dữ liệu để tránh SQL Injection
    $user = mysqli_real_escape_string($con, $user_unsafe);
    $pass = mysqli_real_escape_string($con, $pass_unsafe);

    // Truy vấn kiểm tra thông tin đăng nhập, phân biệt chữ hoa/thường
    $query = mysqli_query($con, "SELECT * FROM taikhoan WHERE (masinhvien = BINARY '$user' OR maquanly = BINARY '$user') AND matkhau = BINARY '$pass'") or die(mysqli_error($con));
    $row = mysqli_fetch_array($query);

    // Lấy thông tin từ kết quả truy vấn
    $counter = mysqli_num_rows($query);

    // Kiểm tra kết quả đăng nhập
    if ($counter == 0) {
        echo "<script type='text/javascript'>
                alert('Thông tin đăng nhập không hợp lệ');
                document.location='login.php';
              </script>";
    } else {
        // Đăng nhập thành công
        $mataikhoan = $row['tentaikhoan'];
        $vaitro = $row['vaitro'];


        // Tạo session token mới
        $session_token = generateSessionToken();

        // Lưu thông tin vào session
        $_SESSION['mataikhoan'] = $mataikhoan;
        $_SESSION['vaitro'] = $vaitro;
        $_SESSION['user'] = $user;
        $_SESSION['session_token'] = $session_token;

        // Phân quyền dựa trên vai trò
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