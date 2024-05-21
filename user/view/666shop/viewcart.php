<section class="h-100" style="background-color: #eee;">
  <div class="container h-100 py-5">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-10">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h3 class="fw-normal mb-0 text-black">Shopping Cart</h3>
        </div>

        <?php
        // Khởi tạo biến $tong và tính tổng giá trị đơn hàng
        $tong = 0; // Bắt đầu với giá trị 0

        if (!empty($_SESSION['giohang'])) {
          foreach ($_SESSION['giohang'] as $item) {
            // Cộng dồn giá trị tổng của từng sản phẩm (giá * số lượng)
            $tong += $item['gia'] * $item['quantity'];
          }
        }
        // Kiểm tra nếu giỏ hàng không trống
        if (!empty($_SESSION['giohang'])) {
          foreach ($_SESSION['giohang'] as $item) {
            $img = isset($item['img']) ? $item['img'] : 'default-image.png'; // Ảnh mặc định
            $tensp = isset($item['tensp']) ? $item['tensp'] : 'Unknown Product'; // Tên sản phẩm mặc định
            $quantity = isset($item['quantity']) ? $item['quantity'] : 1; // Số lượng mặc định
            $id = isset($item['id']) ? $item['id'] : 0; // ID mặc định
            $gia = isset($item['gia']) ? $item['gia'] : 0; // Giá mặc định
            echo '
      <div class="card rounded-3 mb-4">
        <div class="card-body p-4">
          <div class="row d-flex justify-content-between align-items-center">
            <div class="col-md-2 col-lg-2 col-xl-2">
              <img src="../../admin/uploads/' . htmlspecialchars($img) . '" class="img-fluid rounded-3" alt="' . htmlspecialchars($tensp) . '">
            </div>
            <div class="col-md-3 col-lg-3 col-xl-3">
              <p class="lead fw-normal mb-2">' . htmlspecialchars($tensp) . '</p>
              <p>
                <span class="text-muted">Quantity: </span>' . htmlspecialchars($quantity) . '
              </p>
            </div>
            <div class="col-md-3 col-lg-3 col-xl-2 d-flex">
    <button class="btn btn-link px-2 btn-minus" data-product-id="' . $id . '">
        <i class="fas fa-minus"></i>
    </button>
    <input type="number" min="1" name="quantity" value="' . htmlspecialchars($quantity) . '" class="form-control form-control-sm var-value" readonly />
    <button class="btn btn-link px-2 btn-plus" data-product-id="' . $id . '">
        <i class="fas fa-plus"></i>
    </button>
</div>

            <div class="col-md-3 col-lg-2 col-xl-2 offset-lg-1">
              <h5 class="mb-0">' . number_format($gia * $quantity, 0, ',', '.') . ' VNĐ</h5>
            </div>
            <div class="col-md-1 col-lg-1 col-xl-1 text-end">
              <a href="#" class="text-danger" onclick="removeFromCart(' . $id . ')"><i class="fas fa-trash fa-lg"></i></a>
            </div>
          </div>
        </div>
      </div>';
          }
        } else {
          echo "<p>Your cart is currently empty.</p>";
        }
        ?>

        <div class='clear-cart'>
          <a href="index.php?act=delcart" type="button" style="margin-bottom: 50px;"
            class="btn btn-outline-warning btn-lg ms-3" onclick='clearCart()'>Xóa giỏ hàng</a>
          <a href="index.php?act=shop" type="button" style="margin-bottom: 50px;"
            class="btn btn-outline-warning btn-lg ms-3">Tiếp tục mua hàng</a>
        </div>
        <div class="card mb-4">
          <div class="card-body">
            <h5 class="text-end">Total <?= number_format($tong, 0, ',', '.') ?> VNĐ</h5>
          </div>
        </div>
        <?php if (!empty($_SESSION['giohang'])): ?>
        <div class="card mb-4">
          <div class="card-body">
            <!-- Biểu mẫu nhập địa chỉ giao hàng -->
            <!-- Biểu mẫu thanh toán -->
            <form action="index.php?act=thanhtoan" method="post" class="form-payment" onsubmit="return validatePaymentMethod();">
              <!-- Ẩn trường tổng đơn hàng -->
              <input type="hidden" name="tongdonhang" value="<?= $tong ?>"> <!-- Chèn giá trị tổng đơn hàng -->

              <!-- Nhập tên người dùng -->
              <div class="mb-3 form-group">
                <label for="name" class="form-label">Username</label>
                <input type="text" name="name" class="form-control" placeholder="Enter your username" required>
              </div>

              <!-- Nhập địa chỉ -->
              <div class="mb-3 form-group">
                <label for="address" class="form-label">Address</label>
                <input type="text" name="address" class="form-control" placeholder="Enter your address" required>
              </div>

              <!-- Nhập email -->
              <div class="mb-3 form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
              </div>

              <!-- Nhập số điện thoại -->
              <div class="mb-3 form-group">
                <label for="tel" class="form-label">Telephone number</label>
                <input type="tel" name="tel" class="form-control" placeholder="Enter your telephone number" required>
              </div>

              <!-- Chọn phương thức thanh toán -->
              <div class="mb-3 form-group">
                <label for="payment_method" class="form-label">Payment Method</label>
                <select name="payment_method" id="payment_method" class="form-select" required> <!-- Thêm lớp form-select -->
                  <option value="" disabled selected>Choose a method</option>
                  <option value="1">Cash on Delivery</option>
                  <option value="2">Credit Card</option>
                  <option value="3">Bank Transfer</option>
                  <option value="4">E-Wallet</option>
                </select>
              </div>

              <!-- Nút gửi biểu mẫu -->
              <button type="submit" name="thanhtoan" class="btn btn-warning btn-block btn-lg">Proceed to Checkout</button>
            </form>
          </div>
        </div>
        <?php endif; ?>
      </div>
