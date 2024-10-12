<?php
session_start();

// Xóa tất cả các biến session
session_unset();

// Huỷ toàn bộ session
session_destroy();
header("location: login.php");
exit();
?>