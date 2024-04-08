<?php
// Import model
require 'model/DepartmentModel.php';

// m là tên của hàm nằm trong file controller  trg  thư mục controller
$m = trim($_GET['m'] ?? 'index'); // Hàm mặc định trong controller tên là index 
$m = strtolower($m); // Viết thường tất cả tên hàm 

switch ($m) {
    case 'index':
        index();
        break;
    case 'add':
        Add();
        break;
    case 'handle-add':
        handleAdd();
        break;
    case 'delete':
        handleDelete();
        break;
    case 'edit':
        edit();
        break;
    case 'handle-edit':
        handleEdit();
        break;
    default:
        index();
        break;
}

function handleEdit()
{
    if (isset($_POST['btnSave'])) {
        $id = trim($_GET['id'] ?? null);
        $id = is_numeric($id) ? $id : 0;
        $info = getDetailDepartmentById($id); // Gọi tên hàm trong model 

        $name = trim($_POST['name'] ?? null);
        $name = strip_tags($name);

        $leader = trim($_POST['leader'] ?? null);
        $leader = strip_tags($leader);

        $status = trim($_POST['status'] ?? null);
        $status = $status === '0' || $status === '1' ? $status : 0;

        $beginningDate = trim($_POST['date_beginning'] ?? null);
        $beginningDate = date('Y-m-d', strtotime($beginningDate));

        // Kiểm tra thông tin 
        $_SESSION['error_update_department'] = [];
        if (empty($name)) {
            $_SESSION['error_update_department']['name'] = ['Enter name of department, please'];
        } else {
            $_SESSION['error_update_department']['name'] = null;
        }
        if (empty($leader)) {
            $_SESSION['error_update_department']['leader'] = ['Enter leader of department, please'];
        } else {
            $_SESSION['error_update_department']['leader'] = null;
        }

        $logo = $info['logo'] ?? null;
        $_SESSION['error_update_department']['logo'] = null;
        if (!empty($_FILES['logo']['tmp_name'])) {
            $logo = uploadFile(
                $_FILES['logo'],
                'public/uploads/images/',
                ['image/png', 'image/jpg', 'image/jpeg'],
                5 * 1024 * 1024
            );
            if (empty($logo)) {
                $_SESSION['error_update_department']['logo'] = 'File only accept extension is .png, .jpg, .jpeg, .gif and file <= 5Mb';
            } else {
                $_SESSION['error_update_department']['logo'] = null;
            }
        }

        $flagCheckingError = false;
        foreach ($_SESSION['error_update_department'] as $error) {
            if (!empty($error)) {
                $flagCheckingError = true;
                break;
            }
        }
        if (!$flagCheckingError) {
            if (isset($_SESSION['error_update_department'])) {
                unset($_SESSION['error_update_department']);
            }
            $slug = slug_string($name);

            $update = updateDepartmentById($name, $slug, $leader, $status, $beginningDate, $logo, $id);
            if ($update) {
                header("Location:index.php?c=departments&state=success");
            } else {
                header("Location:index.php?c=departments&m=edit&id={$id}&state=error");
            }
        } else {
            header("Location:index.php?c=departments&m=edit&id={$id}&state=failure");
        }
    }
}

function edit()
{
    // Phải đăng nhập 
    if (!isLoginUser()) {
        header("Location:index.php");
        exit();
    }
    $id = trim($_GET['id'] ?? null);
    $id = is_numeric($id) ? $id : 0;
    $info = getDetailDepartmentById($id);
    if (!empty($info)) {
        // Có dữ liệu trong database
        // Thông báo giao diện lỗi
        require 'view/department/edit_view.php';
    } else {
        // Thông báo hiển thị giao diện lỗi
        // Không có dữ liệu trong db
        require 'view/error_view.php';
    }
}

function handleDelete()
{
    // Phải đăng nhập 
    if (!isLoginUser()) {
        header("Location:index.php");
        exit();
    }
    $id = trim($_GET['id'] ?? null);
    $id = is_numeric($id) ? $id : 0;
    $delete = deleteDepartmentById($id);
    if ($delete) {
        header("Location:index.php?c=departments&state_del=success");
    } else {
        header("Location:index.php?c=departments&state_del=failure");
    }
}
function handleAdd()
{
    if (isset($_POST['btnSave'])) {
        $name = trim($_POST['name'] ?? null);
        $name = strip_tags($name);

        $leader = trim($_POST['leader'] ?? null);
        $leader = strip_tags($leader);

        $status = trim($_POST['status'] ?? null);
        $status = $status === '0' || $status === '1' ? $status : 0;

        $beginningDate = trim($_POST['date_beginning'] ?? null);
        $beginningDate = date('Y-m-d', strtotime($beginningDate));

        // Kiểm tra thông tin 
        $_SESSION['error_add_department'] = [];
        if (empty($name)) {
            $_SESSION['error_add_department']['name'] = ['Enter name of department, please'];
        } else {
            $_SESSION['error_add_department']['name'] = null;
        }
        if (empty($leader)) {
            $_SESSION['error_add_department']['leader'] = ['Enter leader of department, please'];
        } else {
            $_SESSION['error_add_department']['leader'] = null;
        }

        // Xử lí upload logo 
        $logo = null;
        $_SESSION['error_add_department']['logo'] = null;
        if (!empty($_FILES['logo']['tmp_name'])) {
            $logo = uploadFile(
                $_FILES['logo'],
                'public/uploads/images/',
                ['image/png', 'image/jpg', 'image/jpeg'],
                5 * 1024 * 1024
            );
            if (empty($logo)) {
                $_SESSION['error_add_department']['logo'] = 'File only accept extension is .png, .jpg, .jpeg, .gif and file <= 5Mb';
            } else {
                $_SESSION['error_add_department']['logo'] = null;
            }
        }

        $flagCheckingError = false;
        foreach ($_SESSION['error_add_department'] as $error) {
            if (!empty($error)) {
                $flagCheckingError = true;
                break;
            }
        }
        
        // Tiến hành kiểm tra lại thông tin
        if (!$flagCheckingError) {
            // Tiến hành insert vào database
            $slug = slug_string($name);
            $insert = insertDepartment($name, $slug, $leader, $status, $logo, $beginningDate);
            if ($insert) {
                header("Location:index.php?c=departments&state=success");
            } else {
                header("Location:index.php?c=departments&m=add&state=error");
            }
        } else {
            header("Location:index.php?c=departments&m=add&state=fail");
        }
    }
}
function Add()
{
    require 'view/department/add_view.php';
}
function index(){
    // phai dang nhap moi duoc su dung chuc nang nay.
    if(!isLoginUser()){
        header("Location:index.php");
        exit();
    }
    $keyword = trim($_GET['search'] ?? null);
    $keyword = strip_tags($keyword);
    $page = trim($_GET['page'] ?? null);
    $page = (is_numeric($page) && $page > 0) ? $page : 1;
    $linkPage = createLink([
        'c' => 'departments',
        'm' => 'index',
        'page' => '{page}',
        'search' => $keyword
    ]);
    $totalItems = getAllDataDepartments($keyword); // goi ten ham trong model
    $totalItems = count($totalItems);
    // departments
    $panigate = pagigate($linkPage, $totalItems, $page, $keyword, 20);
    $start = $panigate['start'] ?? 0;
    $departments = getAllDataDepartmentsByPage($keyword, $start, 20);
    $htmlPage = $panigate['pagination'] ?? null;
    require 'view/department/index_view.php';
}

