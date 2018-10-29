<?php
set_time_limit(0);
$conn = oci_connect('scott', '123456', '47.95.212.18/ORCL', 'AL32UTF8');
if (!$conn) {
    $e = oci_error();
    print htmlentities($e['message']);
    exit;
} else {
    echo "连接oracle成功！\n";

    createAdminData($conn);
    echo "写入管理员表数据成功\n";

    createEmployeeData($conn, 30);
    echo "写入员工表数据成功\n";

    createPresenceData($conn);
    echo "写入出勤表数据成功\n";

    createGoodsData($conn);
    echo "写入原料表数据成功\n";

    createTableData($conn, 20);
    echo "写入餐桌表数据成功\n";

    createFinanceData($conn, 1095);
    echo "写入财务表数据成功\n";

    createOverheadData($conn, 3585);
    echo "写入开销表数据成功\n";

    createInventoryData($conn);
    echo "写入库存表数据成功\n";

    createLossData($conn, 80);
    echo"写入损失表数据成功\n";

    createDishData($conn);
    echo"写入菜品表数据成功\n";

    createOrderData($conn, 109500);
    echo"写入订单表数据成功\n";

    createPreOrderData($conn, 27400);
    echo"写入预定表数据成功\n";

    createSalesData($conn);   //有问题
    echo"写入销售表数据成功\n";

    createEvaluateData($conn);
    echo"写入评价表数据成功\n";

    deletedishlist($conn);


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
        $admin_pic = $admin_data[$i]['admin_pic'];
        $sql_insert = "INSERT INTO SCOTT.admin" .
            "(admin_id,admin_name,admin_passwd,admin_type,create_time,admin_pic)" .
            "VALUES" .
            "('$admin_id','$admin_name','$admin_passwd',$admin_type,'$create_time','$admin_pic')";
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
    $_date = date("Y-m-d", time() + 30 * 24 * 3600);

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
        $query = "SELECT * FROM SCOTT.employee where employ_time='$employ_time'";
        $statement = oci_parse($conn, $query);
        oci_execute($statement);
        $count = 0;
        while ($row = oci_fetch_array($statement, OCI_RETURN_NULLS)) {
            if ($row[0] != null) {
                $count++;
            }
        }
        $param = $count < 10 ? "000$count" : ($count < 100 ? "00$count" : "0$count");
        $employee_id = "$employee_id" . "$param";

        $sql_insert = "INSERT INTO SCOTT.employee" .
            "(employee_id,name,gender,working_year,age,salary,phone_num,employee_type,employ_time,employee_pic)" .
            "VALUES" .
            "('$employee_id','$name',$gender,$working_year,$age,$salary,'$phone_num',$employee_type,'$employ_time','../../src/employee_pic/default.png')";
        $statement = oci_parse($conn, $sql_insert);
        if (!oci_execute($statement)) {
            die($statement);
        }
    }
    oci_free_statement($statement);
}

function createPresenceData($conn)
{
    $_date = date("Y-m-d", time() + 30 * 24 * 3600);
    //查询employee表
    $query = "SELECT * FROM SCOTT.employee";
    $statement1 = oci_parse($conn, $query);
    oci_execute($statement1);
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
            $sql_insert = "INSERT INTO SCOTT.presence" .
                "(presence_id,sign_time,pre_month,employee_id,hasPresented)" .
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
        $count = $i+1;
        $goods_id = $count < 10 ? "000$count" : ($count < 100 ? "00$count" : ($count < 1000 ? "0$count" : "$count"));
        $goods_id = "goo_$goods_type" . "_$goods_id";
        //写入
        $sql_insert = "INSERT INTO SCOTT.goods" .
            "(goods_id,goods_name,goods_price,goods_type)" .
            "VALUES" .
            "('$goods_id','$goods_name',$goods_price,$goods_type)";
        $statement = oci_parse($conn, $sql_insert);
        oci_execute($statement);
    }
    oci_free_statement($statement);
}

