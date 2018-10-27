<?php
$ref = $_SERVER['REFERER'];
if ($ref == "") {
    echo "不允许从地址栏访问";
    exit();
} else {
    $url = parse_url($ref);
    if ($url['host'] != "127.0.0.1" && $url['host'] != "localhost") {
        echo "no";
        exit();
    }
}
session_start(); //开启php_session
if (isset($_GET['request'])) {
    $request = $_GET['request'];
    $admin_id = $_GET['admin_id'];
} elseif (isset($_POST['request'])) {
    $request = $_POST['request'];
    $admin_id = $_POST['admin_id'];
}
if (isset($_SESSION['admin_id']) && $_SESSION['admin_id'] == $admin_id) { //如果已设置session且session对应用户为当前访问用户
    

    $conn = oci_connect('inv_admin', '123456', '47.95.212.18/ORCL', "AL32UTF8"); //连接oracle数据库
    if (!$conn) { //未连接成功，终止脚本并返回错误信息
        $e = oci_error();
        die(json_encode($e));
    } else { //连接成功
        // $sql_query = "SELECT ADMIN_TYPE FROM SCOTT.EMPLOYEE WHERE ADMIN_ID = '$admin_id'";
        // $statement = oci_parse($conn, $sql_query);
        // $admin_type = oci_execute($statement);
        // if ($admin_type != 1 and $admin_type != 4) {
        //     exit();
        // }
        if ($request == "purchase") {
            echo purchase($conn);
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
function islegalnum($str)
{
    if (preg_match('/^(\+)?\d+(\.\d+)?$/', $str)) {
        return true;
    } else {
        return false;
    }
}
function purchase($conn)
{
    if (islegalid($_POST['goods_id']) and islegalnum($_POST['purchase_quantity'])) {
        $sql_query = "SELECT COUNT(PURCHASE_ID) FROM SCOTT.PURCHASE";
        $statement = oci_parse($conn, $sql_query);
        $sum = oci_execute($statement);
        $str = strval($sum);

        $date=date("Y-m-d", time());
        $begin_time=strtotime(date("Y-m-d", time()-3*365*24*3600)." 07:00:00");
        $end_time=strtotime("$date 20:00:00");
        $rand=mt_rand($begin_time, $end_time);
        $ove_id=date("Ymd_His", $rand);
        $ove_date=date("Y-m-d", $rand);
        $ove_id="ove_$ove_id";

        $sql_query = "INSERT INTO overhead (overhead_id, overhead_type, overhead_price, overhead_date, ove_invoice_pic, ove_status) VALUES ('$ove_id', 1, " . $_POST['overhead_price'] . ", '" . date("ymd_hms", time()) . "', '" . $_POST['purchase_date'] . "', 1)";

        $statement = oci_parse($conn, $sql_query);
        // if (!oci_execute($statement)) {
        //     echo json_encode(array("message" => "false"));
        // }

        // $sql_query = "UPDATE INVENTORY SET QUANTITY = " . $_POST['purchase_quantity'] . " WHERE GOODS_ID = '" . $_POST['goods_id'] . "'";
        // $statement = oci_parse($conn, $sql_query);
        if (oci_execute($statement)) {
            echo json_encode(array("message" => "success"));
        } else {
            echo json_encode(array("message" => "false"));
        }
    } else {
        echo json_encode(array("message" => "false"));
    }
}
