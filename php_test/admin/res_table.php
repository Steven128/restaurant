<?php
    session_start(); //开启php_session
    $admin_id = $_GET['admin_id']; //获取admin_id
    if (isset($_SESSION['admin_id']) && $_SESSION['admin_id'] == $admin_id) { //如果已设置session且session对应用户为当前访问用户
        $request = $_GET['request']; //获取请求内容
        
        $conn = oci_connect('scott', '123456', 'localhost:1521/ORCL', "AL32UTF8"); //连接oracle数据库
        if (!$conn) { //未连接成功，终止脚本并返回错误信息
            $e = oci_error();
            die(json_encode($e));
        } else { //连接成功
            if ($request == "getTable") { //请求
                echo getDish($conn);
            else if ($request == "addTable")
                echo addTable($conn);
            else if ($request == "deleteTable")
                echo deleteTable($conn);
        }
    }
    
    function getTable($conn)
    {
        $table_data_array = array();//存放库存信息列表
        $sql_query = "SELECT TABLE_ID,TABLE_NUMBER,DEFAULT_NUMBER,TABLE_ORDER_STATUS WHERE TAB_STATUS>0 ORDER BY TABLE_ID DESC;
        $statement = oci_parse($conn, $sql_query);
        oci_execute($statement);
        while ($row = oci_fetch_array($statement, OCI_RETURN_NULLS)) {//查询结果集
            $table_id = $row[0];
            $table_number = $row[1];
            $default_number = $row[2];
            $table_order_status = $row[3];
            //使用一个数组放入一个员工的信息
            $data_single = array("table_id" => $table_id, "table_number" => $table_number, "default_number" => $default_number, "table_order_status" => $table_order_status);
            array_push($table_data_array, $data_single);//将单个员工信息的数组添加到$emp_data_array中
        }
        return json_encode($table_data_array);//将数组进行json序列化后返回
    }
    function addTable($conn)
    {
        $sql_query = "SELECT COUNT(TABLE_ID) FROM RES_TABLE";
        $statement = oci_parse($conn, $sql_query);
        $sum = oci_execute($statement);
        $str = strval($sum);
        $TABLE_ID = $_POST['month'];
        for($i = 6 - strlen($str); i>0; i--){
            $TABLE_ID += "0";
        }
        $TABLE_ID += $str;
        $sql_query = "INSERT INTO RES_TABLE (TABLE_ID, TABLE_NUMBER, DEFAULT_NUMBER, TABLE_ORDER_STATUS, TAB_STATUS) VALUES ('$TABLE_ID', '$_POST['table_number']', $_POST['default_number'], 0, 1)";
        $statement = oci_parse($conn, $sql_query);
        if(oci_execute($statement) == true)
            echo "success";
        else
            echo "fail";
    }
    function deleteTable($conn)
    {
        $sql_query = "UPDATE RES_TABLE SET TAB_STATUS = 0 WHERE TABLE_ID = '$_POST['table_id']'";
        $statement = oci_parse($conn, $sql_query);
        if(oci_execute($statement) == true)
            echo "success";
        else
            echo "fail";
    }
