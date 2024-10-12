<!DOCTYPE php>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán</title>

    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="css/cart.css">

</head>

<body>
    <?php include("nav.php");
    ?>
            <div class="small-container cart-page">
            <h1>Danh sách yêu thích<h1><br>
                <a class="back" onclick="location.href='index.php'">&larr; Thêm sách vào danh sách yêu thích</a><br>
                <?php if (isset($_GET['error'])) { ?>
                    <a class="error-hide"><?php echo $_GET['error']; ?><a>
                        <?php } ?>
                        <?php
                        if (isset($_SESSION["duplicate_selection"])) {
                            echo '<p>Bạn đã chọn sách này rồi</p>';
                            unset($_SESSION["duplicate_selection"]); // Xóa thông báo khỏi session
                        }
                        ?>
                        <table>
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Trạng thái</th>
                                <th style="width: 130px">Tương tác</th>
                            </tr>
                            <?php
                            $collection = $database->selectCollection('yeu_thich');

                            if (isset($_POST["btn_remove"])) {
                                $bookIdToRemove = $_POST["book_remove"];
                                $userId = (string)$_SESSION["_id"];
                                $deleteResult = $collection->deleteOne(['ma_sach' => $bookIdToRemove, 'ma_user' => $userId]);
                            }

                            $wishList = $collection->find(['ma_user' => (string)$_SESSION["_id"]]);
                            $count = 0;

                            foreach ($wishList as $doc) {
                                $id_sach = $doc->ma_sach;
                                $filter = ['_id' => new MongoDB\BSON\ObjectId($id_sach)];
                                $collection2 = $database->selectCollection("sach");
                                $sach = $collection2->findOne($filter);
                                if (!$sach) {
                                    echo 'Sách đã bị xóa hoặc ẩn khỏi thư viện';
                                    continue;
                                } else {
                                    $count++;
                                    echo '<tr>
                                    <td>
                                        <div class="cart-info">
                                        <img src="' . $sach->anh_bia . '"><div>
                                        <h3>' . $sach->ten_sach . '</h3>
                                        <small>' . $sach->mo_ta . '</small></div>
                                        </div>
                                    <td>';
                                    if ($sach->so_luong > 0) {
                                        echo 'Có thể mượn';
                                    } else {
                                        echo 'Đã hết sách, không thể mượn';
                                    }

                                    echo '</td>
                                    <td>
                                        <a href="book.php?_id=' . (string)$sach->_id . '" class="link-text">Xem Chi Tiết</a><br>
                                        <br>
                                        
                                        <form action="wishlist.php" method="post">
                                        <input type="hidden" name="book_remove" value="' . $sach->_id . '">
                                        <button type="submit" class="btn-remove" name="btn_remove">Xoá khỏi danh sách yêu thích</button>';
                                    if ($sach->so_luong > 0 && $sach->trang_thai_sach != 0) {
                                        echo '</form>
                                        <br>
                                            <form action="xu_ly_sach.php" type="post">
                                            <input name="id_book" type="hidden" value="' . $sach->_id . '"><button type="submit" name="addToCart" class="btn-cart">Thêm vào giỏ sách</button>
                                            </form>';
                                    }
                                    echo '
                                    </td>
                                </tr>';
                                }
                            }

                            if ($count == 0) {
                                echo '<h3>Bạn chưa có sách nào trong danh sách yêu thích</h3>';
                            }
                            ?>


            </div>
            </table>
            <div class="line">

                <script src="js/nav.js"></script>
</body>

</html>