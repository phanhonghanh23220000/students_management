<?php
// locahost/Students_manager/index.php?c=login&m=index
// c là tên của controller nằm trong thư mục controller 
$c = trim($_GET['c'] ?? 'login');
$c = ucfirst($c); // viết hoa chữ cái đầu 
switch ($c) {
    case'Login':
        require "controller/LoginController.php";
        break;
    case'Dashboard':
        require"controller/DashboardController.php";
        break;
    case 'Departments':
        require"controller/DepartmentController.php";
        break;
    case 'Courses':
        require"controller/CourseController.php";
        break;
    default:
        require "controller/LoginController.php";
        break;
}
