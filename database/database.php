<?php
// connection CSDL
// sử dụn thư viện PDO để làm việc với database(MYSQL)

// hàm kết nối database
function connectionDb() {
    try {
        $dbh = new PDO('mysql:host=localhost; dbname=students_manager; charset=utf8', 'root', '');
        return $dbh; // trả về biến kết nối 
    } catch (PDOException $e) {
        // attempt to retry the connection after some timeout for example
        echo "Can not connect to database";
        print_r($e);
        die();
    }
}

// hàm ngắt kết nối databse
function disconnectDb($connecttion) {
    $connecttion = null; 
}