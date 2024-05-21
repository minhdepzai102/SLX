<?php
// uploading image on submit
if(isset($_POST['submit'])){ 
    upload_image(); 
}

function upload_image(){
    $uploadTo = "../uploads/"; 
    $allowedImageType = array('jpg','png','jpeg','gif','pdf','doc');
    $imageName = $_FILES['hinh']['name'];
    $tempPath=$_FILES["hinh"]["tmp_name"];
   
    $basename = basename($imageName);
    $originalPath = $uploadTo.$basename; 
    $imageType = pathinfo($originalPath, PATHINFO_EXTENSION); 
    if(!empty($imageName)){ 
       if(in_array($imageType, $allowedImageType)){ 
         // Upload file to server 
         if(move_uploaded_file($tempPath,$originalPath)){ 
            echo $imageName." was uploaded successfully";
           // write here sql query to store image name in database
          
          }else{ 
            echo 'Image not uploaded! Try again';
          } 
      }else{
         echo $imageType." image type not allowed";
      }
   }else{  
    echo "Please select an image";
   }       
}
?>

<div class="main">
    <h2>Danh Mục</h2>
    <form action="index.php?act=adddm" method="post" enctype="multipart/form-data">
        <input type="text" name="tendm" placeholder="Tên danh mục" id=""> 
        <input type="file" name="hinh" id="image">
        <input type="text" name="uutien" placeholder="Ưu tiên" id="">
        <input type="submit" name="themoi" value="Upload">
    </form>
    <div id="preview"></div>
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
        if(isset($errordeldm)){
            echo $errordeldm;
        }
        ?>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function imagePreview(fileInput) {
        console.log('Function called');
        if (fileInput.files && fileInput.files[0]) {
            console.log('File selected');
            var fileReader = new FileReader();
            fileReader.onload = function (event) {
                console.log('FileReader onload');
                $('#preview').html('<img src="'+event.target.result+'" class="position-absolute top-0 start-100 translate-middle" style="z-index: 100; width: 100px; height: auto;">');
            };
            fileReader.readAsDataURL(fileInput.files[0]);
        } else {
            console.log('No file selected');
        }
    }
    $("#image").change(function () {
        console.log('Change event triggered');
        imagePreview(this);
    });
</script>
