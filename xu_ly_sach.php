<?php
if(!isset($_POST["addToCart"])) {
    include("connection.php");
    session_start();
    $id_user = $_SESSION["_id"];
    $id_book=$_REQUEST["id_book"];
    $id_book=(string)$id_book;
    $filter = ['_id' => new MongoDB\BSON\ObjectId($_SESSION["_id"])];
    $collection = $database ->selectCollection('user');
    $user = $collection -> findOne($filter);
    $ma_rank = $user -> ma_rank;
    $collection2 = $database ->selectCollection('rank');
    $rank = $collection2 -> findOne(["ma_rank"=>$ma_rank]);
    //hay là so sánh số sách đang mượn(query) và số sách được mượn để được phép khởi tạo số sách trong giỏ
    //query chi tiết sách mượn nhứng sách nào chưa trả
    //có khả năng phải sửa bảng chi tiết sách mượn và truyền id user vào ấy chứ. haizzzz
    $so_sach_duoc_muon = $rank -> so_sach_duoc_muon;
    //cái số sách được mượn khéo khi phải cho nó là session được khởi tạo ở login
    //tạm thời test thử ở đây đã
    //lấy từng yêu cầu mượn của user -> duyệt qua từng chi tiết sách mượn -> đếm sách nào chưa trả

    $collectionYCM = $database->selectCollection('yeu_cau_muon');
    //cái này mới là chỉ findone, chứ mình nghĩ là find khác, cả ở bên trên cũng z, phải có vòng foreach
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
    //tận dụng biến j này như nào bây giờ?
    $_SESSION["max"]=$j;

    //hay lúc này sẽ khởi tạo 1 session j


//nếu chưa có thì tạo, nếu có rồi thì check var?
//vậy cái này phải được làm mới lại khi nào?
//mỗi lần đăng nhập? mỗi lần bấm thêm sách vào giỏ, có khả năng hàm xử lý sách này sẽ được gọi nhiều lần
//->mỗi khi bấm thêm vào giỏ sẽ reset, trước mắt cứ thế đã
//trường hợp các session đang chứa sách
    if(!isset($_SESSION['book0'])){
        echo 'chưa có book0';
        //if isset để check var mà gỡ
        for ($i = 0; $i < $j; $i++) {
            $_SESSION["book$i"] = '';
            echo '|'.$i.'| session đc tạo ';
        }
        $_SESSION['book1']=$id_book; 
        header("Location: cart.php");
        exit();  
            
    }
    //nếu đã tạo rồi sẽ xem có chỗ nào trống ko
    if(isset($_SESSION['book0'])){
        //if isset để check var mà gỡ
        //không thêm sách trùng nhau
        for ($i = 0; $i < $j; $i++) {
            if(empty($_SESSION["book$i"])){
                //vị trí i là rỗng
                //kiểm tra id book có trùng với seesion nào đằng trước ko, thôi cứ để xử lý sau z
                for($o=0; $o<$i ; $o++){
                    if($_SESSION["book$o"]==$id_book){
                        header("Location: cart.php?error=Bạn đã thêm sách này trước đó rồi");
                        exit();
                    }
                }
                $_SESSION["book$i"] = $id_book;
                echo 'chèn: '.$i.'|'.$_SESSION["book$i"];
                echo 'vi tri nay can duoc chen vo'.$i;
                header("Location: cart.php");
                exit();
            }
        }
        header("Location: cart.php?error=Số sách trong giỏ của bạn đã đạt tối đa");
        exit();      
    }

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