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
    <?php   
            include("navadmin.php");
            include("connection.php");
            $collection = $database -> selectCollection("user");
            if(isset($_GET["value_search"])){
                $search = $_REQUEST ["value_search"];
                $num = $_REQUEST ["num"];
                $role = $_REQUEST["role"];
                $status= $_REQUEST["status"];
                $regex = new MongoDB\BSON\Regex($search);
                //echo 'role'.$role.'--'.'status'.$status;
                if (!is_numeric($role)||!is_numeric($status)){
                    if(!is_numeric($role)&&(is_numeric($status))){
                        $status=(int)$status;
                        $filter = ['email' => $regex, 'trang_thai_tai_khoan' => $status];
                    }
                    if(is_numeric($role)&&(!is_numeric($status))){
                        $role=(int)$role;
                        $filter = ['email' => $regex, 'ma_rank' => $role];
                    }
                    if(!is_numeric($status)&&(!is_numeric($status))){
                        $filter = ['email' => $regex];
                    }
                }
                else{
                    $status=(int)$status;
                    $role=(int)$role;
                    $filter = ['email' => $regex, 'ma_rank' => $role, 'trang_thai_tai_khoan' => $status];
                    echo 4;
                }
                if(!is_numeric($num)){
                    $options = [ 'sort' => ['tao_luc' => -1]];
                }
                else{
                    $num=(int)$num;
                    $options = ['limit' => $num,
                    'sort' => ['tao_luc' => -1]];
                }

                $user = $collection->find($filter, $options);
                $role="";
            }
            else{
                $user = $collection -> find() ;
            }
    ?>
    <!--products list-->
    <div class="product-listing">
        <div class="add-product">
            <p class="add-product-title">quản lý tài khoản</p>
            <button class="btn btn-new-product" id="new-user" onclick="location.href='sigup.php'">	&#43; thêm user</button>
        </div>
            <form id="ok" action = "user.php" type="get">
            <div class="box">
            <select name="num" class="select" onchange="submitForm()">
                <option value=10 <?php if(isset($_GET["num"])&&(int)$_GET["num"]===10) echo ' selected';?>>hiển thị: 10 user</option>
                <option value=20 <?php if(isset($_GET["num"])&&(int)$_GET["num"]===20) echo ' selected';?>>hiển thị: 20 user</option>
                <option value=30 <?php if(isset($_GET["num"])&&(int)$_GET["num"]===30) echo ' selected';?>>hiển thị: 30 user</option>
                <option value='' <?php if(isset($_GET["num"])&&(int)$_GET["num"]==='') echo ' selected';?>>hiển thị tất cả</option>
            </select>
            <select name="status" class="select" onchange="submitForm()">
                <option value="" <?php if(isset($_GET["status"])&&empty($_GET["status"])&&!is_numeric($_GET["status"])) echo ' selected';?>>Tất cả trạng thái</option>
                <option value=0 <?php if(isset($_GET["status"])&&(int)$_GET["status"]===0&&is_numeric($_GET["status"])) echo ' selected';?>>Chưa được duyệt</option>
                <option value=1 <?php if(isset($_GET["status"])&&(int)$_GET["status"]===1) echo ' selected';?>>Đã duyệt</option>
                <option value=-1 <?php if(isset($_GET["status"])&&(int)$_GET["status"]===-1) echo ' selected';?>>Đã từ chối</option>
                <option value=2 <?php if(isset($_GET["status"])&&(int)$_GET["status"]===2) echo ' selected';?>>Đã khoá</option>
            </select>
            <select name="role" class="select" onchange="submitForm()">
                <option value="" <?php if(isset($_GET["role"])&&(int)$_GET["role"]===''&&!is_numeric($_GET["status"])) echo ' selected';?>>Phân loại: Tất cả user</option>
                <?php $collectionselect = $database -> selectCollection("rank");
                $rankselect = $collectionselect -> find();
                foreach($rankselect as $rank){
                    echo '<option value='.$rank->ma_rank;
                    if(isset($_GET["role"])){ if((int)$_GET["role"]===(int)$rank->ma_rank&&is_numeric($_GET["role"])) echo ' selected';}
                    echo '>'.$rank->ten_rank.'</option>';
                }
                ?>
            </select>
        </div>
            <div class="search" style="margin-left: 67%">
                <input type="text" name= "value_search" placeholder="Tìm kiếm bằng email...">
                <button type= "submit" class="search-btn">&#9906; Tìm kiếm</button>                       
            </div>
            </form>
        </div>
        <div class="small-container oder-page">
        <?php if (isset($_GET['error'])) { 
            echo '<h3>'.$_GET['error'].'</h3>'; }?>
            <table>
                <tr>
                    <th>Email</th>
                    <th>Tên người dùng</th>
                    <th>Trạng thái tài khoản</th>
                    <th>Quyền hạn</th>
                    <th>Ngày tạo</th>
                    <th class="table-btn-zone">Hành động</th>
                </tr>
                <?php
                //những cái sort, tìm kiếm sẽ xử lý collection này
                //$user = $collection -> find(["trang_thai_tai_khoan" => 1]) ;
                foreach ($user as $document) {
                    //mã yêu cầu mượn
                    //$id= (string)$document->_id;
                    echo '<tr>
                    <td><a>'.$document->email.'</a>
                    <td><a>'.$document->ten.'</a>';
                    if($document->trang_thai_tai_khoan===0){
                        echo '<td><p style="color: red">Chưa được duyệt</p></td>';
                    }
                    if($document->trang_thai_tai_khoan===1){
                        echo '<td><p style="color: green">Đã được duyệt</p></td>';
                    }
                    if($document->trang_thai_tai_khoan===2){
                        echo '<td><p style="color: red">Tài khoản đã bị khoá</p></td>';
                    }
                    if($document->trang_thai_tai_khoan===-1){
                        echo '<td><p style="color: red">Đã từ chối</p></td>';
                    }
                    
                    $collection2 = $database -> selectCollection("rank") ;
                    $rank = $collection2 -> findOne(["ma_rank" => $document->ma_rank]);
                    if($document->ma_rank===0){
                        echo '<td><p style="color: red">'.$rank->ten_rank.'</p></td>';
                    }
                    else{
                        echo '<td><p style="color: blue">'.$rank->ten_rank.'</p></td>';
                    }
                    $date1 = $document->tao_luc;
                    $date = new DateTime($date1->toDateTime()->format('Y-m-d H:i:s'));
                    $id = (string)  $document->_id;
                    echo '<td>'.$date->format('d-m-Y').'</td><td>';
                    if($document->trang_thai_tai_khoan===0){
                        echo '<form action="action_user.php" type="post"><input type="hidden" name="id" value="'.$id.'"><button type="submit" name="user_action" value="accept" class="confirm-btn">Duyệt</button><button type="submit" name="user_action" value="cancel" class="cancel-btn">Từ chối</button></form>';
                    }
                    echo '<form action="userDetail.php" type="post"><input type="hidden" name="id" value="'.$id.'"><button type="submit" name="getUser" class="confirm-btn" style="background-color: green;">Chi tiết</button></form></td>
                </tr>';
                }
                
                ?>
        </table>
        </div>
    </div>

    <script src="js/admin.js"></script>
</body>
</html>