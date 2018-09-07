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
            if ($request == "getInventory") //请求
                echo getInventory($conn);
            else if ($request == "purchase")
                echo purchase($conn);
        }
    }
    
    function getInventory($conn)
    {
        $inv_data_array = array();//存放库存信息列表
        $sql_query = "SELECT INVENTORY_ID,GOODS_ID,QUANTITY FROM INVENTORY WHERE INV_STATUS>0";
        $statement = oci_parse($conn, $sql_query);
        oci_execute($statement);
        while ($row = oci_fetch_array($statement, OCI_RETURN_NULLS)) {//查询结果集
            $sql_query = "SELECT GOODS_NAME, GOODS_PRICE, GOODS_TYPE FROM GOODS WHERE GOO_STATUS>0 AND GOODS_ID = $row[1]"
            $statement2 = oci_parse($conn, $sql_query);
            oci_execute($statement2);
            $row2 = oci_fetch_array($statement2, OCI_RETURN_NULLS)
            $inventory_id = $row[0];
            $goods_id = $row[1];
            $quantity = $row[2];
            $goods_name = $row2[1];
            $goods_price = $row2[2];
            $goods_type = $row2[3];
            //使用一个数组放入一个员工的信息
            $data_single = array("inventory_id" => $inventory_id, "goods_id" => $goods_id, "quantity" => $quantity, "goods_name" => $goods_name, "goods_price" => $goods_price, "goods_type" => $goods_type);
            array_push($inv_data_array, $data_single);//将单个员工信息的数组添加到$emp_data_array中
        }
        return json_encode($inv_data_array);//将数组进行json序列化后返回
    }
    function purchase($conn)
    {
        $sql_query = "SELECT COUNT(PURCHASE_ID) FROM PURCHASE";
        $statement = oci_parse($conn, $sql_query);
        $sum = oci_execute($statement);
        $str = strval($sum);
        $PURCHASE_ID = $_POST['purchase_number'];
        for($i = 6 - strlen($str); i>0; i--){
            $PURCHASE_ID += "0";
        }
        $PURCHASE_ID += $str;
        
        $sql_query = "INSERT INTO PURCHASE (PURCHASE_ID, PURCHASE_NUMBER, GOODS_ID, PURCHASE_QUANTITY, PURCHASE_DATE, PUR_STATUS) VALUES ('$PURCHASE_ID', '$_POST['purchase_number']', '$_POST['goods_id']', $_POST['purchase_quantity'], '$_POST['purchase_date']', 1)";
        
        $statement = oci_parse($conn, $sql_query);
        if(oci_execute($statement) == false)
            echo "失败";
        $sql_query = "UPDATE INVENTORY SET QUANTITY = $_POST['purchase_quantity'] WHERE GOODS_ID = '$_POST['purchase_quantity']'";
        $statement = oci_parse($conn, $sql_query);
        if(oci_execute($statement) == true)
            echo "成功";
        else
            echo "失败";
        
    }
