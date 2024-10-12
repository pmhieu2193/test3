<?php

if(!isset($_POST["action_sach_muon"])){
    include("connection.php");
    session_start();
    $valueAction=$_REQUEST["action_sach_muon"];
    $id_book=$_REQUEST["id_book"];
    $id_ycm=$_REQUEST["id_ycm"];
    $collection = $database->selectCollection("chi_tiet_sach_muon");
    $filter = ['ma_yeu_cau_muon'=>$id_ycm, 'ma_sach' => $id_book];
    $sach_muon = $collection -> findOne($filter);
    if($sach_muon){
        if($valueAction== "gia_han"){
            //lấy ngày phải trả +7 ngày, đánh dấu là đã gia hạn
            $thoi_gian_tra=$sach_muon->ngay_phai_tra;                                                        ;
            $date_return = new DateTime($thoi_gian_tra->toDateTime()->format('Y-m-d H:i:s'));
            $date_return->modify('+7 days' );
            $mongoDate = new MongoDB\BSON\UTCDateTime($date_return->getTimestamp() * 1000);
            $update = ['$set' => ['kiem_tra_gia_han'=>1,'ngay_phai_tra' => $mongoDate]];
            $result = $collection->updateOne($filter, $update);
            if($result){
                echo 'gia hạn thành công';
                include('myorderlog.php');
                header('location: my_order.php');
                exit();
            }
            else{
                echo 'gia hạn thất bại';
            }
        }
        if($valueAction== "tra_sach"){
            $update = ['$set' => ['kiem_tra_da_tra'=>2]];
            $result = $collection->updateOne($filter, $update);
            if($result){
                echo 'gửi yêu cầu trả thành công';
                include('myorderlog.php');
                header('location: my_order.php');
                exit();
            }
            else{
                echo 'gửi yêu cầu trả thất bại';
            }
        }
    }
    else{
        echo 'Khong tim thay sach, khong the gia han';
    }
}

?>