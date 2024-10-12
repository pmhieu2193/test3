<!DOCTYPE html>
<html lang="vi">
<?php
include("connection.php");
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
    <img src="img/dark-logo.png" class="logo" alt="">

    <?php
    $result = [];
    $ranks = $database->selectCollection("rank")->find();
    $categories = $database->selectCollection("the_loai")->find();
    $publishers = $database->selectCollection("nha_xuat_ban")->find();
    $collection = $database->selectCollection("sach");
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['form_submitted'])) {
        $ten_sach = $_POST['name'];
        $so_luong = (int)$_POST['quantity'];
        $mo_ta = $_POST['description'];
        $tac_gia = $_POST['author'];
        $ngon_ngu = $_POST['language'];
        $nam_xuat_ban = $_POST['year'];
        $vi_tri = $_POST['location'];
        $ma_rank = (int)$_POST['rank'];
        $ma_the_loai = (int)$_POST['category'];
        $ma_nxb = (int)$_POST['publisher'];
        $ngay_nhap = $_POST['date'];
        $product_id = $_POST['product_id'];
        $anh_bia = "img/" . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "img/" . $_FILES['image']['name']);
        $data = [
            'ten_sach' => $ten_sach,
            'so_luong' => $so_luong,
            'mo_ta' => $mo_ta,
            'ngay_nhap' => new MongoDB\BSON\UTCDateTime(strtotime($ngay_nhap) * 1000),
            'tac_gia' => $tac_gia,
            'ngon_ngu' => $ngon_ngu,
            'nam_xuat_ban' => $nam_xuat_ban,
            'anh_bia' => $anh_bia,
            'vi_tri' => $vi_tri,
            'trang_thai_sach' => 1,
            'ma_rank' => $ma_rank,
            'ma_the_loai' => $ma_the_loai,
            'ma_nxb' => $ma_nxb,
            
        ];
        if($_FILES['image']['name']===''){
            $data = [
                'ten_sach' => $ten_sach,
                'so_luong' => $so_luong,
                'mo_ta' => $mo_ta,
                'ngay_nhap' => new MongoDB\BSON\UTCDateTime(strtotime($ngay_nhap) * 1000),
                'tac_gia' => $tac_gia,
                'ngon_ngu' => $ngon_ngu,
                'nam_xuat_ban' => $nam_xuat_ban,
                'vi_tri' => $vi_tri,
                'trang_thai_sach' => 1,
                'ma_rank' => $ma_rank,
                'ma_the_loai' => $ma_the_loai,
                'ma_nxb' => $ma_nxb,
                
            ];
        }
        if (empty($product_id)) {
            //Tự động tăng
            $lastProduct = $collection->findOne([], ['sort' => ['ma_sach' => -1]]);
            $lastMaSach = $lastProduct ? $lastProduct['ma_sach'] : 0;
            $maSach = $lastMaSach + 1;
            $data['ma_sach'] = $maSach;
            $result = $collection->insertOne($data);
            if ($result->getInsertedCount() > 0) {
                echo 'Thêm sách thành công!';
            } else {
                echo 'Thêm sách thất bại';
            }
        } else {
            $result = $collection->updateOne(['_id' => new MongoDB\BSON\ObjectID($product_id)], ['$set' => $data]);

            if ($result->getModifiedCount() > 0) {
                echo 'Cập nhật sách thành công!';
            } else {
                echo 'Không có sự thay đổi';
            }
        }
        header("Location: admin.php");
        exit;
    }
    if (isset($_GET['id'])) {
        $product_id = $_GET['id'];
        $result = $collection->findOne(['_id' => new MongoDB\BSON\ObjectID($product_id)]);
        if ($result) {
    ?>
            <form class="form-total" method="post" enctype="multipart/form-data">
                <h3 class="head-form">Chỉnh Sửa Sách</h3>
                <label class="label-form" for="">Tên Sách</label>
                <input class="text-form" type="text" id="name" name="name" value="<?= $result['ten_sach'] ?>">
                <label class="label-form" for="">Số Lượng</label>
                <input class="text-form" type="number" id="quantity" name="quantity" value="<?= $result['so_luong'] ?>">
                <label class="label-form" for="">Mô Tả</label>
                <input class="text-form" type="text" id="description" name="description" value="<?= $result['mo_ta'] ?>">
                <label class="label-form" for="">Tác Giả</label>
                <input class="text-form" type="text" id="author" name="author" value="<?= $result['tac_gia'] ?>">
                <label class="label-form" for="">Ngôn Ngữ</label>
                <input class="text-form" type="text" id="language" name="language" value="<?= $result['ngon_ngu'] ?>">
                <label class="label-form" for="">Năm Xuất Bản</label>
                <input class="text-form" type="text" id="year" name="year" value="<?= $result['nam_xuat_ban'] ?>">
                <label class="label-form" for="">Vị Trí</label>
                <input class="text-form" type="text" id="location" name="location" value="<?= $result['vi_tri'] ?>">
                <label class="label-form" for="">Cấp Bậc</label>
                <select id="rank" name="rank">
                    <?php foreach ($ranks as $rank) : ?>
                        <option value="<?= $rank['ma_rank'] ?>" <?= (isset($result['ma_rank']) && $result['ma_rank'] == $rank['ma_rank']) ? 'selected' : '' ?>>
                            <?= $rank['ten_rank'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label class="label-form" for="">Thể Loại Sách</label>
                <select id="category" name="category">
                    <?php foreach ($categories as $category) : ?>
                        <option value="<?= $category['ma_the_loai'] ?>" <?= (isset($result['ma_the_loai']) && $result['ma_the_loai'] == $category['ma_the_loai']) ? 'selected' : '' ?>>
                            <?= $category['ten_the_loai'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label class="label-form" for="">Nhà Xuất Bản</label>
                <select id="publisher" name="publisher">
                    <?php foreach ($publishers as $publisher) : ?>
                        <option value="<?= $publisher['ma_nxb'] ?>" <?= (isset($result['ma_nxb']) && $result['ma_nxb'] == $publisher['ma_nxb']) ? 'selected' : '' ?>>
                            <?= $publisher['ten_nxb'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="hidden" name="product_id" value="<?= $product_id ?>">
                <?php
                if(!isset($_GET['id']))
                    echo '<label for="date">Ngày Nhập:</label>
                        <input type="date" id="date" name="date" value="'.$result['ngay_nhap'].'" required><br><br>';
                ?>
                <label for="image">Ảnh:</label>
                <input type="file" id="image" name="image" accept="image/*" value="<?= $result['anh_bia'] ?>"><br><br>
                <input type="hidden" name="form_submitted" value="1">
                <button class="submit-form" type="submit">Cập nhật</button>
                <a class="submit-form2" href="admin.php">Hủy</a>
            </form>
        <?php
        } else {
            echo "Không tìm thấy sản phẩm.";
        }
    } else {
        ?>
        <form class="form-total" method="post" enctype="multipart/form-data">
            <h3 class="head-form">Thêm Sách</h3>
            <label class="label-form" for="">Tên Sách</label>
            <input class="text-form" type="text" id="name" name="name" value="">
            <label class="label-form" for="">Số Lượng</label>
            <input class="text-form" type="number" id="quantity" name="quantity" value="">
            <label class="label-form" for="">Mô Tả</label>
            <input class="text-form" type="text" id="description" name="description" value="">
            <label class="label-form" for="">Tác Giả</label>
            <input class="text-form" type="text" id="author" name="author" value="">
            <label class="label-form" for="">Ngôn Ngữ</label>
            <input class="text-form" type="text" id="language" name="language" value="">
            <label class="label-form" for="">Năm Xuất Bản</label>
            <input class="text-form" type="text" id="year" name="year" value="">
            <label class="label-form" for="">Vị Trí</label>
            <input class="text-form" type="text" id="location" name="location" value="">
            <label class="label-form" for="">Cấp Bậc</label>
            <select id="rank" name="rank">
                <?php foreach ($ranks as $rank) : ?>
                    <option value="<?= $rank['ma_rank'] ?>" <?= (isset($result['ma_rank']) && $result['ma_rank'] == $rank['ma_rank']) ? 'selected' : '' ?>>
                        <?= $rank['ten_rank'] ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label class="label-form" for="">Thể Loại Sách</label>
            <select id="category" name="category">
                <?php foreach ($categories as $category) : ?>
                    <option value="<?= $category['ma_the_loai'] ?>" <?= (isset($result['ma_the_loai']) && $result['ma_the_loai'] == $category['ma_the_loai']) ? 'selected' : '' ?>>
                        <?= $category['ten_the_loai'] ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label class="label-form" for="">Nhà Xuất Bản</label>
            <select id="publisher" name="publisher">
                <?php foreach ($publishers as $publisher) : ?>
                    <option value="<?= $publisher['ma_nxb'] ?>" <?= (isset($result['ma_nxb']) && $result['ma_nxb'] == $publisher['ma_nxb']) ? 'selected' : '' ?>>
                        <?= $publisher['ten_nxb'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <label class="label-form" for="date">Ngày Nhập:</label>
            <input type="date" id="date" name="date" required><br><br>
            <label class="label-form" for="image">Ảnh:</label>
            <input type="file" id="image" name="image" accept="image/*"><br><br>
            <input type="hidden" name="form_submitted" value="1">
            <button class="submit-form" type="submit">Thêm</button>
            <a class="submit-form2" href="admin.php">Hủy</a>
        </form>

    <?php
    }
    ?>
    <script src="js/addProduct.js"></script>
    <script src="js/admin2.js"></script>

</body>

</html>