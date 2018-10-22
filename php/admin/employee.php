<?php

require '../updatePic.php';
session_start(); //开启php_session
//$ref = $_SERVER['HTTP_REFERER'];
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
    //$request = $_GET['request']; //获取请求内容

    $conn = oci_connect('emp_admin', '123456', 'localhost:1521/ORCL', "AL32UTF8"); //连接oracle数据库
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

        if ($request == "add_employee") {
            addEmployee($conn);
        } else if ($request == "deleteEmployee") {
            deleteEmployee($conn);
        } elseif ($request == "getEmployeeInfo") {
            getEmployeeInfo($conn);
        } elseif ($request == "updateEmployee") {
            updateEmployee($conn);
        } elseif ($request == "updateEmployeePic") {
            updateEmployeePic($conn, "sd");
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
    // $sql_query = "INSERT INTO EMPLOYEE (EMPLOYEE_ID, NAME, GENDER,  WORKING_YEAR, AGE, SALARY, PHONE_NUM, EMPLOYEE_TYPE, EMPLOY_TIME, EMPLOYEE_PIC, EMP_STATUS) VALUES ('asd', 'hou', 1, 2,33, 555, '13212312312', 2, '1998-11-11', 'asd', 1)";
    // $statement = oci_parse($conn, $sql_query);
    // oci_execute($statement);


    $sql_query = "SELECT COUNT(EMPLOYEE_ID) FROM SCOTT.EMPLOYEE";
    $statement = oci_parse($conn, $sql_query);
    oci_execute($statement);
    $count = oci_fetch_array($statement, OCI_RETURN_NULLS)[0];
    $employ_time = date("Y-m-d");
    $gender = $_POST['gender'];
    if ($gender != 1 && $gender != 0)
        return;
    $employee_id = date("ymd", strtotime($employ_time));
    $employee_id = "emp_" . $employee_id . "_$gender" . "_";
    $param = $count < 10 ? "000$count" : ($count < 100 ? "00$count" : "0$count");
    $employee_id = "$employee_id" . "$param";
    $name=$_POST['name'];
    $working_year=0;
    $age=$_POST['age'];
    $salary = $_POST['salary'];
    $phone_num = $_POST['phone_num'];
    $employee_type= $_POST['employee_type'];
    $employee_pic = "../../src/employee_pic/default.png";
    $emp_status = 1;
    
    //$sql_query = "INSERT INTO scott.EMPLOYEE (EMPLOYEE_ID, NAME, GENDER,  WORKING_YEAR, AGE, SALARY, PHONE_NUM, EMPLOYEE_TYPE, EMPLOY_TIME, EMPLOYEE_PIC, EMP_STATUS) VALUES ('$employee_id', '" . $_POST['name'] . "', $gender, 0, " . $_POST['age'] . ", " . $_POST['salary'] . ", '" . $_POST['phone_num'] . "', " . $_POST['employee_type'] . ", '" . $employ_time . "', 'd', 1)";
    $sql_query = "BEGIN scott.addEmployee('$employee_id','$name',$gender,$age,$salary,'$phone_num',$employee_type,'$employ_time'); END;";
    $statement = oci_parse($conn, $sql_query);
    if (oci_execute($statement)) {
        if (isset($_POST['employeePicData']) && $_POST['employeePicData'] != "")
            updateEmployeePic($conn, $employee_id);
        else
            echo json_encode(array("message" => "success"));
    } else {
        echo json_encode(array("message" => "error", "reason" => oci_error()));
    }
    //
}
function deleteEmployee($conn)
{
    if (islegalid($_POST['employee_id'])) {
        $employee_id=$_POST['employee_id'];
        //$sql_query = "UPDATE scott.EMPLOYEE SET EMP_STATUS = 0 WHERE EMPLOYEE_ID = '" . $_POST['employee_id'] . "'";
        $sql_query = "BEGIN scott.deleteEmployee('$employee_id'); END;";
        $statement = oci_parse($conn, $sql_query);
        if (oci_execute($statement)) {
            echo json_encode(array("message" => "success"));
        } else {
            echo json_encode(array("message" => "error", "reason" => oci_error()));
        }
    } else {
        echo json_encode(array("message" => "error", "reason" => oci_error()));
    }

}
function getEmployeeInfo($conn)
{
    if (islegalid($_GET['employee_id'])) {
        $employee_id = $_GET['employee_id'];
        //$sql_query = "SELECT EMPLOYEE_ID,NAME,GENDER,WORKING_YEAR,AGE,SALARY,PHONE_NUM,EMPLOYEE_TYPE,EMPLOY_TIME,EMPLOYEE_PIC FROM SCOTT.EMPLOYEE WHERE EMP_STATUS>0 AND EMPLOYEE_ID='$employee_id'";
        $sql_query = "SELECT * FROM SCOTT.EMPLOYEE WHERE EMP_STATUS>0 AND EMPLOYEE_ID='$employee_id'";
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
        $name = $_POST['name'];
        $gender = $_POST['gender'];
        if ($gender != 1 && $gender != 0)
            return;
        $age = $_POST['age'];
        $salary = $_POST['salary'];
        $phone_num = $_POST['phone_num'];
        $employee_type = $_POST['employee_type'];
        //$sql_insert = "UPDATE SCOTT.EMPLOYEE SET name='$name',gender=$gender,age=$age,salary=$salary,phone_num='$phone_num',employee_type=$employee_type WHERE EMPLOYEE_ID='$employee_id'";
        $sql_insert = "BEGIN scott.updateEmployee('$employee_id','$name',$gender,$age,$salary,'$phone_num',$employee_type); END;";
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
function updateEmployeePic($conn, $upid)
{
    $sd = new uploadPic();
    $sd->upload("upload_employee_pic", $upid);

}


