<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN</title>

    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="css/search.css">
    <link rel="stylesheet" href="css/sigup.css">
    <link rel="stylesheet" href="css/cart.css">
    <link rel="stylesheet" href="css/admin.css">

</head>
<body>
    <img src="img/loader.gif" class="loader" alt="">

    <div class="alert-box">
        <img src="img/error.png" class="alert-img" alt="">
        <p class="alert-msg">Thông báo lỗi</p>
    </div>
    <img src="img/dark-logo.png" class="logo" alt="">

    <!--become seller element-->
    <!--apply form-->
    <?php
    include("navadmin.php");
    include("connection.php");
    if(isset($_REQUEST["btn_checkin"])){
        $id_user=$_REQUEST["id_user"];
        echo $id_user." đã check in!";
        $collection = $database->selectCollection('user');
        $today = new DateTime;
        $mongoDate = new MongoDB\BSON\UTCDateTime($today->getTimestamp() * 1000);
        $filter = ['_id' => new MongoDB\BSON\ObjectId($id_user)];
        $update = ['$set' => ['check_in' => $mongoDate]];
        $result = $collection->updateOne($filter, $update);
        $result2 = $collection->findOne($filter);}
    ?>

    <div class="list-orderdetail">
        <form action="checkin.php" type="post">
        <div class="add-product">
            <p class="add-product-title">Check-in</p>
            <div class="search">
                <input type="text" name="userid" placeholder="Nhập mã bạn đọc...">
                <button type="submit" name="search-checkin" class="search-btn">&#9906; Xác nhận</button> 
        </form>                      
        </div>
        </div>
        <div class="info-ordered">
            <div class="info-order">
            <form action="checkin.php" type="post">
                <?php if(isset($_REQUEST["search-checkin"])){
                        $id=$_REQUEST["userid"];
                        echo '<input type="hidden" name="id_user" value="'.$id.'">';
                        $collection = $database->selectCollection('user');
                                try {
                                    $filter = ['_id' => new MongoDB\BSON\ObjectId($id)];
                                    if($filter){
                                        $user= $collection->findOne($filter);
                                        if($user){
                                            echo '<h3>Thông tin bạn đọc</h3>';
                                            echo '<p>Mã bạn đọc: '.$user->_id.'</p>';
                                            echo '<p>Email: '.$user->email.'</p>';
                                            echo '<p>Tên: '.$user->ten.'<p>';
                                            if($user->gioi_tinh==1){
                                                echo '<p>Giới tính: Nam<p>';
                                            }
                                            else{
                                                echo'<p>Giới tính: Nữ<p>';
                                            }
                                            echo '<p>Địa chỉ: '.$user->dia_chi.'<p>';
                                            $thoi_gian_tao=$user->tao_luc;
                                            //$phpDate = new DateTime("@$thoi_gian_tao");
                                            $phpDate = new DateTime($thoi_gian_tao->toDateTime()->format('Y-m-d H:i:s'));
                                            echo '<p>Thời gian tạo: '.$phpDate->format('d-m-Y').'</p>';
                                            echo '<p>Giờ: '. $phpDate->format('H:i:s').'</p>';
                                            $thoi_gian_check_in=$user->check_in;
                                            //$phpDate = new DateTime("@$thoi_gian_tao");
                                            $phpDateCheckin = new DateTime($thoi_gian_check_in->toDateTime()->format('Y-m-d H:i:s'));
                                            echo '<p>Check-in lần cuối: '.$phpDateCheckin->format('d-m-Y').'</p>';
                                            echo '<p> Trạng thái tài khoản:';
                                            if($user->trang_thai_tai_khoan== 0){
                                                echo 'Tài khoản chưa được duyệt</p>';
                                            }
                                            else if($user->trang_thai_tai_khoan== 1){
                                                echo 'Tài khoản hoạt động bình thường</p>';
                                            }
                                            else if($user->trang_thai_tai_khoan== 2){
                                                echo 'Tài khoản đã bị cấm</p>';
                                            }
                                            else{
                                                echo 'Lỗi, không rõ trạng thái tài khoản: '.$user->trang_thai_tai_khoan.'</p>';
                                            }
                                            $collectionRank = $database->selectCollection('rank');
                                            $rank= $collectionRank->findOne(["ma_rank"=>$user->ma_rank]);
                                            if($rank){
                                                echo '<p> Level: '.$rank->ten_rank.'</p>';
                                            }
                                                echo '<p>Bấm nút này để check-in cho bạn đọc: <button name="btn_checkin" type="submit" class="confirm-btn">Check-in</button></p></form></div>';
                                                //d
                                                //
                                                echo '<div class="small-container cart-page">
                                                <h1>Danh sách sách trễ hạn trả</h1>
                                                <table>
                                                    <tr>
                                                        <th>Sách</th>
                                                        <th class="number-pro">Tình trạng</th>
                                                        <th class="cost">Hạn trả</th>
                                                        <th class="cost">Gia hạn</th>
                                                        <th class="cost">Trạng thái</th>
                                                    </tr>';
                                                $collectionYCM = $database->selectCollection('yeu_cau_muon');
                                                //cái này mới là chỉ findone, chứ mình nghĩ là find khác, cả ở bên trên cũng z, phải có vòng foreach
                                                $ycm= $collectionYCM ->find(["ma_user"=>$id]);
                                                foreach ($ycm as $documentYCM) {
                                                    //mã yêu cầu mượn
                                                    $idYCM= (string)$documentYCM->_id;
                                                    $collectionCTSM = $database->selectCollection('chi_tiet_sach_muon');
                                                    $ctsm= $collectionCTSM->find(["ma_yeu_cau_muon"=>$idYCM]);
                                                    foreach ($ctsm as $documentCTSM) {
                                                        $thoi_gian_tra=$documentCTSM->ngay_phai_tra;                                                        ;
                                                        $date_return = new DateTime($thoi_gian_tra->toDateTime()->format('Y-m-d H:i:s'));
                                                        
                                                        $today= new DateTime;
                                                        if($documentCTSM->kiem_tra_da_tra==1){
                                                            continue;
                                                        }
                                                        if($today<=$date_return){
                                                            continue;
                                                        }

                                                        $id_sach=$documentCTSM->ma_sach;
                                                        $collectionSach= $database->selectCollection('sach');
                                                        $sach= $collectionSach->findOne(['_id' => new MongoDB\BSON\ObjectId($id_sach)]);
                                                        echo '<tr>
                                                        <td>
                                                            <div class="cart-info">
                                                                <img src="'.$sach->anh_bia.'">
                                                                <div>
                                                                    <h3>'.$sach->ten_sach.'</h3>
                                                                    <br>
                                                                    <small>"'.$sach->mo_ta.'"</small>
                                                                    <br>
                                                                    <small>Tác giả: '.$sach->tac_gia.'</small>
                                                                </div>
                                                            </div>
                                                        <td>'.$documentCTSM->tinh_trang_sach_muon.'</td>';
                                
                                                        //$date_return->modify('+7 days' );                                              
                                                        echo '<td>'.$date_return->format('d-m-Y').'</td>
                                                        <td>'; 
                                                        if($documentCTSM->kiem_tra_gia_han==0){
                                                            echo 'Chưa gia hạn</td>';
                                                        }
                                                        else{
                                                            echo 'Đã gia hạn</td>';
                                                        }
                                                    echo '<td style="color: red">Đã trễ hạn</td></tr>';
                                                    }
                                                }
                                                //1 user có nhiều ycm, 1 yêu cầu mượn có nhiều sách

                                        echo '</div></table></div>';
        
                                        
                                        }
                                            //chỗ này là chỗ hiển thị sp
                                            else{
                                                echo '<h3 style ="color : red"> Không tìm thấy Bạn đọc </h3></form></div>';
                                            }
                                        }
                                    

                                    } catch (Exception $e) {
                                        echo '<p>Không tìm thấy User!</p></div>';
                                    }} ?>
</body>
</html>