function createInventoryData($conn)
{
    $query="SELECT * FROM SCOTT.GOODS";
    $statement1=oci_parse($conn, $query);
    oci_execute($statement1);
    $count=0;
    while ($row=oci_fetch_array($statement1, OCI_RETURN_NULLS)) {
        $count++;
        $goods_id = $row[0];
        $_inventory_id=$count < 10 ? "00000$count" : ($count < 100 ? "0000$count" : ($count < 1000 ? "000$count" : "00$count"));
        $inventory_id="inv_$_inventory_id";
        $quantity=mt_rand(50, 300);
        //写入库存信息
        $sql_insert="INSERT INto SCOTT.inventory".
            "(inventory_id,goods_id,quantity)".
            "VALUES".
            "('$inventory_id','$goods_id',$quantity)";
        $statement2=oci_parse($conn, $sql_insert);
        // echo "$inventory_id   $goods_id    $quantity<br>";
        oci_execute($statement2);
    }
    oci_free_statement($statement1);
    oci_free_statement($statement2);
}

function createOverheadData($conn, $quantity)//id根据进货单创建时间,31种,进货单号由发票号码12+8组成
{
    $date=date("Y-m-d", time() + 30 * 24 * 3600);
    $begin_time=strtotime(date("Y-m-d", time()-3*365*24*3600)." 07:00:00");
    $end_time=strtotime("$date 20:00:00");
    for ($i=0;$i<$quantity;$i++) {
        $rand=mt_rand($begin_time, $end_time);
        $ove_id=date("Ymd_His", $rand);
        $ove_date=date("Y-m-d", $rand);
        $ove_id="ove_$ove_id";
        $price = mt_rand(5000, 15000);
        $ove_type = mt_rand(0, 10);
        $ove_type = $ove_type<6?1:($ove_type<8?2:($ove_type<9?3:4));
        $sql_insert="INSERT INTO SCOTT.overhead".
            "(overhead_id,overhead_type,overhead_price,overhead_date)".
            "VALUES".
            "('$ove_id','$ove_type','$price','$ove_date')";
        $statement=oci_parse($conn, $sql_insert);
        // echo "$sql_insert<br>";
        oci_execute($statement);
    }
    oci_free_statement($statement);
}

function createLossData($conn, $quantity)//id根据los_日期
{
    $date=date("Y-m-d", time() + 30 * 24 * 3600);
    $sql1="SELECT goods_id FROM SCOTT.goods";
    $statement=oci_parse($conn, $sql1);
    $res=oci_execute($statement);
    $goods_array=array();
    while ($row = oci_fetch_array($statement, OCI_RETURN_NULLS)) {
        $goods_array[]=$row[0];
    }
    $begin_time=strtotime("2010-01-10 07:00:00");
    $end_time=strtotime("$date 20:00:00");
    for ($i=0;$i<$quantity;$i++) {
        $rand=mt_rand($begin_time, $end_time);
        $rand_time=date("Y-m-d", $rand);
        $id_time=date("Ymd", $rand);
        $rand2=mt_rand(0, 30);
        $loss_id="los_$id_time";
        $loss_quantity=rand(5, 30);
        $goods_id=$goods_array[$rand2];
        $sql_insert="INSERT INTO SCOTT.loss".
            "(loss_id,goods_id,quantity,loss_date)".
            "VALUES".
            "('$loss_id','$goods_id',$loss_quantity,'$rand_time')";
        // echo "$goods_id    $rand_time    $loss_id<br>";
        $statement2=oci_parse($conn, $sql_insert);
        oci_execute($statement2);
    }
    oci_free_statement($statement);
    oci_free_statement($statement2);
}

