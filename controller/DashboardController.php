<?php
// m là tên của hàm nằm trong file controller  trg  thư mục controller
$m = trim($_GET['m'] ?? 'index'); // ham mặc định trong controler tên là index 
$m = strtolower($m); // viết thường tất cả tên hàm 
switch($m) {
    case 'index':
        index();
        break;
    default:
        index();
    break;
    
}

function index() {
    // phai dang nhap moi su dung chuc nang nay
    if(!isLoginUser()) {
        header("Location:index.php");
        exit();
    }
    require 'view/dashboard/index_view.php';
}