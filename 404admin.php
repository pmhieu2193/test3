<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page not found - User Lost</title>

    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="css/404.css">    
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="css/cart.css">
    <link rel="stylesheet" href="css/search.css">
    <link rel="stylesheet" href="css/sigup.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <img src="img/dark-logo.png" class="logo" alt="">

    <?php 
    include("navadmin.php")
    ?>
    <?php if (isset($_GET['error'])) { ?>
    <a><?php echo $_GET['error']; ?><a>
    <?php } ?>
    <img src="img/404.png" class="four-0-four-image" alt="">
    <p class="four-0-four-msg">Trang bạn tìm hiện không khả dụng. Quay lại  <a href="checkin.php">Trang chủ admin</a></p>

    <script src="js/nav.js"></script>
    
</body>
</html>