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
$admin_id = $_GET['admin_id']; //获取admin_id
if (isset($_SESSION['admin_id']) && $_SESSION['admin_id'] == $admin_id) { //如果已设置session且session对应用户为当前访问用户
    $request = $_GET['request']; //获取请求内容

    $conn = oci_connect('scott', '123456', 'localhost:1521/ORCL', "AL32UTF8"); //连接oracle数据库
    if (!$conn) { //未连接成功，终止脚本并返回错误信息
        $e = oci_error();
        die(json_encode($e));
    } else { //连接成功
        // $sql_query = "SELECT ADMIN_TYPE FROM EMPLOYEE WHERE ADMIN_ID = '$admin_id'";
        // $statement = oci_parse($conn, $sql_query);
        // $admin_type = oci_execute($statement);
        // if ($admin_type != 1 and $admin_type != 2) {
        //     exit();
        // }
        if ($request == "addDish") {
            echo addDish($conn);
        } else if ($request == "deleteDish") {
            echo deleteDish($conn);
        }
    }
}
<<<<<<< HEAD:php/admin/dish.php
function islegalid($str)
{
    if (preg_match('/^[_0-9a-z]{5,17}$/i', $str)) {
        return true;
    } else {
        return false;
    }
}
=======
>>>>>>> 0eb805dd8a2c85c4e1465ba7d415cbedc2b32787:php_test/admin/dish.php

function addDish($conn)
{
    $sql_query = "SELECT COUNT(DISH_ID) FROM DISH";
    $statement = oci_parse($conn, $sql_query);
    $sum = oci_execute($statement);
    $str = strval($sum);
    $DISH_ID = date('m');
    for ($i = 6 - strlen($str); $i > 0; $i--) {
        $DISH_ID += "0";
    }
    $DISH_ID += $str;

    $sql_query = "INSERT INTO DISH (DISH_ID, DISH_NAME, DISH_PIC, DISH_PRICE, DISH_TYPE, DIS_STATUS) VALUES ('$DISH_ID', '" . $_POST['dish_name'] . "', '" . $_POST['dish_pic'] . "', " . $_POST['dish_price'] . ", " . $_POST['dish_type'] . ", 1)";

    $statement = oci_parse($conn, $sql_query);
    if (oci_execute($statement)) {
        echo json_encode(array("message" => "true"));
    } else {
        echo json_encode(array("message" => "false"));
    }

}
function deleteDish($conn)
{
    if (islegalid($_POST['dish_id'])) {
        $sql_query = "UPDATE DISH SET DIS_STATUS = 0 WHERE DISH_ID = '" . $_POST['dish_id'] . "'";
        $statement = oci_parse($conn, $sql_query);
        if (oci_execute($statement)) {
            echo json_encode(array("message" => "true"));
        } else {
            echo json_encode(array("message" => "false"));
        }
    } else {
        echo json_encode(array("message" => "false"));
    }

}
