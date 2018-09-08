<?php
set_time_limit(0);
$conn = oci_connect('scott', '123456', 'localhost:1521/ORCL', 'AL32UTF8');
if (!$conn) {
    $e = oci_error();
    print htmlentities($e['message']);
    exit;
} else {
    echo "连接oracle成功！";

    createAdminData($conn);
    echo "<br>写入管理员表数据成功";

    createEmployeeData($conn, 20);
    echo "<br>写入员工表数据成功";

    createPresenceData($conn);
    echo "<br>写入出勤表数据成功";

    createGoodsData($conn);
    echo "<br>写入原料表数据成功";

    createTableData($conn, 20);
    echo "<br>写入餐桌表数据成功";

    createFinanceData($conn, 100);
    echo "<br>写入财务表数据成功";
    oci_close($conn);

}
// echo date("y_m_d", time());
// date("y_m_d", strtotime("-1 day"));
// echo date("Y-m-d", (strtotime("2009-01-01") - 3600 * 24));

function createAdminData($conn)
{
    $admin_list = file_get_contents("admin.json");
    $admin_data = json_decode($admin_list, true);
    for ($i = 0; $i < sizeof($admin_data, 0); $i++) {
        $admin_id = $admin_data[$i]['admin_id'];
        $admin_name = $admin_data[$i]['admin_name'];
        $admin_passwd = $admin_data[$i]['admin_passwd'];
        $admin_passwd = "restaurant$admin_name" . "$admin_passwd";
        $admin_passwd = md5($admin_passwd);
        $admin_type = $admin_data[$i]['admin_type'];
        $create_time = $admin_data[$i]['create_time'];
        $sql_insert = "INSERT INTO admin" .
            "(admin_id,admin_name,admin_passwd,admin_type,create_time,admin_pic)" .
            "VALUES" .
            "('$admin_id','$admin_name','$admin_passwd',$admin_type,'$create_time','../../src/user.png')";
        $statement = oci_parse($conn, $sql_insert);
        oci_execute($statement);
    }
    oci_free_statement($statement);
}

function createEmployeeData($conn, $quantity)
{
    require 'rndChinaName.class.php';
    $name_obj = new rndChinaName();
    require 'uniquePhoneNumber.class.php';
    $phone_obj = new Util();
    $_date = date("Y-m-d", time());

    for ($i = 0; $i < $quantity; $i++) {

        $working_year = mt_rand(0, 50) / 10;
        $employ_time = date("Y-m-d", (strtotime($_date) - $working_year * 365 * 24 * 3600));
        $gender = mt_rand(0, 1);
        $age = mt_rand(25, 55);
        $type_param = mt_rand(0, 99);
        $employee_type = ($type_param < 30 ? 2 : ($type_param < 50 ? 5 : ($type_param < 65 ? 4 : ($type_param < 75 ? 6 : ($type_param < 80 ? 3 : ($type_param < 85 ? 7 : ($type_param < 90 ? 8 : 1)))))));
        $salary = 3000 + $working_year * 100;
        $phone_num = $phone_obj->nextMobile();
        $name = $name_obj->getName(2);
        $employee_id = date("ymd", strtotime($employ_time));
        $employee_id = "emp_" . $employee_id . "_$gender" . "_";

        //
        $query = "SELECT * from employee where employ_time='$employ_time'";
        $statement = oci_parse($conn, $query);
        oci_execute($statement);
        $count = 1;
        while ($row = oci_fetch_array($statement, OCI_RETURN_NULLS)) {
            if ($row[0] != null) {
                $count++;
            }
        }
        $param = $count < 10 ? "000$count" : ($count < 100 ? "00$count" : "0$count");
        $employee_id = "$employee_id" . "$param";

        $sql_insert = "INSERT INTO employee" .
            "(employee_id,name,gender,working_year,age,salary,phone_num,employee_type,employ_time,employee_pic)" .
            "VALUES" .
            "('$employee_id','$name',$gender,$working_year,$age,$salary,'$phone_num',$employee_type,'$employ_time','../../src/user.png')";
        $statement = oci_parse($conn, $sql_insert);
        if (!oci_execute($statement)) {
            die($statement);
        }
    }
    oci_free_statement($statement);
}

function createPresenceData($conn)
{
    $_date = date("Y-m-d", time());
    //查询employee表
    $query = "SELECT * from employee";
    $statement1 = oci_parse($conn, $query);
    oci_execute($statement1);
    $count = 1;
    while ($row = oci_fetch_array($statement1, OCI_RETURN_NULLS)) {
        $employee_id = $row[0];
        $employ_time = $row[8];

        $date = $_date;
        while ($employ_time != $date) {
            $hasPresented_rand = mt_rand(1, 20);
            if ($hasPresented_rand == 1) {
                $sign_time = date("Y-m-d H:i:s", strtotime("$date 10:00:00"));
            } else {
                $begin_time = strtotime("$date 07:00:00");
                $end_time = strtotime("$date 09:30:00");
                $sign_time = mt_rand($begin_time, $end_time);
                $sign_time = date("Y-m-d H:i:s", $sign_time);
            }
            $hasPresented = ($hasPresented_rand == 1) ? 0 : 1;
            $presence_id = mt_rand(10000, 99999);
            $_time = date("ymd_His", strtotime($sign_time));
            $presence_id = "pre_$_time" . "_$presence_id";
            $p_month = date("m", strtotime($sign_time));
            //写入签到信息
            $sql_insert = "INSERT INTO presence" .
                "(presence_id,sign_time,p_month,employee_id,hasPresented)" .
                "VALUES" .
                "('$presence_id','$sign_time','$p_month','$employee_id',$hasPresented)";
            $statement2 = oci_parse($conn, $sql_insert);
            if (!oci_execute($statement2)) {
                die($statement2);
            }
            $date = date("Y-m-d", (strtotime($date) - 3600 * 24));
        }
    }
    oci_free_statement($statement1);
    oci_free_statement($statement2);
}

