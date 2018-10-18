<?php
session_start();
$request = $_GET['request'];
if ($request == "check") {
    if (isset($_SESSION['admin_id'])) {
        echo json_encode(array("message" => "online", "admin_id" => $_SESSION['admin_id'], "admin_name" => $_SESSION['admin_name'], "admin_type" => $_SESSION['admin_type'], "admin_pic" => $_SESSION['admin_pic']));
    } else {
        echo json_encode(array("message" => "offline"));
    }
}
if ($request == "logout") {
    $admin_id = $_GET['admin_id'];
    $referer = $_SERVER['HTTP_REFERER'];
    if ($admin_id == $_SESSION['admin_id']) {
        unset($_SESSION);
        session_destroy();
        if (strpos($referer, "dashboard") > -1) {
            echo    '<!DOCTYPE html>';
            echo    '<html>';
            echo    '<head>';
            echo        '<meta charset="UTF-8">';
            echo        '<meta http-equiv="X-UA-Compatible" content="IE=edge">';
            echo        '<meta name="viewport" content="width=device-width, initial-scale=1">';
            echo        '<title>退出登录 -餐饮店管理系统</title>';
            echo        '<link type="text/css" rel="stylesheet" href="../css/bootstrap.css" />';
            echo        '<link type="text/css" rel="stylesheet" href="../css/page.css" />' ;
            echo        '<script type="text/javascript" src="../js/jQuery/jquery-1.11.3.min.js"></script>';
            echo        '<script type="text/javascript" src="../js/page.js"></script>';
            echo        '<script>';
            echo            '$(document).ready(() => {';
            echo                'setUserInfo(\'\', \'\');';
            echo                 'window.location.replace("../login");';
            echo            '});';
            echo        '</script>';
            echo    '</head>';
            echo    '<body>';
            echo    '<h5>正在退出登录，请稍后...</h5>';
            echo    '</body>';
            echo    '</html>';
        } else {
            echo json_encode(array("message" => "success logout"));
        }
    }else {
        echo json_encode(array("message" => "wrong request"));
    }
}
if ($request == "getUserInfo") {
    $conn = oci_connect('scott', '123456', 'localhost:1521/ORCL', "AL32UTF8");
    $target_id = $_GET['admin_id'];
    $sql_query = "SELECT admin_id,admin_name,admin_type,admin_pic,create_time FROM SCOTT.admin WHERE adm_status>0";
    $statement = oci_parse($conn, $sql_query);
    oci_execute($statement);
    while ($row = oci_fetch_array($statement, OCI_RETURN_NULLS)) {
        $admin_id = $row[0];
        $admin_name = $row[1];
        $admin_type = $row[2];
        $admin_pic = $row[3];
        $create_time = $row[4];
        if ($admin_id == $target_id) {
            echo json_encode(array("message" => "success", "admin_id" => $admin_id, "admin_name" => $admin_name, "admin_type" => $admin_type, "admin_pic" => $admin_pic,"create_time"=>$create_time));
            break;
        }
    }
}
