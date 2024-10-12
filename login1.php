<?php
    include("connection.php");
    session_unset();
    session_destroy();

    if (isset($_POST['email']) && isset($_POST['password'])) {

        function validate($data)
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
    
        $email = validate($_POST['email']);
        $password = validate($_POST['password']);
        //mấy cái bắt lỗi này nên bắt ở js
        //mấy cái này chỉ để bắt lỗi khi tạo tài khoản trùng hay gì đó mà thôi
        if (empty($email)) {
            header("Location: login.php?error=Không được để trống Email");
            exit();
        } else if (empty($password)) {
            header("Location: login.php?error=Không được để trống tài khoản");
            exit();
        } else {
            $collection = $database->selectCollection('user');
            $login = $collection->findOne(['email'=> $email, 'mat_khau' => $password]);
            if($login->trang_thai_tai_khoan==-1){
                header("Location: login.php?error=Tài khoản của bạn đã bị khoá!");
                exit();
            }
            if ($login) {
                session_start();
                $_SESSION['_id'] = $login->_id;
            	$_SESSION['ten'] = $login->ten;
            	$_SESSION['ma_rank'] = $login->ma_rank;
                $_SESSION['email'] = $login->email;
                $_SESSION['trang_thai_tai_khoan'] = $login->trang_thai_tai_khoan;
                if($login->ma_rank == 0){
                    header("Location: checkin.php");
                    exit();
                }
                else{
                    header("Location: index.php");
                    exit();
                }
            }
            else{
                header("Location: login.php?error=Incorect!");
                exit();
            }

        }
}

?>