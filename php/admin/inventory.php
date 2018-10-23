<?php
// $ref = $_SERVER['REFERER'];
// if (isset($_SERVER['HTTP_REFERER']))
//     $ref = $_SERVER['HTTP_REFERER'];
// else
//     $ref = "";
// if ($ref == "") {
//     echo "不允许从地址栏访问";
//     exit();
// } else {
//     $url = parse_url($ref);
//     if ($url['host'] != "127.0.0.1" && $url['host'] != "localhost" && $url['host'] != "47.95.212.18") {
//         echo "get out";
//         exit();
//     }
// }
session_start(); //开启php_session
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


    $conn = oci_connect('inv_admin', '123456', 'localhost:1521/ORCL', "AL32UTF8"); //连接oracle数据库
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
        if ($request == "addInventory") {
            purchase($conn);
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
    $sql_query = "SELECT COUNT(inventory_id) FROM SCOTT.inventory";
    $statement = oci_parse($conn, $sql_query);
    oci_execute($statement);
    $count = oci_fetch_array($statement, OCI_RETURN_NULLS)[0];
    // echo $count;
    $data = $_POST['data'];
        //var_dump($data);
    $num = count($data);
    $sum=0;
    foreach ($data as $value) { 
            // echo $value['name']." ";
            // echo $value['quantity']." ";
        $_inventory_id = $count < 10 ? "00000$count" : ($count < 100 ? "0000$count" : ($count < 1000 ? "000$count" : "00$count"));
        $inventory_id = "inv_$_inventory_id";
        $goods_name = $value['name'];
        $quantity = $value['quantity'];
        $sql_query = "BEGIN scott.addInventory('$inventory_id','$goods_name',$quantity); END;";

        $statement = oci_parse($conn, $sql_query);
        if (!oci_execute($statement)) {
            
        } else {
            $sum++;
        }

    }
    if ($sum!=$num) {
        echo json_encode(array("message" => "error"));
    } else {
        echo json_encode(array("message" => "success"));
    }
}
