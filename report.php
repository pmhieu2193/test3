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
    include("connection.php");
    include("navadmin.php");
    $collectionSach = $database -> selectCollection("sach");
    $collectionUser = $database -> selectCollection("user");
    $collectionRank = $database -> selectCollection("rank");
    $collectionYCM   = $database -> selectCollection("yeu_cau_muon");
    $collectionCTSM= $database -> selectCollection("chi_tiet_sach_muon");
    $collectionTheLoai = $database -> selectCollection("the_loai");
    $collectionWishList = $database -> selectCollection("yeu_thich");
    $collectionPhat = $database -> selectCollection("phat");
    $countUser =$collectionUser->countDocuments();
    $options = ['sort' => ['tao_luc' => -1]];

    if(isset($_GET["start"])&&isset($_GET["end"])){
    $start = (string)$_GET["start"];
    $end = (string)$_GET["end"];
    $start = new MongoDB\BSON\UTCDateTime(strtotime($start.' 00:00:01') * 1000);
    $end = new MongoDB\BSON\UTCDateTime(strtotime($end.' 23:59:59') * 1000);


    // Tạo một truy vấn
    $filter = [
        'tao_luc' => ['$gte' => $start, '$lte' => $end]
    ];
    $filter2 = [
        'check_in' => ['$gte' => $start, '$lte' => $end]
    ];
    $filter3 = [
        'ngay_yeu_cau' => ['$gte' => $start, '$lte' => $end]
    ];
    $filter4 = [
        'thoi_gian_phat' => ['$gte' => $start, '$lte' => $end]
    ];
    $filter5 = [
        'ngay_nhap' => ['$gte' => $start, '$lte' => $end]
    ];
    $filter6 = [
        'ngay_yeu_cau' => ['$gte' => $start, '$lte' => $end], 'trang_thai_yeu_cau' => 1
    ];
    $filter7 = [
        'ngay_yeu_cau' => ['$gte' => $start, '$lte' => $end], 'trang_thai_yeu_cau' => -1
    ];
    $filter8 = [
        'ngay_yeu_cau' => ['$gte' => $start, '$lte' => $end], 'trang_thai_yeu_cau' => 0
    ];

    $userTaoLuc = $collectionUser -> countDocuments($filter);
    $userCheckin= $collectionUser -> countDocuments($filter2);
    $countYCM = $collectionYCM -> countDocuments($filter3);
    $countYCMTC = $collectionYCM -> countDocuments($filter6);
    $countYCMTB = $collectionYCM -> countDocuments($filter7);
    $countYCMDD = $collectionYCM -> countDocuments($filter8);
    $countPhat = $collectionPhat -> countDocuments($filter4);
    $countSach = $collectionSach -> countDocuments($filter5);
    $SoluotMuon = $collectionUser -> find($filter6);
    $count =0;
    $cate = $_REQUEST["cate"];
    foreach($SoluotMuon as $doc){
        $id = (string)$doc -> _id;
        $count2 = $collectionCTSM -> countDocuments(['ma_yeu_cau_muon'=> $id]);
        $count =$count + $count2;       
    }

    $start = new DateTime($start->toDateTime()->format('Y-m-d H:i:s'));
    $end = new DateTime($end->toDateTime()->format('Y-m-d H:i:s'));
    
    }
    else{
    $userTaoLuc = $collectionUser -> countDocuments();
    $userCheckin= $collectionUser -> countDocuments();
    $countYCM = $collectionYCM -> countDocuments();
    $countYCMTC = $collectionYCM -> countDocuments(['trang_thai_yeu_cau' => 1]);
    $countYCMTB = $collectionYCM -> countDocuments(['trang_thai_yeu_cau' => -1]);
    $countYCMDD = $collectionYCM -> countDocuments(['trang_thai_yeu_cau' => 0]);
    $countPhat = $collectionPhat -> countDocuments();
    $countSach = $collectionSach -> countDocuments();

    $SoluotMuon = $collectionUser -> find(['trang_thai_yeu_cau' => 1]);
    $count =0;
    foreach($SoluotMuon as $doc){
        $id = (string)$doc -> _id;
        $count2 = $collectionCTSM -> countDocuments(['ma_yeu_cau_muon'=> $id]);
        $count =$count + $count2;       
    }
    $sach = $collectionSach -> find();
    }       
    ?>
    <div class="list-ordered-product">
        <div class="add-product">
            <p class="add-product-title">Danh sách sản phẩm được đặt</p>
        </div>
        <form id="ok" action="report.php" type="get">
        <div class="box">
            <div class="date-search">
                    <a class="nameselect-combo">Từ ngày</a>
                    <input name="start" type="date" class="date">
                    <a class="nameselect-combo">Tới ngày</a>
                    <input name="end" type="date" class="date">
                    <div class="btn-search-date">
                    <button type="submit" class="btn btn-search-date">Xác nhận</button>
            </div>
            </div>
        </div>
        <h2 class="title-colection">Tổng quan</h2>
        <?php
        if(isset($_GET["start"])&&isset($_GET["end"])){
            echo '<h1 class="info-date">hiển thị từ ngày:'.$start->format('d-m-Y').' đến ngày '.$end->format('d-m-Y').'</h1>';
        }
        ?>
        <select class="select-report" name="cate" onchange="submitForm()">
                <option value='' value="" <?php if(isset($_GET["cate"])&&empty($_GET["cate"])&&!is_numeric($_GET["cate"])) echo ' selected';?>>Phân loại: Tất cả thể loại</option>
                <?php
                $the_loai=$collectionTheLoai -> find();
                foreach($the_loai as $doc){
                    echo ' <option value="'.$doc->ma_the_loai.'" ';
                    if(isset($_GET["cate"])&&(int)$_GET["cate"]===(int)$doc->ma_the_loai&&is_numeric($_GET["cate"])) echo ' selected';
                    echo '>Phân loại: '.$doc->ten_the_loai.'</option>';
                }
                ?>
        </select>
        </form>
        <div class="small-container cart-page">
        <div>
        <p>Tổng số tài khoản: <?php echo $countUser?><p>
        <p>Tổng số tài khoản được tạo mới: <?php echo $userTaoLuc?></p>
        <p>Số bạn đọc check-in thư viện: <?php echo $userCheckin?></p>
        <p>Số sách mới được nhập: <?php echo $countSach?></p>
        <p>Tổng Số yêu cầu mượn: <?php echo $countYCM?><p>
        <p>Tổng số yêu cầu mượn thành công: <?php echo $countYCMTC?></p>
        <p>Tổng số yêu cầu mượn thất bại: <?php echo $countYCMTB?></p>
        <p>Tổng số yêu cầu mượn đang chờ: <?php echo $countYCMDD?></p>
        <p>Số lượt sách mượn thành công: <?php echo $count?></p>
        <p>Lượt phạt: <?php echo $countPhat?> </p>
        <button class="btn report" onclick="showForm()">xem danh sách phạt</button>
        </div>
        
        <div class="form-container">
        <h2 class="title-colection">Danh sách phạt</h2>
            <table>
                <tr>
                    <th>stt</th>
                    <th>Mã</th>
                    <th>Thời gian phạt</th>
                    <th>Lý do phạt</th>
                </tr>
                <?php 
                    if(isset($_GET["start"])&&isset($_GET["end"])){
                        $phat = $collectionPhat->find($filter4);
                    }
                    else{
                        $phat = $collectionPhat->find();
                    }
                    $count=1;
                    foreach($phat as $doc){
                        $id_ycm = $doc -> ma_chi_tiet_sach_muon;
                        $filter = ['_id' => new MongoDB\BSON\ObjectId($id_ycm)];
                        $ctsm = $collectionCTSM -> findOne($filter);
                        $id_sach= $ctsm -> ma_sach;
                        $filter2 = ['_id' => new MongoDB\BSON\ObjectId($id_sach)];
                        $sach = $collectionSach -> findOne($filter2);
                        $date_phat = $doc -> thoi_gian_phat;
                        $date_phat = new DateTime($date_phat->toDateTime()->format('Y-m-d H:i:s'));
                        echo '
                
                <tr>
                    <td><a>'.$count.'</a></td>
                    <td>

                    <div class="cart-info">
                                    <img src="'.$sach->anh_bia.'">
                                    <div>
                                        <h3>'.$sach->ten_sach.'</h3>
                                        <small>'.$sach->mo_ta.'</small><br><br>
                                        <a style="color: blue; cursor: pointer" href= "book.php?_id='.$sach->_id.'">Xem chi tiết sách</a>
                                    </div>
                                </div>


                    </td>
                    <td><a>'.$date_phat->format('d-m-Y').'</a></td>
                    <td><a>'.$doc->ly_do.'</a></td>
                </tr>';
                $count++;    
                }   ?>    
        </table>
    </div>
