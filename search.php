<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kết quả tìm kiếm</title>

    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="css/search.css">

</head>

<body>
    
    <?php include("nav.php"); ?>

    <?php
    $searchTerm = '';

    if (isset($_GET['timkiem'])) {
        $timKiem = $_GET['timkiem'];
        if (!empty($timKiem)) {
            $searchTerm = $timKiem; // Store the search term for later use
            $collection = $database->selectCollection('sach');
            $escapedTerm = preg_quote($timKiem, '/');
            $regexPattern = new \MongoDB\BSON\Regex($escapedTerm, 'i');
            $cursor = $collection->find(['ten_sach' => ['$regex' => $regexPattern->getPattern(), '$options' => $regexPattern->getFlags()]]);
            $results = iterator_to_array($cursor);
            $count = count($results);
            echo '<h2 class="heading">Kết quả tìm kiếm cho: '; echo htmlspecialchars($searchTerm); echo'</h2>';
            if ($count > 0) {
                echo '<section class="search-results">';
                echo '<div class="product-container">';
                foreach ($results as $result) {
                    echo '<div class="product-card">';
                    echo '<div class="product-image">';
                    echo '<img src="' . $result->anh_bia . '" class="product-thumb" alt="" onclick="location.href=\'book.php?_id=' . $result->_id . '\'">';
                    echo '</div>';
                    echo '<div class="product-info">';
                    echo '<h2 class="product-brand">' . $result->ten_sach . '</h2>';
                    echo '<p class="product-short-des">' . $result->tac_gia . '</p>';
                    echo '<p class="product-short-des">' . $result->vi_tri . '</p>';
                    echo '</div>';
                    echo '</div>';
                }
                echo '</div>';
                echo '</section>';
            } else {
                echo '<h3>Không tìm thấy sách.</h3>';
            }
        } else {
            echo 'Vui lòng nhập từ khóa tìm kiếm.';
        }
    }
    ?>

    <?php
    $collectionSach = $database->selectCollection('sach');
    $collectionTheLoai = $database->selectCollection('the_loai');
    $maTheLoai = isset($_GET['ma_the_loai']) ? filter_var($_GET['ma_the_loai'], FILTER_SANITIZE_NUMBER_INT) : '';
    if ($maTheLoai !== '') {
        $theLoaiDocument = $collectionTheLoai->findOne(['ma_the_loai' => (int) $maTheLoai]);
        if ($theLoaiDocument) {
            $result = $collectionSach->find(['ma_the_loai' => (int) $maTheLoai]);
            $documents = iterator_to_array($result);
            $documentCount = count($documents);

            if ($documentCount > 0) {
                echo '<h2 class="heading">Thể Loại: ' . $theLoaiDocument->ten_the_loai . '</h2>';
                echo '<section class="search-results">';
                echo '<div class="product-container">';
                foreach ($documents as $document) {
                    echo '<div class="product-card">';
                    echo '<div class="product-image">';
                    echo '<img src="' . $document->anh_bia . '" class="product-thumb" alt="" onclick="location.href=\'book.php?_id=' . $document->_id . '\'">';
                    echo '</div>';
                    echo '<div class="product-info">';
                    echo '<h2 class="product-brand">' . $document->ten_sach . '</h2>';
                    echo '<p class="product-short-des">' . $document->tac_gia . '</p>';
                    echo '<p class="product-short-des">' . $document->vi_tri . '</p>';
                    echo '</div>';
                    echo '</div>';
                }
                echo '</div>';
                echo '</section>';
            } else {
                echo '<h2 class="heading">Không tìm thấy Sách</h2>';
            }
        } else {
            echo 'Không tìm thấy thể loại.';
        }
    }
    ?>

    <footer></footer>

    <script src="js/nav.js"></script>
    <script src="js/footer.js"></script>
    <script src="js/search.js"></script>
</body>

</html>