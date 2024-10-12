<?php
include("connection.php");
session_start();

if (!isset($_POST["wishlist_action"])) {
    $submitButtonValue = $_REQUEST["wishlist_action"];
    $collection = $database->selectCollection("yeu_thich");
    $collection2 = $database->selectCollection("sach");
    $id = (string)$_REQUEST['id_book'];
    $id_book=(string)$_REQUEST['id_book'];
    $filter = ['_id' => new MongoDB\BSON\ObjectId($id_book)];

    $bookInsert = $collection2->findOne($filter);
    if($bookInsert -> trang_thai_sach === 0){
        header("location: wishlist.php?error=Sách đã bị ẩn, vui lòng chọn sách khác!");
        exit();
    }
    $wishlist = $collection->find(['ma_user'=>(string)$_SESSION["_id"]]);
    foreach($wishlist as $doc){
        $idBook = (string)$doc -> ma_sach;
        $book = $collection2->findOne($filter);
        if($book->trang_thai_sach == 0){
            $userId = (string)$_SESSION["_id"];
            $deleteResult = $collection->deleteOne(['ma_sach' => $idBook , 'ma_user' => $userId]);
        }
    }

    $existingWishlistItem = $collection->findOne(['ma_sach' => $id, 'ma_user' => (string)$_SESSION["_id"]]);
    if (!$existingWishlistItem) {
        $update = ['ma_sach' => $id, 'ma_user' => (string)$_SESSION["_id"]];
        $result = $collection->insertOne($update);
        echo'test insert';
        if ($result) {
            header("location: wishlist.php");
            exit();
        } else {
            header("location: wishlist.php?error=insert_failed");
            exit();
        }
    } else {
        $_SESSION["duplicate_selection"] = true;
        header("location: wishlist.php?error= ");
        exit();
    }
}
?>


    




