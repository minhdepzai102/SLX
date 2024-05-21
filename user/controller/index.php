<?php
// Sử dụng Output Buffering để tránh lỗi "headers already sent"
ob_start(); // Bắt đầu bộ đệm đầu ra

// Kiểm tra xem session đã được bắt đầu chưa trước khi gọi session_start()
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Bắt đầu session nếu chưa được kích hoạt
}

// Bao gồm các tệp cấu hình và chức năng
require_once ("../../config.php");
require_once ("../model/danhmuc.php");
require_once ("../model/sanpham.php");
require_once ("../model/donhang.php");
require_once ("../model/cart.php");
require_once ("../model/user.php");

// Bao gồm header
include ("../view/666shop/header.php");

// Xử lý hành động dựa trên giá trị 'act'
$action = isset($_GET["act"]) ? htmlspecialchars($_GET["act"], ENT_QUOTES, 'UTF-8') : 'home';

switch ($action) {
    case "about":
        include ("../view/666shop/about.php");
        break;

    case 'logout':
        unset($_SESSION['role']);
        unset($_SESSION['user']); // Xóa phiên của người dùng
        unset($_SESSION['giohang']); // Xóa phiên của người dùng
        header("Location: index.php?act=home"); // Chuyển hướng về trang chủ sau khi đăng xuất
        exit;

    case "shop":
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        include ("../view/666shop/shop.php");
        break;

    case "contact":
        include ("../view/666shop/contact.php");
        break;

    case "home":
        include ("../view/666shop/index.php");
        break;

    case "shop-single":
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        include ("../view/666shop/shop-single.php");
        break;
    case 'removecart':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id']);

            foreach ($_SESSION['giohang'] as $key => $item) {
                if ($item['id'] == $id) {
                    unset($_SESSION['giohang'][$key]);
                    break;
                }
            }
            // Reindex the array to avoid potential issues with non-sequential keys
            $_SESSION['giohang'] = array_values($_SESSION['giohang']);
        }
        break;
    case "addcart":
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addtocart'])) {
            $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
            $product_name = htmlspecialchars($_POST['product_name'] ?? 'Unknown Product', ENT_QUOTES, 'UTF-8');
            $product_image = htmlspecialchars($_POST['product_image'] ?? 'default-image.png', ENT_QUOTES, 'UTF-8');
            $product_price = isset($_POST['product_price']) ? floatval($_POST['product_price']) : 0;
            $quantity = 1;

            if (!isset($_SESSION['giohang'])) {
                $_SESSION['giohang'] = []; // Khởi tạo giỏ hàng nếu chưa có
            }

            $exists = false;
            foreach ($_SESSION['giohang'] as &$item) {
                if ($item['id'] === $product_id) {
                    $item['quantity'] += 1; // Tăng số lượng nếu đã tồn tại
                    $exists = true;
                    break;
                }
            }

            if (!$exists) {
                $new_item = [
                    'id' => $product_id,
                    'tensp' => $product_name,
                    'img' => $product_image,
                    'gia' => $product_price,
                    'quantity' => $quantity
                ];
                $_SESSION['giohang'][] = $new_item; // Thêm sản phẩm mới vào giỏ hàng
            }

            header("Location: index.php?act=cart"); // Chuyển hướng đến giỏ hàng
            exit;
        }

        include ("../view/666shop/viewcart.php"); // Đảm bảo hành động không bị chồng chéo
        break;

    case "cart":
        include ("../view/666shop/viewcart.php");
        break;

    case "delcart":
        if (isset($_SESSION["giohang"])) {
            unset($_SESSION["giohang"]); // Xóa giỏ hàng
        }
        header("Location: index.php?act=cart"); // Chuyển hướng về giỏ hàng
        break;
    case "profile":
        include ("../view/666shop/profile.php");
        break;
    case "updatecart":
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['id']) && isset($_POST['quantity'])) {
                $productId = intval($_POST['id']); // Chuyển ID sang số nguyên
                $newQuantity = intval($_POST['quantity']); // Chuyển số lượng sang số nguyên

                // Tìm và cập nhật số lượng sản phẩm trong giỏ hàng
                if ($newQuantity > 0 && isset($_SESSION['giohang'])) {
                    foreach ($_SESSION['giohang'] as &$item) {
                        if ($item['id'] === $productId) {
                            $item['quantity'] = $newQuantity; // Cập nhật số lượng
                            break;
                        }
                    }
                }

                http_response_code(200); // Phản hồi thành công
            } else {
                http_response_code(400); // Phản hồi lỗi nếu dữ liệu không đủ
            }
        }
        break;
    case 'updateinfo':
        include ("../view/666shop/udprofile.php");
        break;
     case 'thanhtoan':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $madh = "SLX" . rand(0, 9999999);  // Random order ID
            $tongdonhang = isset($_POST['tongdonhang']) ? floatval($_POST['tongdonhang']) : 0;
            $name = htmlspecialchars($_POST['name'] ?? '', ENT_QUOTES, 'UTF-8');
            $address = htmlspecialchars($_POST['address'] ?? '', ENT_QUOTES, 'UTF-8');
            $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $ngaydat = date('Y-m-d');
            $tel = htmlspecialchars($_POST['tel'] ?? '', ENT_QUOTES, 'UTF-8');
            $payment_method = htmlspecialchars($_POST['payment_method'] ?? '', ENT_QUOTES, 'UTF-8');
            
            if (isset($_SESSION['user'])) {
                $iduser = getUserIdByUsername($_SESSION['user']);
            } else {
                $iduser = null;  // No user ID if not logged in
            }

            if (!$email) {
                die("Email không hợp lệ.");  // Stop execution if email is invalid
            }

            // Validate payment method
            $valid_payment_methods = ['1', '2', '3', '4']; // List of valid payment methods
            if (!in_array($payment_method, $valid_payment_methods)) {
                die("Phương thức thanh toán không hợp lệ.");  // Stop execution if payment method is invalid
            }

            $iddh = taodonhang($madh, $tongdonhang, $payment_method, $name, $address, $email, $tel, $ngaydat, $iduser);
            if ($iddh > 0) {  // If order is created successfully
                if (isset($_SESSION['giohang'])) {
                    foreach ($_SESSION['giohang'] as $item) {
                        // Assuming addtocart() function is defined elsewhere
                        $result = addtocart($iddh, $item['id'], $item['tensp'], $item['img'], $item['gia'], $item['quantity']);
                        if ($result === 0) {
                            error_log("Failed to add product ID {$item['id']} to order ID {$iddh}.");
                        }
                    }
                    unset($_SESSION['giohang']);
                }

                include ("../view/666shop/thankyou.php");  // Redirect to thank you page
                exit;  // Ensure to stop execution after redirection
            } else {
                error_log("Lỗi khi tạo đơn hàng.");
                die("Có lỗi xảy ra khi tạo đơn hàng. Vui lòng thử lại sau.");  // Display error message if order creation fails
            }
        }
        break;
        case 'history':
            include ("../view/666shop/history.php");
            break;

    default:
        include ("../view/666shop/index.php"); // Trang chủ mặc định
        break;
}

// Bao gồm footer
include ("../view/666shop/footer.php"); // Bao gồm phần cuối trang

// Kết thúc bộ đệm đầu ra
ob_end_flush(); // Gửi dữ liệu và xóa bộ đệm
?>