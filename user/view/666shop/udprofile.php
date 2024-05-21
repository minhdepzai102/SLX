<?php

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user'])) {
    header("Location: ../../admin/wiew/login.php");
    exit;
}

// Kết nối đến cơ sở dữ liệu bằng PDO
try {
    $conn = connectdb();
    // Thiết lập chế độ lỗi để hiển thị các exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Lấy thông tin người dùng từ session
$username = $_SESSION['user'];

// Truy vấn cơ sở dữ liệu để lấy thông tin người dùng
$sql = "SELECT * FROM tbl_users WHERE user = :username";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':username', $username);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "No user found.";
    exit;
}

// Xử lý cập nhật thông tin
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $tel = $_POST['tel'];

    $updateSql = "UPDATE tbl_users SET name = :name, address = :address, tel = :tel WHERE user = :username";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bindParam(":name", $name);
    $updateStmt->bindParam(":address", $address);
    $updateStmt->bindParam(":tel", $tel);
    $updateStmt->bindParam(":username", $username);
    
    if ($updateStmt->execute()) {
        echo "Information updated successfully.";
    } else {
        echo "Error updating information: " . $updateStmt->errorInfo()[2];
    }

    // Refresh page to reflect updated data
    header("Location: index.php?act=profile");
}

$stmt->closeCursor();
$conn = null;
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <h3 class="mb-4">Update Information</h3>
            <form method="post" action="">
                <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($user['address']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="tel" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="tel" name="tel" value="<?php echo htmlspecialchars($user['tel']); ?>" required>
                </div>
                <button type="submit" class="btn btn-primary mb-2">Update Information</button>
            </form>
        </div>
    </div>
</div>
