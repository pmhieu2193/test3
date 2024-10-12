<?php

if(!isset($_POST["gotoCart"])) {
    include("connection.php");
    session_start();
    $id_user = $_SESSION["_id"];
    $filter = ['_id' => new MongoDB\BSON\ObjectId($_SESSION["_id"])];
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
    $j= $so_sach_duoc_muon-$n;
    $_SESSION["max"]=$j;
    header("Location: cart.php");
    exit();  

    //hay lúc này sẽ khởi tạo 1 session j


//nếu chưa có thì tạo, nếu có rồi thì check var?
//vậy cái này phải được làm mới lại khi nào?
//mỗi lần đăng nhập? mỗi lần bấm thêm sách vào giỏ, có khả năng hàm xử lý sách này sẽ được gọi nhiều lần
//->mỗi khi bấm thêm vào giỏ sẽ reset, trước mắt cứ thế đã
//trường hợp các session đang chứa sách

//khi mà mượn sách rồi thì mới gỡ session sách? để session sách luôn bằng với số sách được mượn

    //mình có thể làm nhiều hàm sử dụng cái cổn lù này để include các thứ mà, ko nhất thiết phải ôm 1 mình thằng nhóc này đâu


    // bấm thêm sách vào giở sẽ tạo 1 session, hay mình sẽ khai ra hết luôn ta
    // -> khai ra hết nè


    //có cần truy vấn trong đây ra hay lấy session mã rank luôn nhỉ?
    //thôi cái nào an toàn thì làm z


    //nếu chưa có session, tức chưa có gì trong giỏ, khởi tạo session rồi đưa vào
    //hay là cứ tạo bừa 4 cái, sau đó duyệt qua, kiểm tra có rỗng ko để nhét mã sách vào?
    //số session khởi tạo dựa vào số sách đc phép mượn

    //bấm mượn, bấm trả -> cập nhật lại sách được mượn? tức cập nhật, thêm bớt sesssion hiện có
    

}

?>