<?php
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}
require 'helper/Common.php';
require 'helper/checkLoginUserHelper.php';
require 'helper/uploadFileHelper.php';


define("ROOT_PATH", "index.php");
if(file_exists("route/web.php")) {
    require "route/web.php";
} else{
    die("sorry, website can not access");
}
