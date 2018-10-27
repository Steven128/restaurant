<?php


$data = json_decode($_POST['param']);
$request = $data->request;


$conn = oci_connect('scott', '123456', '47.95.212.18/ORCL', "AL32UTF8"); //连接oracle数据库
if (!$conn) { //未连接成功，终止脚本并返回错误信息
    $e = oci_error();
    die(json_encode($e));
} else {
    if ($request == "getTables") {
        getTables($conn, $data);
    } elseif ($request == "useTable") {
        useTable($conn, $data);
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

function getTables($conn, $data)
{
    $sql_select1 = "SELECT TABLE_ID,TABLE_NUMBER,DEFAULT_NUMBER,TABLE_ORDER_STATUS FROM SCOTT.RES_TABLE WHERE TAB_STATUS>0";
    $statement1 = oci_parse($conn, $sql_select1);
    oci_execute($statement1);
    $table_info = array();
    while ($row = oci_fetch_array($statement1, OCI_RETURN_NULLS)) {
        $table_id=$row[0];
        $table_number = $row[1];
        $default_number = $row[2];
        $table_order_status = $row[3];
        $table_info1 = array('id'=>$table_id, 'tableName' => $table_number, 'seatsCount' => $default_number, 'status' => $table_order_status);
        array_push($table_info, $table_info1);
    }
    echo json_encode(array("message" => "success", "data" => $table_info));
}

function useTable($conn, $data)//餐桌使用，预定餐桌写在preorder里
{
    $table_number=$data->id;
    $sql_update1="UPDATE SCOTT.RES_TABLE SET TABLE_ORDER_STATUS=2 WHERE TABLE_ID='".$table_number."'";
    $statement1=oci_parse($conn, $sql_update1);
    oci_execute($statement1);
    echo json_encode(array("message" => "success"));
}
