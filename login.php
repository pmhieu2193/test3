<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Armor: Đăng nhập</title>

    <link rel="stylesheet" href="css/sigup.css">
</head>
<body>

    <img src="img/loader.gif" class="loader" alt="">

    <div class="alert-box">
        <img src="img/error.png" class="alert-img" alt="">
        <p class="alert-msg"></p>
    </div>

    <div class="container">
        
        <img src="img/dark-logo.png" class="logo "alt="">
        <form action="login1.php" method="post">
            <?php if (isset($_GET['error'])) { ?>
                <a><?php echo $_GET['error']; ?><a>
            <?php } ?>
            <input type="text" autocomplete="off" name="email" placeholder="email">
            <input type="password" autocomplete="off" name="password" placeholder="Mật Khấu">
            <button type="submit" class="submit-btn">Đăng  nhập</button>
        </form>
        <a href="sigup.php" class="link">chưa có tài khoản? Đăng ký</a>
	<a href="index.php" class="link">quay lại trang chủ</a>
    </div>

    <!--<script src="js/form.js"></script> -->

</body>
</html>