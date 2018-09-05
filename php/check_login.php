<?php
session_start();
$request = $_GET['request'];
if ($request == "check") {
    if (isset($_SESSION['admin_id'])) {
        echo json_encode(array("message" => "online", "admin_id" => $_SESSION['admin_id'], "admin_name" => $_SESSION['admin_name'], "admin_type" => $_SESSION['admin_type']));
    } else {
        echo json_encode(array("message" => "offline"));
    }
}
if ($request == "logout") {
    unset($_SESSION);
    session_destroy();
    echo json_encode(array("message" => "success logout"));
}
