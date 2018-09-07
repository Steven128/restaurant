<?php
session_start(); //开启php_session
$admin_id = $_GET['admin_id']; //获取admin_id
if (isset($_SESSION['admin_id']) && $_SESSION['admin_id'] == $admin_id) { //如果已设置session且session对应用户为当前访问用户
    $request = $_GET['request']; //获取请求内容

    $conn = oci_connect('scott', '123456', 'localhost:1521/ORCL', "AL32UTF8"); //连接oracle数据库
    if (!$conn) { //未连接成功，终止脚本并返回错误信息
        $e = oci_error();
        die(json_encode($e));
    } else { //连接成功
        if ($request == "getFinance") { //请求为`getEmployeeList`
            echo getDish($conn);
        } else if ($request == "") {

        }
    }
}

function getFinance($conn)
{
    $fin_data_array = array();//存放库存信息列表
    $sql_query = "SELECT FINANCE_ID,FIN_DATE,MONTH,TURNOVER,COST,PROFIT FROM FINANCE WHERE INV_STATUS>0 ORDER BY FIN_DATE,MONTH DESC;
    $statement = oci_parse($conn, $sql_query);
    oci_execute($statement);
    while ($row = oci_fetch_array($statement, OCI_RETURN_NULLS)) {//查询结果集
        $finance_id = $row[0];
        $fin_date = $row[1];
        $month = $row[2];
        $turnover = $row[3];
        $cost = $row[4];
        $profit = $row[5];
        //使用一个数组放入一个员工的信息
        $data_single = array("finance_id" => $finance_id, "fin_date" => $fin_date, "month" => $month, "turnover" => $turnover, "cost" => $cost, "profit" => $profit);
        array_push($fin_data_array, $data_single);//将单个员工信息的数组添加到$emp_data_array中
    }
    return json_encode($fin_data_array);//将数组进行json序列化后返回
}
