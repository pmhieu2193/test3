<?php if(!isset($_POST["btn_remove"])){
    session_start();
    $id = $_REQUEST["id_remove"];
    //tìm id đó trong các session, xoá nó và dồn những session không trống ở đằng sau lên
    echo 'id sách: '.$id;
    $max = $_SESSION["max"];
    $ganmax=$max-1;

    for($i=0; $i<$max; $i++){
        //i là vị trí sách đc tìm thấy trên session
        if($_SESSION["book$i"]==$id){
            echo 'Sach can xoa: '.$_SESSION["book$i"].'|';
            echo 'vị trí'.$i.'\\';
            $k=$i+1;
                    //kiểm tra sau i có ai ko
            if(empty($_SESSION["book$k"])||$k==$max){
                //nếu sau i không có ai thì xoá thẳng tay
                $_SESSION["book$i"]='';
                echo 'da xoa thanh cong 1!';
            }
            else{
                //nếu sau  i có sách
                for($j=$i+1; $j<$max; $j++){
                    echo 'được chọn thay thế:'.$j;
                    //lấy giá trị session sau session đc xoá
                    $temp= $_SESSION["book$j"];
                    //truyền vào cái xoá
                    $_SESSION["book$i"]=$temp;
                    $i++;
                    echo 'có vào vòng xoá bên trong';
                }
                //tại sao chọn xoá cái trên nhưng cái dưới lại bị xoá mất
                $_SESSION["book$ganmax"]='';
                echo 'da xoa thanh cong 2!';
                header("location: cart.php");
                exit();
            }
            echo 'sach can xoa hien tai:'.$_SESSION["book$i"].'|';
            header("location: cart.php");
                exit();
        }
    }
}
?>