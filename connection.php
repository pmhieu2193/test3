<?php
    require 'vendor/autoload.php';
    use MongoDB\Client;     
    $mongoUri = "mongodb+srv://ancolwithlife:123@thuvien.jkpgl.mongodb.net/thu_vien?retryWrites=true&w=majority";
    $client = new Client($mongoUri);
    $database = $client ->selectDatabase('thu_vien');
?>