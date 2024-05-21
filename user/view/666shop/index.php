<!-- Start Banner Hero -->
<div id="template-mo-zay-hero-carousel" class="carousel slide" data-bs-ride="carousel">
    <ol class="carousel-indicators">
        <li data-bs-target="#template-mo-zay-hero-carousel" data-bs-slide-to="0" class="active"></li>
        <li data-bs-target="#template-mo-zay-hero-carousel" data-bs-slide-to="1"></li>
        <li data-bs-target="#template-mo-zay-hero-carousel" data-bs-slide-to="2"></li>
    </ol>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <div class="container">
                <div class="row p-5">
                    <div class="mx-auto col-md-8 col-lg-6 order-lg-last">
                        <img class="img-fluid" style="width:408px; height:408px; "
                            src="../view/666shop/assets/img/banner01.png" alt="">
                    </div>
                    <div class="col-lg-6 mb-0 d-flex align-items-center">
                        <div class="text-align-left align-self-center">
                            <h1 class="h1 text-bichngoc"><b>Scents</b> </h1>
                            <h3 class="h2">"Thổi bay mọi giới hạn với hương thơm đặc biệt từ SLX store."</h3>
                            <p>@SLX.store</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="carousel-item">
            <div class="container">
                <div class="row p-5">
                    <div class="mx-auto col-md-8 col-lg-6 order-lg-last">
                        <img class="img-fluid" style="width:480px; height:408px; "
                            src="../view/666shop/assets/img/banner02.png" alt="">
                    </div>
                    <div class="col-lg-6 mb-0 d-flex align-items-center">
                        <div class="text-align-left">
                            <h1 class="h1  text-minhngu"><b>Luxury</b></h1>
                            <h3 class="h2">"Hương thơm sang trọng, phong cách vượt trội."</h3>
                            <p>
                                Dòng nước hoa cao cấp, một trong những thương hiệu nước hoa lâu đời và nổi tiếng có
                                hương thơm phức tạp, tinh tế và lâu phai, thường được làm từ các thành phân tự nhiên như
                                hoa và gỗ. Đây là một loại nước hoa sang trọng thích hợp cho cả nam và nữ.</p>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="carousel-item">
            <div class="container">
                <div class="row p-5">
                    <div class="mx-auto col-md-8 col-lg-6 order-lg-last">
                        <img class="img-fluid" style="width:408px; height:408px; "
                            src="../view/666shop/assets/img/banner03.png" alt="">
                    </div>
                    <div class="col-lg-6 mb-0 d-flex align-items-center">
                        <div class="text-align-left">
                            <h1 class="h1  text-khangluoi"><b>Xquisite</b></h1>
                            <h3 class="h2">"Tinh tế là ngôn ngữ của hương thơm."</h3>
                            <p>Hãy để chúng tôi dẫn bạn vào một hành trình khám phá vô tận của những hương thơm tinh tế,
                                nơi mà mỗi chai nước hoa không chỉ là một sản phẩm, mà còn là một câu chuyện về cái đẹp,
                                cái tinh tế và cái cuốn hút.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <a class="carousel-control-prev text-decoration-none w-auto ps-3 text-bichngoc"
        href="#template-mo-zay-hero-carousel" role="button" data-bs-slide="prev">
        <i class="fas fa-chevron-left text-khangluoi"></i>
    </a>
    <a class="carousel-control-next text-decoration-none w-auto pe-3" href="#template-mo-zay-hero-carousel"
        role="button" data-bs-slide="next">
        <i class="fas fa-chevron-right"></i>
    </a>
</div>
<!-- End Banner Hero -->


<!-- Start Categories of The Month -->
<section class="container py-5">

    <?php

    try {
        // Tạo kết nối PDO
        $conn = connectdb();
        // Thiết lập chế độ lỗi để hiển thị các exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Chuẩn bị truy vấn SQL
        $sql = "SELECT id, tendm, img FROM tbl_danhmuc ORDER BY uutien DESC LIMIT 3";

        // Thực hiện truy vấn và lấy kết quả
        $stmt = $conn->query($sql);
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);


    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    $conn = null; // Đóng kết nối
    ?>


    <div class="row">
        <?php foreach ($categories as $category): ?>
            <div class="col-12 col-md-4 p-5 mt-3 d-flex justify-content-center">
                <div class="text-center"> <!-- Added class "text-center" -->
                    <img src="../../admin/uploads/<?php echo $category['img']; ?>"
                        class="rounded-circle img-fluid border rounded-img">
                    <h5 class="mt-3 mb-3"><?php echo $category['tendm']; ?></h5>
                    <p><a class="btn btn-success" href="index.php?act=shop&id=<?php echo $category['id']; ?>">Go Shop</a>
                    </p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</section>
<!-- End Categories of The Month -->


<!-- Start Featured Product -->
<section class="bg-light">
    <div class="container py-5">
        <div class="row text-center py-3">
            <div class="col-lg-6 m-auto">
                <h1 class="h1">Most popular</h1>
                <p>
                    "Experience the allure of the highly viewed."
                </p>
            </div>
        </div>
        <div class="row">
        <?php
// Establish database connection using PDO

// Connect to the database
$conn = connectdb();

// Check if connection is successful
if (!$conn) {
    die("Connection failed: Unable to connect to the database.");
}

try {
    // Query to retrieve 3 products with the highest views
    $sql = "SELECT * FROM tbl_sanpham ORDER BY view DESC LIMIT 3";
    // Prepare the statement
    $stmt = $conn->prepare($sql);
    // Execute the statement
    $stmt->execute();
    // Fetch all rows as an associative array
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($products) > 0) {
        // Display product information
        foreach ($products as $product) {
            echo '<div class="col-12 col-md-4 mb-4">
                <div class="card1 h-100">
                    <a href="index.php?act=shop-single&id=' . $product['id'] . '">
                        <img src="../../admin/uploads/' . $product['img'] . '" class="card1-img-top" alt="Product Image">
                    </a>
                    <div class="card1-body">
                        <p class="card1-brand">' . $product['thuonghieu'] . '</p>
                        <a href="index.php?act=shop-single&id=' . $product['id'] . '" class="card1-title text-decoration-none text-dark"><strong><big>' . $product['tensanpham'] . '</big></strong></a>
                        <p class="card1-price">' . number_format($product['gia'], 0, ',', '.') . ' VND</p>
                    </div>
                </div>
            </div>';
        }
    } else {
        echo "Không có sản phẩm nào được tìm thấy";
    }
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Close the connection
$conn = null;
?>


        </div>
    </div>
</section>
<!-- End Featured Product -->


<!-- Start Footer -->

<!-- End Footer -->

<!-- Start Script -->
<script src="../view/666shop/assets/js/jquery-1.11.0.min.js"></script>
<script src="../view/666shop/assets/js/jquery-migrate-1.2.1.min.js"></script>
<script src="../view/666shop/assets/js/bootstrap.bundle.min.js"></script>
<script src="../view/666shop/assets/js/templatemo.js"></script>
<script src="../view/666shop/assets/js/custom.js"></script>
<!-- End Script -->