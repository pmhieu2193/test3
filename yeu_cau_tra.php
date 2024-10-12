<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yêu cầu trả</title>

    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="css/cart.css">
    <link rel="stylesheet" href="css/search.css">
    <link rel="stylesheet" href="css/sigup.css">
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
    $collection = $database-> selectCollection("chi_tiet_sach_muon");
    //dùng if else để lấy option trên sort, value trên limit lấy từ href
    $options = [
        'sort' => ['created_at' => -1],
        'limit' => 10
    ];
    $count=0;
    //điều kiện hiển thị, filter được đặt ở đây đê xứ lý
    $result= $collection->find(['kiem_tra_da_tra'=>2], $options);
    ?>
    <div class="order-list">
        <div class="add-product">
            <p class="add-product-title">Danh sách yêu cầu trả</p>
        </div>
        <div class="small-container oder-page">
            <table>
                <tr>
                    <th>STT</th>
                    <th>Sách trả</th>
                    <th>Email bạn đọc</th>
                    <th>Ngày phải trả</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
                    <?php foreach ($result as $document) {
                        //id chi tiet sach dc tra
                        $id= $document->_id;
                        $filter = ['_id' => new MongoDB\BSON\ObjectId($id)];
                        $ma_ycm =$document -> ma_yeu_cau_muon;
                        $filterycm = ['_id' => new MongoDB\BSON\ObjectId($ma_ycm)];
                        $collectionYCM = $database->selectCollection("yeu_cau_muon");
                        $ycm= $collectionYCM->findOne($filterycm);
                        $ma_user = $ycm->ma_user;
                        $filteruser = ['_id' => new MongoDB\BSON\ObjectId($ma_user)];
                        $collectionUser = $database->selectCollection("user");
                        $user = $collectionUser->findOne($filteruser);
                        $collectionBook = $database->selectCollection("sach");
                        $ma_sach=(string)$document->ma_sach;
                        $filtersach = ['_id' => new MongoDB\BSON\ObjectId($ma_sach)];
                        $sach = $collectionBook -> findOne($filtersach);

                    echo '
                <tr><td><p>'.$count.'</p></td>';
                $count++;
                echo '<td>
                                <div class="cart-info">
                                    <img src="'.$sach->anh_bia.'">
                                    <div>
                                        <h3>'.$sach->ten_sach.'</h3>
                                        <small>'.$sach->mo_ta.'</small><br><br>
                                        <a style="color: red">Tình trạng sách mượn: '.$document->tinh_trang_sach_muon.'</a><br><br>
                                        <a style="color: blue; cursor: pointer" href= "book.php?_id='.$sach->_id.'">Xem chi tiết sách</a>
                                    </div>
                                </div>
                    </td>';
                if($user){
                    echo'
                    <td><p>Email: '.$user->email.'</p><p>Tên: '.$user->ten.'</p><br><a style="color: blue; cursor: pointer" href= "userDetail.php?id='.$user->_id.'">Xem chi tiết user</a></td>';
                }
                else{
                    echo'<td><p style="color: red">Tài khoản đã bị xoá hoặc có vấn đề xảy ra</p></td>';
                }
                $date = $document->ngay_phai_tra;
                $datePT = new DateTime($date->toDateTime()->format('Y-m-d H:i:s'));
                echo '
                <td><a>'.$datePT->format('d-m-Y').'</a></td>';
                $today = new DateTime;
                if($today>$datePT){
                    echo '<td><a>Đã trễ hạn trả</a></td>';
                }
                else{
                    echo '<td><a>Bình thường</a></td>';
                }
                    echo '<td><a style="color: blue; cursor: pointer" href= "chi_tiet_ycm.php?id='.$ycm->_id.'">Xem chi tiết yêu cầu mượn</a><br><br>
                    <form action ="xu_ly_sach_tra.php" type="post">
                    <input type="hidden" name="id" value="'.(string)$document->_id.'">
                    <button type="submit" name="action_return" class="confirm-btn" style="background-color: green;">
                    Xác nhận trả</button></form><br>
                    <br><h3>Tạo phiếu phạt</h3><form action="xu_ly_phat.php" type="post"><input type="hidden" name="id" value="'.(string)$document->_id.'">
                    <input type="text" name="lydo" placeholder="Vui lòng nhập lý do phạt"><br>
                    <input name="cannotreturn" type="checkbox"><a>Sách không thể hoàn trả</a><br>
                    <button type="submit" name="send" class="confirm-btn" style="background-color: red; color: white ">Xác nhận</button></form><br>
                    <button class="confirm-btn" style="background-color: green" onclick="hideForm()">Huỷ</button> 
                    </td></tr>';   
                }?>
            </div>
        </table>
    <script src="js/admin.js"></script>
</body>
</html>