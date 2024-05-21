<div class="main">
    <h2>Sản Phẩm</h2>
    <form action="index.php?act=sanpham_add" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
        <select name="iddm" id="iddm">
            <option value="0">Chọn danh mục</option>
            <?php
                if(isset($dsdm)){
                    foreach($dsdm as $dm){
                        echo '<option value="'.$dm['id'].'">'.$dm['tendm'].'</option>';
                    }
                }
            ?>
        </select>
        <input type="text" name="tensp" placeholder='Tên sản phẩm' required>
        <input type="text" name="thuonghieu" placeholder='Thương hiệu' required>
        <input type="file" name="hinh" required>
        <input type="text" name="gia" placeholder='Giá' required>
        <input type="text" name="mota" placeholder='Mô tả' required>
        <input type="text" name="dungtich" placeholder='Dung tích (ml)' required>
        <input type="submit" name="themoi" value="Thêm mới">
    </form>
    <br>
    <input class="search-input" type="text" id="searchInput" oninput="searchProductName()" placeholder="Nhập từ khóa...">

    <table>
        <tr>
            <th>STT</th>
            <th>Tên sản phẩm</th>
            <th>Thương hiệu</th>
            <th>Hình ảnh</th>
            <th>Dung tích</th>
            <th>Giá</th>
            <th>Mô tả</th>
            <th>Hành động</th>
        </tr>
        <?php
        // Biến đếm số thứ tự
        $i = 1;

        // Kiểm tra mảng kết quả
        if (isset($kq) && is_array($kq) && count($kq) > 0) {
            foreach ($kq as $sp) { // Sử dụng tên biến rõ ràng hơn
                // Hiển thị từng dòng trong bảng sản phẩm
                echo '<tr>
                        <td>' . $i . '</td>  <!-- Số thứ tự -->
                      <td>' . $sp['tensanpham'] . '</td>  <!-- Tên sản phẩm -->
                        <td>' . $sp['thuonghieu'] . '</td>  <!-- Thương hiệu -->
                        <td><img src="../uploads/' . $sp['img'] . '" alt="' . $sp['tensanpham'] . '" style="width: 100px; height: auto;"></td>  <!-- Hình ảnh -->
                        <td>' . $sp['dungtich'] . '</td>  <!-- Dung tích -->
                        <td>' . number_format($sp['gia'], 0, ',', '.') . ' VND</td>  <!-- Giá -->
                        <td data-full-description="' . $sp['mota'] . '" data-short-description="' . substr($sp['mota'], 0, 50) . '..." onmouseover="showFullDescription(this)" onmouseout="hideFullDescription(this)">' . substr($sp['mota'], 0, 50) . '...</td> <!-- Mô tả -->
                        
                        <td>
                            <a href="index.php?act=updatespform&id=' . $sp['id'] . '">Sửa</a> |
                            <a href="index.php?act=deletesp&id=' . $sp['id'] . '">Xóa</a>
                        </td>
                    </tr>';
                $i++; // Tăng biến đếm
            }
        }
        ?>
    </table>
</div>

<script>
    function validateForm() {
        var category = document.getElementById("iddm").value;
        if (category == 0) {
            alert("Vui lòng chọn danh mục trước khi thêm sản phẩm.");
            return false;
        }
        return true;
    }

    function searchProductName() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("searchInput");
        filter = input.value.toUpperCase();
        table = document.querySelector(".main table");
        tr = table.getElementsByTagName("tr");

        // Duyệt qua tất cả các dòng của bảng
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[1]; // Lấy cột chứa tên sản phẩm
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = ""; // Hiển thị dòng nếu tìm thấy từ khóa
                } else {
                    tr[i].style.display = "none"; // Ẩn dòng nếu không tìm thấy từ khóa
                }
            }
        }
    }

    function showFullDescription(element) {
        element.innerHTML = element.getAttribute('data-full-description');
    }

    function hideFullDescription(element) {
        element.innerHTML = element.getAttribute('data-short-description');
    }
</script>
