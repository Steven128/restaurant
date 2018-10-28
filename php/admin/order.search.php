<?php
session_start();

if (isset($_GET['request']) && $_GET['request'] != "") {
    $request = $_GET['request'];
    $admin_id = $_GET['admin_id'];
} elseif (isset($_POST['request']) && $_POST['request'] != "") {
    $request = $_POST['request'];
    $admin_id = $_POST['admin_id'];
} else {
    die();
}
if (isset($_SESSION['admin_id']) && $_SESSION['admin_id'] == $admin_id) { //如果已设置session且session对应用户为当前访问用户
    //$request = $_GET['request']; //获取请求内容

    $conn = oci_connect('scott', '123456', '47.95.212.18/ORCL', "AL32UTF8"); //连接oracle数据库
    if (!$conn) { //未连接成功，终止脚本并返回错误信息
        $e = oci_error();
        die(json_encode($e));
    } else { //连接成功
        if ($request == "current") {
            searchByCurrent($conn);
        } elseif ($request == "date") {
            searchByDate($conn);
        } elseif ($request == "order") {
            searchByOrder($conn);
        }
    }
}
function islegalid($str)
{
    if (preg_match('/^[_0-9a-z]{5,17}$/i', $str)) {
        return true;
    } else {
        return false;
    }
}

function searchByCurrent($conn)
{
    $sql_query = "SELECT ORDER_ID,TABLE_NUMBER,TOTAL_PRICE,PAY_STATUS,PAY_METHOD,PAY_TIME,ORDER_NOTE FROM SCOTT.ORDER_LIST,SCOTT.RES_TABLE WHERE ORDER_LIST.TABLE_ID=RES_TABLE.TABLE_ID AND ORD_STATUS>0 AND TAB_STATUS>0  AND PAY_STATUS=0 ORDER BY PAY_STATUS,ORDER_ID";
    $data = array();
    $statement = oci_parse($conn, $sql_query);
    oci_execute($statement);
    while ($row = oci_fetch_array($statement, OCI_RETURN_NULLS)) {
        $order_id = $row[0];
        $table_num=$row[1];
        $tol_price=$row[2];
        $pay_status = $row[3];
        $pay_method=$row[4];
        $pay_time=$row[5];
        $order_note=$row[6];
        $single = array("order_id"=> $order_id, "table_num"=> $table_num, "tol_price"=> ($tol_price == null? "-- --":$tol_price), "pay_status"=> $pay_status, "pay_method"=> $pay_method, "pay_time"=> $pay_time == null? "-- --":$pay_time, "order_note"=> $order_note == null? "-- --":$order_note);
        array_push($data, $single);
    }
    echo json_encode(array("message"=>"success","data"=>$data));
}

function searchByDate($conn)
{
    $param = $_GET['param'];
    if ($param == "today") {
        $date = date("Ymd", time());
    } elseif ($param == "yesterday") {
        $date = date("Ymd", time() - 24 * 3600);
    } elseif ($param == "select") {
        $time = strtotime($_GET['date']);
        $date = date("Ymd", $time);
    }
    $sql_query = "SELECT ORDER_ID,TABLE_NUMBER,TOTAL_PRICE,PAY_STATUS,PAY_METHOD,PAY_TIME,ORDER_NOTE FROM SCOTT.ORDER_LIST,SCOTT.RES_TABLE WHERE ORDER_LIST.TABLE_ID=RES_TABLE.TABLE_ID AND ORD_STATUS>0 AND TAB_STATUS>0 AND SUBSTR(ORDER_ID,9,8)='$date' ORDER BY PAY_STATUS,ORDER_ID";
    $data = array();
    $statement = oci_parse($conn, $sql_query);
    oci_execute($statement);
    while ($row = oci_fetch_array($statement, OCI_RETURN_NULLS)) {
        $order_id = $row[0];
        $table_num=$row[1];
        $tol_price=$row[2];
        $pay_status = $row[3];
        $pay_method=$row[4];
        $pay_time=$row[5];
        $order_note=$row[6];
        $single = array("order_id"=> $order_id, "table_num"=> $table_num, "tol_price"=> $tol_price, "pay_status"=> $pay_status, "pay_method"=> $pay_method, "pay_time"=> $pay_time, "order_note"=> $order_note);
        array_push($data, $single);
    }
    echo json_encode(array("message"=>"success","data"=>$data));
}

function searchByOrder($conn)
{
    $order_id = $_GET['order_id'];
    //获取订单信息
    $sql_query = "SELECT ORDER_ID,TABLE_NUMBER,TOTAL_PRICE,PAY_STATUS,PAY_METHOD,PAY_TIME,ORDER_NOTE FROM SCOTT.ORDER_LIST,SCOTT.RES_TABLE WHERE ORDER_LIST.TABLE_ID=RES_TABLE.TABLE_ID AND ORD_STATUS>0 AND TAB_STATUS>0 AND ORDER_ID='$order_id' ORDER BY PAY_STATUS,ORDER_ID";
    $data = array();
    $statement = oci_parse($conn, $sql_query);
    oci_execute($statement);
    $row = oci_fetch_array($statement, OCI_RETURN_NULLS);
    $order_id = $row[0];
    $table_num=$row[1];
    $tol_price=$row[2];
    $pay_status = $row[3];
    $pay_method=$row[4];
    $pay_time=$row[5];
    $order_note=$row[6];
    $data = array("order_id"=> $order_id, "table_num"=> $table_num, "tol_price"=> $tol_price, "pay_status"=> $pay_status, "pay_method"=> $pay_method, "pay_time"=> $pay_time, "order_note"=> $order_note);
    //获取点菜列表
    $sql_query = "SELECT DISH_NAME,DISH_PIC,SALES.DISH_PRICE,SAL_STATUS FROM SCOTT.SALES,SCOTT.DISH WHERE SALES.DISH_ID=DISH.DISH_ID AND ORDER_ID='$order_id' AND SAL_STATUS>0";
    $dish_list = array();
    $statement = oci_parse($conn, $sql_query);
    oci_execute($statement);
    while ($row = oci_fetch_array($statement, OCI_RETURN_NULLS)) {
        $dish_name = $row[0];
        $dish_pic = $row[1];
        $dish_price = $row[2];
        $sal_status = $row[3];
        $single = array("dish_name"=>$dish_name,"dish_pic"=>$dish_pic,"dish_price"=>$dish_price,"sal_status"=>$sal_status);
        array_push($dish_list, $single);
    }
    echo json_encode(array("message"=>"success","data"=>$data,"dish_list"=>$dish_list));
}
