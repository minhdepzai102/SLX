<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Kết nối cơ sở dữ liệu
$conn = connectdb();

// Chuẩn bị truy vấn SQL
$sql = "SELECT id, tendm, img FROM tbl_danhmuc ORDER BY uutien ASC";

// Thực hiện truy vấn và lấy kết quả
$stmt = $conn->query($sql);
$categories = [];
if ($stmt->rowCount() > 0) {
    $categories = $stmt->fetchAll();
} else {
    echo "0 results"; // Hiển thị thông báo nếu không có kết quả
}

// Kiểm tra tổng số sản phẩm trong giỏ hàng
$totalItems = 0; // Khởi tạo tổng số sản phẩm là 0

if (isset($_SESSION['giohang'])) {
    foreach ($_SESSION['giohang'] as $item) {
        $totalItems += $item['quantity']; // Cộng dồn số lượng sản phẩm
    }
}

// Check if user is logged in based on session
$isLoggedIn = isset($_SESSION['user']); // Boolean indicating if a user is logged in
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <title>SLX Store</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="../view/666shop/assets/img/category_img_02.png">
    <link rel="shortcut icon" type="image/x-icon" href="../view/666shop/assets/img/category_img_02.png">

    <link rel="stylesheet" href="../view/666shop/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../view/666shop/assets/css/templatemo.css">
    <link rel="stylesheet" href="../view/666shop/assets/css/custom.css">

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400;500;700;900&display=swap">
    <link rel="stylesheet" href="../view/666shop/assets/css/fontawesome.min.css">

    <link rel="stylesheet" type="text/css" href="../view/666shop/assets/css/slick.min.css">
    <link rel="stylesheet" type="text/css" href="../view/666shop/assets/css/slick-theme.css">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
        integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
        crossorigin="" />

    <style>
        .profile-header {
            position: relative;
            background-color: #f8f9fa;
            text-align: center;
            margin-top: 30px;
        }

        .profile-header img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin-bottom: 15px;
        }

        .profile-header h2 {
            margin: 0;
        }

        .profile-header p {
            margin: 5px 0 15px;
        }

        .profile-content {
            padding: 30px 0;
        }
    </style>
</head>

<body>

    <!-- Start Top Nav -->
    <nav class="navbar navbar-expand-lg bg-dark navbar-light d-none d-lg-block" id="templatemo_nav_top">
        <div class="container text-light">
            <div class="w-100 d-flex justify-content-between">
                <div>
                    <i class="fa fa-envelope mx-2"></i>
                    <a class="navbar-sm-brand text-light text-decoration-none"
                        href="mailto:slxstore@gmail.com">slxstore@gmail.com</a>
                    <i class="fa fa-phone mx-2"></i>
                    <a class="navbar-sm-brand text-light text-decoration-none" href="tel:012-345-6789">012-345-6789</a>
                </div>
                <div>
                    <a class="text-light" href="https://www.facebook.com/minhnef1206/" target="_blank"
                        rel="sponsored"><i class="fab fa-facebook-f fa-sm fa-fw me-2"></i></a>
                    <a class="text-light" href="https://www.instagram.com/minkzuize.69/" target="_blank"><i
                            class="fab fa-instagram fa-sm fa-fw me-2"></i></a>
                </div>
            </div>
        </div>
    </nav>
    <!-- Close Top Nav -->

    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-light shadow">
        <div class="container d-flex justify-content-between align-items-center">
            <a class="navbar-brand text-success logo h1 align-self-center" href="index.php?act=home">
                SLX
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#templatemo_main_nav" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="align-self-center collapse navbar-collapse flex-fill d-lg-flex justify-content-lg-between"
                id="templatemo_main_nav">
                <div class="flex-fill">
                    <ul class="nav navbar-nav d-flex justify-content-between mx-lg-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?act=home">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?act=about">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?act=shop">Shop</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?act=contact">Contact</a>
                        </li>
                    </ul>
                </div>
                <div class="navbar align-self-center d-flex">
                    <div class="d-lg-none flex-sm-fill mt-3 mb-4 col-7 col-sm-auto pr-3">
                        <div class="input-group">
                            <input type="text" class="form-control" id="inputMobileSearch" placeholder="Search ...">
                            <div class="input-group-text">
                                <i class="fa fa-fw fa-search"></i>
                            </div>
                        </div>
                    </div>
                    <a class="nav-icon d-none d-lg-inline" href="#" data-bs-toggle="modal"
                        data-bs-target="#templatemo_search">
                        <i class="fa fa-fw fa-search text-dark mr-2"></i>
                    </a>
                    <a class="nav-icon position-relative text-decoration-none" href="index.php?act=cart">
                        <i class="fa fa-fw fa-cart-arrow-down text-dark mr-1"></i>
                        <?php if ($totalItems > 0): ?>
                            <span
                                class="position-absolute top-0 left-100 translate-middle badge rounded-pill bg-light text-dark">
                                <?php echo $totalItems; // Hiển thị tổng số sản phẩm ?>
                            </span>
                        <?php endif; ?>
                    </a>


                </div>

                <!-- User Profile Dropdown -->
                <div class="dropdown">
                    <a class="nav-icon position-relative text-decoration-none dropdown-toggle" href="#" id="userMenu"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-fw fa-user text-dark mr-3"></i>
                        <span
                            class="position-absolute top-0 left-100 translate-middle badge rounded-pill bg-light text-dark"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                        <?php if (isset($_SESSION['user'])): ?>
                            <li><a class="dropdown-item" href="index.php?act=history">Order History</a></li>
                            <li><a class="dropdown-item" href="index.php?act=profile">Profile</a></li>
                            <?php if ($_SESSION['role'] == 1): ?>
                                <li><a class="dropdown-item" href="../../admin/controller/index.php">Admin</a></li>
                            <?php endif; ?>
                            <li><a class="dropdown-item" href="index.php?act=logout">Logout</a></li>
                        <?php else: ?>
                            <li><a class="dropdown-item" href="../../admin/controller/login.php">Login</a></li>
                        <?php endif; ?>

                    </ul>
                </div>
                <!-- End User Profile Dropdown -->
            </div>
        </div>
        </div>
    </nav>
    <!-- Close Header -->
    <!-- Modal -->
    <div class="modal fade bg-white" id="templatemo_search" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="w-100 pt-1 mb-5 text-right">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="modal-content modal-body border-0 p-0">
                <div class="input-group mb-2">
                    <input type="text" class="form-control" id="inputModalSearch" placeholder="Search ...">
                    <button type="button" class="input-group-text bg-success text-light">
                        <i class="fa fa-fw fa-search text-white"></i>
                    </button>
                </div>
                <div class="live-search-result" style="max-height: 200px; overflow: hidden;">
                    <ul class="list-unstyled search-results">
                        <!-- Kết quả tìm kiếm sẽ được thêm vào đây -->
                    </ul>
                </div>
            </form>
        </div>
    </div>