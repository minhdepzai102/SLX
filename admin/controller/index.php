<?php
session_start(); // Bắt đầu session
ob_start(); // Bắt đầu bộ đệm đầu ra
if (isset($_SESSION["role"]) && ($_SESSION["role"] == 1)) {
    // Bao gồm các cấu hình và tệp cần thiết
    include ("../../config.php");
    include ("../model/danhmuc.php");
    include ("../model/sanpham.php");
    include ("../view/header.php");
    include ("../model/order_functions.php");

    // Xử lý dựa trên tham số 'act' trong URL
    if (isset($_GET['act'])) {
        switch ($_GET['act']) {
            case 'danhmuc':
                // Nhận yêu cầu và xử lý
                // Lấy danh sách danh mục
                $kq = getall_dm(); // Gọi hàm lấy tất cả danh mục
                include ('../view/danhmuc.php'); // Bao gồm tệp hiển thị danh mục
                break;

            case 'taikhoan':
                include ('../view/taikhoan.php'); // Bao gồm tệp hiển thị tài khoản
                break;

            case 'donhang':
                if (isset($_GET['deleteorder'])) {  // Kiểm tra xem tham số deleteorder có được đặt không
                    if (isset($_GET['deleteorder'])) {  // Kiểm tra xem ID của đơn hàng cần xóa có được cung cấp không
                        $orderId = $_GET['deleteorder'];
                        // Xử lý việc xóa đơn hàng
                        $kq = deleteOrder($orderId);
                        header("Location: index.php?act=donhang");
                        return $kq;
                    } else {
                        echo "Không có ID đơn hàng được chỉ định để xóa.";
                    }
                } else {
                    // Mã hiện tại của bạn để hiển thị đơn hàng
                    if (isset($_GET['idorder'])) {  // Kiểm tra có ID đơn hàng không
                        $idorder = intval($_GET['idorder']);
                        $orderDetails = getOrderDetails($idorder);  // Lấy chi tiết đơn hàng

                        if ($orderDetails) {
                            include ('../view/order_details.php');  // Bao gồm tệp hiển thị chi tiết đơn hàng
                        } else {
                            echo "Không tìm thấy chi tiết đơn hàng.";
                        }
                    } else {
                        $orders = getAllOrders();  // Lấy danh sách đơn hàng
                        include ('../view/donhang.php');  // Bao gồm tệp hiển thị đơn hàng
                    }
                }
                break;



            case 'thoat':
                unset($_SESSION['role']);
                header("Location: login.php");
                break;
            case 'sanpham_add':
                if (isset($_POST["themoi"]) && $_POST["themoi"]) {
                    // Các dữ liệu từ biểu mẫu
                    $iddm = $_POST['iddm']; // ID Danh mục
                    $tensp = $_POST['tensp']; // Tên sản phẩm
                    $thuonghieu = $_POST['thuonghieu']; // Tên sản phẩm
                    $gia = $_POST['gia']; // Giá
                    $dungtich = $_POST['dungtich']; // Dung tích
                    $mota = $_POST['mota'];

                    // Khởi tạo biến ảnh rỗng
                    $img = "";

                    // Kiểm tra xem tệp hình ảnh có được tải lên không
                    if (isset($_FILES['hinh']) && $_FILES['hinh']['name'] != "") {
                        $img = $_FILES['hinh']['name'];

                        // Xử lý tải lên tệp tin
                        $target_dir = "../uploads/"; // Thư mục đích
                        if (!is_dir($target_dir)) {
                            mkdir($target_dir, 0777, true); // Tạo thư mục nếu chưa tồn tại
                        }

                        $target_file = $target_dir . basename($img); // Đường dẫn tệp tin

                        if (!move_uploaded_file($_FILES['hinh']['tmp_name'], $target_file)) {
                            echo "File upload error."; // Thông báo lỗi nếu tải lên thất bại
                            $img = ""; // Đặt lại giá trị ảnh nếu có lỗi
                        }
                    }

                    // Chèn dữ liệu vào cơ sở dữ liệu
                    insert_sanpham($iddm, $tensp, $thuonghieu, $img, $gia, $dungtich, $mota);

                    // Bao gồm giao diện hoặc xử lý logic tiếp theo
                    include ('../view/sanpham.php');
                }
                header('Location: index.php?act=sanpham');
                break;

            case 'donhangchuahoanthanh':
                include ("../view/donhang.php");
                break;
            case 'sanpham':

                //load dsdm
                $dsdm = getall_dm();
                //load dssp
                $kq = getall_sanpham();
                include ('../view/sanpham.php'); // Bao gồm tệp hiển thị sản phẩm
                break;

            case 'deletedm':
                // Xóa dữ liệu danh mục theo ID
                if (isset($_GET['id'])) {
                    $id = $_GET['id'];
                    deletedm($id); // Gọi hàm xóa danh mục theo ID
                }else{
                    $errordeldm = 'Vui lòng xóa sản phẩm của danh mục này trước khi xóa danh mục';
                    header("Location: index.php?act=danhmuc");
                    exit(); // Dừng việc thực hiện mã PHP tiếp theo
                }
                $dsdm = getall_dm();
                //load dssp
                $kq = getall_sanpham();
                include ('../view/danhmuc.php'); // Bao gồm lại tệp hiển thị danh mục
                header('Location: index.php?act=danhmuc');
                break;
            case 'updateform':

                if (isset($_GET['id'])) {
                    $id = $_GET['id'];
                    $kqone = getonedm($id);
                    $kq = getall_dm();
                    include ('../view/updatedm_form.php');
                }


                if (isset($_POST['id'])) {
                    $id = $_POST['id'];
                    $tendm = $_POST['tendm'];
                    updatedm($id, $tendm);
                    $kq = getall_dm();
                    include ('../view/danhmuc.php');
                }
                break;
            case 'updatedm':
                if (isset($_POST['id'])) {
                    $id = $_POST['id'];
                    $tendm = $_POST['tendm'];
                    $uutien = $_POST['uutien'];
                    $new_img = "";

                    // Check if a new file is uploaded
                    if (isset($_FILES['new_hinh']) && $_FILES['new_hinh']['name'] != "") {
                        $new_img = $_FILES['new_hinh']['name'];
                        $target_dir = "../uploads/";
                        $target_file = $target_dir . basename($new_img);

                        // Move the uploaded file to the destination
                        if (!move_uploaded_file($_FILES['new_hinh']['tmp_name'], $target_file)) {
                            echo "File upload error.";
                            $new_img = ""; // Reset the new image variable if upload fails
                        }
                    }

                    // Update the category with new details
                    updatedm($id, $tendm, $new_img, $uutien);
                }
                $kq = getall_dm();
                include ('../view/danhmuc.php');
                break;


            case 'adddm':
                if (isset($_POST['themoi']) && $_POST['themoi']) {
                    $tendm = $_POST['tendm'];
                    $uutien = $_POST['uutien'];
                    $img = "";

                    // Check if a file is uploaded
                    if (isset($_FILES['hinh']) && $_FILES['hinh']['name'] != "") {
                        $img = $_FILES['hinh']['name'];
                        $target_dir = "../uploads/"; // Destination directory
                        $target_file = $target_dir . basename($img); // Destination path

                        // Move the uploaded file to the destination
                        if (!move_uploaded_file($_FILES['hinh']['tmp_name'], $target_file)) {
                            echo "File upload error."; // Display error message if upload fails
                            $img = ""; // Reset the image variable if there's an error
                        }
                    }

                    themdm($tendm, $img, $uutien);
                }
                $kq = getall_dm();
                include ('../view/danhmuc.php');
                break;


            case 'updatespform':
                $dsdm = getall_dm();
                if (isset($_GET['id']) && $_GET['id'] > 0) {
                    $spct = getonesp($_GET['id']);
                }

                $kq = getall_sanpham();
                include ('../view/updatesp_form.php');
                break;
            // index.php
            case 'home':
                include ('../view/home.php');
                break;
            case 'searchsp':
                // Check if the keyword is provided
                if (isset($_GET['keyword'])) {
                    $keyword = $_GET['keyword'];
                    // Perform the search // Implement this function in your view file
                }
                // Include the view file
                include ('../view/sanpham.php');
                break;
            case 'WEB':
                header('Location: ../../user/controller/index.php');
                break;
            case 'sanpham_update':
                if (isset($_POST['capnhat']) && $_POST['capnhat']) {
                    // Lấy dữ liệu từ biểu mẫu
                    $idsp = isset($_POST['idsp']) ? $_POST['idsp'] : 0; // ID của sản phẩm cần cập nhật
                    $iddm = isset($_POST['iddm']) ? $_POST['iddm'] : 0; // ID danh mục
                    $tensp = isset($_POST['tensp']) ? $_POST['tensp'] : ''; // Tên sản phẩm
                    $thuonghieu = isset($_POST['thuonghieu']) ? $_POST['thuonghieu'] : ''; // Tên sản phẩm
                    $gia = isset($_POST['gia']) ? $_POST['gia'] : 0; // Giá sản phẩm
                    $mota = isset($_POST['mota']) ? $_POST['mota'] : 0; // Giá sản phẩm
                    $dungtich = isset($_POST['dungtich']) ? $_POST['dungtich'] : ''; // Dung tích

                    // Xử lý tải hình ảnh
                    $img = '';
                    if (isset($_FILES['hinh']) && $_FILES['hinh']['name'] != '') {
                        $img = $_FILES['hinh']['name'];
                        $target_dir = "../uploads/";
                        if (!is_dir($target_dir)) {
                            mkdir($target_dir, 0777, true); // Tạo thư mục nếu chưa tồn tại
                        }
                        $target_file = $target_dir . basename($img);
                        if (!move_uploaded_file($_FILES['hinh']['tmp_name'], $target_file)) {
                            echo "File upload error.";
                            $img = ''; // Reset nếu tải ảnh thất bại
                        }
                    }

                    // Cập nhật sản phẩm trong cơ sở dữ liệu
                    update_sanpham($idsp, $iddm, $tensp, $thuonghieu, $img, $gia, $dungtich, $mota); // Hàm cập nhật sản phẩm

                    // Lấy lại danh sách sản phẩm sau khi cập nhật
                    $kq = getall_sanpham();
                    include ('../view/sanpham.php'); // Bao gồm tệp hiển thị danh sách sản phẩm
                }
                break;


            case 'deletesp':
                if (isset($_GET['id'])) { // Kiểm tra xem ID có được cung cấp không
                    $id = intval($_GET['id']); // Chuyển thành số nguyên để tránh các lỗi bảo mật

                    try {
                        // Gọi hàm xóa sản phẩm
                        deletesp($id);
                        header("Location: index.php?act=sanpham"); // Chuyển hướng sau khi xóa thành công
                    } catch (PDOException $e) {
                        echo "Error deleting product: " . $e->getMessage(); // Thông báo lỗi
                    }
                } else {
                    echo "No ID specified."; // Nếu không có ID, thông báo lỗi
                }
                break;



            default:
                break;
        }
    } else {
        echo "Không có hành động được chỉ định"; // Xử lý trường hợp không có 'act'
    }

    // Bao gồm tệp footer
    include ("../view/footer.php");
} else {
    header("Location: login.php");
    exit(); // Dừng mã sau khi chuyển hướng
}

ob_end_flush(); // Kết thúc bộ đệm đầu ra và gửi dữ liệu ra
?>