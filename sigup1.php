<?php
    include("connection.php");
    session_start(); 

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];
        $password = $_POST["password"];
        $name = $_POST["name"];
        $number = $_POST["number"];
        //xử lý chỗ cccd
        $cccd = $_POST["cccd"];
        $address = $_POST["address"];
        $gender = $_POST["gender"];
        $gender =(int)$gender;
        $birth = $_POST["date"];
        $rank= $_POST["rank"];

        $currentDate = new DateTime();

        $mongoDate = new MongoDB\BSON\UTCDateTime(strtotime($birth) * 1000);

        $create_at = new MongoDB\BSON\UTCDateTime($currentDate->getTimestamp() * 1000);

        //find one email, cccd
        $collection = $database->selectCollection('user');
        $sigup = $collection->findOne(['email'=> $email]);
        if ($sigup) {;
                header("Location: sigup.php?error=Email này đã được sử dụng");
                exit();
        }
        $sigup1 = $collection->findOne(['cccd'=> $cccd]);
        if ($sigup1) {;
            header("Location: sigup.php?error=CCCD này đã được đăng ký trước đó");
            exit();
        }

        $collection = $database->selectCollection("user");

        $newRecord = [
            'email' => $email,
            'mat_khau' => $password,
            'ten' => $name,
            'gioi_tinh' => $gender,
            'cccd' => $cccd,
            'ngay_sinh' => $mongoDate,
            'sdt' => $number,
            'dia_chi' => $address,
            'tao_luc' => $create_at,
            'check_in' => $create_at,
            'trang_thai_tai_khoan' => 0,
            'ma_rank' => 1
          ];
          
          // Thêm bản ghi vào collection
        $insertOneResult = $collection->insertOne($newRecord);
        if ($insertOneResult->getInsertedCount() > 0) {
            echo "Bản ghi đã được thêm vào collection thành công.";
            header("Location: login.php?error=Tạo tài khoản thành công, vui vòng đăng nhập");
            exit();
          } else {
            header("Location: sigup.php?error=Tạo tài khoản thất bại, vui lòng thử lại sau!");
            exit();
          }


    }
?>