<?php
session_start();
@header("content-type:text/html;charset=utf8");
$request = $_POST['request'];
$base64_image_content = $_POST['PicData'];
if ($request == "upload_admin_pic") {
    $admin_id = $_POST['admin_id'];
    if ($_SESSION['admin_id'] != $admin_id && $_SESSION['admin_type'] != 1) {
        die();
    }
//匹配出图片的格式
    if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
        $type = $result[2];
        $new_file = "../src/admin_pic/";
        if (!file_exists($new_file)) {
            //检查是否有该文件夹，如果没有就创建，并给予最高权限
            mkdir($new_file, 0700);
        }
        $new_file = $new_file . $admin_id . ".{$type}";
        if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
            echo json_encode(array("message" => "success"));
        }
    }
} else if ($request == "upload_employee_pic") {
    if ($_SESSION['admin_type'] != 1 && $_SESSION['admin_type'] != 2) {
        die();
    }
    $employee_id = $_POST['employee_id'];
    //匹配出图片的格式
    if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
        $type = $result[2];
        $new_file = "../src/employee_pic/";
        if (!file_exists($new_file)) {
            //检查是否有该文件夹，如果没有就创建，并给予最高权限
            mkdir($new_file, 0700);
        }
        $new_file = $new_file . $admin_id . ".{$type}";

        if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
            echo json_encode(array("message" => "success"));
        }
    }
}
