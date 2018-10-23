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
    if ($request == "login") {
        login($conn);
    }
}

function login($conn)
{
    $employee_id = $_POST['employee_id'];
    $sql_select = "SELECT EMPLOYEE_TYPE FROM SCOTT.EMPLOYEE WHERE EMPLOYEE_ID='" . $employee_id . "'";
    $statement=oci_parse($conn,$sql_select);
    oci_execute($statement);
    $row=oci_fetch_array($statement);
    echo json_encode(array("message" => "success", "data" => $row[0]));
}
