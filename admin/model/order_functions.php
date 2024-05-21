<?php
require_once '../../config.php';  // Bao gồm tệp cấu hình

// Hàm lấy danh sách đơn hàng
function getAllOrders() {
    $conn = connectdb();  // Kết nối cơ sở dữ liệu
    if ($conn) {
        $stmt = $conn->prepare("SELECT * FROM tbl_order ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    return [];
}

// Hàm lấy chi tiết đơn hàng
function getOrderDetails($idorder) {
    $conn = connectdb();  // Kết nối cơ sở dữ liệu
    if ($conn) {
        $stmt = $conn->prepare(
            "SELECT 
                c.id, 
                p.tensanpham, 
                c.soluong, 
                c.dongia,
                o.name, 
                p.img, 
                o.madh, 
                o.address,
                o.tel
            FROM 
                tbl_cart c 
            JOIN 
                tbl_sanpham p ON c.idpro = p.id 
            JOIN 
                tbl_order o ON c.idorder = o.id 
            WHERE 
                c.idorder = ?"  // Đảm bảo đây là phép so sánh, không phải phép chia
        );
        $stmt->execute([$idorder]);  // Thực thi câu lệnh với tham số
        return $stmt->fetchAll(PDO::FETCH_ASSOC);  // Trả về kết quả
    }
    return [];  // Nếu không có kết nối
}






// Hàm kiểm tra kết nối đơn hàng
function validateOrderConnection($idorder) {
    $conn = connectdb();  // Kết nối cơ sở dữ liệu
    if ($conn) {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM tbl_order WHERE id = ?");
        $stmt->execute([$idorder]);
        return $stmt->fetchColumn() > 0;  // Kiểm tra sự tồn tại của đơn hàng
    }
    return false;
}
function deleteOrder($idorder) {
    $conn = connectdb();  // Connect to the database
    if ($conn) {
        try {
            // Prepare the DELETE statement
            $stmt = $conn->prepare("DELETE FROM tbl_order WHERE id = ?");
            // Bind the parameter and execute the statement
            $stmt->execute([$idorder]);
            // Check if any rows were affected (order deleted)
            if ($stmt->rowCount() > 0) {
                return true;  // Order deleted successfully
            } else {
                return false;  // No order deleted (ID might not exist)
            }
        } catch (PDOException $e) {
            // Handle any database errors
            echo "Error deleting order: " . $e->getMessage();
            return false;  // Return false indicating deletion failure
        }
    }
    return false;  // Return false if connection to database fails
}
?>