<?php
// Bắt đầu session nếu chưa được khởi tạo
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra nếu giỏ hàng đã được khởi tạo
if (isset($_SESSION['giohang'])) {
    // Lấy ID sản phẩm từ yêu cầu GET
    $productId = isset($_GET['id']) ? intval($_GET['id']) : 0;

    // Tìm sản phẩm trong giỏ hàng và xóa nó
    foreach ($_SESSION['giohang'] as $key => $item) {
        if ($item['id'] === $productId) {
            unset($_SESSION['giohang'][$key]); // Xóa sản phẩm khỏi giỏ hàng
            break;
        }
    }
}
?>