</section>

<script>
  function validatePaymentMethod() {
    const paymentMethod = document.getElementById('payment_method');
    if (paymentMethod.value === "") {
      alert("Please choose a payment method.");
      return false; // Ngăn không cho biểu mẫu gửi đi
    }
    return true; // Cho phép gửi biểu mẫu
  }

  // Sử dụng lớp CSS để lấy các nút tăng/giảm
  const btnMinus = document.querySelectorAll('.btn-minus');
  const btnPlus = document.querySelectorAll('.btn-plus');

  // Gắn sự kiện "click" cho nút giảm
  btnMinus.forEach((button) => {
    button.addEventListener('click', function () {
      const input = button.parentNode.querySelector('input[type=number]');
      const currentValue = parseInt(input.value);
      if (currentValue > 1) { // Không cho phép giá trị nhỏ hơn 1
        input.value = currentValue - 1; // Giảm giá trị
        updateQuantity(button.dataset.productId, input.value); // Cập nhật phía máy chủ
      }
    });
  });

  // Gắn sự kiện "click" cho nút tăng
  btnPlus.forEach((button) => {
    button.addEventListener('click', function () {
      const input = button.parentNode.querySelector('input[type=number]');
      const currentValue = parseInt(input.value);
      input.value = currentValue + 1; // Tăng giá trị
      updateQuantity(button.dataset.productId, input.value); // Cập nhật phía máy chủ
    });
  });

  // Hàm cập nhật số lượng phía máy chủ
  function updateQuantity(productId, newQuantity) {
    if (newQuantity > 0) {
      fetch('index.php?act=updatecart', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `id=${productId}&quantity=${newQuantity}` // Gửi giá trị mới đến phía máy chủ
      }).then(() => {
        location.reload(); // Tải lại trang sau khi cập nhật
      });
    }
  }

  function removeFromCart(productId) {
    if (confirm("Are you sure you want to remove this item from your cart?")) {
      // Logic to remove item from cart, this might involve a fetch call similar to updateQuantity
      fetch('index.php?act=removecart', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `id=${productId}`
      }).then(() => {
        location.reload(); // Reload the page after removing the item
      });
    }
  }

  function clearCart() {
    if (confirm("Are you sure you want to clear your cart?")) {
      // Logic to clear the cart, this might involve a fetch call similar to updateQuantity
      fetch('index.php?act=clearcart', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: ''
      }).then(() => {
        location.reload(); // Reload the page after clearing the cart
      });
    }
  }
</script>
