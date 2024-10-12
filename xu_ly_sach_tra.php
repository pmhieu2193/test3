<?php
if(!isset($_POST["action_return"])){
    echo 'hi';
    include("connection.php");
    $valuebtn=$_REQUEST["action_return"];
    echo $valuebtn;
    $id_ctsm = $_REQUEST["id"];
    $filter = ['_id' => new MongoDB\BSON\ObjectId($id_ctsm)];
    $timeZone = new DateTimeZone('Asia/Ho_Chi_Minh');
    $currentDateTime = new DateTime('now', $timeZone);
    $mongoDate = new MongoDB\BSON\UTCDateTime($currentDateTime->getTimestamp() * 1000);
    $collection = $database -> selectCollection("chi_tiet_sach_muon");
    $ctsm = $collection -> findOne($filter);
    $collectionBook = $database -> selectCollection('sach');
    $id_sach = ['_id' => new MongoDB\BSON\ObjectId($ctsm->ma_sach)];
    $sach = $collectionBook -> findOne($id_sach);
    $qty_sach = (int)$sach -> so_luong +1;
    $update =['$set' => ['so_luong' => $qty_sach]];
    $sachUpdate = $collectionBook -> updateOne($id_sach,$update);
    if($ctsm){echo "tìm thấy ctsm";}
    else{echo "lỗi";};
    $update =[ '$set' => ['kiem_tra_da_tra' => 1, 'thoi_gian_da_tra'=>$mongoDate]];
    $result = $collection -> updateOne($filter,$update);
    if($result){echo 'update thành công';
        header('location: yeu_cau_tra.php');
        exit();
    }
    else{echo 'update thất bại';}    
}

?>