<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Bắt đầu phiên
}

ob_start(); // Bắt đầu bộ đệm đầu ra

// Bao gồm các cấu hình và tệp cần thiết
require_once '../../config.php';
require_once '../model/order_functions.php';  // Bao gồm các hàm liên quan đến đơn hàng

// Lấy `idorder` từ tham số URL để xem chi tiết đơn hàng
$idorder = isset($_GET['idorder']) ? intval($_GET['idorder']) : 0;

$orderDetails = getOrderDetails($idorder);  // Lấy chi tiết đơn hàng

?>


<body>

<div class="container">
    <?php
    if ($orderDetails && !empty($orderDetails)):  // Kiểm tra nếu có chi tiết đơn hàng
    ?>
        <h2 class="mt-4">Chi tiết Đơn hàng: 
            <?php 
            if (isset($orderDetails[0]['madh'])) {  // Hiển thị mã đơn hàng
                echo htmlspecialchars($orderDetails[0]['madh'], ENT_QUOTES, 'UTF-8');
            } else {
                echo "Không có mã đơn hàng.";
            }
            ?>
        </h2>

        <div class="row">
            <div class="col-md-6">
                <p>Tên khách hàng: 
                    <?php 
                    if (isset($orderDetails[0]['name'])) {  // Hiển thị tên khách hàng
                        echo htmlspecialchars($orderDetails[0]['name'], ENT_QUOTES, 'UTF-8');
                    } else {
                        echo "Không có tên.";
                    }
                    ?>
                </p>
                <p>Địa chỉ:
                    <?php 
                    if (isset($orderDetails[0]['address'])) {  // Hiển thị tên khách hàng
                        echo htmlspecialchars($orderDetails[0]['address'], ENT_QUOTES, 'UTF-8');
                    } else {
                        echo "Không có tên.";
                    }
                    ?>
                </p>
                <p>Số điện thoại: 
                    <?php 
                    if (isset($orderDetails[0]['tel'])) {  // Hiển thị số điện thoại
                        echo htmlspecialchars($orderDetails[0]['tel'], ENT_QUOTES, 'UTF-8');
                    } else {
                        echo "Không có số điện thoại.";
                    }
                    ?>
                </p>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12">
                <table class="table table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Hình ảnh</th>
                            <th scope="col">Tên sản phẩm</th>
                            <th scope="col">Số lượng</th>
                            <th scope="col">Đơn giá</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orderDetails as $detail): ?>  <!-- Vòng lặp qua các chi tiết đơn hàng -->
                        <tr>
                            <td>
                                <?php if (!empty($detail['img'])): ?>
                                    <img src="../uploads/<?php echo htmlspecialchars($detail['img'], ENT_QUOTES, 'UTF-8'); ?>" alt="Ảnh sản phẩm" width="50" height="50">
                                <?php else: ?>
                                    Không có hình ảnh
                                <?php endif; ?>
                            </td>
                            <?php $tong = $detail['soluong'] * $detail['dongia']; ?>
                            <td><?php echo htmlspecialchars($detail['tensanpham'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($detail['soluong'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo number_format($tong, 0, ',', '.') . ' VND'; ?></td>  <!-- Định dạng tiền tệ -->
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Nút quay lại -->
        <div class="row mt-4">
            <div class="col-md-12">
                <a href="index.php?act=donhang" class="btn btn-primary">Quay lại</a>
            </div>
        </div>

    <?php else: ?>
        <p class="mt-4">Không có chi tiết đơn hàng.</p>  <!-- Thông báo nếu không có chi tiết đơn hàng -->
    <?php endif; ?>
</div>

</body>
</html>
