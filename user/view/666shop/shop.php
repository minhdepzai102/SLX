<?php
// Kết nối với cơ sở dữ liệu bằng PDO
try {
    $conn = connectdb();
    // Thiết lập chế độ lỗi để hiển thị các exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Kết nối thất bại: " . $e->getMessage());
}

// Lấy ID danh mục, tùy chọn sắp xếp và trang hiện tại
$iddm = isset($_GET['id']) ? intval($_GET['id']) : 0;
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'alphabet_asc'; // Giá trị mặc định

$page = isset($_GET['page']) ? intval($_GET['page']) : 1; // Trang hiện tại

// Tạo chuỗi SQL sắp xếp dựa trên tùy chọn
$sortQuery = '';
switch ($sort) {
    case 'alphabet_asc':
        $sortQuery = ' ORDER BY tensanpham ASC';
        break;
    case 'alphabet_desc':
        $sortQuery = ' ORDER BY tensanpham DESC';
        break;
    case 'price_asc':
        $sortQuery = ' ORDER BY gia ASC';
        break;
    case 'price_desc':
        $sortQuery = ' ORDER BY gia DESC';
        break;
}

// Phân trang
$items_per_page = 9; // Số sản phẩm mỗi trang
$offset = ($page - 1) * $items_per_page; // Giá trị offset

// Truy vấn sản phẩm với tùy chọn sắp xếp và phân trang
if ($iddm > 0) {
    // Lấy sản phẩm theo danh mục
    $sql = "SELECT * FROM tbl_sanpham WHERE iddm = :iddm {$sortQuery} LIMIT :limit OFFSET :offset";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':iddm', $iddm, PDO::PARAM_INT);
} else {
    // Lấy tất cả sản phẩm
    $sql = "SELECT * FROM tbl_sanpham {$sortQuery} LIMIT :limit OFFSET :offset";
    $stmt = $conn->prepare($sql);
}

