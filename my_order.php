<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sách đang mượn</title>

    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="css/cart.css">

</head>
<body>
    
    <?php 
    include("nav.php");
    include("connection.php");
    $collectionYCM = $database ->selectCollection("yeu_cau_muon");
    $filter = ['_id' => new MongoDB\BSON\ObjectId($_SESSION["_id"])];
    $id_user=(string)$_SESSION["_id"];
    $ycm = $collectionYCM -> find(["ma_user"=>$id_user, "trang_thai_yeu_cau"=>1]);
    $count=$collectionYCM->countDocuments(["ma_user"=>$id_user]);
    $countS=$collectionYCM->countDocuments(["ma_user"=>$id_user, "trang_thai_yeu_cau"=>1]);
    $countF=$collectionYCM->countDocuments(["ma_user"=>$id_user,"trang_thai_yeu_cau"=>-1]);
    $countW=$collectionYCM->countDocuments(["ma_user"=>$id_user,"trang_thai_yeu_cau"=>0]);
    if($count== 0){ echo '<h1>Bạn chưa mượn sách nào trước đó</h1>';}
    $stt=1;
    ?>

    <div class="info-user">
        <div class="title-user">
            <h2>THÔNG TIN CÁ NHÂN</h2>
            <a class="link-text">Để sửa hồ sơ vui lòng liên hệ thủ thư</a>
        </div>
        <?php
        $collectionUser = $database ->selectCollection('user');
        $user = $collectionUser->findOne($filter);
        ?>
        <a><img src="img/person.png"> Tên: <?php echo $user->ten ?></a><br>
        <a><img src="img/phonenum.png"> Số điện thoại: <?php echo $user->sdt ?></a><br>
        <a> Email: <?php echo $user->email ?></a><br>
        <a> Căn cước công dân: <?php echo $user->cccd ?></a><br>
        <a> Địa chỉ: <?php echo $user->dia_chi ?></a><br>
        <a> Ngày sinh: <?php 
        $ngay_sinh = $user->ngay_sinh;
        $ngay_sinh = new DateTime($ngay_sinh->toDateTime()->format('Y-m-d H:i:s'));
        echo $ngay_sinh->format('d-m-Y') ?></a><br>
        <a> Giới tính: <?php if($user->gioi_tinh==1) echo 'Nam';
        else echo 'Nữ';?></a><br>
         Rank: <?php 
        $collectionRank = $database ->selectCollection('rank');
        $rank = $collectionRank -> findOne(['ma_rank'=>(int)$_SESSION['ma_rank']]);
        if($rank->ma_rank==0)
        echo '<a style="color: red">'.$rank->ten_rank;
        else{
            echo '<a>'.$rank->ten_rank;
        }
        ?></a><br>
        <a> Thời gian tạo: <?php 
        $tao_luc = $user->tao_luc;
        $tao_luc = new DateTime($tao_luc->toDateTime()->format('Y-m-d H:i:s'));
        echo $tao_luc->format('d-m-Y') ?></a><br>
        <a> Check-in thư viện lần cuối: <?php 
        $checkin = $user->check_in;
        $checkin = new DateTime($checkin->toDateTime()->format('Y-m-d H:i:s'));
        echo $checkin->format('d-m-Y') ?></a><br>
        </a><br>
        <h4>Số yêu cầu mượn đã gửi: <?php echo $count?></h4>
        <h4 style="color: green">Số yêu cầu mượn thành công: <?php echo $countS?></h4>
        <h4 style="color: orange">Số yêu cầu mượn đang đợi: <?php echo $countW?></h4>
        <h4 style="color: red">Số yêu cầu mượn bị từ chối: <?php echo $countF?></h4>
        <h4 style="color: red">Số lượt gia hạn đã sử dụng: <?php echo $_SESSION['gia_han'].'/'. $rank->so_luot_gia_han; if($_SESSION['gia_han']==(int)$rank->so_luot_gia_han) echo '<p>Bạn đã sử dụng hết số lượt gia hạn, trả sách đã gia hạn trước đó để nhập thêm lượt gia hạn</p>'; ?></h4>
        <a> .......... </a><br>    
        <div class="line"></div>
        <div class="title-user">
        <h2 class="title-history-cart">SÁCH ĐANG MƯỢN</h2>  
        </div>
    </div>

    <div class="small-container cart-page">
        <?php 
        if ($count > 0) {
            foreach ($ycm as $documentYCM) {
                $idYCM= (string)$documentYCM->_id;     
                $collectionCTSM = $database->selectCollection('chi_tiet_sach_muon');
                $ctsm= $collectionCTSM->find(["ma_yeu_cau_muon"=>$idYCM]);
                $count2=$collectionCTSM->countDocuments(["ma_yeu_cau_muon"=>$idYCM,"kiem_tra_da_tra"=>1]);
                $count3=$collectionCTSM->countDocuments(["ma_yeu_cau_muon"=>$idYCM]);
                if($ctsm) {
                    if($count2==$count3) continue;
                    else{
                        echo '<div style="background: grey; padding: 5px; color: white"><h3>Mã yêu cầu mượn: '.$documentYCM->_id.'</h3>';
                        $date = $documentYCM->ngay_yeu_cau;
                        $dateNYC = new DateTime($date->toDateTime()->format('Y-m-d H:i:s'));
                        $timestamp =$documentYCM -> gio_yeu_cau;
                        $utcDateTime = new MongoDB\BSON\UTCDateTime($timestamp * 1000);
                        // Chuyển đổi UTCDateTime thành đối tượng DateTime trong PHP
                        $dateTime = $utcDateTime->toDateTime();
                        echo "<h3>Thời gian yêu cầu: ".$dateNYC->format('d-m-Y')." || ".$dateTime->format('H:i:s')."<h4></div>";
                        echo '<br><table></div>
                        <tr>
                            <th>Stt</th>
                            <th>Sách</th>
                            <th>Ngày phải trả</th>
                            <th>Gia hạn</th>
                            <th>Trạng thái</th>
                            <th>Tình trạng</th>
                            <th>Hành động</th>
                        </tr>';
                        foreach ($ctsm as $documentCTSM) {
                            if($documentCTSM->kiem_tra_da_tra==0||$documentCTSM->kiem_tra_da_tra==2){
                                $id_sach = $documentCTSM->ma_sach;
                                $collectionSach=$database->selectCollection('sach');
                                $filtersach = ['_id' => new MongoDB\BSON\ObjectId($id_sach)];
                                $today = new DateTime;
                                try{
                                    //$filtersach = ['_id' => new MongoDB\BSON\ObjectId($id_sach)];
                                    $book = $collectionSach->findOne($filtersach);
                                    $dateReturn = $documentCTSM->ngay_phai_tra;
                                    $datePT = new DateTime($dateReturn->toDateTime()->format('Y-m-d H:i:s'));
                                    echo '
                                    <tr>
                                    <td>'.$stt.'</td>
                                    <td>
                                        <div class="cart-info">
                                            <img src="'.$book->anh_bia.'">
                                            <div>
                                                <h3>'.$book->ten_sach.'</h3>
                                                <h3>ID: '.$book->_id.'</h3>
                                                <small>'.$book->mo_ta.'</small>
                                                <br>';
                                                if($book->trang_thai_sach==0){
                                                    echo '<a>Sách đã bị ẩn</a>';
                                                }
                                                else{
                                                    echo '<a class="link-text" onclick="location.href=';
                                                    echo "'book.php?_id=".(string)$book->_id."'";
                                                    echo '">Xem chi tiết</a>';
                                                }
                                            echo '</div>
                                        </div>
                                    </td>
                                    <td><a>'.$datePT->format('d-m-Y').'</a></td>';
                                    if($documentCTSM->kiem_tra_gia_han==0){
                                        echo '<td>Chưa gia hạn</td>';
                                    }
                                    else{
                                        echo '<td>Đã gia hạn</td>';
                                    }
                                    if($documentCTSM->kiem_tra_da_tra==0){
                                        echo '<td>Đang mượn</td>';
                                    }
                                    if($documentCTSM->kiem_tra_da_tra==2){
                                        echo '<td>Đã gửi yêu cầu trả</td>';
                                    }
                                    if($today>=$datePT){
                                        echo '<td><a style="color: red">Đã trễ hạn trả</a></td>';
                                    }
                                    else{
                                        echo '<td>Bình thường</td>';
                                    }
                                    echo'
                                    <td>';if($documentCTSM->kiem_tra_gia_han==0&&$_SESSION['gia_han']<(int)$rank->so_luot_gia_han){echo '<form action="xu_ly_sach_muon.php" type="post"><input type="hidden" name="id_book" value="'.(string)$book->_id.'"><input type="hidden" name="id_ycm" value="'.(string)$documentYCM->_id.'"><button type="submit" name="action_sach_muon" value="gia_han" class="btn-cart" style="background: white; color: black">Gia hạn</button></form>';}
                                    if($documentCTSM->kiem_tra_da_tra==2){
                                        echo '<form action="xu_ly_sach_muon.php" type="post"><form action="xu_ly_sach_muon.php" type="post"><input type="hidden" name="id_book" value="'.(string)$book->_id.'"><input type="hidden" name="id_ycm" value="'.(string)$documentYCM->_id.'"><button type="submit" name="action_sach_muon" value="tra_sach" class="btn-cart">Gửi lại yêu cầu trả</button></form></td> </tr>';
                                    }
                                    else{
                                        echo '<form action="xu_ly_sach_muon.php" type="post"><form action="xu_ly_sach_muon.php" type="post"><input type="hidden" name="id_book" value="'.(string)$book->_id.'"><input type="hidden" name="id_ycm" value="'.(string)$documentYCM->_id.'"><button type="submit" name="action_sach_muon" value="tra_sach" class="btn-cart">Trả sách</button></form></td></tr>';
                                    }

                                $stt++;
                            }
                            catch(Exception $e) {
                                echo 'Sách đã bị ẩn hoặc gỡ khỏi thư viện';

                            }}
                                
                                
                        }
                        echo '</table><br>';
                        
                    }
                }
                if(!$ctsm) {echo 'thất bại, ko thể đọc chi tiết sách';}
            }
        }
        ?>
        
    </div>
    <script src="js/nav.js"></script>
</body>
</html>