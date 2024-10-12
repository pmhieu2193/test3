<?php
  include("connection.php");
  if (!isset($_POST["action"])) {
    $submitButtonValue = $_REQUEST["action"];
    if ($submitButtonValue === "update") {
    $collection = $database -> selectCollection("user");
    $id=$_REQUEST["id"];
    $filter = ['_id' => new MongoDB\BSON\ObjectId($id)];
    $ten = (string)$_REQUEST["ten"];
    $cccd= (string)$_REQUEST["cccd"];
    $number =  (string)$_REQUEST["number"];
    $address = (string)$_REQUEST["address"];
    $pass= (string)$_REQUEST["pass"];
    $status= (int)$_REQUEST["status"];
    $gender=(int)$_REQUEST["gender"];
    $birth = $_REQUEST["date"];
    $mongoDate = new MongoDB\BSON\UTCDateTime(strtotime($birth) * 1000);
    $rank= (int)$_REQUEST["rank"];
    //cái này nên xử lý bằng js
    //if(empty($tag)) {
      //echo" chưa tick kìa";
      //header("location: userdetail.php?id=$id");
      //exit();
    //}
    $update = ['$set' => ['mat_khau' => $pass, 'ten' => $ten, 'gioi_tinh' => $gender, 'cccd' => $cccd, 'ngay_sinh' => $mongoDate, 'sdt' => $number, 'dia_chi' => $address, 'trang_thai_tai_khoan' => $status, 'ma_rank' => $rank ]];
    if(!empty($gender)||!empty($birth)){
      if(!empty($gender)&&empty($birth)){
        $update = ['$set' => ['mat_khau' => $pass, 'ten' => $ten, 'cccd' => $cccd, 'ngay_sinh' => $mongoDate, 'sdt' => $number, 'dia_chi' => $address, 'trang_thai_tai_khoan' => $status, 'ma_rank' => $rank ]];
      }
      if(empty($gender)&&!empty($birth)){
        $update = ['$set' => ['mat_khau' => $pass, 'ten' => $ten, 'gioi_tinh' => $gender, 'cccd' => $cccd, 'sdt' => $number, 'dia_chi' => $address, 'trang_thai_tai_khoan' => $status, 'ma_rank' => $rank ]];
      } 
      if(empty($gender)&&empty($birth)){
        $update = ['$set' => ['mat_khau' => $pass, 'ten' => $ten, 'cccd' => $cccd, 'sdt' => $number, 'dia_chi' => $address, 'trang_thai_tai_khoan' => $status, 'ma_rank' => $rank ]];
      }
    }

    $result = $collection->updateOne($filter, $update);
    if ($result) {
      header("location: user.php");
      exit();
    }
    else{
      echo 'Cập nhật user thất bại, có lỗi xảy ra';
      exit();
    }
  
  }
    if ($submitButtonValue === "delete") {
        $id=$_REQUEST["id"];
        $filter = ['_id' => new MongoDB\BSON\ObjectId($id)];
        $collection = $database -> selectCollection("user");
        $result = $collection->deleteOne($filter);
        if($result){
          header('location: user.php?error=Đã xoá User!');
          exit();
        }
        else echo 'xoá thất bại';
      }
}
?>