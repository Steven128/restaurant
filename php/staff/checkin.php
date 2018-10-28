<?php

$data = json_decode($_POST['param']);
$request = $data->request;

$conn = oci_connect('scott', '123456', '47.95.212.18/ORCL', "AL32UTF8"); //连接oracle数据库
if (!$conn) { //未连接成功，终止脚本并返回错误信息
    $e = oci_error();
    die(json_encode($e));
} else {
    if ($request == "checkin") {
        checkin($conn,$data);
    }
}

function checkin($conn,$data)
{
    
}
