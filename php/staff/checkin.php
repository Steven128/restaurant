<?php

if (isset($_POST['request']) && $_POST['request'] != "") {
    $request = $_POST['request'];
} else {
    die();
}

$conn = oci_connect('scott', '123456', 'localhost:1521/ORCL', "AL32UTF8"); //连接oracle数据库
if (!$conn) { //未连接成功，终止脚本并返回错误信息
    $e = oci_error();
    die(json_encode($e));
} else {
    if ($request == "checkin") {
        checkin($conn);
    }
}

function checkin($conn)
{

}