</div>

        <h2 class="title-colection">Danh sách chi tiết</h2>
            <table>
                <tr>
                    <th>stt</th>
                    <th>Sách</th>
                    <th>Lượt mượn thành công</th>
                    <th>Lượt thêm vào wishList</th>
                </tr>
                <?php 
                 if(isset($_GET["start"])&&isset($_GET["end"])){
                    $cate = $_REQUEST["cate"];
                    if(!is_numeric($cate)){
                        $sach = $collectionSach -> find();
                    }
                    else{
                        $sach = $collectionSach -> find(['ma_the_loai' => (int)$cate]);
                    }
                }
                else{
                    $sach = $collectionSach->find();
                }
                $count=1;

                foreach($sach as $doc){
                    $countLM=0;
                    $id_sach=(string)$doc->_id;
                    $countWL= $collectionWishList -> countDocuments(['ma_sach' => $id_sach]);
                    $ycm = $collectionYCM -> find(['trang_thai_yeu_cau'=> 1]);
                    foreach($ycm as $ycm){
                        $ctsm = $collectionCTSM -> findOne(['ma_yeu_cau_muon'=>(string)$ycm->_id, 'ma_sach'=> $id_sach]);
                        if($ctsm) $countLM ++;
                    }
                echo '
                
                <tr>
                    <td><a>'.$count.'</a></td>
                    <td>

                    <div class="cart-info">
                                    <img src="'.$doc->anh_bia.'">
                                    <div>
                                        <h3>'.$doc->ten_sach.'</h3>
                                        <small>'.$doc->mo_ta.'</small><br><br>
                                        <a style="color: blue; cursor: pointer" href= "book.php?_id='.$doc->_id.'">Xem chi tiết sách</a>
                                    </div>
                                </div>


                    </td>
                    <td><a>'.$countLM.'</a></td>
                    <td><a>'.$countWL.'</a></td>
                </tr>';
                $count++;
                }   ?>    
        </table>
    </div>
</div>
<?php



?>

    <script src="js/admin.js"></script>
</body>
</html>