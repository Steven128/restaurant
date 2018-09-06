<?php
header("content-type:text/html;charset=utf8");
$lifeTime = 7 * 24 * 3600;
session_set_cookie_params($lifeTime);
session_start();

if (isset($_SESSION['admin_id'])) {
    echo json_encode(array("message" => "already_login", "admin_id" => $_SESSION['admin_id'], "admin_name" => $_SESSION['admin_name'], "admin_type" => $_SESSION['admin_type'], "admin_pic" => $_SESSION['admin_pic']));
} else {
    $name = $_POST['name'];
    $password = $_POST['password'];

    $conn = oci_connect('scott', '123456', 'localhost:1521/ORCL', "AL32UTF8");
    if (!$conn) {
        $e = oci_error();
        die(json_encode($e));
    } else {
        $exist = 0;
        $login = 0;
        $admin_id = "";
        $admin_name = "";
        $admin_passwd = "";
        $admin_type = 0;
        $sql_query = "SELECT admin_id,admin_name,admin_passwd,admin_type,admin_pic FROM admin WHERE adm_status>0";
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
        if ($exist == 0) {
            //未找到用户
            unset($_SESSION);
            session_destroy();
            echo json_encode(array("message" => "admin_not_found"));
        } else if ($login == 0) {
            //密码错误
            unset($_SESSION);
            session_destroy();
            echo json_encode(array("message" => "wrong_password"));
        } else if ($exist == 1 && $login == 1) {
            //登陆成功
            $_SESSION['admin_id'] = $admin_id;
            $_SESSION['admin_name'] = $admin_name;
            $_SESSION['admin_type'] = $admin_type;
            $_SESSION['admin_pic'] = $admin_pic;
            echo json_encode(array("message" => "success_login", "admin_id" => $_SESSION['admin_id'], "admin_name" => $_SESSION['admin_name'], "admin_type" => $_SESSION['admin_type'], "admin_pic" => $_SESSION['admin_pic']));
        } else {
            //错误
            unset($_SESSION);
            session_destroy();
            echo json_encode(array("message" => "wrong_request"));
        }
    }
}
