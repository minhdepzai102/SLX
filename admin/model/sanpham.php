<?php
function getall_sanpham() {
    $conn = connectdb();
    // Truy vấn để lấy tất cả danh mục
    $query = "SELECT * FROM tbl_sanpham"; // Sửa đổi theo tên bảng thực tế
    $stmt = $conn->prepare($query); // Chuẩn bị truy vấn
    $stmt->execute(); // Thực thi truy vấn
    $kq = $stmt->fetchAll(PDO::FETCH_ASSOC); // Lấy kết quả
    return $kq; // Trả về kết quả
}

// Function to insert a product
function insert_sanpham($iddm, $tensp, $thuonghieu, $img, $gia, $dungtich, $mota) {
    $conn = connectdb();
    $sql = "INSERT INTO tbl_sanpham (iddm, tensanpham, thuonghieu, img, gia, dungtich, mota) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$iddm, $tensp, $thuonghieu, $img, $gia, $dungtich, $mota]);
    return $conn->lastInsertId();
}
// Function to search for products based on a keyword



function getonesp($id){
    $conn = connectdb();
    $query = "SELECT * FROM tbl_sanpham WHERE id=".$id; // Sửa đổi theo tên bảng thực tế
    $stmt = $conn->prepare($query); // Chuẩn bị truy vấn
    $stmt->execute(); // Thực thi truy vấn
    $kq = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $kq;
}

function update_sanpham($id, $iddm, $tensp, $thuonghieu, $img, $gia, $dungtich, $mota) {
    $conn = connectdb(); // Kết nối cơ sở dữ liệu

    try {
        if ($img != "") {
            // Cập nhật sản phẩm với hình ảnh mới
            $sql = "UPDATE tbl_sanpham 
                    SET iddm = ?, tensanpham = ?, thuonghieu = ?, img = ?, gia = ?, dungtich = ?, mota = ? 
                    WHERE id = ?";
            $params = [$iddm, $tensp, $thuonghieu, $img, $gia, $dungtich, $mota, $id]; // Thêm $id vào cuối mảng
        } else {
            // Cập nhật sản phẩm mà không thay đổi hình ảnh
            $sql = "UPDATE tbl_sanpham 
                    SET iddm = ?, tensanpham = ?, thuonghieu = ?, gia = ?, dungtich = ?, mota = ? 
                    WHERE id = ?";
            $params = [$iddm, $tensp, $thuonghieu, $gia, $dungtich, $mota, $id]; // Thêm $id vào cuối mảng
        }

        $stmt = $conn->prepare($sql); // Chuẩn bị câu lệnh SQL
        $stmt->execute($params); // Thực hiện truy vấn

        return true; // Trả về true nếu cập nhật thành công
    } catch (PDOException $e) {
        echo "Error updating product: " . $e->getMessage(); // Thông báo lỗi nếu xảy ra lỗi
        return false; // Trả về false nếu cập nhật thất bại
    }
}

    function deletesp($id) {
        $conn = connectdb();
        $sql = "DELETE FROM tbl_sanpham WHERE id=".$id;
        $conn -> exec($sql);
    }


?>