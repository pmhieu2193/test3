<?php
include("connection.php");
session_start();
$collectionYCM = $database ->selectCollection("yeu_cau_muon");
$collectionRANK =$database ->selectCollection("rank");
$rank = $collectionRANK -> findOne(['ma_rank'=>(int) $_SESSION['ma_rank']]);
$so_gia_han_toi_da = $rank->so_luot_gia_han;
$id_user = (string) $_SESSION['_id'];
$count=$collectionYCM->countDocuments(["ma_user"=>$id_user]);
$ycm = $collectionYCM->find(["ma_user"=>$id_user]);
$_SESSION['gia_han'];
$countGiaHan=0;
if($count== 0){
    $_SESSION['gia_han']=0;
    header('location: my_order.php');
    exit();
}
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
                foreach ($ctsm as $documentCTSM) {
                    if($documentCTSM->kiem_tra_da_tra==0||$documentCTSM->kiem_tra_da_tra==2){
                            if($documentCTSM->kiem_tra_gia_han!=0){
                                $countGiaHan++;
                                echo 'alo';
                            }
                    }
                }
            }                       
        }                
    }
    $_SESSION['gia_han']=(int)$countGiaHan;
    header('location: my_order.php');
    exit();

}
?>