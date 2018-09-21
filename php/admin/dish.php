<?php

session_start(); //开启php_session
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
include "../updatePic.php";
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
        // $sql_query = "SELECT ADMIN_TYPE FROM SCOTT.dish WHERE ADMIN_ID = '$admin_id'";
        // $statement = oci_parse($conn, $sql_query);
        // $admin_type = oci_execute($statement);
        // if ($admin_type != 1 and $admin_type != 2) {
        //     exit();
        // }
        if ($request == "addDish") {
            echo addDish($conn);
        } else if ($request == "deleteDish") {
            echo deleteDish($conn);
        }elseif ($request == "getDishInfo") {
            echo getDishInfo($conn);
        } elseif($request== "updateDish"){
            echo updateDish($conn);
        } elseif ($request == "updateDishPic") {
            echo updateDishPic($conn);
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

function addDish($conn)
{
    $sql_query = "SELECT COUNT(DISH_ID) FROM SCOTT.DISH";
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
        echo json_encode(array("message" => "success"));
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
            echo json_encode(array("message" => "success"));
        } else {
            echo json_encode(array("message" => "false"));
        }
    } else {
        echo json_encode(array("message" => "false"));
    }

}
function getDishInfo($conn)
{
    if (islegalid($_GET['dish_id'])) {
        $dish_id = $_GET['dish_id'];
        $sql_query = "SELECT * FROM SCOTT.DISH WHERE DIS_STATUS>0 AND DISH_ID='$dish_id'";
        $statement = oci_parse($conn, $sql_query);
        oci_execute($statement);
        while ($row = oci_fetch_array($statement, OCI_RETURN_NULLS)) { //查询结果集
            $dish_id = $row[0];
            $dish_name = $row[1];
            $dish_pic = $row[2];
            $dish_price = $row[3];
            $dish_type = $row[4];
            $dish_info = array("dish_id" => $dish_id, "dish_name" => $dish_name, "dish_pic" => $dish_pic, "dish_price" => $dish_price, "dish_type" => $dish_type);
        }
        echo json_encode(array("message" => "success", "data" => $dish_info));
    } else {
        echo json_encode(array("message" => "error", "reason" => oci_error()));
    }
}

function updateDish($conn)
{
    if (islegalid($_POST['dish_id'])) {
        $dish_id = $_POST['dish_id'];
        $dish_name = $_POST["dish_name"];
        $dish_price = $_POST["dish_price"];
        $dish_type = $_POST["dish_type"];
        $sql_insert = "UPDATE SCOTT.DISH SET dish_name='$dish_name',dish_price=$dish_price,dish_type=$dish_type WHERE dish_id='$dish_id'";
        $statement = oci_parse($conn, $sql_insert);

        if (oci_execute($statement)) {
            echo json_encode(array("message" => "success"));
        } else {
            echo json_encode(array("message" => "error", "reason" => oci_error()));
        }
    } else {
        echo json_encode(array("message" => "error", "reason" => oci_error()));
    }
}
function updateDishPic($conn){
    new updatePic().upload("upload_dish_pic");
}
