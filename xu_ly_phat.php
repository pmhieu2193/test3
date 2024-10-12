<?php
if(!isset($_POST["send"])) {
    include("connection.php");
    echo 'đã vào form';
    $idycm=$_REQUEST["id"];
    $timeZone = new DateTimeZone('Asia/Ho_Chi_Minh');
    $currentDateTime = new DateTime('now', $timeZone);
    $mongoDate = new MongoDB\BSON\UTCDateTime($currentDateTime->getTimestamp() * 1000);
    $collection = $database -> selectCollection("phat");
    $ly_do=$_REQUEST["lydo"];
    $filer=['_id' => new MongoDB\BSON\ObjectId($idycm)];
    $colectionycm = $database -> selectCollection("chi_tiet_sach_muon");
    $ctycm = $colectionycm -> findOne($filer);
    if($ctycm)echo 'tìm thấy ctycm';
    else echo 'ko thấy ctycm';
    $id_sach = $ctycm -> ma_sach;
    $filersach=['_id' => new MongoDB\BSON\ObjectId($id_sach)];
    $colectionsach = $database -> selectCollection("sach");
    $sach = $colectionsach -> findOne($filersach);
    if($sach)echo "thay sach";
    else echo'ko thay sach';
    if(!isset($_REQUEST["cannotreturn"])){
        echo 'ko check box';
        $numbook= (int)$sach -> so_luong +1;
        $update = ['$set' => ['so_luong' => $numbook]];
        $updatesach = $colectionsach -> updateOne($filersach, $update);
        if($updatesach)echo 'update sach thanh cong';
        else echo 'update sach that bai';
    }
    $updatectycm =[ '$set' => ['kiem_tra_da_tra' => 1, 'thoi_gian_da_tra'=>$mongoDate]];
    $result = $colectionycm -> updateOne($filer,$updatectycm);
    if($result)echo 'xac nhan tra thanh cong';
    $new =[
        'thoi_gian_phat' => $mongoDate,
        'ly_do' => $ly_do,
        'ma_chi_tiet_sach_muon' => (string)$idycm
    ];
    $phat = $collection -> insertOne($new);
    if($phat){echo 'them phat thanh cong';
    header("location: yeu_cau_tra.php" );
    exit();
}
    else echo'ko the them phat';

}





?>