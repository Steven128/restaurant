<?php
session_start();

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

    $conn = oci_connect('emp_admin', '123456', 'localhost:1521/ORCL', "AL32UTF8"); //连接oracle数据库
    if (!$conn) { //未连接成功，终止脚本并返回错误信息
        $e = oci_error();
        die(json_encode($e));
    } else { //连接成功
        
        if ($request == "date") {
            searchByDate($conn);
        } elseif ($request == "week") {
            searchByWeek($conn);
        } elseif ($request == "month") {
            searchByMonth($conn);
        } elseif ($request == "season") {
            searchBySeason($conn);
        } elseif ($request == "employee") {
            searchByEmployee($conn);
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

function searchByDate($conn)
{
    $param = $_GET['param'];
    if ($param == "today") {
        $date = date("Y-m-d", time());
    } elseif ($param == "yesterday") {
        $date = date("Y-m-d", time() - 24 * 3600);
    } elseif ($param == "select") {
        $time = strtotime($_GET['date']);
        $date = date("Y-m-d", $time);
    }
    $sql_query = "SELECT EMPLOYEE.EMPLOYEE_ID,NAME,GENDER,PHONE_NUM,EMPLOYEE_TYPE,PRESENCE_ID,SIGN_TIME,HASPRESENTED FROM SCOTT.PRESENCE,SCOTT.EMPLOYEE WHERE EMPLOYEE.EMPLOYEE_ID=PRESENCE.EMPLOYEE_ID AND EMP_STATUS>0 AND PRE_STATUS>0 AND SUBSTR(SIGN_TIME,0,10)='$date' ORDER BY HASPRESENTED,SIGN_TIME,EMPLOYEE_TYPE";
    $data = array();
    $statement = oci_parse($conn, $sql_query);
    oci_execute($statement);
    while ($row = oci_fetch_array($statement, OCI_RETURN_NULLS)) {
        $employee_id = $row[0];
        $name=$row[1];
        $gender=$row[2];
        $phone_num = $row[3];
        $employee_type=$row[4];
        $presence_id=$row[5];
        $sign_time=$row[6];
        $hasPresented=$row[7];
        $single = array("employee_id"=> $employee_id, "name"=> $name, "gender"=> $gender, "phone_num"=> $phone_num, "employee_type"=> $employee_type, "presence_id"=> $presence_id, "sign_time"=> $sign_time, "hasPresented"=> $hasPresented);
        array_push($data, $single);
    }
    echo json_encode(array("message"=>"success","data"=>$data));
}

function searchByWeek($conn)
{
    $param = $_GET['param'];
    if ($param == "this") {
        $end_date = date("Y-m-d", time());
        $begin_date = date("Y-m-d", time() - 7 * 24 * 3600);
    } elseif ($param == "last") {
        $end_date = date("Y-m-d", time() - 7 * 24 * 3600);
        $begin_date = date("Y-m-d", time() - 14 * 24 * 3600);
    }
    $sql_query = "SELECT EMPLOYEE.EMPLOYEE_ID,NAME,GENDER,PHONE_NUM,EMPLOYEE_TYPE,PRESENCE_ID,SIGN_TIME,HASPRESENTED FROM SCOTT.PRESENCE,SCOTT.EMPLOYEE WHERE EMPLOYEE.EMPLOYEE_ID=PRESENCE.EMPLOYEE_ID AND EMP_STATUS>0 AND PRE_STATUS>0 AND SUBSTR(SIGN_TIME,0,10)<='$end_date' AND SUBSTR(SIGN_TIME,0,10)>'$begin_date' AND HASPRESENTED=0 ORDER BY SIGN_TIME,EMPLOYEE_TYPE";
    $data = array();
    $statement = oci_parse($conn, $sql_query);
    oci_execute($statement);
    while ($row = oci_fetch_array($statement, OCI_RETURN_NULLS)) {
        $employee_id = $row[0];
        $name = $row[1];
        $gender = $row[2];
        $phone_num = $row[3];
        $employee_type = $row[4];
        $presence_id = $row[5];
        $sign_time = $row[6];
        $hasPresented = $row[7];
        $single = array("employee_id" => $employee_id, "name" => $name, "gender" => $gender, "phone_num" => $phone_num, "employee_type" => $employee_type, "presence_id" => $presence_id, "sign_time" => $sign_time, "hasPresented" => $hasPresented);
        array_push($data, $single);
    }
    $sql_query = "SELECT COUNT(*) FROM SCOTT.PRESENCE WHERE PRE_STATUS>0 AND SUBSTR(SIGN_TIME,0,10)<='$end_date' AND SUBSTR(SIGN_TIME,0,10)>'$begin_date' AND HASPRESENTED=1";
    $statement = oci_parse($conn, $sql_query);
    oci_execute($statement);
    $row = oci_fetch_array($statement, OCI_RETURN_NULLS);
    $hasPresented = $row[0];
    $sql_query = "SELECT COUNT(*) FROM SCOTT.PRESENCE WHERE PRE_STATUS>0 AND SUBSTR(SIGN_TIME,0,10)<='$end_date' AND SUBSTR(SIGN_TIME,0,10)>'$begin_date'";
    $statement = oci_parse($conn, $sql_query);
    oci_execute($statement);
    $row = oci_fetch_array($statement, OCI_RETURN_NULLS);
    $all = $row[0] == 0 ? 1 : $row[0];
    $ratio = ($hasPresented/$all) * 100;
    $ratio = substr($ratio, 0, 5)."%";
    echo json_encode(array("message" => "success", "data" => $data, "ratio"=>$ratio));
}

function searchByMonth($conn)
{
    $param = $_GET['param'];
    if ($param == "this") {
        $month = date("Y-m", time());
    } elseif ($param == "last") {
        $month = date("Y-m", time() - 30 * 24 * 3600);
    } elseif ($param == "select") {
        $month = $_GET['month'];
    }
    $sql_query = "SELECT EMPLOYEE.EMPLOYEE_ID,NAME,GENDER,PHONE_NUM,EMPLOYEE_TYPE,PRESENCE_ID,SIGN_TIME,HASPRESENTED FROM SCOTT.PRESENCE,SCOTT.EMPLOYEE WHERE EMPLOYEE.EMPLOYEE_ID=PRESENCE.EMPLOYEE_ID AND EMP_STATUS>0 AND PRE_STATUS>0 AND SUBSTR(SIGN_TIME,0,7)='$month' AND HASPRESENTED=0 ORDER BY SIGN_TIME,EMPLOYEE_TYPE";
    $data = array();
    $statement = oci_parse($conn, $sql_query);
    oci_execute($statement);
    while ($row = oci_fetch_array($statement, OCI_RETURN_NULLS)) {
        $employee_id = $row[0];
        $name = $row[1];
        $gender = $row[2];
        $phone_num = $row[3];
        $employee_type = $row[4];
        $presence_id = $row[5];
        $sign_time = $row[6];
        $hasPresented = $row[7];
        $single = array("employee_id" => $employee_id, "name" => $name, "gender" => $gender, "phone_num" => $phone_num, "employee_type" => $employee_type, "presence_id" => $presence_id, "sign_time" => $sign_time, "hasPresented" => $hasPresented);
        array_push($data, $single);
    }
    $sql_query = "SELECT COUNT(*) FROM SCOTT.PRESENCE WHERE PRE_STATUS>0 AND SUBSTR(SIGN_TIME,0,7)='$month' AND HASPRESENTED=1";
    $statement = oci_parse($conn, $sql_query);
    oci_execute($statement);
    $row = oci_fetch_array($statement, OCI_RETURN_NULLS);
    $hasPresented = $row[0];
    $sql_query = "SELECT COUNT(*) FROM SCOTT.PRESENCE WHERE PRE_STATUS>0 AND SUBSTR(SIGN_TIME,0,7)='$month'";
    $statement = oci_parse($conn, $sql_query);
    oci_execute($statement);
    $row = oci_fetch_array($statement, OCI_RETURN_NULLS);
    $all = $row[0] == 0 ? 1 : $row[0];
    $ratio = ($hasPresented / $all) * 100;
    $ratio = substr($ratio, 0, 5) . "%";
    echo json_encode(array("message" => "success", "data" => $data, "ratio" => $ratio));
}

function searchBySeason($conn)
{
    $param = $_GET['param'];
    if ($param == "this") {
        $end_month = date("Y-m", time());
        $begin_month = date("Y-m", time() - 30 * 3 * 24 * 3600);
    } elseif ($param == "last") {
        $end_month = date("Y-m", time() - 30 * 3 * 24 * 3600);
        $begin_month = date("Y-m", time() - 30 * 6 * 24 * 3600);
    }
    $sql_query = "SELECT EMPLOYEE.EMPLOYEE_ID,NAME,GENDER,PHONE_NUM,EMPLOYEE_TYPE,PRESENCE_ID,SIGN_TIME,HASPRESENTED FROM SCOTT.PRESENCE,SCOTT.EMPLOYEE WHERE EMPLOYEE.EMPLOYEE_ID=PRESENCE.EMPLOYEE_ID AND EMP_STATUS>0 AND PRE_STATUS>0 AND SUBSTR(SIGN_TIME,0,7)<='$end_month' AND SUBSTR(SIGN_TIME,0,7)>'$begin_month' AND HASPRESENTED=0 ORDER BY SIGN_TIME,EMPLOYEE_TYPE";
    $data = array();
    $statement = oci_parse($conn, $sql_query);
    oci_execute($statement);
    while ($row = oci_fetch_array($statement, OCI_RETURN_NULLS)) {
        $employee_id = $row[0];
        $name = $row[1];
        $gender = $row[2];
        $phone_num = $row[3];
        $employee_type = $row[4];
        $presence_id = $row[5];
        $sign_time = $row[6];
        $hasPresented = $row[7];
        $single = array("employee_id" => $employee_id, "name" => $name, "gender" => $gender, "phone_num" => $phone_num, "employee_type" => $employee_type, "presence_id" => $presence_id, "sign_time" => $sign_time, "hasPresented" => $hasPresented);
        array_push($data, $single);
    }
    $sql_query = "SELECT COUNT(*) FROM SCOTT.PRESENCE WHERE PRE_STATUS>0 AND SUBSTR(SIGN_TIME,0,7)<='$end_month' AND SUBSTR(SIGN_TIME,0,7)>'$begin_month' AND HASPRESENTED=1";
    $statement = oci_parse($conn, $sql_query);
    oci_execute($statement);
    $row = oci_fetch_array($statement, OCI_RETURN_NULLS);
    $hasPresented = $row[0];
    $sql_query = "SELECT COUNT(*) FROM SCOTT.PRESENCE WHERE PRE_STATUS>0 AND SUBSTR(SIGN_TIME,0,7)<='$end_month' AND SUBSTR(SIGN_TIME,0,7)>'$begin_month'";
    $statement = oci_parse($conn, $sql_query);
    oci_execute($statement);
    $row = oci_fetch_array($statement, OCI_RETURN_NULLS);
    $all = $row[0] == 0 ? 1 : $row[0];
    $ratio = ($hasPresented / $all) * 100;
    $ratio = substr($ratio, 0, 5) . "%";
    echo json_encode(array("message" => "success", "data" => $data, "ratio" => $ratio));
}

function searchByEmployee($conn)
{
    $employee_name = $_GET['employee_name'];
    $sql_query = "SELECT COUNT(*) FROM EMPLOYEE WHERE EMP_STATUS>0 AND NAME=$employee_name";
    $statement = oci_parse($conn, $sql_query);
    oci_execute($statement);
    $row = oci_fetch_array($statement, OCI_RETURN_NULLS);
    $emp_count = $row[0];
    if ($emp_count == 1) {
        //没有重名员工

        $sql_query = "SELECT * FROM SCOTT.EMPLOYEE WHERE EMP_STATUS>0 AND NAME=$employee_name";
        $statement = oci_parse($conn, $sql_query);
        oci_execute($statement);
        $row = oci_fetch_array($statement, OCI_RETURN_NULLS);
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
        $employee_info = array("employee_id" => $employee_id, "name" => $name, "gender" => $gender, "working_year" => $working_year, "age" => $age, "salary" => $salary, "phone_num" => $phone_num, "employee_type" => $employee_type, "employ_time" => $employ_time, "employee_pic" => $employee_pic);

        $sql_query = "SELECT PRESENCE_ID,SIGN_TIME,HASPRESENTED FROM SCOTT.PRESENCE WHERE PRE_STATUS>0 AND EMPLOYEE_ID='$employee_id' AND HASPRESENTED=0 ORDER BY SIGN_TIME DESC";
        $statement = oci_parse($conn, $sql_query);
        oci_execute($statement);
        $presence_data = array();
        while ($row = oci_fetch_array($statement, OCI_RETURN_NULLS)) {
            $sign_time = $row[1];
            $hasPresented = $row[2];
            $single = array("sign_time" => $sign_time, "hasPresented" => $hasPresented);
            array_push($presence_data, $single);
        }
        echo json_encode(array("employee_info"=> $employee_info, "presence_data"=> $presence_data));
    }
}
