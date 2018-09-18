<?php
session_start();
$request = "";
$admin_id = "";
if (isset($_GET['request'])) {
    $request = $_GET['request'];
    $admin_id = $_GET['admin_id'];
} elseif (isset($_POST['request'])) {
    $request = $_POST['request'];
    $admin_id = $_POST['admin_id'];
}
if (isset($_SESSION['admin_id'])) {
    if ($admin_id != $_SESSION['admin_id'] || ($_SESSION['admin_type'] != 1 && $_SESSION['admin_type'] != 2)) {
        die();
    }
    $conn = oci_connect('scott', '123456', 'localhost:1521/ORCL', "AL32UTF8");
    if ($request == "getEmployeeInfo") {
        $employee_id = $_GET['employee_id'];
        $sql_query = "SELECT EMPLOYEE_ID,NAME,GENDER,WORKING_YEAR,AGE,SALARY,PHONE_NUM,EMPLOYEE_TYPE,EMPLOY_TIME,EMPLOYEE_PIC FROM SCOTT.EMPLOYEE WHERE EMP_STATUS>0 AND EMPLOYEE_ID='$employee_id'";
        $statement = oci_parse($conn, $sql_query);
        oci_execute($statement);
        while ($row = oci_fetch_array($statement, OCI_RETURN_NULLS)) { //查询结果集
            $employee_id = $row[0];
            $name = $row[1];
            $gender = $row[2];
            $working_year = $row[3];
            $age = $row[4];
            $salary = $row[5];
            $phone_num = $row[6];
            $employee_type = $row[7];
            $employ_time = $row[8];
            $employee_pic = $row[9];
            $employee_info = array("employee_id" => $employee_id, "name" => $name, "gender" => $gender, "working_year" => $working_year, "age" => $age, "salary" => $salary, "phone_num" => $phone_num, "employee_type" => $employee_type, "employ_time" => $employ_time,"employee_pic"=>$employee_pic);
        }
        echo json_encode(array("message" => "success", "data" => $employee_info));
    } elseif ($request == "updateEmployee") {
        $employee_id = $_POST['employee_id'];
        $sql_insert = "UPDATE SCOTT.EMPLOYEE SET name='".$_POST['name']."',gender='".$_POST['gender']."',age='".$_POST['age']."',salary='".$_POST['salary']."',phone_num='".$_POST['phone_num']."',employee_type='".$_POST['employee_type']."' WHERE EMPLOYEE_ID='".$_POST['employee_id']."'";
        $statement = oci_parse($conn, $sql_insert);
        
        if (oci_execute($statement)) {
            echo json_encode(array("message" => "success"));
        } else {
            echo json_encode(array("message" => "error", "reason" => oci_error()));
        }
    }
}
