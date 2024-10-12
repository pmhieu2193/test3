<!DOCTYPE html>
<html lang="vi">
<?php
include("connection.php");
error_reporting(0);
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa sản phẩm</title>
    <link rel="stylesheet" href="css/sigup.css">
    <link rel="stylesheet" href="css/addProduct.css">
    <link rel="stylesheet" href="css/admin2.css">

</head>

<body>
    <img src="img/loader.gif" class="loader" alt="">
    <div class="alert-box">
        <img src="img/error.png" class="alert-img" alt="">
        <p class="alert-msg">Lỗi</p>
    </div>
    <img src="img/dark-logo.png" class="logo" alt="">

    <?php
    $collection = $database->selectCollection("the_loai");

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['form_submitted'])) {
        $ma_the_loai = (int)$_POST['idcate'];
        $ten_the_loai = $_POST['name'];

        $data = [
            'ma_the_loai' => $ma_the_loai,
            'ten_the_loai' => $ten_the_loai,
        ];

        $product_id = isset($_POST['product_id']) ? $_POST['product_id'] : null;

        if (empty($product_id)) {
            $result = $collection->insertOne($data);
            if ($result->getInsertedCount() > 0) {
                echo 'Success';
            } else {
                echo 'Error';
            }
        } else {
            $result = $collection->updateOne(['_id' => new MongoDB\BSON\ObjectID($product_id)], ['$set' => $data]);

            if ($result->getModifiedCount() > 0) {
                echo 'Success';
            } else {
                echo 'Error';
            }
        }
        header("Location: category.php");
        exit;
    }

    if (isset($_GET['id'])) {
        $product_id = $_GET['id'];
        $result = $collection->findOne(['_id' => new MongoDB\BSON\ObjectID($product_id)]);
        if ($result) {
    ?>
            <form method="post" enctype="multipart/form-data">
                <h3 class="head-form">Chỉnh sửa Thể Loại Sách</h3>
                <label class="label-form" for="">Mã Thể Loại</label>
                <input class="text-form" type="text" id="idcate" name="idcate" value="<?= $result['ma_the_loai'] ?>">
                <label class="label-form" for="">Tên Thể Loại</label>
                <input class="text-form" type="text" id="name" name="name" value="<?= $result['ten_the_loai'] ?>">
                <input type="hidden" name="product_id" value="<?= $product_id ?>">
                <input type="hidden" name="form_submitted" value="1">
                <button class="submit-form" type="submit">Cập nhật</button>
                <a class="submit-form2" href="admin.php">Hủy</a>
            </form>
        <?php
        } else {
            echo "Không tìm thấy nhà xuất bản.";
        }
    } else {
        ?>
        <form method="post" enctype="multipart/form-data">
            <h3 class="head-form">Thêm Thể Loại Sách</h3>
            <label class="label-form" for="">Mã Thể Loại</label>
            <input class="text-form" type="text" id="idcate" name="idcate" value="">
            <label class="label-form" for="">Tên Thể Loại</label>
            <input class="text-form" type="text" id="name" name="name" value="">
            <input type="hidden" name="form_submitted" value="1">
            <button class="submit-form" type="submit">Thêm</button>
            <a class="submit-form2" href="admin.php">Hủy</a>
        </form>
    <?php
    }
    ?>

    <script src="js/addProduct.js"></script>

</body>

</html>