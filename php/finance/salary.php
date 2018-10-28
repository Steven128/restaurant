<?php

session_start(); //开启php_session
// if (isset($_SERVER['HTTP_REFERER']))
//     $ref = $_SERVER['HTTP_REFERER'];
// else
//     $ref = "";
// if ($ref == "") {
//     echo "不允许从地址栏访问";
//     exit();
// } else {
//     $url = parse_url($ref);
//     if ($url['host'] != "127.0.0.1" && $url['host'] != "localhost" &&$url['host']!="localhost") {
//         echo "get out";
//         exit();
//     }
// }

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
    //$request = $_GET['request']; //获取请求内容

    $conn = oci_connect('fin_admin', '123456', '47.95.212.18/ORCL', "AL32UTF8"); //连接oracle数据库
    if (!$conn) { //未连接成功，终止脚本并返回错误信息
        $e = oci_error();
        die(json_encode($e));
    } else { //连接成功
        // $sql_query = "SELECT ADMIN_TYPE FROM SCOTT.ADMIN WHERE ADMIN_ID = '$admin_id'";
        // $statement = oci_parse($conn, $sql_query);
        // $admin_type = oci_execute($statement);
        // if ($admin_type != 1) {
        //     exit();
        // }

        if ($request == "getEmployeeInfo") {
            getEmployeeInfo($conn);
        } elseif ($request == "updateEmployee") {
            updateEmployee($conn);
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


function getEmployeeInfo($conn)
{
    if (islegalid($_GET['employee_id'])) {
        $employee_id = $_GET['employee_id'];
        //$sql_query = "SELECT EMPLOYEE_ID,NAME,GENDER,WORKING_YEAR,AGE,SALARY,PHONE_NUM,EMPLOYEE_TYPE,EMPLOY_TIME,EMPLOYEE_PIC FROM SCOTT.EMPLOYEE WHERE EMP_STATUS>0 AND EMPLOYEE_ID='$employee_id'";
        $sql_query = "SELECT * FROM SCOTT.empRead WHERE EMPLOYEE_ID='$employee_id'";
        $statement = oci_parse($conn, $sql_query);
        oci_execute($statement);
        while ($row = oci_fetch_array($statement, OCI_RETURN_NULLS)) { //查询结果集
            $employee_id = $row[0];
            $name = $row[1];
            $gender = $row[2];
            if ($gender == 1)
                $gender = "男";
            else
                $gender = "女";
            $working_year = $row[3];
            $age = $row[4];
            $salary = $row[5];
            $phone_num = $row[6];
            $employee_type = $row[7];
            $employ_time = $row[8];
            $employee_pic = $row[9];
            $employee_info = array("employee_id" => $employee_id, "name" => $name, "gender" => $gender, "working_year" => $working_year, "age" => $age, "salary" => $salary, "phone_num" => $phone_num, "employee_type" => $employee_type, "employ_time" => $employ_time, "employee_pic" => $employee_pic);
        }
        echo json_encode(array("message" => "success", "data" => $employee_info));
    } else {
        echo json_encode(array("message" => "error", "reason" => oci_error()));
    }
}
function updateEmployee($conn)
{
    if (islegalid($_POST['employee_id'])) {
        $employee_id = $_POST['employee_id'];
        
        $salary = $_POST['salary'];
        //$sql_insert = "UPDATE SCOTT.EMPLOYEE SET name='$name',gender=$gender,age=$age,salary=$salary,phone_num='$phone_num',employee_type=$employee_type WHERE EMPLOYEE_ID='$employee_id'";
        $sql_insert = "BEGIN scott.updateSalary('$employee_id',$salary); END;";
        //echo $sql_insert;
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


