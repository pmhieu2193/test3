<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch sử mượn sách</title>
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
    if($count== 0){ echo '<h1>Bạn chưa mượn sách nào trước đó</h1>';}
    $stt=1;
    ?>

    <div class="small-container cart-page">
    <h1 style="border: 10px">LỊCH SỬ MƯỢN SÁCH</h1><br>
        <?php 
        if ($count > 0) {
            foreach ($ycm as $documentYCM) {
                $idYCM= (string)$documentYCM->_id;     
                $collectionCTSM = $database->selectCollection('chi_tiet_sach_muon');
                $ctsm= $collectionCTSM->find(["ma_yeu_cau_muon"=>$idYCM]);
                //nếu trong yêu cầu mà chưa trả cái nào hết thì bỏ qua
                $count2=$collectionCTSM->countDocuments(["ma_yeu_cau_muon"=>$idYCM,"kiem_tra_da_tra"=>0]);
                $count3=$collectionCTSM->countDocuments(["ma_yeu_cau_muon"=>$idYCM,"kiem_tra_da_tra"=>2]);
                $count4=$collectionCTSM->countDocuments(["ma_yeu_cau_muon"=>$idYCM]);
                if($ctsm) {
                    if(($count2+$count3)==$count4) continue;
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
                            <th>Thời gian đã trả</th>
                            <th>Hành động</th>
                        </tr>';
                        foreach ($ctsm as $documentCTSM) {
                            if($documentCTSM->kiem_tra_da_tra==1){
                                $id_sach = $documentCTSM->ma_sach;
                                $collectionSach=$database->selectCollection('sach');
                                $filtersach = ['_id' => new MongoDB\BSON\ObjectId($id_sach)];
                                $today = new DateTime;
                                try{
                                    $book = $collectionSach->findOne($filtersach);
                                    $dateDT = $documentCTSM->thoi_gian_da_tra;
                                    $dateDT = new DateTime($dateDT->toDateTime()->format('Y-m-d H:i:s'));
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
                                    <td><a>'.$dateDT->format('d-m-Y').'</a></td>';
                                    echo '<td><div><form action="xu_ly_sach.php" type="post"><input name="id_book" type="hidden" value="'.$book->_id.'"><button type="submit" name="addToCart" class="btn-cart">Mượn lại</button></form></td></tr>';

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