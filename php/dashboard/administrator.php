<?php

require '../updatePic.php';
session_start(); //开启php_session
if (isset($_SERVER['HTTP_REFERER'])) {
    $ref = $_SERVER['HTTP_REFERER'];
} else {
    $ref = "";
}
if ($ref == "") {
    echo "不允许从地址栏访问";
    exit();
} else {
    $url = parse_url($ref);
    if ($url['host'] != "127.0.0.1" && $url['host'] != "localhost" &&$url['host']!="localhost") {
        echo "get out";
        exit();
    }
}

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

    $conn = oci_connect('scott', '123456', 'localhost:1521/ORCL', "AL32UTF8"); //连接oracle数据库
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

        if ($request == "add_admin") {
            addEmployee($conn);
        } elseif ($request == "deleteAdmin") {
            deleteEmployee($conn);
        } elseif ($request == "getAdminInfo") {
            getEmployeeInfo($conn);
        } elseif ($request == "updateAdmin") {
            updateEmployee($conn);
        } elseif ($request == "updateAdminPic") {
            updateEmployeePic($conn, "");
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
    $admin_type = $_POST['admin_type'];
    $sql_query = "SELECT COUNT(ADMIN_ID) FROM SCOTT.ADMIN WHERE ADMIN_TYPE=$admin_type";
    $statement = oci_parse($conn, $sql_query);
    oci_execute($statement);
    $count = oci_fetch_array($statement, OCI_RETURN_NULLS)[0];

    $create_time = date("Y-m-d h:i:s");
    if ($admin_type == 1) {
        $type="def";
    } elseif ($admin_type==2) {
        $type="com";
    } elseif ($admin_type==3) {
        $type="fin";
    } elseif ($admin_type==4) {
        $type="inv";
    }
    $admin_id2 = date("ymd");
    $param = $count < 10 ? "0$count" : $count;
    $admin_id2 = "adm_$type" .  "_$admin_id2" . "_$count";
    
    $admin_name = $_POST['admin_name'];
    $admin_passwd = "restaurant$admin_name" . "123456";
    $admin_passwd = md5($admin_passwd);
    
    //$sql_query = "INSERT INTO scott.EMPLOYEE (EMPLOYEE_ID, NAME, GENDER,  WORKING_YEAR, AGE, SALARY, PHONE_NUM, EMPLOYEE_TYPE, EMPLOY_TIME, EMPLOYEE_PIC, EMP_STATUS) VALUES ('$employee_id', '" . $_POST['name'] . "', $gender, 0, " . $_POST['age'] . ", " . $_POST['salary'] . ", '" . $_POST['phone_num'] . "', " . $_POST['employee_type'] . ", '" . $employ_time . "', 'd', 1)";
    $sql_query = "BEGIN scott.addAdmin('$admin_id2','$admin_name','$admin_passwd',$admin_type,'$create_time'); END;";
    $statement = oci_parse($conn, $sql_query);
    if (oci_execute($statement)) {
        if (isset($_POST['adminPicData']) && $_POST['adminPicData'] != "") {
            updateEmployeePic($conn, $admin_id2);
        } else {
            echo json_encode(array("message" => "success"));
        }
    } else {
        echo json_encode(array("message" => "error", "reason" => oci_error()));
    }
    //
}
function deleteEmployee($conn)
{
    $admin_name = $_POST['admin_name'];
    //$sql_query = "UPDATE scott.EMPLOYEE SET EMP_STATUS = 0 WHERE EMPLOYEE_ID = '" . $_POST['employee_id'] . "'";
    $sql_query = "BEGIN scott.deleteAdmin('$admin_name'); END;";
    $statement = oci_parse($conn, $sql_query);
    if (oci_execute($statement)) {
        echo json_encode(array("message" => "success"));
    } else {
        echo json_encode(array("message" => "error", "reason" => oci_error()));
    }
}
function getEmployeeInfo($conn)
{
    $admin_id2 = $_GET['admin_id'];
    //$sql_query = "SELECT EMPLOYEE_ID,NAME,GENDER,WORKING_YEAR,AGE,SALARY,PHONE_NUM,EMPLOYEE_TYPE,EMPLOY_TIME,EMPLOYEE_PIC FROM SCOTT.EMPLOYEE WHERE EMP_STATUS>0 AND EMPLOYEE_ID='$employee_id'";
    $sql_query = "SELECT * FROM SCOTT.admin WHERE ADM_STATUS>0 AND admin_id='$admin_id'";
    $statement = oci_parse($conn, $sql_query);
    oci_execute($statement);
    while ($row = oci_fetch_array($statement, OCI_RETURN_NULLS)) { //查询结果集
        $admin_id = $row[0];
        $name = $row[1];
        $gender = $row[2];
        if ($gender == 1) {
            $gender = "男";
        } else {
            $gender = "女";
        }
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
}
function updateEmployee($conn)
{
    $admin_id = $_POST['admin_id'];
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    if ($gender != 1 && $gender != 0) {
        return;
    }
    $age = $_POST['age'];
    $salary = $_POST['salary'];
    $phone_num = $_POST['phone_num'];
    $admin_type = $_POST['employee_type'];
    //$sql_insert = "UPDATE SCOTT.EMPLOYEE SET name='$name',gender=$gender,age=$age,salary=$salary,phone_num='$phone_num',employee_type=$employee_type WHERE EMPLOYEE_ID='$employee_id'";
    $sql_insert = "BEGIN scott.updateAdmin('$admin_id','$name',$gender,$age,$salary,'$phone_num',$employee_type); END;";
    //echo $sql_insert;
    $statement = oci_parse($conn, $sql_insert);

    if (oci_execute($statement)) {
        echo json_encode(array("message" => "success"));
    } else {
        echo json_encode(array("message" => "error", "reason" => oci_error()));
    }
}
function updateEmployeePic($conn, $upid)
{
    $sd = new uploadPic();
    $sd->upload("upload_admin_pic", $upid);
}
