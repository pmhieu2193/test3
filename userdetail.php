<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý User</title>
    <link rel="stylesheet" href="css/sigup.css">
    <link rel="stylesheet" href="css/addProduct.css">
</head>
<body>
    <img src="img/loader.gif" class="loader" alt="">

    <div class="alert-box">
        <img src="img/error.png" class="alert-img" alt="">
        <p class="alert-msg">Lỗi</p>
    </div>

    <img src="img/dark-logo.png" class="logo" alt="">

    <div class="form">
    <?php
        if (!isset($_POST["getUser"])) {
        include("connection.php");
        session_start();
        if (isset($_GET['error'])) { 
            echo '<h3>'.$_GET['error'].'</h3>';
        }
        $id=$_REQUEST["id"];
        $filter = ['_id' => new MongoDB\BSON\ObjectId($id)];
        $collection = $database -> selectCollection("user");
        try{
            $user = $collection -> findOne($filter);
            $date1 = $user->tao_luc;
            $datecreate = new DateTime($date1->toDateTime()->format('Y-m-d H:i:s'));
            $date2 = $user->check_in;
            $datecheckin = new DateTime($date2->toDateTime()->format('Y-m-d H:i:s'));
            $date3 = $user->ngay_sinh;
            $birthday = new DateTime($date3->toDateTime()->format('Y-m-d H:i:s'));
            //giờ làm trang chi tiết user nào
            //tên, ngày sinh,....
            echo '
                <h3>Thông tin mặc định</h3><br>
                <div style="background: orange; border-radius: 5px; padding: 20px">
                <a>Id User: <a><h3>'.$id.'</h3><br>
                <a>Email</a><h3>'.$user->email.'</h3><br>
                <p>Tạo lúc: '.$datecreate->format('d-m-Y').'</p>
                <p>Check in gần nhất: '.$datecheckin->format('d-m-Y').'</p></div><br>
                <h3>Tuỳ chỉnh Thông tin cá nhân</h3><br><br>
                <div style="background: orange; border-radius: 5px; padding: 20px">
                <form action="xu_ly_user.php" type="post">
                <a>Tên</a><input type="text" name="ten" placeholder="Tên" value="'.$user->ten.'">
                <a>CCCD</a><input type="text" name="cccd" placeholder="Căn cước công dân" value="'.$user->cccd.'">
                <a>Số điện thoại</a><input type="number" name="number" placeholder="Số điện thoại" value="'.(string)$user->sdt.'">
                <a>Địa chỉ<input type="text" name ="address" placeholder="Địa chỉ" value="'.$user->dia_chi.'"></textarea>
                <a>Mật khẩu</a><input type="text" name="pass" placeholder="Mật khẩu/ chỉ được xem không, được đổi nhé, br" value="'.$user->mat_khau.'">
                </div>
                <br><h3>Tuỳ chỉnh Thông tin tài khoản</h3><br><br>
                <div style="background: orange; border-radius: 5px; padding: 20px">
                <label for="status">Tuỳ chỉnh trạng thái tài khoản: </label>
                <select name="status" id="status" >';
                    $status = $user->trang_thai_tai_khoan;
                    if ($status==-1) echo '<option value="-1" selected="selected">Đã từ chối</option>';
                    else{
                        if ($status==0) echo '<option value="0" selected="selected">Chờ xác nhận (Khách vãng lai)</option>';
                        else echo '<option value="0">Đặt tài khoản về tài khoản khách</option>';
                        if ($status==1) echo '<option value="1" selected="selected">Đã xác nhận</option>';
                        else echo '<option value="1">Đã xác nhận</option>';
                        if ($status==2) echo '<option value="2" selected="selected">Khoá tài khoản</option>';
                        else echo '<option value="2">Khoá tài khoản</option>';
                    }
                echo '
                </select>
                <label>
                    <a> Giới tính: </a>
                    <input type="checkbox" name="gender" value="1" onchange="uncheckOther(this)" class="checkbox"'; if($user->gioi_tinh==1) echo 'checked'; echo' ><a> Nam</a>
                    <input type="checkbox" name="gender" value="0" onchange="uncheckOther(this)" class="checkbox"'; if($user->gioi_tinh==0) echo 'checked'; echo' ><a> Nữ</a>
                </label>
                <br>
                <a>Ngày sinh: '.$birthday->format('d-m-Y').'</a><br><br><input type="date" autocomplete="off" name="date">
                <br>
                <br><label for="rank">Cấp bậc: </label>
                <select name="rank" id="rank">
                    <option value="1">Sinh viên</option>
                    <option value="2">Học sinh</option>
                    <option value="3">Công chức</option>
                    <option value="4">Khách vãng lai</option>
                </select>
                <br><br>
                
            </div><br>
            <input type="checkbox" class="checkbox" id="tac" name="tac" value="1">
            <label for="tac">Tôi đã kiểm tra kĩ thông tin</label></div>

            <div class="buttons">
                <input type="hidden" name="id" value="'.$id.'">';
                if($status != -1){
                    echo '<button type="submit" name="action" class="btn" id="add-btn" value="update">Lưu chỉnh sửa</button>';
                }
                echo '
                <button type="submit" name="action" class="btn" id="add-btn" value="delete" style="background-color: red;">Xoá tài khoản</button>
                <button class="btn" id="save-btn"><a href="user.php">Huỷ</a></button>
    </div></form>';
        }
        catch (Exception $e) {
            echo 'Không tìm thấy User!';
        }

}

?>
    <script src="js/addProduct.js"></script>
    <script>
        function uncheckOther(checkbox) {
            var checkboxes = document.getElementsByName("gender");
            checkboxes.forEach(function(currentCheckbox) {
            if (currentCheckbox !== checkbox) {
                currentCheckbox.checked = false;
            }
            });
        }
    </script>
    
</body>
</html>