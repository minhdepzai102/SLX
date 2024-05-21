<?php
try {
    $conn = connectdb();
    // Thiết lập chế độ lỗi để hiển thị các exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Kết nối thất bại: " . $e->getMessage());
}

// Bước 2: Truy vấn sản phẩm theo ID
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0; // Ensure product_id is an integer
$sql = "SELECT * FROM tbl_sanpham WHERE id = :product_id";
try {
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Lỗi truy vấn: " . $e->getMessage());
}
?>

<!-- Modal -->
<div class="modal fade bg-white" id="templatemo_search" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="w-100 pt-1 mb-5 text-right">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="" method="get" class="modal-content modal-body border-0 p-0">
            <div class="input-group mb-2">
                <input type="text" class="form-control" id="inputModalSearch" name="q" placeholder="Search ...">
                <button type="submit" class="input-group-text bg-success text-light">
                    <i class="fa fa-fw fa-search text-white"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Open Content -->
<section class="bg-light">
    <?php
    if ($product) {
        echo '<div class="container pb-5">
            <div class="row">
                <div class="col-lg-5 mt-5">
                    <div class="card mb-3">
                        <img class="card-img img-fluid" src="../../admin/uploads/' . htmlspecialchars($product['img'], ENT_QUOTES, 'UTF-8') . '" alt="' . htmlspecialchars($product['tensanpham'], ENT_QUOTES, 'UTF-8') . '">
                    </div>
                </div>
                <div class="col-lg-7 mt-5">
                    <div class="card">
                        <div class="card-body">
                            <h1 class="h2">' . htmlspecialchars($product['tensanpham'], ENT_QUOTES, 'UTF-8') . '</h1>
                            <p class="h3 py-2">' . number_format($product['gia'], 0, ',', '.') . ' VND</p>
                            <ul class="list-inline">
                                <li class="list-inline-item">
                                    <h6>Brand:</h6>
                                </li>
                                <li class="list-inline-item">
                                    <p class="text-muted"><strong>' . htmlspecialchars($product['thuonghieu'], ENT_QUOTES, 'UTF-8') . '</strong></p>
                                </li>
                            </ul>
                            <h6>Description:</h6>
                            <p>' . htmlspecialchars($product['mota'], ENT_QUOTES, 'UTF-8') . '</p>
                            <form action="index.php?act=addcart" method="post">
                                <input type="hidden" name="product_id" value="' . htmlspecialchars($product['id'], ENT_QUOTES, 'UTF-8') . '">
                                <input type="hidden" name="product_name" value="' . htmlspecialchars($product['tensanpham'], ENT_QUOTES, 'UTF-8') . '">
                                <input type="hidden" name="product_image" value="' . htmlspecialchars($product['img'], ENT_QUOTES, 'UTF-8') . '">
                                <input type="hidden" name="product_price" value="' . htmlspecialchars($product['gia'], ENT_QUOTES, 'UTF-8') . '">
                                <input type="hidden" name="product_quantity" value="1"> <!-- Số lượng mặc định là 1 -->
                                <div class="row pb-3">
                                    
                                    
                                    <div class="col d-grid">
                                        <button type="submit" class="btn btn-success btn-lg" name="addtocart">Add To Cart</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>';
    }
    ?>

    <!-- Close Content -->

    <!-- Start Script -->
    <script src="../view/666shop/assets/js/jquery-1.11.0.min.js"></script>
    <script src="../view/666shop/assets/js/jquery-migrate-1.2.1.min.js"></script>
    <script src="../view/666shop/assets/js/bootstrap.bundle.min.js"></script>
    <script src="../view/666shop/assets/js/templatemo.js"></script>
    <script src="../view/666shop/assets/js/custom.js"></script>
    <!-- End Script -->

    <!-- Start Slider Script -->
    <script src="../view/666shop/assets/js/slick.min.js"></script>
    <script>
        $('#carousel-related-product').slick({
            infinite: true,
            arrows: false,
            slidesToShow: 4,
            slidesToScroll: 3,
            dots: true,
            responsive: [{
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 3
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 3
                    }
                }
            ]
        });

        // Quantity control
        var btnMinus = document.getElementById("btn-minus");
        var btnPlus = document.getElementById("btn-plus");
        var varValue = document.getElementById("var-value");

        btnMinus.addEventListener("click", function () {
            var currentValue = parseInt(varValue.innerText);
            if (currentValue > 1) {
                varValue.innerText = currentValue - 1;
            }
        });

        btnPlus.addEventListener("click", function () {
            var currentValue = parseInt(varValue.innerText);
            varValue.innerText = currentValue + 1;
        });
    </script>
    <!-- End Slider Script -->

