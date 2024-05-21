<?php
function getall_sanpham($iddm = null) {
    $conn = connectdb(); // Kết nối cơ sở dữ liệu
    $sql = "SELECT * FROM tbl_sanpham";
    if ($iddm !== null) {
        $sql .= " WHERE iddm = ?"; // Truy vấn với tham số an toàn
    }
    $stmt = $conn->prepare($sql); // Chuẩn bị truy vấn
    if ($iddm !== null) {
        $stmt->execute([$iddm]); // Thực thi với giá trị tham số
    } else {
        $stmt->execute(); // Thực thi không có tham số
    }
    return $stmt->fetchAll(PDO::FETCH_ASSOC); // Trả về kết quả
}


// Function to insert a product
function insert_sanpham($iddm, $tensp, $thuonghieu, $img, $gia, $dungtich, $mota) {
    $conn = connectdb();
    $sql = "INSERT INTO tbl_sanpham (iddm, tensanpham, thuonghieu, img, gia, dungtich, mota) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$iddm, $tensp, $thuonghieu, $img, $gia, $dungtich, $mota]);
    return $conn->lastInsertId();
}


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
        // Xây dựng câu lệnh SQL cho trường hợp có hình ảnh và không có hình ảnh
        if ($img != "") {
            $sql = "UPDATE tbl_sanpham 
                    SET iddm = ?, tensanpham = ?, thuonghieu - ?,img = ?, gia = ?, dungtich = ?, mota = ? 
                    WHERE id = ?";
            $params = [$iddm, $tensp, $thuonghieu, $img, $gia, $dungtich, $mota];
        } else {
            $sql = "UPDATE tbl_sanpham 
                    SET iddm = ?, tensanpham = ?, thuonghieu - ?, gia = ?, dungtich = ?, mota = ? 
                    WHERE id = ?";
            $params = [$iddm, $tensp, $thuonghieu, $img, $gia, $dungtich, $mota];
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
    function get_sanpham_by_danhmuc($iddm) {
        $conn = connectdb();
        $sql = "SELECT * FROM tbl_sanpham WHERE iddm = ?"; // Truy vấn dựa trên ID danh mục
        $stmt = $conn->prepare($sql);
        $stmt->execute([$iddm]); // Thực hiện truy vấn với tham số
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Trả về danh sách sản phẩm
    }
    
    ?>