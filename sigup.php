<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Black Liblary : Create Account</title>

    <link rel="stylesheet" href="css/sigup.css">
</head>
<body>
    
    <img src="img/loader.gif" class="loader" alt="">

    <div class="alert-box">
        <img src="img/error.png" class="alert-img" alt="">
        <p class="alert-msg">Error message</p>
    </div>

    <div class="container">
        <img src="img/dark-logo.png" class="logo "alt="">
        <form action="sigup1.php" method="post">
            <?php if (isset($_GET['error'])) { ?>
                <a><?php echo $_GET['error']; ?><a>
            <?php } ?>
            <input type="text" autocomplete="off" name="email" placeholder="Email">
            <input type="password" autocomplete="off" name="password" placeholder="Mật khẩu">
            <input type="text" autocomplete="off" name="name" placeholder="Họ tên">
            <input type="text" autocomplete="off" name="number" placeholder="Số điện thoại">
            <input type="text" autocomplete="off" name="cccd" placeholder="Căn cước công dân nếu trên 16 tuổi">
            <input type="text" autocomplete="off" name="address" placeholder="Địa chỉ">
            <label>
                <a> Giới tính: </a>
                <input type="checkbox" name="gender" value="1" onchange="uncheckOther(this)" class="checkbox" ><a> Nam</a>
                <input type="checkbox" name="gender" value="0" onchange="uncheckOther(this)" class="checkbox" ><a> Nữ</a>
            </label>
            <br>
            <label><a>Ngày sinh</a></label>
            <input type="date" autocomplete="off" name="date" placeholder="Ngày sinh">
            <br>
            <label for="fruit">Bạn là: </label>
            <select name="rank" id="rank">
                <?php 
                $flag=0;
                include("connection.php");
                $collection = $database -> selectCollection('rank');
                $rank = $collection -> find();
                foreach($rank as $doc){
                    if($flag===0){
                        $flag=1;
                        continue;
                    }
                    else{
                        echo '<option value="'.$doc->ma_rank.'">'.$doc->ten_rank.'</option>';
                    }
                }
                ?>
            </select>
            <br>
            <input type="checkbox" checked class="checkbox" id="term-and-cond">
            <label for="term-and-cond">đồng ý <a href="">điều khoản và chính sách bảo mật</a></label>
            <button type="submit" class="submit-btn">Tạo Tài Khoản</button>
        </form>
        <a href="login.php" class="link">Đã có tài khoản? Nhấn để đăng nhập</a>
	<a href="index.php" class="link">Quay lại trang chủ</a>
    </div>

    <!-- Tại sao chỗ này lại ko thể liên kết đến file js? -->
    <script src="js/form.js"></script>
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