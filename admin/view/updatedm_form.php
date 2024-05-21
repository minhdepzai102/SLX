<div class="main">
    <h2>Update Danh Mục</h2>
    <?php
    // Kiểm tra biến $kqone trước khi sử dụng để tránh lỗi
    if (isset($kqone) && is_array($kqone) && count($kqone) > 0) {
        $first_dm = $kqone[0]; // Lấy chi tiết của danh mục cần cập nhật
    } else {
        echo "Dữ liệu danh mục không hợp lệ."; // Thông báo nếu không có dữ liệu hợp lệ
    }
    ?>
    <form action="index.php?act=updatedm" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo isset($first_dm['id']) ? $first_dm['id'] : ''; ?>">
        <input type="text" name="tendm" placeholder="Tên danh mục"
            value="<?php echo isset($first_dm['tendm']) ? $first_dm['tendm'] : ''; ?>">
        <!-- New file input field for the image -->
        <input type="file" name="new_hinh">
        <input type="text" name="uutien" placeholder="Ưu tiên"
            value="<?php echo isset($first_dm['uutien']) ? $first_dm['uutien'] : ''; ?>">
        <input type="submit" name="capnhat" value="Cập nhật">
    </form>

    <br>
    <table>
        <tr>
            <th>STT</th>
            <th>Tên Danh Mục</th>
            <th>Hình ảnh hiển thị</th>
            <th>Ưu tiên</th>
            <th>Hiển thị</th>
            <th>Hành động</th>
        </tr>

        <?php
        // Khởi tạo biến $i để đếm số thứ tự
        $i = 1;

        // Kiểm tra mảng $kq và duyệt qua từng phần tử
        if (isset($kq) && is_array($kq) && count($kq) > 0) {
            foreach ($kq as $dm) {
                echo '<tr>
                        <td>' . $i . '</td>
                        <td>' . $dm['tendm'] . '</td>
                        <td><img src="../uploads/' . $dm['img'] . '" alt="' . $dm['tendm'] . '" style="width: 100px; height: auto;"></td> <!-- Hình ảnh -->
                        <td>' . $dm['uutien'] . '</td>
                        <td>' . $dm['hienthi'] . '</td>
                        <td>
                            <a href="index.php?act=updateform&id=' . $dm['id'] . '">Sửa</a>
                            <a href="index.php?act=deletedm&id=' . $dm['id'] . '">Xóa</a>
                        </td>
                    </tr>';
                $i++; // Tăng biến đếm STT
            }
        }
        ?>

    </table>
</div>