function createGoodsData($conn)
{
    $goods_list = file_get_contents("goods.json");
    $goods_data = json_decode($goods_list, true);
    for ($i = 0; $i < sizeof($goods_data, 0); $i++) {
        $goods_name = $goods_data[$i]['goods_name'];
        $goods_price = $goods_data[$i]['goods_price'];
        $goods_type = $goods_data[$i]['goods_type'];
        //
        $query = "SELECT * FROM goods WHERE goods_type='$goods_type'";
        $statement = oci_parse($conn, $query);
        oci_execute($statement);
        $count = 1;
        while ($row = oci_fetch_array($statement, OCI_RETURN_NULLS)) {
            $count++;
        }
        $goods_id = $count < 10 ? "00000$count" : ($count < 100 ? "0000$count" : ($count < 1000 ? "000$count" : "00$count"));
        $goods_id = "goo_$goods_type" . "_$goods_id";
        //写入
        $sql_insert = "INSERT INTO goods" .
            "(goods_id,goods_name,goods_price,goods_type)" .
            "VALUES" .
            "('$goods_id','$goods_name',$goods_price,$goods_type)";
        $statement = oci_parse($conn, $sql_insert);
        if (!oci_execute($statement)) {
            die($statement);
        }
    }
    oci_free_statement($statement);
}

function createInventoryData($conn)
{
    $query="SELECT * FROM GOODS";
    $statement1=oci_parse($conn,$query);
    oci_execute($statement1);
    $count=1;
    while($row=oci_fetch_array($statement1,OCI_RETURN_NULL)){
        $count++;
        $goods_id = $row[0];
        $_inventory_id=$count < 10 ? "00000$count" : ($count < 100 ? "0000$count" : ($count < 1000 ? "000$count" : "00$count"));
        $inventory_id="inv_$_inventory_id";
        $quantity=mt_rand(50,200);
        //写入库存信息
        $sql_insert="INSERT INto inventory".
            "(inventory_id,goods_id,quantity)".
            "VALUES".
            "('$inventory_id','$goods_id',$quantity)";
        $statement2=oci_parse($conn,$sql_insert);
        oci_execute($statement2);
    }
    oci_free_statement($statement1);
    oci_free_statement($statement2);

}

function createPurchaseData($conn)
{
    $query="SELECT * FROM GOODS";

}

function createLossData($conn)
{

}

function createDishData($conn)
{

}

function createTableData($conn, $quantity)
{
    for ($i = 0; $i < $quantity; $i++) {
        $table_number = $i + 1;
        $table_number = $table_number < 10 ? "00$table_number" : "0$table_number";
        if ($i < $quantity * 0.6) {
            //非单间
            $table_id = "tab_comm_$table_number";
            if ($i < $quantity * 0.2) {
                $default_number = 4;
            } else if ($i < $quantity * 0.4) {
                $default_number = 6;
            } else {
                $default_number = 8;
            }
        } else {
            //单间
            $table_id = "tab_cell_$table_number";
            if ($i < $quantity * 0.8) {
                $default_number = 10;
            } else {
                $default_number = 15;
            }
        }
        $sql_insert = "INSERT INTO res_table" .
            "(table_id,table_number,default_number)" .
            "VALUES" .
            "('$table_id','$table_number',$default_number)";
        $statement = oci_parse($conn, $sql_insert);
        if (!oci_execute($statement)) {
            die($statement);
        }
    }
    oci_free_statement($statement);
}

function createOrderData($conn)
{

}

function createPreOrderData($conn)
{

}

function createSalesData($conn)
{

}

function createEvaluateData($conn)
{

}

function createFinanceData($conn, $quantity)
{
    $_date = date("y-m-d", time());
    for ($i = 0; $i < $quantity; $i++) {
        $date = DATE("ymd", strtotime($_date));
        $finance_id = "fin_$date";
        $fin_date = date("Y-m-d", strtotime($_date));
        $month = date("m", strtotime($_date));
        $turnover = mt_rand(6000, 10000);
        $cost = mt_rand(4000, 6000);
        $profit = $turnover - $cost;

        $sql_insert = "INSERT INTO finance" .
            "(finance_id,fin_date,month,turnover,cost,profit)" .
            "VALUES" .
            "('$finance_id','$fin_date',$month,$turnover,$cost,$profit)";

        $statement = oci_parse($conn, $sql_insert);
        if (!oci_execute($statement)) {
            die($statement);
        }

        $_date = date("Y-m-d", (strtotime($_date) - 3600 * 24));
    }
    oci_free_statement($statement);
}
