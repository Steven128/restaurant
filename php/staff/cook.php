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
    if ($request == "getCookDishs") {
        getCookDishs($conn);
    } else
    if ($request == "changeCookStatus") {
        changeCookStatus($conn);
    }
}

function getCookDishs($conn)
{
    $sql_select = "SELECT SALES.SALES_ID,SALES.DISH_ID,SALES.ORDER_ID,DISH.DISH_NAME FROM SCOTT.SALES JOIN DISH ON SALES.DISH_ID=DISH.DISH_ID WHERE SAL_STATUS=1 OR SAL_STATUS=2";
    $statement = oci_parse($conn, $sql_select);
    oci_execute($statement);
    $cook_info = null;
    while ($row = oci_fetch_array($statement, OCI_RETURN_NULLS)) {
        $sales_id = $row[0];
        $dish_id = $row[1];
        $order_id = $row[2];
        $dish_name = $row[3];
        $cook_info1 = array('sales_id' => $sales_id, 'dish_id' => $dish_id, 'order_id' => $order_id, 'dish_name' => $dish_name);
        array_push($cook_info, $cook_info1);
    }
    echo json_encode(array("message" => "success", "data" => $cook_info));
}

function changeCookStatus($conn)
{
    $dish_id = $_POST['dish_id'];
    $order_id = $_POST['order_id'];
    $sql_select = "SELECT SALES_ID,SAL_STATUS FROM SCOTT.SALES WHERE ORDER_ID='" . $order_id . "' AND DISH_ID='" . $dish_id . "'";
    $statement1 = oci_parse($conn, $sql_select);
    oci_execute($statement1);
    $row = oci_fetch_array($statement1, OCI_RETURN_NULLS);
    $sale_id = $row[0];
    $statu = $row[1];
    $statu++;
    $sql_update = "UPDATE SCOTT.SALES SET SAL_STATUS =" . $statu . " WHERE SALES_ID='" . $sale_id . "'";
    $statement2 = oci_parse($conn, $sql_update);
    oci_execute($statement2);
    echo json_encode(array("message" => "success"));
}
