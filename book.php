<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product - </title>

    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="css/product.css">
</head>
<body>
    <?php include("nav.php");?>

    <?php 
    if(isset($_GET["_id"])){
        $id=$_GET["_id"];
        $collection = $database->selectCollection('sach');
        try {
            $filter = ['_id' => new MongoDB\BSON\ObjectId($id)];
            if($filter){
                $book= $collection->findOne($filter);
                if($book){
                    $collection2 = $database->selectCollection('the_loai');
                    $matl=$book->ma_the_loai;
                    $tentheloai= $collection2->findOne(['ma_the_loai'=> $matl]);
                    $collection3 = $database->selectCollection('nha_xuat_ban');
                    $manxb=$book->ma_nxb;
                    $tennxb= $collection3->findOne(['ma_nxb'=> $manxb]);
                    
                    echo '<section class="product-details">';
                    echo '<img src='.$book->anh_bia.' class="image-slider">';
                    echo '<div><h2 class="product-brand">'.$book->ten_sach.'</h2>';
                    echo '<p class="product-short-des">'.$book->mo_ta.'</p>';
                    echo '<p>Vị trí: '.$book->vi_tri.'</p>';
                    echo '<p> Tác giả: '.$book->tac_gia.'</p>';
                    echo '<p> Ngôn ngữ:' .$book->ngon_ngu.'</p>';
                    echo '<p> Năm xuất bản: '.$book->nam_xuat_ban.'</p>';
                    echo '<p> Thể loại: '. $tentheloai->ten_the_loai.'</p>';
                    echo '<p> rank: '.$book->ma_rank.'</p>';
                    echo '<p> Nhà xuất bản: '.$tennxb->ten_nxb.'</p>';
                    echo '<br>';
                    if($book->so_luong>0){
                        echo '<h3 style ="color : green"> Trạng thái: Còn sách </h3>';
                        if(isset ($_SESSION['ma_rank'])){
                            $ma_rank=$_SESSION['ma_rank'];
                            if($ma_rank!=-1&&isset($_SESSION['_id'])){
                                if($_SESSION['trang_thai_tai_khoan']!=0){
                                    echo '<div><form action="xu_ly_sach.php" type="post"><input name="id_book" type="hidden" value="'.$book->_id.'"><button type="submit" name="addToCart" class="btn cart-btn">Thêm vào giỏ sách</button></form>';
                                }
                                if($book->so_luong> 0)
                                    echo '<form action="action_wishlist.php" type="post"><input name="id_book" type="hidden" value="'.$book->_id.'"><button type="submit" name="addToWishList" class="btn cart-btn">Thêm vào danh sách yêu thích &#x2764;</button></form></div>';
                            }
                            else{
                                echo 'Bạn chưa thể mượn sách này, hãy kích hoạt tài khoản trước';
                            }
                        }
                    }
                    else{
                        echo '<h3 style ="color : red"> Trạng thái: Sách đã bị mượn hết </h3>';
                    }
                    echo '</section> </form>';
                }
            }

            } catch (Exception $e) {
                header("Location: 404.php");
                exit();
            }
        
        }else{
            echo 'không tìm thấy sách';
        }

    ?>
    <footer></footer>

    <script src="js/nav.js"></script>
    <script src="js/footer.js"></script>
    <script src="js/home.js"></script>
    <script src="js/product.js"></script>
</body>
</html>
