<?php

$data = json_decode($_POST['param']);
$request = $data->request;

$conn = oci_connect('scott', '123456', '47.95.212.18/ORCL', "AL32UTF8"); //连接oracle数据库
if (!$conn) { //未连接成功，终止脚本并返回错误信息
    $e = oci_error();
    die(json_encode($e));
} else {
    if ($request == "getMenu") {
        getMenu($conn, $data);
    } elseif ($request == "createOrder") {
        createOrder($conn, $data);
    } elseif ($request == "deleteDish") {
        deleteDish($conn, $data);
    } elseif ($request == "getOrder") {
        getOrder($conn, $data);
    } elseif ($request == "payOrder") {
        payOrder($conn, $data);
    } elseif ($request == "preOrder") {
        preOrder($conn, $data);
    } elseif ($request == "deleteOrder") {
        deleteOrder($conn, $data);
    }
}

function object_to_array($obj)
{
    $obj = (array) $obj;
    foreach ($obj as $k => $v) {
        if (gettype($v) == 'resource') {
            return;
        }
        if (gettype($v) == 'object' || gettype($v) == 'array') {
            $obj[$k] = (array) object_to_array($v);
        }
    }

    return $obj;
}

function getMenu($conn, $data)
{ //图片传输未解决
    $sql_select1 = "SELECT DISH_ID,DISH_NAME,DISH_PIC,DISH_PRICE,DISH_TYPE FROM SCOTT.DISH WHERE DIS_STATUS=1";
    $statement1 = oci_parse($conn, $sql_select1);
    oci_execute($statement1);
    $menu_info = array();
    while ($row = oci_fetch_array($statement1, OCI_RETURN_NULLS)) {
        $dish_id = $row[0];
        $dish_name = $row[1];
        $dish_pic = $row[2];

        $dish_price = $row[3];
        $dish_type = $row[4];
        $menu_info1 = array('id' => $dish_id, 'name' => $dish_name, 'picture' => $dish_pic, 'price' => $dish_price, 'type' => $dish_type);
        array_push($menu_info, $menu_info1);
    }
    $_json = json_encode(array("message" => "success", "data" => $menu_info));
    header('Content-Length:' . strlen($_json));
    echo $_json;
}

function createOrder($conn, $data)
{
    $dishes = object_to_array($data->dishes);
    //$order_note = $_POST["order_note"];
    $order_note = null;
    $dish_list = null;
    //$total_price = null;
    $table_id = $data->table->id;
    $table_number = $data->table->tableName;
    $sql_select1 = "SELECT ORDER_ID FROM SCOTT.ORDER_LIST WHERE TABLE_ID='" . $table_id . "' AND PAY_STATUS=0";
    $statement3 = oci_parse($conn, $sql_select1);
    oci_execute($statement3);
    $row = oci_fetch_array($statement3);
    if ($row == null) {
        $order_time = date("YmdHis", time());
        //$table_number = $table_number < 10 ? "00$table_number " : ($table_number < 100 ? "0$table_number" : "$table_number");
        $order_id = "ord_" . "$table_number" . "_" . "$order_time";
        $count = 0;
        $sql_insert1 = "INSERT INTO SCOTT.ORDER_LIST (order_id,table_id,pay_status) VALUES ('$order_id','$table_id',0)";
        $statement1 = oci_parse($conn, $sql_insert1);
        oci_execute($statement1); //创建order并提交
        foreach ($dishes as $dish) {
            $count++;
            $dish_id = $dish['id'];
            $dish_list = "$dish_list" . "," . "$dish_id";
            $dish_price = $dish['price'];
            //$total_price += $dish_price;
            $_count = $count;
            $_count = $_count < 10 ? "00$_count" : ($c_ount < 100 ? "0$_count" : "$_count");
            $sales_id = "sal_" . substr($order_id, -18) . "_" . "$_count";
            $sql_insert2 = "INSERT INTO SCOTT.SALES (SALES_ID,DISH_ID,DISH_PRICE,ORDER_ID,SAL_STATUS) VALUES ('$sales_id','$dish_id',$dish_price,'$order_id',1)";
            $statement2 = oci_parse($conn, $sql_insert2);
            oci_execute($statement2); //创建sales表并提交
        }

        echo json_encode(array("message" => "success"));
    } else {
        $order_id = $row[0];
        $sql_select2 = "SELECT COUNT(SALES_ID) FROM SCOTT.SALES WHERE ORDER_ID='" . $order_id . "'";
        $statement4 = oci_parse($conn, $sql_select2);
        oci_execute($statement4);
        $row1 = oci_fetch_array($statement4);
        $count = $row1[0];
        foreach ($dishes as $dish) {
            $count++;
            $dish_id = $dish['id'];
            $dish_list = "$dish_list" . "," . "$dish_id";
            $dish_price = $dish['price'];
            // $total_price += $dish_price;
            $_count = $count;
            $_count = $_count < 10 ? "00$_count" : ($_count < 100 ? "0$_count" : "$_count");
            $sales_id = "sal_" . substr($order_id, -18) . "_" . "$_count";
            $sql_insert2 = "INSERT INTO SCOTT.SALES (SALES_ID,DISH_ID,DISH_PRICE,ORDER_ID,SAL_STATUS) VALUES ('$sales_id','$dish_id',$dish_price,'$order_id',1)";
            $statement2 = oci_parse($conn, $sql_insert2);
            oci_execute($statement2); //创建sales表并提交
        }
        echo json_encode(array("message" => "success"));
    }
}

