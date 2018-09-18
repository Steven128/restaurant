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
    

    $conn = oci_connect('scott', '123456', 'localhost:1521/ORCL', "AL32UTF8"); //连接oracle数据库
    if (!$conn) { //未连接成功，终止脚本并返回错误信息
        $e = oci_error();
        die(json_encode($e));
    } else { //连接成功
        // $sql_query = "SELECT ADMIN_TYPE FROM SCOTT.EMPLOYEE WHERE ADMIN_ID = '$admin_id'";
        // $statement = oci_parse($conn, $sql_query);
        // $admin_type = oci_execute($statement);
        // if ($admin_type != 1 and $admin_type != 2) {
        //     exit();
        // }
        if ($request == "addTable") {
            echo addTable($conn);
        } else if ($request == "deleteTable") {
            echo deleteTable($conn);
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
function addTable($conn)
{
    $sql_query = "SELECT COUNT(TABLE_ID) FROM SCOTT.RES_TABLE";
    $statement = oci_parse($conn, $sql_query);
    $sum = oci_execute($statement);
    $str = strval($sum);
    $TABLE_ID = date('m');
    for ($i = 6 - strlen($str); $i > 0; $i--) {
        $TABLE_ID += "0";
    }
    $TABLE_ID += $str;
    $sql_query = "INSERT INTO RES_TABLE (TABLE_ID, TABLE_NUMBER, DEFAULT_NUMBER, TABLE_ORDER_STATUS, TAB_STATUS) VALUES ('$TABLE_ID', '" . $_POST['table_number'] . "', " . $_POST['default_number'] . ", 0, 1)";
    $statement = oci_parse($conn, $sql_query);
    if (oci_execute($statement) == true) {
        echo json_encode(array("message" => "true"));
    } else {
        echo json_encode(array("message" => "false"));
    }

}
function deleteTable($conn)
{
    if (islegalid($_POST['table_id'])) {
        $sql_query = "UPDATE RES_TABLE SET TAB_STATUS = 0 WHERE TABLE_ID = '" . $_POST['table_id'] . "'";
        $statement = oci_parse($conn, $sql_query);
        if (oci_execute($statement) == true) {
            echo json_encode(array("message" => "true"));
        } else {
            echo json_encode(array("message" => "false"));
        }
    } else {
        echo json_encode(array("message" => "false"));
    }
}
