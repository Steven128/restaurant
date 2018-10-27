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
//     if ($url['host'] != "127.0.0.1" && $url['host'] != "localhost" &&$url['host']!="47.95.212.18") {
//         echo "get out";
//         exit();
//     }
// }
include "../updatePic.php";
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


    $conn = oci_connect('dis_admin', '123456', '47.95.212.18/ORCL', "AL32UTF8"); //连接oracle数据库
    if (!$conn) { //未连接成功，终止脚本并返回错误信息
        $e = oci_error();
        die(json_encode($e));
    } else { //连接成功
        // $sql_query = "SELECT ADMIN_TYPE FROM SCOTT.dish WHERE ADMIN_ID = '$admin_id'";
        // $statement = oci_parse($conn, $sql_query);
        // $admin_type = oci_execute($statement)[0];
        // if ($admin_type != 1 and $admin_type != 2) {
        //     exit();
        // }
        if ($request == "add_dish") {
            addDish($conn);
        } else if ($request == "deleteDish") {
            deleteDish($conn);
        } elseif ($request == "getDishInfo") {
            getDishInfo($conn);
        } elseif ($request == "update_dish") {
            updateDish($conn);
        } elseif ($request == "updateDishPic") {
            updateDishPic($conn,"ds");
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

function addDish($conn)
{
    $sql_query = "SELECT COUNT(DISH_ID) FROM SCOTT.DISH";
    $statement = oci_parse($conn, $sql_query);
    oci_execute($statement);
    $count = oci_fetch_array($statement, OCI_RETURN_NULLS)[0];
    $x = $count + 1;
    $dish_name = $_POST['dish_name'];
    $dish_price = $_POST['dish_price'];
    $dish_type = $_POST['dish_type'];
    $param = $x < 10 ? "000$x" : ($x < 100 ? "00$x" : "0$x");
    $dish_id = "dis_$dish_type" . "_$param";
    $sql_query = "BEGIN scott.addDish('$dish_id','$dish_name',$dish_price,$dish_type); END;";

    $statement = oci_parse($conn, $sql_query);
    if (oci_execute($statement)) {
        if (isset($_POST['dishPicData']) && $_POST['dishPicData'] != "")
            updateDishPic($conn, $dish_id);
        else
            echo json_encode(array("message" => "success"));
    } else {
        echo json_encode(array("message" => "error", "reason" => oci_error()));
    }

}
function deleteDish($conn)
{
    if (islegalid($_POST['dish_id'])) {
        $dish_id = $_POST['dish_id'];
        $sql_query = "BEGIN scott.deleteDish('$dish_id'); END;";
        $statement = oci_parse($conn, $sql_query);
        if (oci_execute($statement)) {
            echo json_encode(array("message" => "success"));
        } else {
            echo json_encode(array("message" => "false"));
        }
    } else {
        echo json_encode(array("message" => "false"));
    }

}
function getDishInfo($conn)
{
    if (islegalid($_GET['dish_id'])) {
        $dish_id = $_GET['dish_id'];
        $sql_query = "SELECT * FROM SCOTT.DISH WHERE DIS_STATUS>0 AND DISH_ID='$dish_id'";
        $statement = oci_parse($conn, $sql_query);
        oci_execute($statement);
        while ($row = oci_fetch_array($statement, OCI_RETURN_NULLS)) { //查询结果集
            $dish_id = $row[0];
            $dish_name = $row[1];
            $dish_pic = $row[2];
            $dish_price = $row[3];
            $dish_type = $row[4];
            $dish_info = array("dish_id" => $dish_id, "dish_name" => $dish_name, "dish_pic" => $dish_pic, "dish_price" => $dish_price, "dish_type" => $dish_type);
        }
        echo json_encode(array("message" => "success", "data" => $dish_info));
    } else {
        echo json_encode(array("message" => "error", "reason" => oci_error()));
    }
}

function updateDish($conn)
{
    // if (islegalid($_POST['dish_id'])) {
    //echo $dish_id;
    $dish_id = $_POST['dish_id'];
    $dish_name = $_POST['dish_name'];
    $dish_price = $_POST['dish_price'];
    $dish_type = $_POST['dish_type'];

    $sql_insert = "BEGIN scott.updateDish('$dish_id','$dish_name',$dish_price,$dish_type); END;";
    $statement = oci_parse($conn, $sql_insert);

    if (oci_execute($statement)) {
        echo json_encode(array("message" => "success"));
    } else {
        echo json_encode(array("message" => "error", "reason" => oci_error()));
    }
    // } else {
    //     echo json_encode(array("message" => "error", "reason" => oci_error()));
    // }
}
function updateDishPic($conn, $upid)
{
    echo $_POST['dish_id'];
    $sd = new uploadPic();
    $sd->upload("upload_dish_pic", $upid);
}
