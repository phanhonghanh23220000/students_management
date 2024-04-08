<?php
function addNewCourse($name, $department_id, $status)
{
    // Check if the department_id exists in the departments table
    $db = connectionDb();
    $check_department_sql = "SELECT COUNT(*) as count FROM departments WHERE id = :department_id";
    $check_stmt = $db->prepare($check_department_sql);
    $check_stmt->bindParam(':department_id', $department_id, PDO::PARAM_INT);
    $check_stmt->execute();
    $department_exists = $check_stmt->fetch(PDO::FETCH_ASSOC);

    // If department_id doesn't exist, return false
    if ($department_exists['count'] == 0) {
        return false;
    }

    // Proceed with inserting the course
    $sql = "INSERT INTO courses (name, department_id, status, created_at) 
            VALUES (:name, :department_id, :status, NOW())";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':department_id', $department_id);
    $stmt->bindParam(':status', $status);
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}
function deleteCourseById($id) {
    $sql = "UPDATE courses SET deleted_at = NOW() WHERE id = :id";
    $db = connectionDb();
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $success = $stmt->execute();
    disconnectDb($db);
    return $success;
}


function updateCourseById($name, $department_id, $status, $id)
{
    $checkUpdate = false;
    $db = connectionDb();
    $sql = "UPDATE courses SET name = :name, department_id = :department_id, status = :status, updated_at = NOW() WHERE id = :id";
    $stmt = $db->prepare($sql);
    if ($stmt) {
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':department_id', $department_id, PDO::PARAM_INT);
        $stmt->bindParam(':status', $status, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            $checkUpdate = true;
        }
    }
    return $checkUpdate;
}


function getDetailCourseById($id=0){
    $sql = "SELECT * FROM `courses` WHERE `id` = :id AND `deleted_at` IS NULL";
    $db = connectionDb();
    $data=[];
    $stmt = $db->prepare($sql);
    if($stmt){
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if($stmt->execute()){
            $data = $stmt->fetch(PDO::FETCH_ASSOC); 
        }
    }
    disconnectDb($db);
    return $data;
}


function getAllCoursesByPage($keyword = null, $start = 0, $limit = 10)
{
    $key = "%{$keyword}%";

    // Use a single ":keyword" placeholder for both name and department searches
    $sql = "SELECT * FROM `courses` 
            WHERE (`name` LIKE :keyword OR `department_id` IN (SELECT id FROM `departments` WHERE `name` LIKE :keyword)) 
              AND `deleted_at` IS NULL 
            LIMIT :startData, :limitData";

    $db = connectionDb();
    $stmt = $db->prepare($sql);
    $data = [];

    if ($stmt) {
        // Bind the :keyword placeholder once for both uses
        $stmt->bindParam(':keyword', $key, PDO::PARAM_STR);
        $stmt->bindParam(':startData', $start, PDO::PARAM_INT);
        $stmt->bindParam(':limitData', $limit, PDO::PARAM_INT);

        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        }
    }

    disconnectDb($db);
    return $data;
}



function getAllCourses($keyword=null){
    $key = "%{$keyword}%";
    $sql = "SELECT * FROM `courses` WHERE (`name` LIKE :nameCourse) AND `deleted_at` IS NULL";
    $db = connectionDb();
    $stmt = $db->prepare($sql);
    $data = [];
    if($stmt){
        $stmt->bindParam(':nameCourse', $key, PDO::PARAM_STR);
        if($stmt->execute()){
            $data =$stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
    disconnectDb($db);
    return $data;
}

function getAllCoursesFromDB() {
    $db = connectionDb(); 
    $sql = "SELECT * FROM courses WHERE deleted_at IS NULL";
    $stmt = $db->prepare($sql);
    $stmt->execute();   
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    disconnectDb($db); 
    return $courses; 
}


function insertCourse($name, $departmentId, $status, $beginningDate){
    
    $slug = slug_string($name);

    $sqlInsert = "INSERT INTO courses(`name`, `slug`, `department_id`, `date_beginning`, `status`, `created_at`) VALUES (:nameCourse, :slug, :departmentId, :beginningDate, :statusCourse, :createdAt)";
    
    $checkInsert = false;
    $db = connectionDb();
    $stmt = $db->prepare($sqlInsert);
    $currentDate = date('Y-m-d H:i:s');
    
    if($stmt){
        $stmt->bindParam(':nameCourse', $name, PDO::PARAM_STR);
        $stmt->bindParam(':slug', $slug, PDO::PARAM_STR);
        $stmt->bindParam(':departmentId', $departmentId, PDO::PARAM_INT);
        $stmt->bindParam(':beginningDate', $beginningDate, PDO::PARAM_STR);
        $stmt->bindParam(':statusCourse', $status, PDO::PARAM_INT);
        $stmt->bindParam(':createdAt', $currentDate, PDO::PARAM_STR);
        
        if($stmt->execute()){
            $checkInsert = true;
        }
    }
    disconnectDb($db);
    return $checkInsert;
}
function searchCoursesByProduct($keyword) {
    $db = connectionDb();
    $sql = "SELECT * FROM courses WHERE name LIKE :keyword OR department_id IN (SELECT id FROM departments WHERE name LIKE :keyword)";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':keyword', '%' . $keyword . '%', PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

