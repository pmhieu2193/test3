<!DOCTYPE html>
<html lang="vi">
<?php
include("connection.php");
?>

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
    <?php include("navadmin.php")?>
    <!--products list-->
    <div class="product-listing">
        <div class="add-product">
            <p class="add-product-title">Quản lý Nhà Xuất Bản</p>
            <a href="editPublisher.php"><button class="btn btn-new-product" id="new-user">&#43;Thêm Nhà Xuất Bản</button></a>
        </div>
        <div class="small-container oder-page">
            <table>
                <tr>
                    <th>Tên Nhà Xuất Bản</th>
                    <th>Địa Chỉ Nhà Xuất Bản</th>
                    <th>Số Điện Thoại</th>
                    <th class="table-btn-zone">Hành động</th>
                </tr>
                <?php
                error_reporting(0);
                $collection = $database->selectCollection('nha_xuat_ban');
                $result = $collection->find([]);

                foreach ($result as $document) {
                    echo '<tr>';
                    echo '<td><a>' . $document->ten_nxb . '</a></td>';
                    echo '<td><a>' . $document->dia_chi_nxb . '</a></td>';
                    echo '<td><p style="color: red">' . $document->sdt_nxb . '</p></td>';
                    echo '<td><a href="editPublisher.php?id=' . $document->_id . '" class="confirm-btn">chỉnh sửa</a>';
                ?>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="delete_id" value="<?= $document->_id ?>">
                        <button type="submit" class="cancel-btn" onclick="return confirm('Bạn có chắc chắn muốn xóa không?')">xoá</button>
                    </form>
                <?php
                    echo '</td>';
                    echo '</tr>';
                }
                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {
                    $delete_id = $_POST['delete_id'];
                    $result = $collection->deleteOne(['_id' => new MongoDB\BSON\ObjectID($delete_id)]);
                    header("Location: publisher.php");
                    exit;
                }
                ?>

        </div>
        </table>
    </div>

    <script src="js/admin.js"></script>
</body>

</html>