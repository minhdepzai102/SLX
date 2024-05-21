<footer class="bg-dark" id="tempaltemo_footer">
    <div class="container">
        <div class="row">

            <div class="col-md-4 pt-5">
                <h2 class="h2 text-success border-bottom pb-3 border-light logo">SLX Store</h2>
                <ul class="list-unstyled text-light footer-link-list">
                    <li>
                        <i class="fas fa-map-marker-alt fa-fw"></i>
                        11 Nguyễn Đình Chiểu, Đa Kao, Quận 1, Thành phố Hồ Chí Minh, Việt Nam
                    </li>
                    <li>
                        <i class="fa fa-phone fa-fw"></i>
                        <a class="text-decoration-none" href="tel:012-345-6789">012-345-6789</a>
                    </li>
                    <li>
                        <i class="fa fa-envelope fa-fw"></i>
                        <a class="text-decoration-none" href="mailto: slxstore@gmail.com"> slxstore@gmail.com</a>
                    </li>
                </ul>
            </div>

            <div class="col-md-4 pt-5">
                <h2 class="h2 text-light border-bottom pb-3 border-light">Products</h2>
                <ul class="list-unstyled text-light footer-link-list">
                    <?php $count = 0; ?>
                    <?php foreach ($categories as $category): ?>
                        <?php if ($count < 7): ?>
                            <li><a class="text-decoration-none"
                                    href="index.php?act=shop&id=<?php echo $category['id']; ?>"><?php echo $category['tendm']; ?></a>
                            </li>
                            <?php $count++; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>


            <div class="col-md-4 pt-5">
                <h2 class="h2 text-light border-bottom pb-3 border-light">Info</h2>
                <ul class="list-unstyled text-light footer-link-list">
                    <li><a class="text-decoration-none" href="index.php?act=home">Home</a></li>
                    <li><a class="text-decoration-none" href="index.php?act=about">About Us</a></li>
                    <li><a class="text-decoration-none" href="index.php?act=contact">Shop Locations</a></li>
                    <li><a class="text-decoration-none" href="index.php?act=contact">FAQs</a></li>
                    <li><a class="text-decoration-none" href="index.php?act=contact">Contact</a></li>
                </ul>
            </div>

        </div>

        <div class="row text-light mb-4">
            <div class="col-12 mb-3">
                <div class="w-100 my-3 border-top border-light"></div>
            </div>
            <div class="col-auto me-auto">
                <ul class="list-inline text-left footer-icons">
                    <li class="list-inline-item border border-light rounded-circle text-center">
                        <a class="text-light text-decoration-none" target="_blank" href="https://www.facebook.com/minhnef1206/"><i
                                class="fab fa-facebook-f fa-lg fa-fw"></i></a>
                    </li>
                    <li class="list-inline-item border border-light rounded-circle text-center">
                        <a class="text-light text-decoration-none" target="_blank" href="https://www.instagram.com/minkzuize.69/"><i
                                class="fab fa-instagram fa-lg fa-fw"></i></a>
                    </li>
                </ul>
            </div>
            <div class="col-auto">
                <label class="sr-only" for="subscribeEmail">Email address</label>
                <div class="input-group mb-2">
                    <input type="text" class="form-control border-light" id="subscribeEmail"
                        placeholder="Email address">
                    <button class="input-group-text btn-success text-light">Subscribe</button>
                </div>
            </div>
        </div>
    </div>

    <div class="w-100 bg-black py-3">
        <div class="container">
            <div class="row pt-2">
                <div class="col-12">
                    <p class="text-left text-light">
                        Copyright &copy; 2040 Company Name
                        | Designed by <a rel="sponsored" href="https://www.facebook.com/minhnef1206/" target="_blank">SLX Store</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

</footer>
<script src="https://code.jquery.com/jquery-3.6.0.min.js">

</script>
<script>
    $(document).ready(function () {
        const searchInput = $('#inputModalSearch');
        const searchResults = $('.search-results');

        searchInput.on('input', function () {
            const query = $(this).val().trim(); // Lấy giá trị tìm kiếm

            if (query.length > 0) { // Tìm kiếm sau khi có ít nhất 3 ký tự
                $.ajax({
                    url: '../view/666shop/search.php', // Đường dẫn đúng
                    type: 'GET', // Kiểm tra phương thức
                    data: { keyword: query }, // Đảm bảo dữ liệu được truyền đúng
                    dataType: 'json', // Mong đợi phản hồi dạng JSON
                    success: function (result) {
                        searchResults.empty(); // Xóa kết quả cũ
                        if (result.length > 0) {
                            result.forEach(item => {
                                const listItem = $('<li>').append(
                                    $('<a>').addClass('row text-decoration-none').attr('href', `index.php?act=shop-single&id=${item.id}`).append(
                                        // Product image with Bootstrap class for responsive layout and rounded corners
                                        $('<div>').addClass('col-3 col-xl-2 text-center').append(
                                            $('<img>').addClass('img-fluid rounded-circle').attr({
                                                src: '../../admin/uploads/' + item.img,
                                                alt: 'Product Image',
                                                style: 'max-width: 100px; max-height: 100px;', // Ensure image doesn't exceed specified size
                                            })
                                        ),
                                        // Product information with styling
                                        $('<div>').addClass('col-9 col-xl-10').append(
                                            $('<span>').addClass('h5 text-dark').text(item.tensanpham), // Product name with Bootstrap header class
                                            $('<p>').addClass('text-dark').html(`Price: ${numberFormat(item.gia, 0, '.', ',')} VND`) // Formatted price
                                        )
                                    )
                                );

                                // Custom number formatting function
                                function numberFormat(num, decimals, decimalSeparator, thousandsSeparator) {
                                    if (isNaN(num)) {
                                        return num; // Return unchanged if not a number
                                    }

                                    var fixed = num.toFixed(decimals);
                                    var parts = fixed.split('.');
                                    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousandsSeparator);

                                    return parts.join(decimalSeparator);
                                }

                                searchResults.append(listItem);
                            });
                        } else {
                            searchResults.append('<li>No results found.</li>'); // Không có kết quả
                        }
                    },
                    error: function () {
                        searchResults.empty(); // Xóa kết quả cũ
                        searchResults.append('<li>Something went wrong. Please try again.</li>'); // Thông báo lỗi
                    }
                });
            } else {
                searchResults.empty(); // Xóa kết quả khi chuỗi tìm kiếm quá ngắn
            }
        });
    });
</script>
</body>

</html>