<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN</title>

    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="css/cart.css">
    <link rel="stylesheet" href="css/search.css">
    <link rel="stylesheet" href="css/sigup.css">
    <link rel="stylesheet" href="css/admin.css">

</head>
<body>
    <img src="img/loader.gif" class="loader" alt="">

    <div class="alert-box">
        <img src="img/error.png" class="alert-img" alt="">
        <p class="alert-msg">Thông báo lỗi</p>
    </div>
    <img src="img/dark-logo.png" class="logo" alt="">

    <!--become seller element-->
    <!--apply form-->
    <?php
    include("navadmin.php");
    include("connection.php");
    $collection = $database-> selectCollection("yeu_cau_muon");
    if(isset($_GET["value_search"])){
        $collectionUser = $database-> selectCollection("user");
        $search = $_REQUEST ["value_search"];
        $num = (int)$_REQUEST ["num"];
        $status = $_REQUEST["status"];
        $regex = new MongoDB\BSON\Regex($search);
    //dùng if else để lấy option trên sort, value trên limit lấy từ href
        $options = [
            'sort' => ['ngay_yeu_cau' => -1],
            'limit' => $num
        ];
        if (!is_numeric($status)){
            $filter = ['ma_user' => $regex];
        }
        else{
            $status = (int)$status;
            $filter = ['ma_user'=> $regex, 'trang_thai_yeu_cau' => $status];
        }

        $result = $collection->find($filter, $options);

    }
    else{
        $options = [
            'sort' => ['ngay_yeu_cau' => -1]
        ];
        $result= $collection->find([], $options);
    }
    //điều kiện hiển thị, filter được đặt ở đây đê xứ lý
    ?>
    <div class="order-list">
        <div class="add-product">
            <p class="add-product-title">Danh sách yêu cầu mượn</p>
        </div>
        <form id="ok" action = "yeu_cau_muon.php" type="get">
        <div class="box">
            <select name="num" class="select" onchange="submitForm()">
                <option value='' <?php if(isset($_GET["num"])&&(int)$_GET["num"]==='') echo ' selected';?>>Hiển thị: tất cả</option>
                <option value=10 <?php if(isset($_GET["num"])&&(int)$_GET["num"]===10) echo ' selected';?>>10 mục</option>
                <option value=20 <?php if(isset($_GET["num"])&&(int)$_GET["num"]===20) echo ' selected';?>>20 mục</option>
                <option value=30 <?php if(isset($_GET["num"])&&(int)$_GET["num"]===30) echo ' selected';?>>30 mục</option>
            </select>
            <select name="status" class="select" onchange="submitForm()">
                <option value="" <?php if(isset($_GET["status"])&&empty($_GET["status"])&&!is_numeric($_GET["status"])) echo ' selected';?>>Lọc theo: Tất cả</option>
                <option value=0 <?php if(isset($_GET["status"])&&(int)$_GET["status"]===0&&is_numeric($_GET["status"])) echo ' selected';?>>Chưa duyệt</option>
                <option value=1 <?php if(isset($_GET["status"])&&(int)$_GET["status"]===1&&is_numeric($_GET["status"])) echo ' selected';?>>Đã duyệt</option>
                <option value=-1 <?php if(isset($_GET["status"])&&(int)$_GET["status"]===-1&&is_numeric($_GET["status"])) echo ' selected';?>>Đã từ chối</option>
            </select>
            <div class="search-order">
                <input name="value_search" type="text" placeholder="Tìm kiếm theo mã user...">
                <button type="submit" class="search-btn">&#9906; Tìm kiếm</button>                       
            </div>
        </div>
</form>
        <div class="small-container oder-page">
            <table>
                <tr>
                    <th>Mã yêu cầu#</th>
                    <th>Email</th>
                    <th>Tên bạn đọc</th>
                    <th>Rank</th>
                    <th>Thời gian mượn</th>
                    <th>Ngày trả dự tính</th>
                    <th>Tình trạng yêu cầu</th>
                    <th>Hành động</th>
                </tr>
                    <?php foreach ($result as $document) {
                        $id= $document->ma_user;
                        $filter = ['_id' => new MongoDB\BSON\ObjectId($id)];
                        $collection2 = $database->selectCollection("user");
                        $user= $collection2->findOne($filter);
                    //mã yêu cầu mượn
                    //$id= (string)$document->_id;
                    echo '
                <tr><td><p>'.(string)$document->_id.'</p>';
                if($user){
                    $collection3 = $database->selectCollection('rank');
                    $rank=  $collection3 -> findOne(['ma_rank'=> $user->ma_rank]);
                    echo'
                    <td><p>'.$user->email.'</p>
                    <td><p>'.$user->ten.'</p></td>
                    <td><p>'.$rank->ten_rank.'</p></td>';
                }
                else{
                    echo'<td><p style="color: red">Tài khoản đã bị xoá</p>
                    <td><p>None</p></td>
                    <td><p>None</p></td>';
                }
                $date = $document->ngay_yeu_cau;
                $dateNYC = new DateTime($date->toDateTime()->format('Y-m-d H:i:s'));
                $timestamp =$document -> gio_yeu_cau;
                $utcDateTime = new MongoDB\BSON\UTCDateTime($timestamp * 1000);
                // Chuyển đổi UTCDateTime thành đối tượng DateTime trong PHP
                $dateTime = $utcDateTime->toDateTime();
                echo '
                <td><a>'.$dateNYC->format('d-m-Y').' / '.$dateTime->format('H:i:s').'</a></td>
                <td><a>'.$dateNYC->modify('+7 days' )->format('d-m-Y').'</a></td>';
                if($user){
                if($document->trang_thai_yeu_cau==0){
                    echo '<td><a>Đang đợi duyệt</a></td>';
                    echo'
                <td><form action="chi_tiet_ycm.php" type="post"><input type="hidden" name="id" value="'.(string)$document->_id.'"><button type="submit" name="getOrder" class="confirm-btn">Hành động</button></form><form action="xu_ly_YCM.php" type="post"><input type="hidden" name="id_ycm" value="'.(string)$document->_id.'"><button name="actionYCM" value="cancel" class="cancel-btn">Huỷ</button><input type="hidden" name="orderpage" value="1"></form></td></tr>';
                } 
                if($document->trang_thai_yeu_cau==1){
                    echo '<td><a style="color: green">Đã duyệt</a></td>';
                    echo'<td><form action ="chi_tiet_ycm.php" type="post"><button type="submit" name="getOrder" class="confirm-btn" style="background-color: green;"><input type="hidden" name="id" value="'.(string)$document->_id.'">Chi tiết</button></form></td></tr>';
                }
                if($document->trang_thai_yeu_cau==-1){
                    echo '<td><a style="color: red">Đã từ chối</a></td>';
                    echo'<td><form action ="chi_tiet_ycm.php" type="post"><button type="submit" name="getOrder" class="confirm-btn" style="background-color: green;"><input type="hidden" name="id" value="'.(string)$document->_id.'">Chi tiết</button></form></td></tr>';
                }}
                else{
                    echo '<td>None</td><td>None</td>';
                }
                    
                }?>
            </div>
        </table>
    </div>

    <script src="js/admin.js"></script>
</body>
</html>