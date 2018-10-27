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
if (!isset($_SESSION['admin_id']) || $_SESSION['admin_id'] != $admin_id) {
    die();
}
$conn = oci_connect('scott', '123456', '47.95.212.18/ORCL', "AL32UTF8");
if (!$conn) { //未连接成功，终止脚本并返回错误信息
    $e = oci_error();
    die(json_encode($e));
} else {
    if ($request == "update_password") {
        $oldPasswd = $_POST['old_password'];
        $newPasswd = $_POST['new_password'];
        $exist = 0;
        $true = 0;
        $sql_query = "SELECT admin_passwd FROM SCOTT.admin WHERE admin_id='$admin_id'";
        $statement = oci_parse($conn, $sql_query);
        oci_execute($statement);
        $row = oci_fetch_array($statement, OCI_RETURN_NULLS);
        $admin_passwd = $row[0];
        if($admin_passwd == $oldPasswd) {
            //旧密码正确
            updatePasswd($conn,$admin_id,$newPasswd);
        }else {
            echo json_encode(array("message"=>"success","result"=>"wrong_oldPasswd"));
        }
    }
}

function updatePasswd($conn,$admin_id,$newPasswd) {
    $sql_update = "UPDATE admin SET admin_passwd='$newPasswd' WHERE adm_status>0 AND admin_id='$admin_id'";
        $statement = oci_parse($conn, $sql_update);
        if(oci_execute($statement)){
            echo json_encode(array("message" => "success","result"=>"update_passwd_success"));
        }else {
            echo json_encode(array("message" => "failed","result"=>oci_error()));
        }
}
