<?PHP
// nơi viết truy vấn dữ liệu 
require 'database/database.php';
// viết hàm kiểm tra đăng nhập của users
// kiểm tra tài khoản đăng nhập có tồn tại trong Database hay không 
 
function checkLoginUser($username, $password) {
    // username và password : dưx lieu ma ng dung dang nhap tu form login
    $db = connectionDb(); // có đươcj kết nối database
    // viet cau lenh truy van
    $sql = "SELECT a.*, u.`full_name`, u.`email`, u.`phone` FROM `accounts` AS a INNER JOIN `users` AS u ON a.user_id =u.id WHERE `username`= :user AND `password`= :pass AND a.`status` = 1 LIMIT 1";    
    $statement = $db->prepare($sql); // kiem tra cau lenh sql
    $dataUser = []; // mảng chưa thông tin ng dùng
    if($statement) {
        // kiem tra cac tham so 
        $statement->bindParam(':user',$username, PDO::PARAM_STR);
        $statement->bindParam(':pass',$password, PDO::PARAM_STR);
        // thuc thi cau lenh
        if($statement->execute()) {
            // kiem tra xem truy van sql co du lieu tro ve hay k 
            if($statement->rowCount() > 0) {
                // co du lieu in Db , lay du lieu do ra 
                $dataUser = $statement->fetch(PDO::FETCH_ASSOC);
            }
        }
    }
    disconnectDb($db); // dong ket noi
    return $dataUser; // trả về dữ liệu chứa thông tin người dùng 
}