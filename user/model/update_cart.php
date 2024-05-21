<?php
// Bắt đầu session nếu chưa được khởi tạo
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra nếu giỏ hàng đã được khởi tạo
if (isset($_SESSION['giohang'])) {
    // Lấy thông tin từ yêu cầu GET
    $productId = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $newQuantity = isset($_GET['quantity']) ? intval($_GET['quantity']) : 1;

    // Tìm sản phẩm trong giỏ hàng và cập nhật số lượng
    foreach ($_SESSION['giohang'] as &$item) {
        if ($item['id'] === $productId) {
            if ($newQuantity > 0) {
                $item['quantity'] = $newQuantity;
            } else {
                // Nếu số lượng là 0 hoặc nhỏ hơn, xóa sản phẩm khỏi giỏ hàng
                unset($_SESSION['giohang'][array_search($item, $_SESSION['giohang'])]);
            }
            break;
        }
    }
}
?>
