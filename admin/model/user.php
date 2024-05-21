<?php

function checkuser($user, $pass) {
    $conn = connectdb();  // Kết nối cơ sở dữ liệu
    
    // Chuẩn bị và thực hiện truy vấn
    $stmt = $conn->prepare("SELECT role, pass FROM tbl_users WHERE user = ?");
    $stmt->execute([$user]);
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);  // Lấy kết quả
    
    // Kiểm tra nếu mật khẩu nhập vào khớp với mật khẩu đã lưu
    if ($result && password_verify($pass, $result['pass'])) {
        return $result['role'];  // Trả về vai trò nếu đúng
    }
    
    return -1;  // Trả về -1 nếu sai hoặc không tìm thấy -1;  // Trả về -1 nếu sai hoặc không tìm thấy
}

function getall_user(){
    $conn = connectdb();
    // Truy vấn để lấy tất cả danh mục
    $query = "SELECT * FROM tbl_users"; // Sửa đổi theo tên bảng thực tế
    $stmt = $conn->prepare($query); // Chuẩn bị truy vấn
    $stmt->execute(); // Thực thi truy vấn
    $kq = $stmt->fetchAll(PDO::FETCH_ASSOC); // Lấy kết quả
    return $kq; // Trả về kết quả
}
?>
