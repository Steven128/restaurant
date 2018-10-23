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
    if ($request == "getTables") {
        getTables($conn);
    } else
    if ($request == "useTable") {
        useTable($conn);
    }
}

function getTables($conn)
{
    $sql_select1 = "SELECT TABLE_NUMBER,DEFAULT_NUMBER,TABLE_ORDER_STATUS FROM SCOTT.RES_TABLE WHERE TAB_STATUS=1";
    $statement1 = oci_parse($conn, $sql_select1);
    oci_execute($statement1);
    $table_info = array();
    while ($row = oci_fetch_array($statement1, OCI_RETURN_NULLS)) {
        $table_number = $row[0];
        $default_number = $row[1];
        $table_order_status = $row[2];
        $table_info1 = array('table_number' => $table_number, 'default_number' => $default_number, 'table_order_status' => $table_order_status);
        array_push($table_info, $table_info1);
    }
    echo json_encode(array("message" => "success", "data" => $table_info));
}

function useTable($conn)//餐桌使用，预定餐桌写在preorder里
{  
    $table_number=$_POST['table_number'];
    $sql_update1="UPDATE SCOTT.RES_TABLE SET TABLE_ORDER_STATUS=2 WHERE TABLE_NUMBER=$table_number";
    $statement1=oci_parse($conn,$sql_update1);
    oci_execute($statement1);
    echo json_encode(array("message" => "success"));
}
