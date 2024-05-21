<?php

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user'])) {
    // Nếu chưa đăng nhập, chuyển hướng người dùng đến trang đăng nhập
    header("Location: login.php");
    exit;
}

// Kết nối đến cơ sở dữ liệu
$conn = connectdb();

// Kiểm tra xem hàm getUserIdByUsername đã tồn tại chưa trước khi khai báo
if (!function_exists('getUserIdByUsername')) {
    function getUserIdByUsername($username) {
        global $conn;
        $stmt = $conn->prepare("SELECT id FROM tbl_users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
}

// Lấy iduser từ session
$iduser = getUserIdByUsername($_SESSION['user']);

// Truy vấn cơ sở dữ liệu để lấy lịch sử đơn hàng của người dùng
$sql = "SELECT * FROM tbl_order WHERE iduser = :iduser";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':iduser', $iduser);
$stmt->execute();
$result = $stmt->fetchAll();

// Kiểm tra xem có đơn hàng nào không
if (count($result) > 0) {
    // Hiển thị bảng lịch sử đơn hàng
    echo '<div class="container">
            <h1 class="mt-4 mb-3">Order History</h1>
            <table class="table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer Name</th>
                        <th>Total</th>
                        <th>Order Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>';
    // Lặp qua từng hàng trong kết quả truy vấn
    foreach ($result as $row) {
        // Chuyển đổi trạng thái sang chuỗi tương ứng
        switch ($row['trangthai']) {
            case 0:
                $status = 'Chưa xử lý';
                break;
            case 1:
                $status = 'Đang xử lý';
                break;
            case 2:
                $status = 'Đang giao';
                break;
            case 3:
                $status = 'Hoàn thành';
                break;
            default:
                $status = 'Chưa xử lý';
                break;
        }
        echo '<tr>
                <td>' . htmlspecialchars($row['madh']) . '</td>
                <td>' . htmlspecialchars($row['name']) . '</td>
                <td>' . number_format($row['tongdonhang'], 0, ',', '.') . '</td>
                <td>' . htmlspecialchars($row['ngaydat']) . '</td>
                <td>' . htmlspecialchars($status) . '</td>
              </tr>';
    }
    echo '</tbody>
          </table>
          </div>';
} else {
    // Nếu không có đơn hàng nào
    echo '<div class="container d-flex justify-content-center align-items-center" style="height: 100vh;"><p class="text-center">No orders found.</p></div>';
}
?>
