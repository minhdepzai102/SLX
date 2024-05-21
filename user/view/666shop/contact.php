


<!-- Start Content Page -->
<div class="container-fluid bg-light py-5">
    <div class="col-md-6 m-auto text-center">
        <h1 class="h1">Cảm ơn các bạn rất nhiều vì đã
            tin tưởng và lựa chọn SLX.</h1>
        <p>
        Các bạn có thể đến với SLX, tâm sự với chúng mình, cùng chia sẻ cảm nhận của các bạn về các
loại nước hoa bạn thích. với các bạn đang đắn đo hay sử dụng lần đầu cũng đừng ngại nhé,
mình sẽ cố gắng trả lời các bạn nhiều nhất, review sản phẩm tốt nhất để các bạn chọn được
hương thơm ưng ý nhất.
SLX love you!
        </p>
    </div>
</div>

<!-- Start Map -->
<!-- Start Map -->
<div id="mapid" style="width: 100%; height: 300px;"></div>
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
    integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
    crossorigin=""></script>
<script>
    var mymap = L.map('mapid').setView([10.7892159, 106.700141], 13);  // Tọa độ và mức zoom

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {  // Tầng bản đồ cơ bản
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
            '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
        maxZoom: 18,
    }).addTo(mymap);

    // Đặt điểm đánh dấu
    L.marker([10.7892159, 106.700141]).addTo(mymap)
        .bindPopup("PTIT, 11 Nguyễn Đình Chiểu, Đa Kao, Quận 1, Thành phố Hồ Chí Minh, Việt Nam")
        .openPopup();
</script>

<!-- End Map -->

<!-- Ena Map -->

<!-- Start Contact -->
<div class="container py-5">
    <div class="row py-5">
        <form class="col-md-9 m-auto" method="post" role="form">
            <div class="row">
                <div class="form-group col-md-6 mb-3">
                    <label for="inputname">Name</label>
                    <input type="text" class="form-control mt-1" id="name" name="name" placeholder="Name">
                </div>
                <div class="form-group col-md-6 mb-3">
                    <label for="inputemail">Email</label>
                    <input type="email" class="form-control mt-1" id="email" name="email" placeholder="Email">
                </div>
            </div>
            <div class="mb-3">
                <label for="inputsubject">Subject</label>
                <input type="text" class="form-control mt-1" id="subject" name="subject" placeholder="Subject">
            </div>
            <div class="mb-3">
                <label for="inputmessage">Message</label>
                <textarea class="form-control mt-1" id="message" name="message" placeholder="Message"
                    rows="8"></textarea>
            </div>
            <div class="row">
                <div class="col text-end mt-2">
                    <button type="submit" class="btn btn-success btn-lg px-3">Let’s Talk</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- End Contact -->


<!-- Start Footer -->

<!-- End Footer -->

<!-- Start Script -->
<script src="../view/666shop/assets/js/jquery-1.11.0.min.js"></script>
<script src="../view/666shop/assets/js/jquery-migrate-1.2.1.min.js"></script>
<script src="../view/666shop/assets/js/bootstrap.bundle.min.js"></script>
<script src="../view/666shop/assets/js/templatemo.js"></script>
<script src="../view/666shop/assets/js/custom.js"></script>
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
    integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
    crossorigin=""></script>
<!-- End Script -->