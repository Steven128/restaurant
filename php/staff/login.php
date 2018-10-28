<?php

// if (isset($_POST['request']) && $_POST['request'] != "") {
//     $request = $_POST['request'];
// } else {
//     die();
// }

$data = json_decode($_POST['param']);
$request = $data->request;

$conn = oci_connect('scott', '123456', '47.95.212.18/ORCL', "AL32UTF8"); //连接oracle数据库
if (!$conn) { //未连接成功，终止脚本并返回错误信息
    $e = oci_error();
    die(json_encode($e));
} else {
    if ($request == "login") {
        login($conn,$data);
    }
}

// function object_to_array($obj) {
//     $obj = (array)$obj;
//     foreach ($obj as $k => $v) {
//         if (gettype($v) == 'resource') {
//             return;
//         }
//         if (gettype($v) == 'object' || gettype($v) == 'array') {
//             $obj[$k] = (array)object_to_array($v);
//         }
//     }

//     return $obj;
// }

// function login($conn,$data)
// {
//     $employee_id = $data->id;
//     $sql_select = "SELECT EMPLOYEE_TYPE FROM SCOTT.EMPLOYEE WHERE EMPLOYEE_ID='".$employee_id."'";
//     $statement = oci_parse($conn, $sql_select);
//     oci_execute($statement);
//     $row = oci_fetch_array($statement,OCI_RETURN_NULLS);
//     if ($row == null) {
//         echo json_encode(array("message"=>"error","data"=>$row[0]));
//     } else {
//         echo json_encode(array("message" => "success", "data" => $row[0]));
//     }
// }


// 使用员工姓名和电话号进行登陆的login
function login($conn,$data){
    $employee_name=$data->id;
    $employee_tel=$data->password;
    $sql_select = "SELECT EMPLOYEE_TYPE FROM SCOTT.EMPLOYEE WHERE NAME='" . $employee_name . "' AND PHONE_NUM='".$employee_tel."'";
    $statement=oci_parse($conn,$sql_select);
    oci_execute($statement);
    $row=oci_fetch_array($statement);
    if ($row == null) {
        echo json_encode(array("message"=>"error"));
    } else {
        echo json_encode(array("message" => "success", "data" => $row[0]));
    }
}