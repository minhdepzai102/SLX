<?php
// Kiểm tra xem session đã được bắt đầu chưa trước khi gọi session_start()
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Bắt đầu session nếu chưa được kích hoạt
}

// Các cấu hình và tệp bao gồm cần thiết
include("../../config.php");
include("../model/user.php");

$login_error = ""; // Biến báo lỗi cho đăng nhập
$signup_error = ""; // Biến báo lỗi cho đăng ký

// Xử lý đăng nhập
if ((isset($_POST['Login'])) && ($_POST['Login'])) {
    $user = trim($_POST['user']); // Tên người dùng
    $pass = trim($_POST['pass']); // Mật khẩu
    $conn = connectdb(); // Kết nối cơ sở dữ liệu
    
    if ($conn) { // Kiểm tra nếu kết nối thành công
        $role = checkuser($user, $pass); // Kiểm tra vai trò người dùng
        $_SESSION['role'] = $role; // Lưu vai trò vào session
        $_SESSION['user'] = $user; // Lưu tên người dùng vào session

        if ($role == 1) { // Nếu vai trò là admin
            header('Location: index.php'); // Chuyển hướng đến trang admin
            exit; // Đảm bảo mã dừng sau khi chuyển hướng
        } else if($role == 0){
            header ('Location: ../../user/web/index.php');
            exit;
        }
        else {
            $login_error = "Username or password incorrect"; // Thông báo lỗi đăng nhập
        }
    } else {
        $login_error = "Database connection error"; // Thông báo lỗi kết nối
    }
}

// Xử lý đăng ký
if ((isset($_POST['Signup'])) && ($_POST['Signup'])) {
    $username = trim($_POST['user']); // Tên người dùng
    $email = trim($_POST['email']); // Email
    $password = trim($_POST['pass']); // Mật khẩu
    $terms = isset($_POST['terms']); // Kiểm tra điều khoản
  
    // Kiểm tra tính hợp lệ
    if (!$terms) {
        $signup_error = "Bạn phải đồng ý với điều khoản và điều kiện.";
    } elseif (empty($username) || empty($email) || empty($password)) {
        $signup_error = "Vui lòng điền tất cả các trường.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $signup_error = "Email không hợp lệ.";
    } elseif (strlen($password) <= 6) {
        $signup_error = "Mật khẩu phải có hơn 6 ký tự.";
    } else {
        $conn = connectdb(); // Kết nối cơ sở dữ liệu
    
        if ($conn) { // Kiểm tra nếu kết nối thành công
            $check_stmt = $conn->prepare("SELECT COUNT(*) FROM tbl_users WHERE user = ? OR email = ?");
            $check_stmt->execute([$username, $email]);
            $duplicate_count = $check_stmt->fetchColumn(); // Số lượng trùng lặp
            
            if ($duplicate_count > 0) {
                $signup_error = "Tên người dùng hoặc email đã tồn tại."; // Thông báo lỗi trùng lặp
            } else {
                // Băm mật khẩu
                $hashed_pass = password_hash($password, PASSWORD_BCRYPT); // Mã hóa mật khẩu

                $stmt = $conn->prepare("INSERT INTO tbl_users (user, email, pass) VALUES (?, ?, ?)");
                $stmt->execute([$username, $email, $hashed_pass]); // Chèn người dùng mới

                if ($stmt->rowCount() > 0) {
                    header("Location: login.php"); // Chuyển hướng đến trang đăng nhập
                    exit; // Dừng sau khi chuyển hướng
                } else {
                    $signup_error = "Đăng ký thất bại, vui lòng thử lại.";
                }
            }
        } else {
            $signup_error = "Database connection error"; // Thông báo lỗi kết nối
        }
    }
}

ob_end_flush(); // Kết thúc bộ đệm đầu ra
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login and Signup Form</title>
  <link rel="stylesheet" type="text/css" href="login.css">
</head>
<body>
  <?php
  // Đặt lớp wrapper thành active nếu có lỗi trong đăng ký
  $wrapper_class = !empty($signup_error) ? "wrapper active" : "wrapper";
  ?>
  <div class="<?php echo $wrapper_class; ?>">
    <!-- Biểu mẫu đăng nhập -->
    <div class="form-wrapper sign-in">
      <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <h2>Login</h2>
        <div class="input-group">
          <input type="text" name="user" required>
          <label for="">Username</label>
        </div>
        <div class="input-group">
          <input type="password" name="pass" required>
          <label for="">Password</label>
        </div>
        <?php
        if (!empty($login_error)) { // Hiển thị thông báo lỗi đăng nhập
            echo "<span class='error-message' style='color: red; display: block; margin-bottom: 5px;'>" . $login_error . "</span>";
        }
        ?>
        <input type="submit" name="Login" value="Login" class="btnLogin">
        <div class="signUp-link">
          <p>Don't have an account? <a href="#" class="signUpBtn-link">Sign Up</a></p>
        </div>
      </form>
    </div>

    <!-- Biểu mẫu đăng ký -->
    <div class="form-wrapper sign-up">
      <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <h2>Sign Up</h2>
        <div class="input-group">
          <input type="text" name="user" required>
          <label for="">Username</label>
        </div>
        <div class="input-group">
          <input type="email" name="email" required>
          <label for="">Email</label>
        </div>
        <div class="input-group">
          <input type="password" name="pass" required>
          <label for="">Password</label>
        </div>
        <div class="remember">
          <label><input type="checkbox" name="terms" required> I agree to the terms & conditions</label>
        </div>
        <?php
        if (!empty($signup_error)) { // Hiển thị thông báo lỗi đăng ký
            echo "<span class='error-message' style='color: red; display: block; margin-bottom: 5px;'>" . $signup_error . "</span>";
        }
        ?>
        <span class="password-error" style="color: red; display: block; margin-bottom: 5px;"></span>
        <input type="submit" name="Signup" value="Signup" class="btnLogin">
        <div class="signUp-link">
          <p>Already have an account? <a href="login.php" class="signInBtn-link">Sign In</a></p>
        </div>
      </form>
    </div>
  </div>

  <!-- JavaScript để chuyển đổi giữa đăng nhập và đăng ký -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
        const signInBtnLink = document.querySelector('.signInBtn-link');
        const signUpBtnLink = document.querySelector('.signUpBtn-link');
        const wrapper = document.querySelector('.wrapper');

        signUpBtnLink.addEventListener('click', () => {
            wrapper.classList.toggle('active');
        });

        signInBtnLink.addEventListener('click', () => {
            wrapper.classList.toggle('active');
        });

        // JavaScript validation for password length
        const signupForm = document.querySelector('.sign-up form');
        signupForm.addEventListener('submit', function(event) {
            const passwordInput = signupForm.querySelector('input[name="pass"]');
            const passwordErrorSpan = signupForm.querySelector('.password-error');
            const password = passwordInput.value;

            if (password.length <= 6) {
                event.preventDefault(); // Prevent form submission
                passwordErrorSpan.textContent = 'Mật khẩu phải có hơn 6 ký tự.'; // Show error message
                passwordInput.focus(); // Focus the password input
            } else {
                passwordErrorSpan.textContent = ''; // Clear the error message if valid
            }
        });

        // Hide error messages after 5 seconds
        const errorMessages = document.querySelectorAll('.error-message');
        if (errorMessages) {
            setTimeout(() => {
                errorMessages.forEach(msg => {
                    msg.style.display = 'none';
                });
            }, 3500);
        }
    });
  </script>
</body>
</html>
