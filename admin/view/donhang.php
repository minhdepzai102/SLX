<?php
// Tệp donhang.php

// Kiểm tra xem hàm đã tồn tại chưa trước khi định nghĩa
if (!function_exists('getPaymentMethodDescription')) {
    function getPaymentMethodDescription($pttt) {
        switch ($pttt) {
            case 1:
                return "Cash on Delivery";  // Tiền mặt khi giao hàng
            case 2:
                return "Credit Card";  // Thẻ tín dụng
            case 3:
                return "Bank Transfer";  // Chuyển khoản ngân hàng
            case 4:
                return "E-Wallet";  // Ví điện tử
            default:
                return "Unknown";  // Giá trị không xác định
        }
    }
}

function getOrders($conn, $key) {
    $sql = "SELECT * FROM tbl_order";
    if ($key == 'chuahoanthanh') {
        $sql .= " WHERE trangthai != 3";
    } elseif ($key == 'dahoanthanh') {
        $sql .= " WHERE trangthai = 3";
    }
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Kết nối đến cơ sở dữ liệu
$conn = connectdb();

$key = isset($_GET['key']) ? $_GET['key'] : '';
$orders = getOrders($conn, $key);

// Đóng kết nối
$conn = null;
?>


<div class="container">
    <h2 class="mt-4">Danh sách Đơn hàng</h2>
    <div class="table-container">
        <input type="text" id="searchInput" placeholder="Tìm kiếm..." class="form-control mb-3">
        <button onclick="searchOrder()" class="btn btn-primary mb-3">Tìm kiếm</button>
        <table class="table table-bordered mt-3">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Mã Đơn hàng</th>
                    <th>Tổng Đơn hàng</th>
                    <th>Phương thức Thanh toán</th>
                    <th>Tên Khách hàng</th>
                    <th>Ngày Đặt</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?php echo htmlspecialchars($order['id'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($order['madh'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo number_format($order['tongdonhang'], 0, ',', '.') . ' VND'; ?></td>  <!-- Định dạng tiền tệ -->
                    <td><?php echo htmlspecialchars(getPaymentMethodDescription($order['pttt']), ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($order['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($order['ngaydat'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td>
                        <!-- Selection dropdown for order status -->
                        <select class="form-control" name="status" onchange="changeStatus(this.value, <?php echo $order['id']; ?>)">
                            <option value="0" <?php echo ($order['trangthai'] == 0) ? 'selected' : ''; ?>>Chưa xử lý</option>
                            <option value="1" <?php echo ($order['trangthai'] == 1) ? 'selected' : ''; ?>>Đang xử lý</option>
                            <option value="2" <?php echo ($order['trangthai'] == 2) ? 'selected' : ''; ?>>Đang giao</option>
                            <option value="3" <?php echo ($order['trangthai'] == 3) ? 'selected' : ''; ?>>Hoàn thành</option>
                        </select>
                    </td>
                    <td class="text-center">
                    <div class="d-flex justify-content-center gap-2">
                        <a href="index.php?act=donhang&idorder=<?php echo $order['id']; ?>" class="btn btn-primary btn-sm px-3 mx-1">
                            <i class="fas fa-eye"></i> Xem Chi tiết
                        </a>
                        <a href="index.php?act=donhang&deleteorder=<?php echo $order['id']; ?>" class="btn btn-danger btn-sm px-3 mx-1" onclick="return confirm('Bạn có chắc chắn muốn xóa đơn hàng này không?');">
                            <i class="fas fa-trash-alt"></i> Xóa
                        </a>
                    </div>
                </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    // JavaScript function to handle status change
    function changeStatus(status, orderId) {
        // Send an AJAX request to update the order status
        // You need to implement the server-side logic to update the status
        // This is just a basic example to demonstrate the client-side part
        // You may use JavaScript frameworks like jQuery or fetch API for better AJAX handling
        // Example using fetch API:
        fetch('../view/update_status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ status: status, orderId: orderId }),
        })
        .then(response => response.json())
        .then(data => {
            // Handle the response from the server
            console.log(data);
            // You can update the UI if needed based on the response
        })
        .catch((error) => {
            console.error('Error:', error);
        });
    }

    function searchOrder() {
        var input, filter, table, tr, tdId, tdName, i;
        input = document.getElementById("searchInput");
        filter = input.value.toUpperCase();
        table = document.getElementsByTagName("table")[0];
        tr = table.getElementsByTagName("tr");
        for (i = 0; i < tr.length; i++) {
            tdId = tr[i].getElementsByTagName("td")[1]; // Tìm kiếm theo cột mã đơn hàng
            tdName = tr[i].getElementsByTagName("td")[4]; // Tìm kiếm theo cột tên khách hàng
            if (tdId || tdName) {
                var txtValueId = tdId.textContent || tdId.innerText;
                var txtValueName = tdName.textContent || tdName.innerText;
                if (txtValueId.toUpperCase().indexOf(filter) > -1 || txtValueName.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
</script>
