<div class="main">
    <h2>Sửa Thông Tin Sản Phẩm</h2>
    <form action="index.php?act=sanpham_update" method="post" enctype="multipart/form-data">
        <!-- Chứa ID sản phẩm để biết sản phẩm nào đang được cập nhật -->
        <input type="hidden" name="idsp" value="<?php echo $spct[0]['id']; ?>">

        <select name="iddm">
            <option value="0">Chọn danh mục</option>
            <?php
            $iddmcur = $spct[0]['iddm'];
            foreach ($dsdm as $dm) {
                $selected = ($dm['id'] == $iddmcur) ? "selected" : ""; // Chọn danh mục hiện tại
                echo '<option value="' . $dm['id'] . '" ' . $selected . '>' . $dm['tendm'] . '</option>';
            }
            ?>
        </select>

        <!-- Tên sản phẩm với giá trị mặc định -->
        <input type="text" name="tensp" placeholder='Tên sản phẩm' value="<?php echo $spct[0]['tensanpham']; ?>">

        <!-- Thương hiệu -->
        <input type="text" name="thuonghieu" placeholder='Thương hiệu' value="<?php echo $spct[0]['thuonghieu']; ?>">

        <!-- Hình ảnh (tùy chọn) -->
        <input type="file" name="hinh">

        <!-- Giá sản phẩm -->
        <input type="text" name="gia" placeholder='Giá' value="<?php echo $spct[0]['gia']; ?>">

        <!-- Mô tả -->
        <input type="text" name="mota" placeholder='Mô tả' value="<?php echo $spct[0]['mota']; ?>">

        <!-- Dung tích -->
        <input type="text" name="dungtich" placeholder='Dung tích' value="<?php echo $spct[0]['dungtich']; ?>">

        <!-- Nút cập nhật -->
        <input type="submit" name="capnhat" value="Cập nhật">
        <!-- Nút hủy -->
        <a href="index.php?act=sanpham" class="cancel-button">Hủy</a>
    </form>

    <br>
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
                        <!-- Thay đổi cách hiển thị mô tả -->
<td><?php echo substr(' . $sp['mota'] . ', 0, 50); ?>...</td>

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