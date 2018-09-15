<?php
session_start(); //开启php_session
$admin_id = $_GET['admin_id']; //获取admin_id
if (isset($_SESSION['admin_id']) && $_SESSION['admin_id'] == $admin_id) { //如果已设置session且session对应用户为当前访问用户
    $request = $_GET['request']; //获取请求内容

    $conn = oci_connect('scott', '123456', 'localhost:1521/ORCL', "AL32UTF8"); //连接oracle数据库
    if (!$conn) { //未连接成功，终止脚本并返回错误信息
        $e = oci_error();
        die(json_encode($e));
    } else { //连接成功
        if ($request == "purchase") {
            echo purchase($conn);
        }

    }
}

function purchase($conn)
{
    $sql_query = "SELECT COUNT(PURCHASE_ID) FROM PURCHASE";
    $statement = oci_parse($conn, $sql_query);
    $sum = oci_execute($statement);
    $str = strval($sum);
    $PURCHASE_ID = "pur_".date("ymd_his",time());

    $sql_query = "INSERT INTO PURCHASE (PURCHASE_ID, PURCHASE_NUMBER, GOODS_ID, PURCHASE_QUANTITY, PURCHASE_DATE, PUR_STATUS) VALUES ('$PURCHASE_ID', '" . $_POST['purchase_number'] . "', '" . $_POST['goods_id'] . "', " . $_POST['purchase_quantity'] . ", '" . $_POST['purchase_date'] . "', 1)";

    $statement = oci_parse($conn, $sql_query);
    if (!oci_execute($statement)) {
        echo json_encode(array("message" => "false"));
    }

    $sql_query = "UPDATE INVENTORY SET QUANTITY = " . $_POST['purchase_quantity'] . " WHERE GOODS_ID = '" . $_POST['purchase_quantity'] . "'";
    $statement = oci_parse($conn, $sql_query);
    if (oci_execute($statement)) {
        echo json_encode(array("message" => "false"));
    } else {
        echo json_encode(array("message" => "false"));
    }

}
