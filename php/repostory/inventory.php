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
    

    $conn = oci_connect('inv', '123456', 'localhost:1521/ORCL', "AL32UTF8"); //连接oracle数据库
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
            echo addInventory($conn);
        }

    }
}
function islegalname($str)
{
    return true;
    if (preg_match('/^[_0-9a-z]{5,17}$/i', $str)) {
        return true;
    } else {
        return false;
    }
}
function islegalquantity($str)
{
    if (preg_match('/^(\+)?\d+(\.\d+)?$/', $str)) {
        return true;
    } else {
        return false;
    }
}
function addInventory($conn)
{
    if (islegalid($_POST['goods_name']) and islegalnum($_POST['quantity'])) {
        $sql_query = "SELECT goods_id FROM SCOTT.GOODS WHERE goods_name='".$_POST['goods_name']."'";
        $statement = oci_parse($conn, $sql_query);
        $goods_id = oci_execute($statement);
        //$str = strval($sum);
        //$INVENTORY_ID = "inv_" . date("ymd_hms", time());
        $sql_query = "SELECT count(goods_id) from SCOTT.GOODS";
        $statement = oci_parse($conn, $sql_query);
        $count = oci_execute($statement);
        $_inventory_id=$count < 10 ? "00000$count" : ($count < 100 ? "0000$count" : ($count < 1000 ? "000$count" : "00$count"));
        $inventory_id="inv_$_inventory_id";
        $sql_query = "INSERT INTO INVENTORY (inventory_id, goods_id, quantity, inv_status) VALUES ('$inventory_id','$goods_id'," .$_POST['quantity'].", 1)";

        $statement = oci_parse($conn, $sql_query);
        
        if (oci_execute($statement)) {
            echo json_encode(array("message" => "success"));
        } else {
            echo json_encode(array("message" => "false"));
        }
    } else {
        echo json_encode(array("message" => "false"));
    }
}


function updateInventory($conn)
{
    if (islegalid($_POST['goods_name']) and islegalnum($_POST['quantity'])) {
        $sql_query = "SELECT goods_id FROM SCOTT.GOODS WHERE goods_name='".$_POST['goods_name']."'";
        $statement = oci_parse($conn, $sql_query);
        $goods_id = oci_execute($statement);
        //$str = strval($sum);

        $sql_query = "UPDATE INVENTORY SET quantity='".$_POST['quantity']."' WHERE goods_id='$goods_id'";

        $statement = oci_parse($conn, $sql_query);
        
        if (oci_execute($statement)) {
            echo json_encode(array("message" => "success"));
        } else {
            echo json_encode(array("message" => "false"));
        }
    } else {
        echo json_encode(array("message" => "false"));
    }
}