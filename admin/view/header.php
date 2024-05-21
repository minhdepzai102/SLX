<?php
// Kiểm tra session trước khi bắt đầu
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Bắt đầu session nếu chưa được kích hoạt
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="apple-touch-icon" href="../../user/view/666shop/assets/img/category_img_02.png">
    <link rel="shortcut icon" type="image/x-icon" href="../../user/view/666shop/assets/img/category_img_02.png">
    <meta charset="UTF-8">
    <title>SLX Admin</title>
    <link rel="stylesheet" href="../view/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        header {
            background-color: #343a40; /* Màu nền header */
            padding: 20px 0; /* Khoảng cách giữa header và nội dung */
            color: #ffffff; /* Màu chữ */
            display: flex; /* Hiển thị các phần tử trong header theo chiều ngang */
            justify-content: space-between; /* Các phần tử sẽ được căn giữa và căn cách nhau */
            align-items: center; /* Căn dọc các phần tử vào trung tâm */
        }

        header h2 {
            margin: 0; /* Xóa margin mặc định */
            font-size: 24px; /* Kích thước chữ */
            font-weight: bold; /* Độ đậm */
            text-transform: uppercase; /* Chuyển đổi chữ hoa */
            margin-left: 10px ;
        }

        nav {
            display: flex; /* Hiển thị các mục menu theo chiều ngang */
        }

        nav a {
            color: #ffffff; /* Màu chữ menu */
            text-decoration: none; /* Xóa gạch chân dưới chữ */
            transition: color 0.3s; /* Hiệu ứng chuyển màu khi hover */
            margin-right: 20px; /* Khoảng cách giữa các mục menu */
        }

        nav a:hover {
            color: #ffc107; /* Màu chữ khi hover */
        }
        .editButton {
            cursor: pointer;
            color: blue;
        }
        /* CSS cho phần mô tả mở rộng */
.mota {
    display: none; /* Mặc định ẩn đi */
}

.mota.expanded {
    display: block; /* Hiển thị nếu có class "expanded" */
}

    </style>
</head>

<body>
    <header>
        <?php
        if (isset($_SESSION['user'])) { // Kiểm tra session 'user' và hiển thị
            echo '<h2>Hello, ' . htmlspecialchars($_SESSION['user']) . '</h2>';
        } else {
            echo '<h2>Welcome</h2>'; // Nếu không có user trong session
        }
        ?>
        <nav>
            <a href="index.php?act=home">Home</a>
            <a href="index.php?act=danhmuc">Categories</a>
            <a href="index.php?act=sanpham">Products</a>
            <a href="index.php?act=taikhoan">Accounts</a>
            <a href="index.php?act=donhang">Orders</a>
            <a href="index.php?act=WEB">WEB</a>
            <a href="index.php?act=thoat">Logout</a>
        </nav>
    </header>
</body>

</html>
