<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ARMOR-SHOP</title>
    <link rel="stylesheet" href="css/home.css">
</head>
<body>
    <?php include("nav.php"); 
    //session_start();?>
    <!--hero section-->
    <header class="hero-section">
        <div class="content">
        </div>
    </header>


    <!--cards-container-->
    <section class="product">
        <h2 class="product-category">Sách mới <img src="img/new.png"></h2>
        <button class="pre-btn"><img src="img/arrow.png" alt=""></button>
        <button class="nxt-btn"><img src="img/arrow.png" alt=""></button>
        <div class="product-container">
        <?php 
            $collection = $database->selectCollection('sach');
            $result = $collection->find(['trang_thai_sach'=>1]);
            foreach ($result as $document) {
                echo   '<div class="product-card">
                        <div class="product-image">
                        <span class="discount-tag">';?>
                        <?php if($document->so_luong>0){
                                echo '<a style ="color: green">Hiện có</a></span>';}
                            else{
                                echo '<a style ="color: green">Đã mượn hết</a></span>';
                            }
                        ?>
                        <?php echo'<img src="';?><?php echo  $document->anh_bia;?><?php echo'" class="product-thumb" alt="" onclick="location.href=';?><?php echo"'book.php?_id="?><?php echo $document->_id;?><?php echo "'"?>
                        <?php echo'">
                    </div>
                    <div class="product-info">
                        <h2 class="product-brand">'?> <?php echo $document->ten_sach; ?> <?php echo'</h2>
                        <p>'?><?php echo  $document->mo_ta; ?> <?php echo'</p>
                        <a>Tác giả: '.$document->tac_gia.'</a></span>
                    </div>
                </div>'; ?>
        <?php } ?>
            </div>
    </section>

    <section class="product">
        <h2 class="product-category">Sách mượn nhiều <img src="img/bestsell.png"></h2>
        <button class="pre-btn"><img src="img/arrow.png" alt=""></button>
        <button class="nxt-btn"><img src="img/arrow.png" alt=""></button>
        <div class="product-container">
        <?php 
            $collection = $database->selectCollection('sach');
            $result = $collection->find(['trang_thai_sach'=>1]);
            foreach ($result as $document) {
                echo   '<div class="product-card">
                        <div class="product-image">
                        <span class="discount-tag">';?>
                        <?php if($document->so_luong>0){
                                echo '<a style ="color: green">Hiện có</a></span>';}
                            else{
                                echo '<a style ="color: green">Đã mượn hết</a></span>';
                            }
                        ?>
                        <?php echo'<img src="';?><?php echo  $document->anh_bia;?><?php echo'" class="product-thumb" alt="" onclick="location.href=';?><?php echo"'book.php?_id="?><?php echo $document->_id;?><?php echo "'"?>
                        <?php echo'">
                    </div>
                    <div class="product-info">
                        <h2 class="product-brand">'?> <?php echo $document->ten_sach; ?> <?php echo'</h2>
                        <p>'?><?php echo  $document->mo_ta; ?> <?php echo'</p>
                        <a>Tác giả: '.$document->tac_gia.'</a><a> NXB:'.$document->ma_nxb.'</a></span>
                    </div>
                </div>'; ?>
        <?php } ?>
        </div>
    </section>

    <section class="product">
        <h2 class="product-category">Sách đặc biệt <img src="img/hotsell.png"></h2>
        <button class="pre-btn"><img src="img/arrow.png" alt=""></button>
        <button class="nxt-btn"><img src="img/arrow.png" alt=""></button>
        <div class="product-container">
        <?php 
            $collection = $database->selectCollection('sach');
            $result = $collection->find(['trang_thai_sach'=>1]);
            foreach ($result as $document) {
                echo   '<div class="product-card">
                        <div class="product-image">
                        <span class="discount-tag">';?>
                        <?php if($document->so_luong>0){
                                echo '<a style ="color: green">Hiện có</a></span>';}
                            else{
                                echo '<a style ="color: green">Đã mượn hết</a></span>';
                            }
                        ?>
                        <?php echo'<img src="';?><?php echo  $document->anh_bia;?><?php echo'" class="product-thumb" alt="" onclick="location.href=';?><?php echo"'book.php?_id="?><?php echo $document->_id;?><?php echo "'"?>
                        <?php echo'">
                    </div>
                    <div class="product-info">
                        <h2 class="product-brand">'?> <?php echo $document->ten_sach; ?> <?php echo'</h2>
                        <p>'?><?php echo  $document->mo_ta; ?> <?php echo'</p>
                        <a>Tác giả: '.$document->tac_gia.'</a><a> NXB:'.$document->ma_nxb.'</a></span>
                    </div>
                </div>'; ?>
        <?php } ?>
        </div>
    </section>


    <!--collections-->
            </div>
        </div>
    </section>

    <footer></footer>

    <script src="js/home.js"></script>
    <script src="js/nav.js"></script>
    <script src="js/footer.js"></script>
</body>
</html>