<?php


function taodonhang($madh, $tongdonhang, $payment_method, $name, $address, $email, $tel, $ngaydat, $iduser) {
    $conn = connectdb();
    if ($conn) {
        try {
            $stmt = $conn->prepare("INSERT INTO tbl_order (madh, tongdonhang, pttt, name, address, email, tel, ngaydat, iduser) 
                                    VALUES (:madh, :tongdonhang, :pttt, :name, :address, :email, :tel, :ngaydat, :iduser)");
            $stmt->execute([
                ':madh' => $madh,
                ':tongdonhang' => $tongdonhang,
                ':pttt' => $payment_method,
                ':name' => $name,
                ':address' => $address,
                ':email' => $email,
                ':tel' => $tel,
                ':ngaydat' => $ngaydat,
                ':iduser' => $iduser
            ]);

            return $conn->lastInsertId();  // Return the ID of the created order
        } catch (PDOException $e) {
            error_log("Lỗi khi tạo đơn hàng: " . $e->getMessage());
            return 0;
        }
    } else {
        error_log("Kết nối cơ sở dữ liệu thất bại.");
        return 0;
    }
}

?>