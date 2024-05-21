
<?php
include('../../config.php');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra xem yêu cầu đã được gửi từ phía client hay chưa
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Thực hiện kết nối đến cơ sở dữ liệu
    try {
        $conn = connectdb();
    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        exit();
    }

    // Kiểm tra xem dữ liệu đã được gửi từ phía client hay không
    if (isset($_POST['userId']) && isset($_POST['role'])) {
        $userId = $_POST['userId'];
        $role = $_POST['role'];

        // Cập nhật vai trò của người dùng trong cơ sở dữ liệu
        try {
            $stmt = $conn->prepare("UPDATE tbl_users SET role = :role WHERE id = :userId");
            $stmt->bindParam(':role', $role);
            $stmt->bindParam(':userId', $userId);
            $stmt->execute();

            // Trả về kết quả thành công nếu không có lỗi xảy ra
            echo "User role updated successfully.";
        } catch(PDOException $e) {
            // In ra lỗi nếu có bất kỳ lỗi nào xảy ra trong quá trình cập nhật
            echo "Error updating user role: " . $e->getMessage();
        }
    } else {
        // Nếu dữ liệu không được gửi đến từ phía client, thông báo lỗi
        echo "User ID or role not provided.";
    }
} else {
    // Nếu yêu cầu không phải là POST, thông báo lỗi
    echo "Invalid request method.";
}
?>
