<?php
header('Content-Type: application/json');

// Hàm kết nối đến cơ sở dữ liệu
require("../../config.php");

// Kết nối đến cơ sở dữ liệu
$conn = connectdb();

// Truy vấn dữ liệu từ bảng tbl_order
$sql = "SELECT ngaydat, COUNT(*) AS ordersCount, SUM(tongdonhang) AS totalRevenue FROM tbl_order GROUP BY ngaydat";
$result = $conn->query($sql);

// Kiểm tra và xử lý kết quả
if ($result !== false && $result->rowCount() > 0) {
    $orders = $result->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($orders);
} else {
    echo json_encode(array()); // Trả về một mảng JSON trống nếu không có dữ liệu
}

// Đóng kết nối
$conn = null;
?>
