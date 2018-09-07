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
        echo "正在注销，请稍后...";
        echo "<script type=\"text/javascript\" src=\"../js/jQuery/jquery-1.11.3.min.js\"></script>";
        echo "<script type=\"text/javascript\" src=\"../js/page.js\"></script>";
        echo "<script>$(document).ready(() => {setUserInfo('', '');window.location.replace(\"../login\");});</script>";

    } else {
        echo json_encode(array("message" => "success logout"));
    }
}
