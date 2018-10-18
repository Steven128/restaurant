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
    $referer = $_SERVER['HTTP_REFERER'];
    unset($_SESSION);
    session_destroy();
    if (strpos($referer, "dashboard") > -1) {
        echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\" />";
        echo "正在注销，请稍后...";
        echo "<script type=\"text/javascript\" src=\"../js/jQuery/jquery-1.11.3.min.js\"></script>";
        echo "<script type=\"text/javascript\" src=\"../js/page.js\"></script>";
        echo "<script>$(document).ready(() => {setUserInfo('', '');window.location.replace(\"../login\");});</script>";

    } else {
        echo json_encode(array("message" => "success logout"));
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
