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
            if ($request == "getDish") { //请求
                echo getDish($conn);
            } else if ($request == "addDish") {
                echo addDish($conn);
            } else if ($request == "deleteDish") {
                echo deleteDish($conn);
            }
        }
    }
    
    function getDish($conn)
    {
        $dish_data_array = array();//存放库存信息列表
        $sql_query = "SELECT DISH_ID,DISH_NAME,DISH_PIC,DISH_PRICE,DISH_TYPE FROM DISH WHERE INV_STATUS>0 ORDER BY DISH_TYPE,DISH_NAME DESC";
        $statement = oci_parse($conn, $sql_query);
        oci_execute($statement);
        while ($row = oci_fetch_array($statement, OCI_RETURN_NULLS)) {//查询结果集
            $dish_id = $row[0];
            $dish_name = $row[1];
            $dish_pic = $row[2];
            $dish_price = $row[3];
            $dish_type = $row[4];
            //使用一个数组放入一个员工的信息
            $data_single = array("dish_id" => $dish_id, "dish_name" => $dish_name, "dish_pic" => $dish_pic, "dish_price" => $dish_price, "dish_type" => $dish_type);
            array_push($dish_data_array, $data_single);//将单个员工信息的数组添加到$emp_data_array中
        }
        return json_encode($dish_data_array);//将数组进行json序列化后返回
    }
    function addDish($conn)
    {
        $sql_query = "SELECT COUNT(DISH_ID) FROM DISH";
        $statement = oci_parse($conn, $sql_query);
        $sum = oci_execute($statement);
        $str = strval($sum);
        $DISH_ID = $_POST['month'];
        for($i = 6 - strlen($str); $i>0; $i--){
            $DISH_ID += "0";
        }
        $DISH_ID += $str;
        
        $sql_query = "INSERT INTO DISH (DISH_ID, DISH_NAME, DISH_PIC, DISH_PRICE, DISH_TYPE, DIS_STATUS) VALUES ('"$DISH_ID"', '$_POST['dish_name']', '$_POST['dish_pic']', $_POST['dish_price'], $_POST['dish_type'], 1)";
        
        $statement = oci_parse($conn, $sql_query);
        $bool = oci_execute($statement);
        if($bool)
            return json_encode("bool" => "true");
        else
            return json_encode("bool" => "false");
        
    }
    function deleteDish($conn)
    {
        $sql_query = "UPDATE DISH SET DIS_STATUS = 0 WHERE DISH_ID = '"$_POST['dish_id']"'";
        $statement = oci_parse($conn, $sql_query);
        $bool = oci_execute($statement);
        if($bool)
            return json_encode("bool" => "true");
        else
            return json_encode("bool" => "false");
        
    }