function deleteDish($conn, $data) //业务逻辑需要改

{
    $order_id = $data->order;
    $dish_id = $data->dish->id;
    $sql_select = "SELECT SAL_STATUS FROM SCOTT.SALES WHERE ORDER_ID='" . $order_id . "' AND DISH_ID='" . $dish_id . "'";
    $statement2 = oci_parse($conn, $sql_select);
    oci_execute($statement2);
    $row = oci_fetch_array($statement2);
    if ($row[0] >= 2) {
        echo json_encode(array("message" => "error"));
    } else {
        $sql_update1 = "UPDATE SCOTT.SALES SET SAL_STATUS=0 WHERE ORDER_ID='" . $order_id . "' AND DISH_ID='" . $dish_id . "'";
        $statement1 = oci_parse($conn, $sql_update1);
        oci_execute($statement1);
        echo json_encode(array("message" => "success"));
    }
}

function getOrder($conn, $data)
{
    $table_id = $data->table;
    $sql_select1 = "SELECT * FROM SCOTT.ORDER_LIST WHERE TABLE_ID='" . $table_id . "' AND PAY_STATUS=0 AND ORD_STATUS=1";
    $statement1 = oci_parse($conn, $sql_select1);
    oci_execute($statement1);
    $row = oci_fetch_array($statement1);
    $order_id = $row[0];
    $sql_select2 = "SELECT SALES.DISH_ID,SALES.DISH_PRICE,DISH.DISH_NAME FROM SCOTT.SALES JOIN SCOTT.DISH ON SALES.DISH_ID=DISH.DISH_ID WHERE ORDER_ID='" . $order_id . "' AND SAL_STATUS>0";
    $statement2 = oci_parse($conn, $sql_select2);
    oci_execute($statement2);
    $dishes_info = array();
    while ($row1 = oci_fetch_array($statement2, OCI_RETURN_NULLS)) {
        $dish_info = array("id" => $row1[0], "name" => $row1[2], "price" => $row1[1]);
        array_push($dishes_info, $dish_info);
    }
    echo json_encode(array("message" => "success", "order" => $order_id, "dishes" => $dishes_info));
}

function payOrder($conn, $data)
{
    $order_id = $data->order->order_id;
    $table_id = $data->order->table_id;
    $total_price = $data->order->total_price;
    $method = $data->method;
    $pay_time = date("Y-m-d H:i:s", time());
    $sql_update1 = "UPDATE SCOTT.ORDER_LIST SET PAY_METHOD=$method,PAY_STATUS=1,TOTAL_PRICE=$total_price,PAY_TIME='" . $pay_time . "' WHERE ORDER_ID='" . $order_id . "'";
    $statement1 = oci_parse($conn, $sql_update1);
    $sql_update2 = "UPDATE SCOTT.RES_TABLE SET TABLE_ORDER_STATUS=0 WHERE TABLE_ID='" . $table_id . "'";
    $statement2 = oci_parse($conn, $sql_update2);
    if (oci_execute($statement1) && oci_execute($statement2)) {
        echo json_encode(array("message" => "success"));
    } else {
        echo json_encode(array("message" => "error"));
    }
}

function preOrder($conn, $data) //未完成

{
    $pre_order_time = date("Y-m-d H:i:s", time());
    $arrive_time = $_POST['arrive_time'];
}

function deleteOrder($conn, $datsa)
{
    $order_id = $data->order->order_id;
    $sql_update1 = "UPDATE SCOTT.ORDER_LIST SET ORD_STATUS=0 WHERE ORDER_ID='" . $order_id . "'";
    $statement1 = oci_parse($conn, $sql_update1);
    $sql_update2 = "UPDATE SCOTT.SALES SET SAL_STATUS=0 WHERE ORDER_ID='" . $order_id . "'";
    $statement2 = oci_parse($conn, $sql_update2);
    if (oci_execute($statement1) && oci_execute($statement2)) {
        echo json_encode(array("message" => "success"));
    } else {
        echo json_encode(array("message" => "error"));
    }
}
