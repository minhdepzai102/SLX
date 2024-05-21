<?php
// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

try {
    // Connect to the database using PDO
    $conn = connectdb();
    // Retrieve user information from session
    $username = $_SESSION['user'];

    // Query the database to fetch user information
    $sql = "SELECT * FROM tbl_users WHERE user = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Handle information update
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = $_POST['name'];
        $address = $_POST['address'];
        $tel = $_POST['tel'];

        $updateSql = "UPDATE tbl_users SET name = ?, address = ?, tel = ? WHERE user = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->execute([$name, $address, $tel, $username]);

        // Refresh page to reflect updated data
        header("Refresh:0");
        exit; // Exit after updating to prevent further execution
    }
} catch (PDOException $e) {
    // Handle database connection or query errors
    echo "Connection failed: " . $e->getMessage();
    exit; // Exit after encountering an error
}
?>
<body>

<div class="container">
    <!-- Profile Header -->
    <div class="profile-header">
        <h2><?php echo htmlspecialchars($user['name']); ?></h2>
        <p class="text-muted"><?php echo htmlspecialchars($user['email']); ?></p>
    </div>
    
    <!-- Profile Content -->
    <div class="profile-content">
        <div class="row">
            <!-- Personal Information -->
            <div class="col-md-6">
                <h3>Personal Information</h3>
                <ul class="list-group">
                    <li class="list-group-item"><strong>Full Name:</strong> <?php echo htmlspecialchars($user['name']); ?></li>
                    <li class="list-group-item"><strong>Gender:</strong> Male</li>
                </ul>
            </div>

            <!-- Contact Information -->
            <div class="col-md-6">
                <h3>Contact Information</h3>
                <ul class="list-group">
                    <li class="list-group-item"><strong>Phone:</strong> <?php echo htmlspecialchars($user['tel']); ?></li>
                    <li class="list-group-item"><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></li>
                    <li class="list-group-item"><strong>Address:</strong> <?php echo htmlspecialchars($user['address']); ?></li>
                </ul>
            </div>
        </div>
        <a href="index.php?act=updateinfo" class="btn btn-primary">Update Information</a>
        <!-- Update Information Form -->
        
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