$stmt->bindParam(':limit', $items_per_page, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$dssp = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Lấy tổng số sản phẩm
$total_sql = "SELECT COUNT(*) as total FROM tbl_sanpham";
if ($iddm > 0) {
    $total_sql .= " WHERE iddm = :iddm";
}

$total_stmt = $conn->prepare($total_sql);
if ($iddm > 0) {
    $total_stmt->bindParam(':iddm', $iddm, PDO::PARAM_INT);
}
$total_stmt->execute();
$total_items = $total_stmt->fetchColumn();
$total_pages = ceil($total_items / $items_per_page);

// Đóng kết nối và các prepared statements
$stmt->closeCursor();
$total_stmt->closeCursor();
$conn = null;

// Xử lý danh mục
$dsdm = getall_dm();
array_unshift($dsdm, ['id' => 0, 'tendm' => 'All']); // Chèn "All" vào đầu danh sách

// Xác định tên danh mục
$tendm = "All";
foreach ($dsdm as $dm) {
    if ($dm['id'] == $iddm) {
        $tendm = $dm['tendm'];
        break;
    }
}
?>







<!-- Start Content -->
<div class="container py-5">
    <div class="row">

        <div class="col-lg-3">
            <h1 class="h2 pb-4">Categories</h1>
            <ul class="list-unstyled templatemo-accordion">
                <li class="pb-3">
                    <a class="collapsed d-flex justify-content-between h3 text-decoration-none" href="#">
                        Product
                        <i class="pull-right fa fa-fw fa-chevron-circle-down mt-1"></i>
                    </a>
                    <ul id="collapseThree" class="collapse list-unstyled pl-3">
                        <?php
                        foreach ($dsdm as $dm) {
                            $url = ($dm['id'] > 0) ? "index.php?act=shop&id=" . $dm['id'] : "index.php?act=shop"; // URL cho "All" và các danh mục khác
                            $activeClass = ($dm['id'] == $iddm) ? "font-weight-bold" : ""; // Đánh dấu danh mục hiện tại
                            echo '<li><a class="text-decoration-none ' . $activeClass . '" href="' . $url . '">' . $dm['tendm'] . '</a></li>';
                        }
                        ?>


                    </ul>
                </li>
            </ul>
        </div>
        <div class="col-lg-9">
            <div class="row">
                <div class="col-md-6">
                    <ul class="list-inline shop-top-menu pb-3 pt-1">
                        <li class="list-inline-item">
                            <a class="h3 text-dark text-decoration-none mr-3" href="#"><?php echo $tendm; ?></a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-6 pb-4">
                    <div class="d-flex">
                        <?php
                        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'alphabet_asc'; // Giá trị mặc định nếu 'sort' không tồn tại
                        
                        // Thiết lập 'selected' dựa trên giá trị của 'sort'
                        function isSelected($sortValue, $currentSort)
                        {
                            return ($sortValue == $currentSort) ? 'selected' : ''; // Nếu trùng khớp, trả về 'selected'
                        }

                        // Sử dụng hàm 'isSelected' để thiết lập 'selected'
                        ?>
                        <select id="sort-select" class="form-control w-40">
                            <option value="alphabet_asc" <?= isSelected('alphabet_asc', $sort) ?>>A-Z</option>
                            <option value="alphabet_desc" <?= isSelected('alphabet_desc', $sort) ?>>Z-A</option>
                            <option value="price_asc" <?= isSelected('price_asc', $sort) ?>>Price: Low to High</option>
                            <option value="price_desc" <?= isSelected('price_desc', $sort) ?>>Price: High to Low</option>
                        </select>


                    </div>
                </div>
            </div>
            <div class="row">
                <div class="row">
                    <?php
                    if (isset($dssp) && is_array($dssp) && count($dssp) > 0) {
                        foreach ($dssp as $sp) {
                            echo '
        <div class="col-md-4">
            <div class="card mb-4 product-wap rounded-0">
                <div class="card rounded-0">
                    <img class="card-img rounded-0 img-fluid" src="../../admin/uploads/' . $sp['img'] . '" style="height: 300px; width: 300px;">
                    <div class="card-img-overlay rounded-0 product-overlay d-flex align-items-center justify-content-center">
                        <ul class="list-unstyled">
                            <li>
                                <!-- Xem chi tiết sản phẩm -->
                                <a class="btn btn-success text-white mt-2" href="index.php?act=shop-single&id=' . $sp['id'] . '">
                                    <i class="far fa-eye"></i>
                                </a>
                            </li>
                            <li>
                                <!-- Form để thêm vào giỏ hàng -->
                                <form action="index.php?act=addcart" method="post">
                                    <!-- Truyền các thông tin ẩn -->
                                    <input type="hidden" name="product_id" value="' . $sp['id'] . '">
                                    <input type="hidden" name="product_name" value="' . $sp['tensanpham'] . '">
                                    <input type="hidden" name="product_image" value="' . $sp['img'] . '">
                                    <input type="hidden" name="product_price" value="' . $sp['gia'] . '">
                                    <button type="submit" class="btn btn-success text-white mt-2" name="addtocart">
                                        <i class="fas fa-cart-plus"></i>
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div style="text-align: center;">
                        <a href="index.php?act=shop-single&id=' . $sp['id'] . '" class="h3 text-decoration-none">' . $sp['tensanpham'] . '</a>
                    </div>
                    <p class="text-center mb-0">' . number_format($sp['gia'], 0, ',', '.') . ' VND</p>
                </div>
            </div>
        </div>';
                        }
                    } ?>


                </div>
                <div class="row">
                    <?php
                    if (isset($products) && count($products) > 0) {
                        foreach ($products as $product) {
                            echo '
        <div class="col-md-4 mb-4">
            <div class="card product-card rounded-0">
                <!-- Hình ảnh sản phẩm -->
                <img class="card-img rounded-0 img-fluid" src="' . $product['image'] . '" alt="' . $product['name'] . '">

                <!-- Các tùy chọn sản phẩm -->
                <div class="card-img-overlay product-options d-flex align-items-center justify-content-center">
                    <ul class="list-unstyled">
                        <li>
                            <!-- Nút xem chi tiết sản phẩm -->
                            <a class="btn btn-primary text-white" href="shop-single.php?id=' . $product['id'] . '">
                                <i class="far fa-eye"></i>
                            </a>
                        </li>
                        <li>
                            <!-- Form để thêm vào giỏ hàng -->
                            <form action="index.php?act=addcart" method="post">
                                <!-- Truyền các thông tin sản phẩm qua input ẩn -->
                                <input type="hidden" name="product_id" value="' . $product['id'] . '">
                                <input type="hidden" name="product_name" value="' . $product['name'] . '">
                                <input type="hidden" name="product_image" value="' . $product['image'] . '">
                                <input type="hidden" name="product_price" value="' . $product['gia'] . '">

                                <!-- Nút để thêm sản phẩm vào giỏ hàng -->
                                <button type="submit" class="btn btn-success text-white" name="addtocart">
                                    <i class="fas fa-cart-plus"></i>
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>

                <!-- Thông tin chi tiết sản phẩm -->
                <div class="card-body text-center">
                    <a href="shop-single.php?id=' . $product['id'] . '" class="h3 text-decoration-none">' . $product['name'] . '</a>
                    <p class="text-center">' . number_format($product['gia'], 0, ',', '.') . ' VND</p>
                </div>
            </div>
        </div>';
                        }
                    }
                    ?>


                </div>


            </div>
            <ul class="pagination justify-content-center">
                <?php
                for ($i = 1; $i <= $total_pages; $i++) {
                    $active = ($i == $page) ? 'active' : '';
                    echo '<li class="page-item ' . $active . '">';
                    echo '<a class="page-link" href="index.php?act=shop&id=' . $iddm . '&page=' . $i . '&sort=' . $sort . '">' . $i . '</a>';
                    echo '</li>';
                }
                ?>
            </ul>
        </div>

    </div>
</div>

<!-- End Content -->

<!-- Start Brands -->
<section class="bg-light py-5">
    <div class="container my-4">
        <div class="row text-center py-3">
            <div class="col-lg-6 m-auto">
                <h1 class="h1">Our Brands</h1>
                <p>@slx.store</p>
            </div>
            <div class="col-lg-9 m-auto tempaltemo-carousel">
                <div class="row d-flex flex-row">
                    <!--Controls-->
                    <div class="col-1 align-self-center">
                        <a class="h1" href="#multi-item-example" role="button" data-bs-slide="prev">
                            <i class="text-light fas fa-chevron-left"></i>
                        </a>
                    </div>
                    <!--End Controls-->

                    <!--Carousel Wrapper-->
                    <div class="col">
                        <div class="carousel slide carousel-multi-item pt-2 pt-md-0" id="multi-item-example"
                            data-bs-ride="carousel">
                            <!--Slides-->
                            <div class="carousel-inner product-links-wap" role="listbox">

                                <div class="carousel-item active">
                                    <div class="row">
                                        <div class="col-3 p-md-5">
                                            <a href="#"><img class="img-fluid brand-img"
                                                    src="https://xxivstore.com/wp-content/uploads/2023/11/Nuoc-hoa-Clive-Christian.png"
                                                    alt="Brand Logo"></a>
                                        </div>
                                        <div class="col-3 p-md-5">
                                            <a href="#"><img class="img-fluid brand-img"
                                                    src="https://xxivstore.com/wp-content/uploads/2023/11/Nuoc-hoa-Xerjoff.png"
                                                    alt="Brand Logo"></a>
                                        </div>
                                        <div class="col-3 p-md-5">
                                            <a href="#"><img class="img-fluid brand-img"
                                                    src="https://xxivstore.com/wp-content/uploads/2022/04/Orto-Parisi.png"
                                                    alt="Brand Logo"></a>
                                        </div>
                                        <div class="col-3 p-md-5">
                                            <a href="#"><img class="img-fluid brand-img"
                                                    src="https://xxivstore.com/wp-content/uploads/2022/08/Hang-nuoc-hoa-Zoologist.png"
                                                    alt="Brand Logo"></a>
                                        </div>
                                    </div>
                                </div>
                                <!--End First slide-->

                                <!--Second slide-->
                                <div class="carousel-item">
                                    <div class="row">
                                        <div class="col-3 p-md-5">
                                            <a href="#"><img class="img-fluid brand-img"
                                                    src="https://xxivstore.com/wp-content/uploads/2023/11/Logo-Carner-Barcelnoa.png"
                                                    alt="Brand Logo"></a>
                                        </div>
                                        <div class="col-3 p-md-5">
                                            <a href="#"><img class="img-fluid brand-img"
                                                    src="https://xxivstore.com/wp-content/uploads/2023/11/Nuoc-hoa-Ex-Nihilo.png"
                                                    alt="Brand Logo"></a>
                                        </div>
                                        <div class="col-3 p-md-5">
                                            <a href="#"><img class="img-fluid brand-img"
                                                    src="https://xxivstore.com/wp-content/uploads/2021/07/Nasomatto.png"
                                                    alt="Brand Logo"></a>
                                        </div>
                                        <div class="col-3 p-md-5">
                                            <a href="#"><img class="img-fluid brand-img"
                                                    src="https://xxivstore.com/wp-content/uploads/2024/01/logo-roja-parfum-1.png"
                                                    alt="Brand Logo"></a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <!--End Slides-->
                        </div>
                    </div>
                    <!--End Carousel Wrapper-->

                    <!--Controls-->
                    <div class="col-1 align-self-center">
                        <a class="h1" href="#multi-item-example" role="button" data-bs-slide="next">
                            <i class="text-light fas fa-chevron-right"></i>
                        </a>
                    </div>
                    <!--End Controls-->
                </div>
            </div>
        </div>
    </div>
</section>
<!--End Brands-->


<!-- Start Footer -->

<!-- End Footer -->

<!-- Start Script -->
<script src="../view/666shop/assets/js/jquery-1.11.0.min.js"></script>
<script src="../view/666shop/assets/js/jquery-migrate-1.2.1.min.js"></script>
<script src="../view/666shop/assets/js/bootstrap.bundle.min.js"></script>
<script src="../view/666shop/assets/js/templatemo.js"></script>
<script src="../view/666shop/assets/js/custom.js"></script>
<script>
    document.getElementById('sort-select').addEventListener('change', function () {
        const sortValue = this.value;
        const iddm = <?= json_encode($iddm) ?>; // ID danh mục
        const currentPage = <?= json_encode($page) ?>; // Trang hiện tại

        // Tạo URL mới dựa trên các giá trị hiện tại
        let url = `index.php?act=shop&id=${iddm}&page=${currentPage}&sort=${sortValue}`; // Bao gồm 'sort' mới

        // Chuyển hướng tới URL mới
        window.location.href = url;
    });




</script>
<!-- End Script -->