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
        if ($request == "getEmployeeList") { //请求为`getEmployeeList`
            echo getEmployeeList($conn);
        } else if ($request == "") {

        }
    }
}

function getEmployeeList($conn)
{
    $emp_data_array = array();//存放员工信息列表
    $sql_query = "SELECT EMPLOYEE_ID,NAME,GENDER,WORKING_YEAR,AGE,SALARY,PHONE_NUM,EMPLOYEE_TYPE,EMPLOY_TIME FROM EMPLOYEE WHERE EMP_STATUS>0 ORDER BY EMPLOYEE_TYPE ASC,EMPLOY_TIME DESC,WORKING_YEAR DESC";
    $statement = oci_parse($conn, $sql_query);
    oci_execute($statement);
    while ($row = oci_fetch_array($statement, OCI_RETURN_NULLS)) {//查询结果集
        $employee_id = $row[0];
        $name = $row[1];
        $gender = $row[2];
        $working_year = $row[3];
        $age = $row[4];
        $salary = $row[5];
        $phone_num = $row[6];
        $employee_type = $row[7];
        $employ_time = $row[8];
        //使用一个数组放入一个员工的信息
        $data_single = array("employee_id" => $employee_id, "name" => $name, "gender" => $gender, "working_year" => $working_year, "age" => $age, "salary" => $salary, "phone_num" => $phone_num, "employee_type" => $employee_type, "employ_time" => $employ_time);
        array_push($emp_data_array, $data_single);//将单个员工信息的数组添加到$emp_data_array中
    }
    return json_encode($emp_data_array);//将数组进行json序列化后返回
}
