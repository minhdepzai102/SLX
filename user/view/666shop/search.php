<?php
include('../../../config.php');
try {
    // Tạo kết nối PDO
    $conn = connectdb();
    // Thiết lập chế độ lỗi để hiển thị các exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $keywork = isset($_GET['keyword']) ? $_GET['keyword'] : ''; // Kiểm tra từ khóa

    $sql = "SELECT id, tensanpham, img, gia FROM tbl_sanpham WHERE tensanpham LIKE :keyword LIMIT 10"; 
    $stmt = $conn->prepare($sql);
    $likeTerm = '%' . $keywork . '%';
    $stmt->bindParam(':keyword', $likeTerm, PDO::PARAM_STR);

    $stmt->execute();
    $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Trả về kết quả dưới dạng JSON
    header('Content-Type: application/json');
    echo json_encode($searchResults);
} catch(PDOException $e) {
    // Xử lý nếu có lỗi xảy ra
    echo "Connection failed: " . $e->getMessage();
}

// Đóng kết nối
$conn = null;
?>
