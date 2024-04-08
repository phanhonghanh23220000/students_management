<?php
require 'model/loginModel.php'; // import model 
// m là tên của hàm nằm trong file controller  trg  thư mục controller
$m = trim($_GET['m'] ?? 'index'); // ham mặc định trong controler tên là index 
$m = strtolower($m); // viết thường tất cả tên hàm 
switch($m) {
    case 'index':
        index();
    break;
    case 'handle':
        handleLogin();
    break;
    case 'logout';
        handleLogout();
    break;
    default:
        index();
    break;
}
// logout
function handleLogout() {
    if(isset($_POST['btnLogout'])){
        // huy cac session
        session_destroy();
        // quay ve trang dang nhap
        header("Location:index.php");
    }
}

// login
function handleLogin() {
    if(isset($_POST['btnLogin'])) {
        $username = trim($_POST['username'] ?? null);
        $username = strip_tags($username); // strip_tags: xoa cac the html trong chuoi
        $password = trim($_POST['password'] ?? null);
        $password = strip_tags($password);

        $userInfo = checkLoginUser($username, $password);
        if(!empty($userInfo)) {
            // tai khoan ton tai
            // luu thong tin users vai session\

            $_SESSION['username'] = $userInfo['username'];
            $_SESSION['fullname'] = $userInfo['full_name'];
            $_SESSION['email'] = $userInfo['email'];
            $_SESSION['idUser'] = $userInfo['user_id'];
            $_SESSION['idRole'] = $userInfo['role_id'];
            $_SESSION['idAccount'] = $userInfo['id'];
            // cho vao trang quan tri
            header("Location:index.php?c=dashboard");
        } else {
            // tai khoan k ton tai
            // quay lai dang nhap va thong bao loi
            header("Location:index.php?state=error");
        }
    }
}
function index(){
    if(isLoginUser()) {
        header("Location:index:php?c=dashboard");
        exit();
    }
    require"view/login/index_view.php";
}

