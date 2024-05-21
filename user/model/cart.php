<?php
// Thêm sản phẩm vào giỏ hàng với kiểm tra tính hợp lệ của idpro và iduser
function addtocart($idorder, $idpro, $tensp, $img, $dongia, $soluong) {
    $conn = connectdb(); // Kết nối cơ sở dữ liệu
    if ($conn) {
        try {
            // Kiểm tra xem idorder có hợp lệ không
            $stmt_order = $conn->prepare("SELECT id FROM tbl_order WHERE id = :idorder");
            $stmt_order->execute([':idorder' => $idorder]);
            if ($stmt_order->rowCount() === 0) {
                error_log("ID đơn hàng không hợp lệ: " . $idorder);
                return 0; 
            }

            // Kiểm tra xem idpro có hợp lệ không
            $stmt_pro = $conn->prepare("SELECT id FROM tbl_sanpham WHERE id = :idpro");
            $stmt_pro->execute([':idpro' => $idpro]);
            if ($stmt_pro->rowCount() === 0) {
                error_log("ID sản phẩm không hợp lệ: " . $idpro);
                return 0; 
            }

            // Thêm vào bảng `tbl_cart`
            $stmt = $conn->prepare("INSERT INTO tbl_cart (idorder, idpro, tensp, img, dongia, soluong) 
                                    VALUES (:idorder, :idpro, :tensp, :img, :dongia, :soluong)");
            $stmt->execute([
                ':idorder' => $idorder,
                ':idpro' => $idpro,
                ':tensp' => htmlspecialchars($tensp, ENT_QUOTES, 'UTF-8'),
                ':img' => htmlspecialchars($img, ENT_QUOTES, 'UTF-8'),
                ':dongia' => $dongia,
                ':soluong' => $soluong
            ]);

            return $stmt->rowCount(); 

        } catch (PDOException $e) {
            error_log("Lỗi khi thêm vào giỏ hàng: " . $e->getMessage());
            return 0;
        }
    } else {
        error_log("Kết nối cơ sở dữ liệu thất bại.");
        return 0;
    }
}

?>