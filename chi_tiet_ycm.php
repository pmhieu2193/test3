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
    ?>

    <div class="list-orderdetail">
        <div class="add-product">
            <p class="add-product-title">Chi tiết yêu cầu mượn id: <?php
            if(!isset($_POST["getOrder"])){
                include("connection.php");
                $id=$_GET["id"];
                echo $id;
                $filter = ['_id' => new MongoDB\BSON\ObjectId($id)];
                $document = $database -> selectCollection("yeu_cau_muon");
                $ycm= $document -> findOne($filter);
                $date = $ycm->ngay_yeu_cau;
                $dateNYC = new DateTime($date->toDateTime()->format('Y-m-d H:i:s'));
                $document2=$database->selectCollection('user');
                $id_user=$ycm->ma_user;
                $filter2 = ['_id' => new MongoDB\BSON\ObjectId($id_user)];
                $user= $document2->findOne($filter2);
            }
            ?></p>
            <button class="btn btn1 btn-new-product"><img src="img/print.png"></button>
        </div>
        <div class="info-ordered">
            <div class="info-order">
                <h3>Thông tin yêu cầu mượn</h3>
                <p>Thời gian yêu cầu: <?php echo $dateNYC->format('d-m-Y');?> 15:04:59</p>
                <?php if($ycm->trang_thai_yeu_cau==0)
                    echo '<p>Trạng thái yêu cầu: <a style="color: red">Đang đợi duyệt</a></p>';
                if($ycm->trang_thai_yeu_cau==1){
                    echo '<p>Trạng thái yêu cầu: <a style="color: green">Đã duyệt/ Mượn thành công</a></p>';

                }
                if($ycm->trang_thai_yeu_cau==-1){
                    echo '<p>Trạng thái yêu cầu: <a style="color: red">Yêu cầu mượn đã bị từ chối</a></p>';

                }    ?>
            </div>
            <div class="info-order">
                <h3>Thông tin người mượn</h3>
                <?php if($user){ 
                    $collection3=$database->selectCollection('rank');
                    $rank=$collection3->findOne(['ma_rank'=> $user->ma_rank]);
                echo'<p>Email: <a style="color: red">'.$user->email.'</a></p><input type="hidden"><p>Tên: <a style="color: red">'.$user->ten.'</a></p><p>Số điện thoại: '.$user->sdt.'</p>
                <p>cccd: '.$user->cccd.'</p>
                <p>Địa chỉ:
                <a>'.$user->dia_chi.'</a></p>
                <p style="color: blue">Hạng: '.$rank->ten_rank.'</p></div>';
                }
                if(!$user) echo'<p>Tài khoản người dùng này đã bị xoá</p></div>';?>

            <div class="info-order">
                <h3>Hành động</h3>
                <p class="link-text">Bạn: <?php 
                    echo $_SESSION["ten"].'</p>';
                if($_SESSION['ma_rank']==0){
                    echo '<p>Hạng: Tài khoản ADMIN</p>';
                    if($ycm->trang_thai_yeu_cau== 1){
                        $id_admin=(string)$ycm->ma_nguoi_duyet;
                        $filter = ['_id' => new MongoDB\BSON\ObjectId($id_admin)];
                        $admin= $document2->findOne($filter);
                        echo '<p>Trạng thái yêu cầu: <button class="confirmed-btn">Đã xác nhận</button>';
                        echo'<p>Người xác nhận yêu cầu: <a style="color: red">'.$admin->ten.'</a></p>';
                    }
                    if($ycm->trang_thai_yeu_cau== -1){
                        $id_admin=(string)$ycm->ma_nguoi_duyet;
                        $filter = ['_id' => new MongoDB\BSON\ObjectId($id_admin)];
                        $admin= $document2->findOne($filter);
                        echo '<p>Trạng thái yêu cầu: <button class="canceled-btn">Đã từ chối</button>';
                        echo'<p>Người từ chối yêu cầu: <a style="color: red">'.$admin->ten.'</a></p>';
                    }
                    if($ycm->trang_thai_yeu_cau== 0){
                        echo '<form action="xu_ly_YCM.php" type="post"><input type="hidden" name="id_ycm" value="'.$id.'"><div style="background: grey; padding: 10px"><p style="color: white">Vui lòng xác nhận/từ chối yêu cầu mượn: <br><button name="actionYCM" value="confirm" class="confirm-btn">Xác nhận</button></p><br> <button name="actionYCM" value="cancel" class="cancel-btn">Từ chối yêu cầu mượn</button></p></div>';
                    }
                }
                else{
                    echo '<a>Chỉ tài khoản ADMIN mới thực hiện được hành động này</a>';
                }
                 ?>
            </div>
        </div>
        <div class="small-container cart-page">
            <table>
                <?php
                    $id = (string)$id;
                    $flag=1;
                    $flag2=1;
                    $collection3 = $database -> selectCollection("chi_tiet_sach_muon");
                    $ctsm = $collection3->find(['ma_yeu_cau_muon'=>$id]);
                    $count=0;
                    foreach($ctsm as $collection3){
                        $collection4 = $database -> selectCollection('sach');
                        $id_sach= (string)$collection3->ma_sach;
                        $filter = ['_id' => new MongoDB\BSON\ObjectId($id_sach)];
                        $book= $collection4->findOne($filter);
                        $thoi_gian_tra=$collection3->ngay_phai_tra;
                        $phpDate = new DateTime($thoi_gian_tra->toDateTime()->format('Y-m-d H:i:s'));
                        $today = new DateTime;
                ?>
                <tr>
                    <th>Sách <?php echo $count+1?></th>
                    <?php if($ycm->trang_thai_yeu_cau!=-1) echo '<th class="cost">Tình trạng sách mượn</th>';?>
                    <?php if($ycm->trang_thai_yeu_cau!=-1) echo '<th class="number-pro">Hạn trả</th>';?>
                    <?php if($ycm->trang_thai_yeu_cau==1){
                        echo '<th class="cost">Trạng thái</th><th class="number-pro">Gia hạn</th><th class="cost">Tình trạng trả</th>';
                    } ?>
                </tr>
                    <?php
                    echo'<tr><td>
                        <div class="cart-info">
                            <img src="'.$book->anh_bia.'">
                            <div>
                                <small>ID: '.(string)$book->_id.'</small>
                                <input type="hidden" name="book_id_'.$count.'" value="'.(string)$book->_id.'">
                                <h3>'.$book->ten_sach.'</h3>
                                <small>'.$book->mo_ta.'</small>
                            </div>
                        </div>';
                    if($ycm->trang_thai_yeu_cau==1){
                        echo '<td><p>'.$collection3->tinh_trang_sach_muon.'</p></td>';
                        if(empty($collection3->tinh_trang_sach_muon)) $flag2=0;
                    }
                    if($ycm->trang_thai_yeu_cau==0)
                    echo '<td><input name="chi_tiet'.$count.'" type="text"></td>';
                    $count=$count+1;
                    
                    if($ycm->trang_thai_yeu_cau!=-1) echo '<td>'.$phpDate->format('d-m-Y').'</td>';
                    if($ycm->trang_thai_yeu_cau==1){
                        if($today>=$phpDate){
                            echo '<td><a style="color: red">Đã trễ hạn trả</a></td>';
                        }
                        else{
                            echo '<td><a style="color: green">Đang trong thời gian mượn</a></td>';
                        }
                        if($collection3->kiem_tra_gia_han== 1){
                            echo '<td><a style="color: green">Đã gia hạn</a></td>';}
                        if($collection3->kiem_tra_gia_han== 0){
                            echo '<td>Chưa gia hạn</td>';}
                        if($collection3->kiem_tra_da_tra !=1){
                            $flag=0;
                            echo '<td>Chưa trả</td>';}
                        if($collection3->kiem_tra_da_tra== 1){
                            echo '<td>Đã trả!</td>';}
                echo'</tr>';}}
                ?>
            </div>
        </table>
        <?php   if($ycm->trang_thai_yeu_cau==0) echo '<input type="hidden" name="count" value="'.$count.'"></form>';
        ?>
    </div>
    <div class="info-ship">
        <?php if($ycm->trang_thai_yeu_cau==1&&$flag==1){
            echo '<p>&#8226; Tất cả sách đã được trả</p>';
        }
        if($ycm->trang_thai_yeu_cau==1&&$flag==0){
            echo '<p style="color: red">Có sách vẫn chưa được hoàn trả</p>';
        }        
        if($flag2==0){
            echo '<p>Sách thiếu tình trạng trước khi mượn</p>';
        }?>
    </div>
</body>
</html>