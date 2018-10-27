<?php


$data = json_decode($_POST['param']);
$request = $data->request;


$conn = oci_connect('scott', '123456', '47.95.212.18/ORCL', "AL32UTF8"); //连接oracle数据库
if (!$conn) { //未连接成功，终止脚本并返回错误信息
    $e = oci_error();
    die(json_encode($e));
} else {
    if ($request == "getCookDishs") {
        getCookDishs($conn,$data);
    } else
    if ($request == "changeCookStatus") {
        changeCookStatus($conn,$data);
    }
}

// function object_to_array($obj) {
//     $obj = (array)$obj;
//     foreach ($obj as $k => $v) {
//         if (gettype($v) == 'resource') {
//             return;
//         }
//         if (gettype($v) == 'object' || gettype($v) == 'array') {
//             $obj[$k] = (array)object_to_array($v);
//         }
//     }
 
//     return $obj;
// }

function getCookDishs($conn,$data)
{
    $sql_select = "SELECT SALES.SALES_ID,DISH.DISH_NAME FROM SCOTT.SALES JOIN DISH ON SALES.DISH_ID=DISH.DISH_ID WHERE SAL_STATUS=1 OR SAL_STATUS=2";
    $statement = oci_parse($conn,$data, $sql_select);
    oci_execute($statement);
    $cook_info = null;
    while ($row = oci_fetch_array($statement, OCI_RETURN_NULLS)) {
        $sales_id = $row[0];
        $dish_name = $row[3];
        $cook_info1 = array('id' => $sales_id, 'name' => $dish_name);
        array_push($cook_info, $cook_info1);
    }
    echo json_encode(array("message" => "success", "data" => $cook_info));
}

function changeCookStatus($conn,$data)
{
    $dish_name = $data->dish->name;
    $sales_id = $data->dish->id;
    $sql_select = "SELECT SAL_STATUS FROM SCOTT.SALES WHERE SALES_ID='" . $sales_id . "'";
    $statement1 = oci_parse($conn, $sql_select);
    oci_execute($statement1);
    $row = oci_fetch_array($statement1, sOCI_RETURN_NULLS);
    $statu = $row[0];
    $statu++;
    $sql_update = "UPDATE SCOTT.SALES SET SAL_STATUS =" . $statu . " WHERE SALES_ID='" . $sales_id . "'";
    $statement2 = oci_parse($conn, $sql_update);
    oci_execute($statement2);
    echo json_encode(array("message" => "success"));
}
