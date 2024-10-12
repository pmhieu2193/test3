<?php
session_start();
echo'
    <div class="nav-space">
    <div class="nav-admin">
        <img src="img/user.png">
        <p class="add-product-title name-admin">'.$_SESSION['ten'].'</p>
        <form action="logout.php" type="post"><button type="submit" name="logout" class="btn btn-new-product" id="new-product">Đăng xuất</button></form></div>';
    echo '<p class="add-product-title nav-link" onclick="location.href=';
    echo "'checkin.php'";
    echo '">Check in</p>';
    echo '<p class="add-product-title nav-link" onclick="location.href=';
    echo "'admin.php'";
    echo '">Quản lý sách</p>';
    echo '<p class="add-product-title nav-link" onclick="location.href=';
    echo "'user.php'";
    echo '">Quản lý tài khoản</p>';
    echo '<p class="add-product-title nav-link" onclick="location.href=';
    echo "'yeu_cau_muon.php'";
    echo '">Yêu cầu mượn</p>';
    echo '<p class="add-product-title nav-link" onclick="location.href=';
    echo "'yeu_cau_tra.php'";
    echo '">Yêu cầu trả</p>';
    echo '<p class="add-product-title nav-link" onclick="location.href=';
    echo "'report.php'";
    echo '">báo cáo</p>';
    echo '</div>';
?>