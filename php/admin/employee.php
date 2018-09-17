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
        // $sql_query = "SELECT ADMIN_TYPE FROM ADMIN WHERE ADMIN_ID = '$admin_id'";
        // $statement = oci_parse($conn, $sql_query);
        // $admin_type = oci_execute($statement);
        // if ($admin_type != 1) {
        //     exit();
        // }

        if ($request == "addEmployee") {
            echo addEmployee($conn);
        } else if ($request == "deleteEmployee") {
            echo deleteEmployee($conn);
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

function addEmployee($conn)
{
    $sql_query = "SELECT COUNT(EMPLOYEE_ID) FROM EMPLOYEE";
    $statement = oci_parse($conn, $sql_query);
    $sum = oci_execute($statement);
    $str = strval($sum);
    $EMPLOYEE_ID = date('m');
    for ($i = 4 - strlen($str); $i > 0; $i--) {
        $EMPLOYEE_ID += "0";
    }
    $EMPLOYEE_ID += $str;

    $sql_query = "INSERT INTO EMPLOYEE (EMPLOYEE_ID, NAME, GENDER,  WORKING_YEAR, AGE, SALARY, PHONE_NUM, EMPLOYEE_TYPE, EMPLOY_TIME, EMPLOYEE_PIC, EMP_STATUS) VALUES ('$EMPLOYEE_ID', '" . $_POST['name'] . "', " . $_POST['gender'] . ", " . $_POST['working_year'] . ", " . $_POST['age'] . ", " . $_POST['salary'] . ", '" . $_POST['phone_num'] . "', " . $_POST['employee_type'] . ", '" . $_POST['employee_time'] . "', '" . $_POST['employee_pic'] . "', 1)";

    $statement = oci_parse($conn, $sql_query);
    if (oci_execute($statement)) {
        echo json_encode(array("message" => "true"));
    } else {
        echo json_encode(array("message" => "false"));
    }

}
function deleteEmployee($conn)
{
    if (islegalid($_POST['employee_id'])) {
        $sql_query = "UPDATE EMPLOYEE SET EMP_STATUS = 0 WHERE EMPLOYEE_ID = '" . $_POST['employee_id'] . "'";
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
