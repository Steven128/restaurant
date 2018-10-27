<?php
header("content-type:text/html;charset=utf8");
$lifeTime = 7 * 24 * 3600;
// session_set_cookie_params($lifeTime);
session_start();
$request = $_POST['request'];
if ($request == "check") {
    $name = $_POST['name'];
    $conn = oci_connect('scott', '123456', '47.95.212.18/ORCL', "AL32UTF8");
    if (!$conn) {
        $e = oci_error();
        die(json_encode($e));
    } else {
        $exist = 0;
        $sql_query = "SELECT admin_name FROM SCOTT.admin WHERE adm_status>0";
        $statement = oci_parse($conn, $sql_query);
        oci_execute($statement);
        while ($row = oci_fetch_array($statement, OCI_RETURN_NULLS)) {
            if ($name == $row[0]) {
                $exist = 1;
                break;
            }
        }
        if ($exist == 1) {
            echo json_encode(array("message" => "exists", "session" => session_id()));
        } else {
            echo json_encode(array("message" => "does_not_exist"));
        }
    }
} else if ($request == "validate") {
    $name = $_POST['name'];
    $password = $_POST['password'];
    $phpsessid = $_POST['phpsessid'];
    if ($phpsessid == session_id()) {
        $conn = oci_connect('scott', '123456', '47.95.212.18/ORCL', "AL32UTF8");
        $exist = 0;
        $login = 0;
        $admin_id = "";
        $admin_name = "";
        $admin_passwd = "";
        $admin_type = 0;
        $sql_query = "SELECT admin_id,admin_name,admin_passwd,admin_type,admin_pic FROM SCOTT.admin WHERE adm_status>0";
        $statement = oci_parse($conn, $sql_query);
        oci_execute($statement);
        while ($row = oci_fetch_array($statement, OCI_RETURN_NULLS)) {
            $admin_id = $row[0];
            $admin_name = $row[1];
            $admin_passwd = $row[2];
            $admin_type = $row[3];
            $admin_pic = $row[4];
            if ($admin_name == $name) {
                //找到用户
                $exist = 1;
                if ($admin_passwd == $password) {
                    //密码正确
                    $login = 1;
                }
                break;
            }
        }
        if ($exist == 1 && $login == 1) {
            //查找成功
            echo json_encode(array("message" => "success"));
        } else {
            echo json_encode(array("message" => "permission_denied"));
        }
    }
} else if ($request == "reset") {
    $name = $_POST['name'];
    $password = $_POST['password'];
    $phpsessid = $_POST['phpsessid'];
    if ($phpsessid == session_id()) {
        $conn = oci_connect('scott', '123456', '47.95.212.18/ORCL', "AL32UTF8");
        $sql_update = "UPDATE admin SET admin_passwd='$password' WHERE adm_status>0 AND admin_name='$name'";
        $statement = oci_parse($conn, $sql_update);
        oci_execute($statement);
        echo json_encode(array("message" => "success"));
    }
}
