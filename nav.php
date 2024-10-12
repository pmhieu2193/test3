<?php
    include ("connection.php");
    session_start(); 
?>

<?php echo'
    <div class="nav">
        <img src="img/dark-logo.png" class="brand-logo" alt="">
        <div class="nav-items">
        <form action="search.php" method="GET" accept-charset="UTF-8">
                <div class="search">
                    <input type="text" class="search-box" name="timkiem" placeholder="Tìm tên sách...">
                    <button type="submit" class="search-btn">Tìm kiếm</button>                       
                </div>
        </form>
                <a>
                    <img src="img/user.png" id="user-img" alt="">
                    <div class="login-logout-popup hide">
                        <p class="account-info">';?>
                        <?php if(isset($_SESSION["email"])){
                            echo $_SESSION["email"];
                            echo '<form action="logout.php" type="post"><button type="submit" name="logout" class="btn" id="user-btn">đăng xuất</button></form>';
                        }
                        else{
                            echo "Chưa đăng nhập";
                            echo '<form action="login.php"><button type="submit" name="login" class="btn" id="user-btn">đăng nhập</button></form>';
                        }
                        ?><?php echo '</p>
                    </div>
                </a>';
                if(isset($_SESSION["email"])){
                    echo '<form action="myorderlog.php" type="post"><a><input type="image" name="gotoMyOrder" src="img/history.png"></a></form>';
                    echo '<a href="lich_su_muon_sach.php"><input type="image" name="gotoCart" src="img/clock.png"></a>';
                }
                echo '<form action="cartlog.php" type="post"><a><input type="image" name="gotoCart" src="img/cart.png"></a></form>
        </div>
    </div>
    
    <ul class="links-container">
        <li class="link-item"><a href="index.php" class="link"><img src="img/home.png">Trang chủ</a></li>
        <li class="link-item"><a href="404.php" class="link">Nội quy</a></li> 
        <li class="link-item"><a href="404.php" class="link">Hướng dẫn sử dụng</a></li> ';
        if(isset($_SESSION["email"])){
        echo '<li class="link-item"><a href="wishlist.php" class="link">Danh sách yêu thích</a></li>';
        }
        echo '<li class="link-item"> 
            <div>
                <form id="ok" action = "search.php" type="get">
                <select name="ma_the_loai" class="link" onchange="submitForm()">
                <option value="">Thể loại</option>'; ?>
                <?php $collection = $database->selectCollection('the_loai');
                $result = $collection->find([]);
                foreach ($result as $document) {
                    echo '<option value="'.$document->ma_the_loai.'"';
                    if(isset($_GET['ma_the_loai'])){ 
                        if((int)$_GET['ma_the_loai']===(int)$document->ma_the_loai) echo ' selected ';}
                    echo '>'.$document->ten_the_loai.'</option>';
                }
    echo '</select> </form> </div> </li> </ul>'; 
    
    echo '
    <script src="js/admin.js"></script></script>'?>
