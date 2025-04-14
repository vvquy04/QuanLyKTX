<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Đại học Thủy lợi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #4e73df, #4e73df);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 90%;
            max-width: 900px;
            display: flex;
            position: relative;
        }

        .login-branding {
            background-color: #fff;
            padding: 40px;
            width: 40%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 1;
        }

        .login-branding::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            height: 100%;
            width: 40px;
            background: linear-gradient(to right, rgba(255, 255, 255, 1), rgba(245, 249, 255, 0));
            z-index: -1;
        }

        .logo-container {
            margin-bottom: 20px;
            text-align: center;
        }

        .logo {
            width: 160px;
            height: 160px;
            margin-bottom: 5px;
        }

        .university-name {
            color: #0d47a1;
            font-size: 18px;
            font-weight: 600;
            margin-top: 10px;
        }

        .university-tagline {
            color: #4a6fa5;
            font-size: 14px;
            text-align: center;
            margin-top: 15px;
        }

        .login-form {
            padding: 40px;
            width: 60%;
        }

        .form-title {
            font-size: 24px;
            font-weight: 700;
            color: #0d47a1;
            margin-bottom: 30px;
            position: relative;
            display: inline-block;
        }
        
        .form-title::after {
            content: '';
            position: absolute;
            width: 50px;
            height: 3px;
            background-color: #1e88e5;
            bottom: -8px;
            left: 0;
        }

        .input-group {
            margin-bottom: 24px;
            position: relative;
        }

        .input-group i {
            position: absolute;
            left: 16px;
            top: 16px;
            color: #78909c;
        }

        .form-input {
            width: 100%;
            padding: 15px 15px 15px 45px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
            background-color: #f9fbfd;
            color: #333;
        }

        .form-input:focus {
            outline: none;
            border-color: #1e88e5;
            box-shadow: 0 0 0 2px rgba(30, 136, 229, 0.2);
            background-color: #fff;
        }

        .form-input::placeholder {
            color: #90a4ae;
        }

        .login-btn {
            width: 100%;
            padding: 15px;
            background-color: #1e88e5;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .login-btn:hover {
            background-color: #1976d2;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .forgot-password {
            display: block;
            text-align: right;
            color: #1e88e5;
            text-decoration: none;
            font-size: 14px;
            margin-top: 12px;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #78909c;
            text-align: center;
        }

        .error-message {
            color: #d32f2f;
            font-size: 14px;
            margin-bottom: 10px;
            display: none;
        }

        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
            }
            
            .login-branding {
                width: 100%;
                padding: 30px 20px;
            }
            
            .login-form {
                width: 100%;
                padding: 30px 20px;
            }
            
            .logo {
                width: 120px;
                height: 120px;
            }
        }

        .wave {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100px;
            background: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDBweCIgdmlld0JveD0iMCAwIDEyODAgMTQwIiBwcmVzZXJ2ZUFzcGVjdFJhdGlvPSJub25lIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxnIGZpbGw9IiMwZDQ3YTEiPjxwYXRoIGQ9Ik0xMjgwIDBsLTI2Mi4xIDExNi4yNmE3My4yOSA3My4yOSAwIDAgMS0zOS4wOSA2TDAgMHYxNDBoMTI4MHoiLz48L2c+PC9zdmc+);
            background-size: cover;
            background-repeat: no-repeat;
            z-index: -1;
        }

        .circles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -2;
        }

        .circles li {
            position: absolute;
            display: block;
            list-style: none;
            width: 20px;
            height: 20px;
            background: rgba(255, 255, 255, 0.1);
            animation: animate 25s linear infinite;
            bottom: -150px;
            border-radius: 50%;
        }

        .circles li:nth-child(1) {
            left: 25%;
            width: 80px;
            height: 80px;
            animation-delay: 0s;
        }

        .circles li:nth-child(2) {
            left: 10%;
            width: 20px;
            height: 20px;
            animation-delay: 2s;
            animation-duration: 12s;
        }

        .circles li:nth-child(3) {
            left: 70%;
            width: 20px;
            height: 20px;
            animation-delay: 4s;
        }

        .circles li:nth-child(4) {
            left: 40%;
            width: 60px;
            height: 60px;
            animation-delay: 0s;
            animation-duration: 18s;
        }

        .circles li:nth-child(5) {
            left: 65%;
            width: 20px;
            height: 20px;
            animation-delay: 0s;
        }

        .circles li:nth-child(6) {
            left: 75%;
            width: 110px;
            height: 110px;
            animation-delay: 3s;
        }

        .circles li:nth-child(7) {
            left: 35%;
            width: 150px;
            height: 150px;
            animation-delay: 7s;
        }

        .circles li:nth-child(8) {
            left: 50%;
            width: 25px;
            height: 25px;
            animation-delay: 15s;
            animation-duration: 45s;
        }

        .circles li:nth-child(9) {
            left: 20%;
            width: 15px;
            height: 15px;
            animation-delay: 2s;
            animation-duration: 35s;
        }

        .circles li:nth-child(10) {
            left: 85%;
            width: 150px;
            height: 150px;
            animation-delay: 0s;
            animation-duration: 11s;
        }

        @keyframes animate {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 1;
                border-radius: 0;
            }
            100% {
                transform: translateY(-1000px) rotate(720deg);
                opacity: 0;
                border-radius: 50%;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-branding">
            <div class="logo-container">
                <img src="images/tlu.png" alt="Logo Đại học Thủy lợi" class="logo">
                <div class="university-name">QUẢN LÝ KÝ TÚC XÁ <br> ĐẠI HỌC THỦY LỢI</div>
            </div>
        </div>

        <div class="login-form">
            <h2 class="form-title">Đăng nhập</h2>
            <div class="error-message" id="error-message"></div>
            <form action="login_process.php" method="POST" id="login-form" onsubmit="return validateForm()">
                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="username" id="username" class="form-input" placeholder="Mã đăng nhập" >
                </div>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" id="password" class="form-input" placeholder="Mật khẩu" >
                </div>
                <button type="submit" name="login" class="login-btn">
                    <i class="fas fa-sign-in-alt"></i> Đăng nhập
                </button>
                <a href="#" class="forgot-password">Quên mật khẩu?</a>
            </form>
            <div class="footer">
                © 2025 Đại học Thủy lợi
            </div>
        </div>
    </div>

    <ul class="circles">
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
    </ul>

    <script>
        function validateForm() {
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            const errorMessage = document.getElementById('error-message');

            if (!username) {
                errorMessage.textContent = 'Vui lòng nhập mã đăng nhập';
                errorMessage.style.display = 'block';
                return false;
            }
            if (!password) {
                errorMessage.textContent = 'Vui lòng nhập mật khẩu';
                errorMessage.style.display = 'block';
                return false;
            }
            errorMessage.style.display = 'none';
            return true;
        }
    </script>
</body>
</html>