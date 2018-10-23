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
    if ($request == "getMenu") {
        getMenu($conn);
    } else
    if ($request == "createOrder") {
        createOrder($conn);
    } else
    if ($request == "deleteDish") {
        deleteDish($conn);
    } else
    if ($request == "getOrder") {
        getOrder($conn);
    } else
    if ($request == "payOrder") {
        payOrder($conn);
    } else
    if ($request == "preOrder") {
        perOrder($conn);
    }
}

function getMenu($conn)
{ //图片传输未解决
    $sql_select1 = "SELECT DISH_ID,DISH_NAME,DISH_PIC,DISH_PRICE,DISH_TYPE FROM SCOTT.DISH WHERE DIS_STATUS=1";
    $statement1 = oci_parse($conn, $sql_select1);
    coi_execute($statement1);
    $menu_info = null;
    while ($row = oci_fetch_array($statement1, OCI_RETURN_NULL)) {
        $dish_id = $row[0];
        $dish_name = $row[1];
        $dish_pic = $row[2];
        $dish_price = $row[3];
        $dish_type = $row[4];
        $menu_info1 = array('dish_id' => $dish_id, 'dish_name' => $dish_name, 'dish_pic' => $dish_pic, 'dish_price' => $dish_price, ' dish_type' => $dish_type);
        array_push($menu_info, $menu_info1);
    }
    echo json_encode(array("message" => "success", "data" => $menu_info));
}

function createOrder($conn)
{
    $dishes = $_POST["dishes"];
    //$order_note = $_POST["order_note"];
    $order_note = null;
    $dish_list = null;
    $total_price = null;
    $table = $_POST['table'];
    $table_id = $table['table_id'];
    $table_number = $table['table_number'];
    $sql_select1 = "SELECT ORDER_ID FROM SCOTT.ORDER_LIST WHERE TABLE_ID='" . $table_id . "' AND PAY_STATUS=0";
    $statement3 = oci_parse($conn, $sql_select1);
    oci_execute($statement3);
    $row = oci_fetch_array($statement3);
    if ($row == null) {
        $order_time = date("YmdHis", time());
        $table_number = $table_number < 10 ? "00 $table_number " : ($table_number < 100 ? "0  $table_number" : "$table_number");
        $order_id = "ord_" . "$table_number" . "_" . "$order_time";
        $count = 0;
        foreach ($dishes as $dish) {
            $count++;
            $dish_id = $dish['dish_id'];
            $dish_list = "$dish_list" . "," . "$dish_id";
            $dish_price = $dish['dish_price'];
            $total_price += $dish_price;
            $_count = $count;
            $_count = $_count < 10 ? "00$_count" : ($c_ount < 100 ? "0$_count" : "$_count");
            $sales_id = "sal_" . substr($order_id, -18) . "_" . "$_count";
            $sql_insert2 = "INSERT INTO SCOTT.SALES (SALES_ID,DISH_ID,DISH_PRICE,ORDER_ID,SAL_STATUS) VALUES ('$sales_id','$dish_id',$dish_price,'$order_id',1)";
            $statement2 = oci_parse($conn, $sql_insert2);
            oci_execute($statement2); //创建sales表并提交
        }
        $sql_insert1 = "INSERT INTO SCOTT.ORDER_LIST (order_id,table_id,dish_list,total_price,pay_status,order_note,) VALUES ('$order_id','$table_id','$dish_list',$total_price,0,'$order_note')";
        $statement1 = oci_parse($conn, $sql_insert1);
        oci_execute($statement1); //创建order并提交
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
            $dish_id = $dish['dish_id'];
            $dish_list = "$dish_list" . "," . "$dish_id";
            $dish_price = $dish['dish_price'];
            $total_price += $dish_price;
            $_count = $count;
            $_count = $_count < 10 ? "00$_count" : ($c_ount < 100 ? "0$_count" : "$_count");
            $sales_id = "sal_" . substr($order_id, -18) . "_" . "$_count";
            $sql_insert2 = "INSERT INTO SCOTT.SALES (SALES_ID,DISH_ID,DISH_PRICE,ORDER_ID,SAL_STATUS) VALUES ('$sales_id','$dish_id',$dish_price,'$order_id',1)";
            $statement2 = oci_parse($conn, $sql_insert2);
            oci_execute($statement2); //创建sales表并提交
        }
        $sql_select3 = "SELECT DISH_LIST FROM SCOTT.ORDER_LIST WHERE ORDER_ID='" . $order_id . "'";
        $statement5 = oci_parse($conn, $sql_select3);
        oci_execute($statement5);
        $row2 = oci_fetch_array($statement5);
        $dish_list1 = $row2[0];
        $dish_list = $dish_list . "," . $dish_list1;
        $sql_update = "UPDATE SCOTT.ORDER_LIST SET DISH_LIST='" . $dish_list . "' WHERE ORDER_ID='" . $order_id . "'";
        $statement6 = oci_parse($conn, $sql_update);
        echo json_encode(array("message" => "success"));
    }

}

function deleteDish($conn)
{
    $order_id = $_POST['order_id'];
    $dish_id = $_POST['dish_id'];
    $sql_update1 = "UPDATE SALES SET SAL_STATUS=0 WHERE ORDER_ID='" . $order_id . "'";
    $statement1 = oci_parse($conn, $sql_update1);
    $sql_select1 = "SELECT DISH_LIST FROM SCOTT.ORDER_LIST WHERE ORDER_ID='" . $order_id . "'";
    $statement2 = oci_parse($conn, $sql_select1);
    oci_execute($statement2);
    $dish_list1 = null;
    while ($row = oci_fetch_array($statement, OCI_RETURN_NULLS)) {
        $dish_list1 = $row[0];
    }
    $dish_array1 = explode(",", $dish_list1);

    oci_execute($statement1);
}

function getOrder($conn)
{

}

function payOrder($conn)
{

}

function perOrder($conn)
{

}
