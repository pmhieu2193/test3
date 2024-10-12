<?php
if(!isset($_POST["tao_YCM"])) {
    include("connection.php");
    session_start();
    $id_user=(string)$_SESSION["_id"];
    $filter = ['_id' => new MongoDB\BSON\ObjectId($_SESSION["_id"])];

    //xử lý nếu giờ hiện tại nằm ngoài giờ làm việc thì ko thể gọi hàm này, chắc xử lý js
    //hoặc cho nó tự động từ chối ycm luôn

    $timeZone = new DateTimeZone('Asia/Ho_Chi_Minh');
    $currentDateTime = new DateTime('now', $timeZone);

    $mongoDate = new MongoDB\BSON\UTCDateTime($currentDateTime->getTimestamp() * 1000);
    $timestamp = $currentDateTime->getTimestamp();
    $timeString = $currentDateTime->format('H:i:s');
    echo "Thời gian hiện tại: " . $timeString . "<br>";
    echo "Timestamp: " . $timestamp;

    $newDocument = [
        'ngay_yeu_cau' => $mongoDate,
        'gio_yeu_cau' => $timestamp,
        'trang_thai_yeu_cau' => 0,
        'ma_user' => $id_user,
        'ma_nguoi_duyet' => ''
    ];
    $TimeReturn = $currentDateTime -> modify('+7 days');
    $mongoDateReturn = new MongoDB\BSON\UTCDateTime($TimeReturn->getTimestamp() * 1000);

    $collection = $database->selectCollection('yeu_cau_muon');
    $insertResult = $collection->insertOne($newDocument);

    if ($insertResult->getInsertedCount() > 0) {
        $id_ycm=(string)$insertResult->getInsertedId();
        echo "Bản ghi đã được tạo mới thành công. ID: " . $id_ycm;
        $countBook = $_REQUEST["count"];
        $collection2 = $database -> selectCollection("chi_tiet_sach_muon");
        $collection3 =$database -> selectCollection("sach");

        for($i=0; $i<$countBook; $i++){
            $id_sach = (string)$_SESSION["book$i"]; 
            $ok = [
                "ma_yeu_cau_muon" => $id_ycm,
                "ma_sach" => $id_sach,
                "ngay_phai_tra" => $mongoDateReturn,
                "tinh_trang_sach_muon"=> "",
                "kiem_tra_gia_han" => 0,
                "kiem_tra_da_tra" => 0,
                "thoi_gian_da_tra" => new MongoDB\BSON\UTCDateTime(0)               
            ];
            $ctsm= $collection2->insertOne($ok);
            if($ctsm){
                echo 'chi tiết sách mượn'.$i.' tạo thành công';
            }
            else{
                echo 'chi tiết sách mượn tạo thất bại';
            }
        }
        //
        $collection = $database ->selectCollection('user');
        $user = $collection -> findOne($filter);
        $ma_rank = $user -> ma_rank;
        $collection2 = $database ->selectCollection('rank');
        $rank = $collection2 -> findOne(["ma_rank"=>$ma_rank]);
        $so_sach_duoc_muon = $rank -> so_sach_duoc_muon;
        $collectionYCM = $database->selectCollection('yeu_cau_muon');
        $id_user=(string)$id_user;
        $ycm= $collectionYCM ->find(["ma_user"=>$id_user]);
        $n=0;
        foreach ($ycm as $documentYCM) {
            echo 'test';
            //mã yêu cầu mượn
            $idYCM= (string)$documentYCM->_id;
            $collectionCTSM = $database->selectCollection('chi_tiet_sach_muon');
            $ctsm= $collectionCTSM->find(["ma_yeu_cau_muon"=>$idYCM]);
            if($ctsm) {echo 'ctsm thành công';}
            foreach ($ctsm as $documentCTSM) {
                if($documentCTSM->kiem_tra_da_tra==0) $n++;
            }
        }
        echo 'số sách đã mượn là: '.$n;
        echo '/số sách còn lại được mượn là:'.$so_sach_duoc_muon-$n;
        $j= $so_sach_duoc_muon-$n;
        $_SESSION["max"]=$j;
        for($i=0; $i<$countBook; $i++){
            $_SESSION["book$i"]='';
        }
        header("location: cart.php");
        exit();
    } else {
        echo "Không thể tạo mới bản ghi.";
    }

}
?>