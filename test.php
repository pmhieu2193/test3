<?php
    require 'vendor/autoload.php';

    use MongoDB\Client;     
    $mongoUri = "mongodb://localhost:27017";
    $client = new Client($mongoUri);

    $database = $client ->selectDatabase('thu_vien');
    $collection = $database->selectCollection('yeu_thich');
    echo "ok con de";
    $insert = $collection -> insertOne([
        'ma_sach' =>2,
        'ma_user' =>2
    ]);
    printf("insert %d document", $insert->getFindCount());

?>
