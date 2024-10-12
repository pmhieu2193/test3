<!DOCTYPE php>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ sách</title>

    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="css/cart.css">

</head>
<body>
    
    <?php include("nav.php");?>
    <a class="back" onclick="location.href='index.php'">&larr; Mượn thêm sách khác</a> 
    <br>
    <a class="back"><?php if (isset($_GET['error'])) { 
                 echo $_GET['error'].'</a>';} ?> 
    <br>
    <?php if(isset( $_SESSION["max"])) { echo '<a class="back">Số sách được mượn còn lại là:'.$_SESSION["max"].' Bạn chỉ được thêm số lượng sách tương ứng vào giỏ</a>';
    echo '<div class="small-container cart-page">
    <h2>Giỏ sách của tôi<h2>
    <table>
        <tr>
            <th>Sản phẩm</th>
            <th>Trạng thái</th>
            <th style="width: 130px">Tương tác</th>
        </tr>'; }
    else{
        echo '<a class="back">Vui lòng đăng nhập để sử dụng chức năng này</a>';
    }?>
            <?php
            if(isset($_SESSION["max"])){
                $j=(int)$_SESSION["max"];
                $count=0;
                for ($i = 0; $i < $j; $i++) {
                    if(!empty($_SESSION["book$i"])){
                        $count++;
                        $filter = ['_id' => new MongoDB\BSON\ObjectId($_SESSION["book$i"])];
                        $collection = $database ->selectCollection('sach');
                        $sach= $collection -> findOne($filter);
                        echo'
                        <tr>
                            <td>
                                <div class="cart-info">
                                    <img src="'.$sach->anh_bia.'">
                                    <div>
                                        <h3>'.$sach->ten_sach.'</h3>
                                        <small>'.$sach->mo_ta.'</small>
                                    </div>
                                </div>
                            <td>';
                            if($sach->so_luong>0){
                                echo 'Có thể mượn';
                            }
                            else{
                                echo 'Đã hết sách, không thể mượn';
                            }
                            echo '
                            </td>
                            <td>
                                <a class="link-text" onclick="location.href=';
                                echo "'book.php?_id=".(string)$_SESSION["book$i"]."'";
                                echo '">Xem chi tiết</a>
                                <br>
                                <form action="remove_book.php" type="post"><input type="hidden" name="id_remove" value="'.(string)$_SESSION["book$i"].'"><button type="submit" class="btn-remove" name="btn_remove">Xoá sách khỏi giỏ</button></form></td>
                        </tr>';
                    }}}
                else{
                    echo "Không có sách";}
            ?>
        </div>
    </table>
    <div class="total-price">
    <?php if(isset($_SESSION["max"])&&$count>0&&$_SESSION["trang_thai_tai_khoan"]!=0){
        $today= new DateTime;
        echo '
        <table>
            <tr>
                <td>Số sách</td>
                <td>'.$count.'</td>
            </tr>
            <tr>
                <td>Ngày mượn:</td>
                <td>'.$today->format("d-m-Y").'</td>
            </tr>
            <tr>
                <td>Ngày phải trả chung:</td>
                <td>'.$today->modify('+7 days')->format("d-m-Y").'</td>
            </tr>
            <tr>
                <td></td>
                <td><form action="tao_YCM.php" type="post"><input type="hidden" name ="count" value="'.$count.'"><button type="submit" name="tao_YCM" class="btn-cart">Yêu cầu mượn</td>
            </tr>
        </table>
    </div>';
    }
    ?>
    <script src="js/nav.js"></script>
</body>
</html>