function createDishData($conn)//type=2为主食，type=1为早餐，type=3为甜品和饮料，type=4为小食
{
    $dish_list=file_get_contents("dish.json");
    $dish_data=json_decode($dish_list, true);
    for ($i=0;$i<sizeof($dish_data, 0);$i++) {
        $dish_pic=$dish_data[$i]['dish_pic'];
        $dish_name=$dish_data[$i]['dish_name'];
        $dish_type=$dish_data[$i]['dish_type'];
        $dish_price=$dish_data[$i]['dish_price'];
        $x=$i+1;
        $param = $x < 10 ? "000$x" : ($x < 100 ? "00$x" : "0$x");
        $dish_id="dis_$dish_type"."_$param";
        $sql_insert = "INSERT INTO SCOTT.dish" .
            "(dish_id,dish_name,dish_pic,dish_price,dish_type)" .
            "VALUES" .
            "('$dish_id','$dish_name','$dish_pic',$dish_price,$dish_type)";
        //echo "$dish_id   $dish_name   $dish_pic   $dish_price   $dish_type <br>";
        $statement=oci_parse($conn, $sql_insert);
        oci_execute($statement);
    }
    oci_free_statement($statement);
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
            } elseif ($i < $quantity * 0.4) {
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
        $sql_insert = "INSERT INTO SCOTT.res_table" .
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

function createOrderData($conn, $quantity)//订单编号用ord_餐桌号_下单时间
{
    $date=date("Y-m-d", time() + 30 * 24 * 3600);
    $dish_list=file_get_contents("dish.json");
    $dish_data=json_decode($dish_list, true);
    $dish_count=sizeof($dish_data, 0);
    $sql_select="SELECT table_id FROM SCOTT.res_table";
    $statement1=oci_parse($conn, $sql_select);
    oci_execute($statement1);
    $tables_array=array();
    $count=-1;
    while ($row = oci_fetch_array($statement1, OCI_RETURN_NULLS)) {
        $tables_array[]=$row[0];
        $count++;
    }
    $begin_time=time()- 3 * 365 * 24 * 3600;
    $end_time=strtotime("$date 23:59:59");
    for ($x=0;$x<$quantity;$x++) {
        $dish_list=null;//点菜列表
        $sum_price=0;//总价
        $rand1=mt_rand(0, $count);
        $table_id=$tables_array[$rand1];//餐桌id
        $table_number=substr($table_id, -3);//餐桌号
        $rand2=mt_rand($begin_time, $end_time);//随机下单时间
        $order_time=date("YmdHis", $rand2);//下单时间
        $rand5=mt_rand(1, 3);//付款方式&用餐时间
        $pay_time=date("Y-m-d H:i:s", strtotime('+'.$rand5.'hour', $rand2));//结账时间
        $order_id="ord_"."$table_number"."_$order_time";//订单id
        $rand3=mt_rand(3, 10);//随机每单点菜数
        for ($i=0;$i<$rand3;$i++) {//随机生成点菜单
            $rand4=mt_rand(0, $dish_count-1);
            $dish_type=$dish_data[$rand4]['dish_type'];
            $param = $rand4 < 10 ? "000$rand4" : ($rand4 < 100 ? "00$rand4" : "0$rand4");
            $dish_id="dis_$dish_type"."_$param";
            $dish_list="$dish_list"."$dish_id";
            $sum_price+=(float)$dish_data[$rand4]['dish_price'];
            if ($i<$rand3-1) {
                $dish_list.=",";
            }
        }
        $order_note="多放香菜";
        //echo" $order_id   $dish_list   $sum_price   $rand5  $table_id   $pay_time  $order_note <br>";
        $sql_insert="INSERT INTO SCOTT.order_list".
            "(order_id,dish_list,total_price,pay_method,pay_status,table_id,pay_time,order_note)".
            "VALUES".
            "('$order_id','$dish_list',$sum_price,$rand5,1,'$table_id','$pay_time','$order_note')";
        $statement2=oci_parse($conn, $sql_insert);
        oci_execute($statement2);
    }
    oci_free_statement($statement1);
    oci_free_statement($statement2);
}

function createPreOrderData($conn, $quantity)
{
    $sql_select="SELECT order_id,dish_list,table_id FROM SCOTT.order_list";
    $statement1=oci_parse($conn, $sql_select);
    oci_execute($statement1);
    $order_id_array[]=array();
    $dish_list_array[]=array();
    $table_id_array[]=array();
    while ($row=oci_fetch_array($statement1, OCI_RETURN_NULLS)) {
        $order_id_array[]=$row[0];
        //echo "$row[0]";
        $dish_list_array[]=$row[1];
        //echo "$row[1]";
        $table_id_array[]=$row[2];
    }
    for ($i=0;$i<$quantity;$i++) {
        $rand1=mt_rand(0, $quantity-1);
        $table_id=$table_id_array[$rand1];
        $pre_order="pre_".substr($order_id_array[$rand1], -18);
        $pre_dish=$dish_list_array[$rand1];
        $order_time=substr($order_id_array[$rand1], -14);
        $orderid=$order_id_array[$rand1];
        $pre_order_time=date("Y-m-d H:i:s", strtotime($order_time));
        $arrive_time=date("Y-m-d H:i:s", strtotime('+20 minute', strtotime($pre_order_time)));
        $sql_insert="INSERT INTO SCOTT.pre_order".
             "(preorder_id,preorder_time,arrive_time,order_id,dish_list,table_id)".
             "VALUES".
             "('$pre_order','$pre_order_time','$arrive_time','$orderid','$pre_dish','$table_id')";
        //echo"  $pre_order   $pre_order_time  $arrive_time  $orderid  $pre_dish<br>";
        $statement2=oci_parse($conn, $sql_insert);
        oci_execute($statement2);
    }
    oci_free_statement($statement1);
    oci_free_statement($statement2);
}

function createSalesData($conn)
{
    $sql_select1="SELECT order_id,dish_list FROM SCOTT.order_List";
    $statement1=oci_parse($conn, $sql_select1);
    oci_execute($statement1);
    while ($row=oci_fetch_array($statement1, OCI_RETURN_NULLS)) {
        $arr=explode(",", $row[1]);
        $count=0;
        $order_id=$row[0];
        foreach ($arr as $a) {
            //$a是菜品编号
            $count++;
            $sql_select2="SELECT dish_price FROM SCOTT.dish WHERE dish_id='$a'";
            $statement2=oci_parse($conn, $sql_select2);
            oci_execute($statement2);
            while ($row=oci_fetch_array($statement2, OCI_RETURN_NULLS)) {
                $dish_price=$row[0];
            }
            //echo "a:$dish_price<br>";
            $count=$count<10 ? "00$count" : ($count<100 ? "0$count" : "$count");
            $sales_id="sal_".substr($order_id, -18)."_"."$count";
            $sql_insert="INSERT INTO SCOTT.sales".
                "(sales_id,dish_id,dish_price,order_id,sal_status)".
                "VALUES".
                "('$sales_id','$a',$dish_price,'$order_id',3)";
            //echo "$sales_id   $a  $dish_price  $order_id <br>";
            $statement3=oci_parse($conn, $sql_insert);
            oci_execute($statement3);
            oci_free_statement($statement2);
            oci_free_statement($statement3);
        }
    }
    oci_free_statement($statement1);
}

function createEvaluateData($conn)
{
    $message=array("服务很好","食物很棒","价格实惠","还会再来");
    $sql_select="SELECT order_id FROM SCOTT.order_list";
    $statement1=oci_parse($conn, $sql_select);
    oci_execute($statement1);
    while ($row=oci_fetch_array($statement1, OCI_RETURN_NULLS)) {
        $eva_id="eva_".substr($row[0], -18);
        $order_id=$row[0];
        $star=mt_rand(3, 5);
        $rand1=mt_rand(0, 3);
        $note=$message[$rand1];
        $sql_insert="INSERT INTO SCOTT.evaluate".
            "(evaluate_id,order_id,rating,evaluate_note)".
            "VALUES".
            "('$eva_id','$order_id',$star,'$note')";
        $statement2=oci_parse($conn, $sql_insert);
        oci_execute($statement2);
    }
    oci_free_statement($statement1);
    oci_free_statement($statement2);
}

function createFinanceData($conn, $quantity)
{
    $_date = date("y-m-d", time());
    for ($i = 0; $i < $quantity; $i++) {
        $date = DATE("ymd", strtotime($_date));
        $finance_id = "fin_$date";
        $fin_date = date("Y-m-d", strtotime($_date));
        $month = date("m", strtotime($_date));
        $turnover = mt_rand(13000, 18000);
        $cost = mt_rand(6000, 8000);
        $profit = $turnover - $cost;

        $sql_insert = "INSERT INTO SCOTT.finance" .
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



function deletedishlist($conn){
    $sql_delete="ALTER table order_list drop column dish_list";
    $statement=oci_parse($conn,$sql_delete);
    oci_execute($statement);
    oci_free_statement($statement);
    echo "已删除order_list表的dish_list列";
}