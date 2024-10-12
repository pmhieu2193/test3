<?php
  include("connection.php");
  if (!isset($_POST["user_action"])) {
    $submitButtonValue = $_REQUEST["user_action"];
    $document=$database->selectCollection("user");
    $id=$_REQUEST["id"];
    $filter = ['_id' => new MongoDB\BSON\ObjectId($id)];

    if($submitButtonValue=="accept"){
        echo'xác nhận';
        $update = ['$set' => ['trang_thai_tai_khoan' => 1]];
        $result = $document -> updateOne($filter, $update);
        if($result){
            header("location: user.php");
            exit();
        }
        else{
            header("location: user.php");
            exit();
        }

    }
    if($submitButtonValue=="cancel"){
        echo "từ chối";
        $update = ['$set' => ['trang_thai_tai_khoan' => -1]];
        $result = $document -> updateOne($filter, $update);
        if($result){
            header("location: user.php");
            exit();
        }
        else{
            header("location: user.php");
            exit();
        }
    }
    




}

?>