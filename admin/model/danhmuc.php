<?php
function getall_dm() {
        $conn = connectdb();
        // Truy vấn để lấy tất cả danh mục
        $query = "SELECT * FROM tbl_danhmuc"; // Sửa đổi theo tên bảng thực tế
        $stmt = $conn->prepare($query); // Chuẩn bị truy vấn
        $stmt->execute(); // Thực thi truy vấn
        $kq = $stmt->fetchAll(PDO::FETCH_ASSOC); // Lấy kết quả
        return $kq; // Trả về kết quả
}

function deletedm($id){
    $conn = connectdb();
    $sql = "DELETE FROM tbl_danhmuc WHERE id=".$id;
    $conn -> exec($sql);
}

function getonedm($id){
    $conn = connectdb();
    $query = "SELECT * FROM tbl_danhmuc WHERE id=".$id; // Sửa đổi theo tên bảng thực tế
    $stmt = $conn->prepare($query); // Chuẩn bị truy vấn
    $stmt->execute(); // Thực thi truy vấn
    $kq = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $kq;
}

function updatedm($id,$tendm,$img,$uutien){
$conn = connectdb();
$sql = "UPDATE tbl_danhmuc SET tendm='$tendm', img = '$img', uutien = '$uutien' WHERE id=".$id;
  // Prepare statement
  $stmt = $conn->prepare($sql);
  // execute the query
  $stmt->execute();
}

function themdm($tendm, $img, $uutien) {
    $conn = connectdb();
    $sql = "INSERT INTO tbl_danhmuc (tendm, img, uutien) VALUES (:tendm, :img, :uutien)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':tendm', $tendm);
    $stmt->bindParam(':img', $img);
    $stmt->bindParam(':uutien', $uutien);
    $stmt->execute();
}

?>