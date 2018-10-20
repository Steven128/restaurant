<?php
// $ref = $_SERVER['REFERER'];
// if ($ref == "") {
//     echo "不允许从地址栏访问";
//     exit();
// } else {
//     $url = parse_url($ref);
//     if ($url['host'] != "127.0.0.1" && $url['host'] != "localhost") {
//         echo "no";
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
}else{
    die();
}
if (isset($_SESSION['admin_id']) && $_SESSION['admin_id'] == $admin_id) { //如果已设置session且session对应用户为当前访问用户

    $conn = oci_connect('tab_admin', '123456', 'localhost:1521/ORCL', "AL32UTF8"); //连接oracle数据库
    if (!$conn) { //未连接成功，终止脚本并返回错误信息
        $e = oci_error();
        die(json_encode($e));
    } else { //连接成功
        // $sql_query = "SELECT ADMIN_TYPE FROM SCOTT.table WHERE ADMIN_ID = '$admin_id'";
        // $statement = oci_parse($conn, $sql_query);
        // $admin_type = oci_execute($statement);
        // if ($admin_type != 1 and $admin_type != 2) {
        //     exit();
        // }
        if ($request == "addTable") {
            addTable($conn);
        } else if ($request == "deleteTable") {
            deleteTable($conn);
        } elseif ($request == "getTableInfo") {
            getTableInfo($conn);
        } elseif ($request == "updateTable") {
            updateTable($conn);
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
    $sql_query = "INSERT INTO scott.RES_TABLE (TABLE_ID, TABLE_NUMBER, DEFAULT_NUMBER, TABLE_ORDER_STATUS, TAB_STATUS) VALUES ('$TABLE_ID', '" . $_POST['table_number'] . "', " . $_POST['default_number'] . ", 0, 1)";
    $statement = oci_parse($conn, $sql_query);
    if (oci_execute($statement) == true) {
        echo json_encode(array("message" => "success"));
    } else {
        echo json_encode(array("message" => "false"));
    }

}
function deleteTable($conn)
{
    if (islegalid($_POST['table_id'])) {
        $sql_query = "UPDATE scott.RES_TABLE SET TAB_STATUS = 0 WHERE TABLE_ID = '" . $_POST['table_id'] . "'";
        $statement = oci_parse($conn, $sql_query);
        if (oci_execute($statement) == true) {
            echo json_encode(array("message" => "success"));
        } else {
            echo json_encode(array("message" => "false"));
        }
    } else {
        echo json_encode(array("message" => "false"));
    }
}
function getTableInfo($conn)
{
    if (islegalid($_GET['table_id'])) {
        $table_id = $_GET['table_id'];
        $sql_query = "SELECT * FROM SCOTT.RES_TABLE WHERE TAB_STATUS>0 AND TABLE_ID='$table_id'";
        $statement = oci_parse($conn, $sql_query);
        oci_execute($statement);
        $table_info = null;
        while ($row = oci_fetch_array($statement, OCI_RETURN_NULLS)) { //查询结果集
            $table_id = $row[0];
            $table_number = $row[1];
            $default_number = $row[2];
            $table_order_status = $row[3];
            $table_info = array("table_id" => $table_id, "table_number" => $table_number, "default_number" => $default_number, "table_order_status" => $table_order_status);
        }
        echo json_encode(array("message" => "success", "data" => $table_info));
    } else {
        echo json_encode(array("message" => "error", "reason" => oci_error()));
    }
}
function updateTable($conn)
{
    if (islegalid($_POST['table_id'])) {
        $table_id = $_POST['table_id'];
        $table_number = $_POST["table_number"];
        $default_number = $_POST["default_number"];
        $table_order_status = $_POST["table_order_status"];
        $sql_insert = "UPDATE SCOTT.RES_TABLE SET table_number='$table_number',default_number=$default_number,table_order_status=$table_order_status WHERE table_id='$table_id'";
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
