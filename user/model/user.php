<?php
function checkuser($user, $pass) {
    $conn = connectdb();  // Kết nối cơ sở dữ liệu
    
    // Chuẩn bị và thực hiện truy vấn
    $stmt = $conn->prepare("SELECT role, pass FROM tbl_users WHERE user = ?");
    $stmt->execute([$user]);
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);  // Lấy kết quả
    
    // Kiểm tra nếu kết quả không rỗng và cột 'pass' tồn tại
    if ($result && isset($result['pass']) && password_verify($pass, $result['pass'])) {
        return $result['role'];  // Trả về vai trò nếu đúng
    }
    
    return -1;  // Trả về -1 nếu sai hoặc không tìm thấy
}


function getUserInfo($username) {
    $conn = connectdb(); // Kết nối cơ sở dữ liệu

    if ($conn) { // Kiểm tra nếu kết nối cơ sở dữ liệu thành công
        try {
            // Chuẩn bị câu lệnh SQL để lấy thông tin người dùng
            $stmt = $conn->prepare("SELECT * FROM tbl_users WHERE user = ?");
            $stmt->execute([$username]); // Thực thi câu lệnh đã chuẩn bị với tên người dùng
            $user_info = $stmt->fetch(PDO::FETCH_ASSOC); // Lấy thông tin người dùng dưới dạng mảng kết hợp
        } catch (PDOException $e) {
            error_log("Error retrieving user info: " . $e->getMessage());
            return false; // Trả về false nếu có lỗi
        }
    } else {
        error_log("Database connection error");
        return false; // Trả về false nếu kết nối cơ sở dữ liệu thất bại
    }
}
function getUserIdByUsername($username) {
    $conn = connectdb(); // Kết nối cơ sở dữ liệu

    if ($conn) { // Kiểm tra kết nối cơ sở dữ liệu thành công
        try {
            // Chuẩn bị câu lệnh SQL để lấy ID người dùng dựa trên tên người dùng
            $stmt = $conn->prepare("SELECT id FROM tbl_users WHERE user = ?");
            $stmt->execute([$username]); // Thực thi câu lệnh đã chuẩn bị với tên người dùng
            $user_id = $stmt->fetchColumn(); // Lấy ID người dùng từ kết quả truy vấn

            return $user_id; // Trả về ID người dùng nếu tìm thấy
        } catch (PDOException $e) {
            error_log("Error retrieving user ID: " . $e->getMessage());
            return false; // Trả về false nếu có lỗi
        }
    } else {
        error_log("Database connection error");
        return false; // Trả về false nếu kết nối cơ sở dữ liệu thất bại
    }
}

?>
