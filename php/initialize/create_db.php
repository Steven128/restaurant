<?php
$conn = oci_connect('scott', '123456', 'localhost:1521/ORCL', "AL32UTF8");

// if (!$conn) {
//     $e = oci_error();
//     print htmlentities($e['message']);
//     exit;
// } else {
//     echo "连接oracle成功！";
//     $sql_create_tab = "CREATE TABLE finance(" .
//         "finance_id VARCHAR(20) NOT NULL PRIMARY KEY," .
//         "fin_date VARCHAR(10) NOT NULL," .
//         "month NUMERIC(2,0) NOT NULL," .
//         "turnover NUMERIC(10,2) NOT NULL," .
//         "cost NUMERIC(10,2) NOT NULL," .
//         "profit NUMERIC(10,2) NOT NULL)";
//     $statement = oci_parse($conn, $sql_create_tab);
//     if (oci_execute($statement)) {
//         echo "<br>创建财务表成功！";
//         $statement = oci_parse($conn, "COMMENT ON TABLE finance IS '财务表'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN finance.finance_id IS '财务ID'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN finance.fin_date IS '日期'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN finance.month IS '月份'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN finance.turnover IS '营业额'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN finance.cost IS '成本'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN finance.profit IS '利润'");
//         oci_execute($statement);
//     } else {
//         echo $statement;
//     }

//     $sql_create_tab = "CREATE TABLE admin(" .
//         "admin_id VARCHAR(20) NOT NULL PRIMARY KEY," .
//         "admin_name VARCHAR(20) NOT NULL," .
//         "admin_passwd VARCHAR(32) NOT NULL," .
//         "admin_type NUMERIC(1,0) NOT NULL," .
//         "create_time VARCHAR(25) NOT NULL," .
//         "admin_pic VARCHAR(100) NOT NULL," .
//         "adm_status NUMERIC(1,0) DEFAULT 1 NOT NULL)";
//     $statement = oci_parse($conn, $sql_create_tab);
//     if (oci_execute($statement)) {
//         echo "<br>创建管理员表成功！";
//         $statement = oci_parse($conn, "COMMENT ON TABLE admin IS '管理员表'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN admin.admin_id IS '管理员ID'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN admin.admin_name IS '姓名'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN admin.admin_passwd IS '密码'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN admin.admin_type IS '管理员类型，1为超级管理员，2为普通管理员，3为财务管理员，4为仓库管理员'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN admin.create_time IS '账号创建时间'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN admin.admin_pic IS '管理员头像'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN admin.adm_status IS '账号状态，1有效 0已删除'");
//         oci_execute($statement);
//     } else {
//         echo $statement;
//     }

//     $sql_create_tab = "CREATE TABLE employee(" .
//         "employee_id VARCHAR(20) NOT NULL PRIMARY KEY," .
//         "name VARCHAR(20) NOT NULL," .
//         "gender NUMERIC(1,0) NOT NULL," .
//         "working_year NUMBER(2,1) NOT NULL," .
//         "age NUMERIC(2,0) NOT NULL," .
//         "salary NUMERIC(10,2) NOT NULL," .
//         "phone_num VARCHAR(11)," .
//         "employee_type NUMERIC(1,0) NOT NULL," .
//         "employ_time VARCHAR(10) NOT NULL," .
//         "employee_pic VARCHAR(100) NOT NULL," .
//         "emp_status NUMERIC(1,0) DEFAULT 1 NOT NULL)";
//     $statement = oci_parse($conn, $sql_create_tab);
//     if (oci_execute($statement)) {
//         echo "<br>创建员工表成功！";
//         $statement = oci_parse($conn, "COMMENT ON TABLE employee IS '员工表'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN employee.employee_id IS '员工ID'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN employee.name IS '员工姓名'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN employee.gender IS '员工性别，1为男，0为女'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN employee.working_year IS '工龄，单位年'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN employee.age IS '年龄'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN employee.salary IS '工资'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN employee.phone_num IS '手机号'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN employee.employee_type IS '员工类别，1为餐厅管理人员，2为服务员，3为前台人员，4为厨师，5为保洁人员，6为仓库管理员，7为会计，8为其他'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN employee.employ_time IS '聘用日期'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN employee.employee_pic IS '员工照片'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN employee.emp_status IS '员工类别，0为已离职，1为在职，2为其他'");
//         oci_execute($statement);
//     } else {
//         echo $statement;
//     }

//     $sql_create_tab = "CREATE TABLE presence(" .
//         "presence_id VARCHAR(25) NOT NULL PRIMARY KEY," .
//         "sign_time VARCHAR(25) NOT NULL," .
//         "pre_month NUMERIC(2,0) NOT NULL," .
//         "employee_id VARCHAR(20) NOT NULL," .
//         "hasPresented NUMERIC(1,0) NOT NULL," .
//         "pre_status NUMERIC(1,0) DEFAULT 1 NOT NULL," .
//         "FOREIGN KEY (employee_id) REFERENCES employee(employee_id) ON DELETE CASCADE)";
//     $statement = oci_parse($conn, $sql_create_tab);
//     if (oci_execute($statement)) {
//         echo "<br>创建出勤表成功！";
//         $statement = oci_parse($conn, "COMMENT ON TABLE presence IS '出勤表'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN presence.presence_id IS '出勤ID'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN presence.sign_time IS '签到时间'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN presence.pre_month IS '月份'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN presence.employee_id IS '员工ID（外键）'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN presence.hasPresented IS '是否出勤，0为未出勤，1为已出勤'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN presence.pre_status IS '状态，0为已删除，1为有效'");
//         oci_execute($statement);
//     } else {
//         echo $statement;
//     }

//     $sql_create_tab = "CREATE TABLE goods(" .
//         "goods_id VARCHAR(20) NOT NULL PRIMARY KEY," .
//         "goods_name VARCHAR(20) NOT NULL," .
//         "goods_price NUMERIC(5,2) NOT NULL," .
//         "goods_type NUMERIC(1,0) NOT NULL," .
//         "quantity NUMERIC(10,2) DEFAULT 0 NOT NULL," .
//         "goo_status NUMERIC(1,0) DEFAULT 1 NOT NULL)";
//     $statement = oci_parse($conn, $sql_create_tab);
//     if (oci_execute($statement)) {
//         echo "<br>创建原料表成功！";
//         $statement = oci_parse($conn, "COMMENT ON TABLE goods IS '原料表'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN goods.goods_id IS '原料ID'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN goods.goods_name IS '原料名称'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN goods.goods_price IS '单价'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN goods.goods_type IS '原料类别'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN goods.goo_status IS '状态，0为已删除，1为有效'");
//         oci_execute($statement);
//     } else {
//         echo $statement;
//     }

//     $sql_create_tab = "CREATE TABLE inventory(" .
//         "inventory_id VARCHAR(20) NOT NULL," .
//         "goods_id VARCHAR(20) NOT NULL," .
//         "quantity NUMERIC(12,2) NOT NULL," .
//         "inv_status NUMERIC(1,0) DEFAULT 1 NOT NULL," .
//         "FOREIGN KEY (goods_id) REFERENCES goods(goods_id) ON DELETE CASCADE)";
//     $statement = oci_parse($conn, $sql_create_tab);
//     if (oci_execute($statement)) {
//         echo "<br>创建库存表成功！";
//         $statement = oci_parse($conn, "COMMENT ON TABLE inventory IS '库存表'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN inventory.inventory_id IS '库存ID'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN inventory.goods_id IS '原料ID（外键）'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN inventory.quantity IS '数量'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN inventory.inv_status IS '状态，0已删除 1有效'");
//         oci_execute($statement);
//     } else {
//         echo $statement;
//     }


//     $sql_create_tab = "CREATE TABLE inventory(" .
//         "inventory_id VARCHAR(20) NOT NULL PRIMARY KEY," .
//         "goods_id VARCHAR(20) NOT NULL," .
//         "quantity NUMERIC(12,2) NOT NULL," .
//         "inv_status NUMERIC(1,0) DEFAULT 1 NOT NULL," .
//         "FOREIGN KEY (goods_id) REFERENCES goods(goods_id) ON DELETE CASCADE)";
//     $statement = oci_parse($conn, $sql_create_tab);
//     if (oci_execute($statement)) {
//         echo "<br>创建库存表成功！";
//         $statement = oci_parse($conn, "COMMENT ON TABLE inventory IS '库存表'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN inventory.inventory_id IS '库存ID'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN inventory.goods_id IS '原料ID（外键）'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN inventory.quantity IS '数量'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN inventory.inv_status IS '状态，0已删除 1有效'");
//         oci_execute($statement);
//     } else {
//         echo $statement;
//     }

//     $sql_create_tab = "CREATE TABLE loss(" .
//         "loss_id VARCHAR(20) NOT NULL PRIMARY KEY," .
//         "goods_id VARCHAR(20) NOT NULL," .
//         "quantity NUMERIC(12,2) NOT NULL," .
//         "loss_date VARCHAR(10) NOT NULL," .
//         "los_status NUMERIC(1,0) DEFAULT 1 NOT NULL," .
//         "FOREIGN KEY (goods_id) REFERENCES goods(goods_id) ON DELETE CASCADE)";
//     $statement = oci_parse($conn, $sql_create_tab);
//     if (oci_execute($statement)) {
//         echo "<br>创建损失表成功！";
//         $statement = oci_parse($conn, "COMMENT ON TABLE loss IS '损失表'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN loss.loss_id IS '损失ID'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN loss.goods_id IS '原料ID（外键）'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN loss.quantity IS '损失数量'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN loss.loss_date IS '损失时间'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN loss.los_status IS '状态，0已删除 1有效'");
//         oci_execute($statement);
//     } else {
//         echo $statement;
//     }

//     $sql_create_tab = "CREATE TABLE overhead(" .
//         "overhead_id VARCHAR(20) NOT NULL PRIMARY KEY," .
//         "overhead_type NUMERIC(1,0) NOT NULL," .
//         "overhead_price NUMERIC(10,2) NOT NULL," .
//         "overhead_date VARCHAR(10) NOT NULL," .
//         "ove_invoice_pic VARCHAR(100)," .
//         "ove_status NUMERIC(1,0) DEFAULT 1 NOT NULL)";
//     $statement = oci_parse($conn, $sql_create_tab);
//     if (oci_execute($statement)) {
//         echo "<br>创建开销表成功！";
//         $statement = oci_parse($conn, "COMMENT ON TABLE overhead IS '开销表'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN overhead.overhead_id IS '开销ID'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN overhead.overhead_type IS '开销类型，1为进货，2为水电费，3为房租，4为其他'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN overhead.overhead_price IS '总金额'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN overhead.overhead_date IS '开销日期'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN overhead.ove_invoice_pic IS '发票图片路径'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN overhead.ove_status IS '状态，0已删除 1有效'");
//         oci_execute($statement);
//     } else {
//         echo $statement;
//     }

//     $sql_create_tab = "CREATE TABLE dish(" .
//         "dish_id VARCHAR(20) NOT NULL PRIMARY KEY," .
//         "dish_name VARCHAR(100) NOT NULL," .
//         "dish_pic VARCHAR(100) NOT NULL," .
//         "dish_price NUMERIC(5,2) NOT NULL," .
//         "dish_type NUMERIC(1,0) NOT NULL," .
//         "dis_status NUMERIC(1,0) DEFAULT 1 NOT NULL)";
//     $statement = oci_parse($conn, $sql_create_tab);
//     if (oci_execute($statement)) {
//         echo "<br>创建菜品表成功！";
//         $statement = oci_parse($conn, "COMMENT ON TABLE dish IS '菜品表'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN dish.dish_id IS '菜品ID'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN dish.dish_name IS '菜名'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN dish.dish_pic IS '图片'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN dish.dish_price IS '售价'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN dish.dish_type IS '菜品类别，1为早餐，2为主食，3为甜品和饮料，4为小食'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN dish.dis_status IS '状态，0已删除 1有效'");
//         oci_execute($statement);
//     } else {
//         echo $statement;
//     }

//     $sql_create_tab = "CREATE TABLE res_table(" .
//         "table_id VARCHAR(20) NOT NULL PRIMARY KEY," .
//         "table_number VARCHAR(20) NOT NULL," .
//         "default_number NUMERIC(3,0) NOT NULL," .
//         "table_order_status NUMERIC(1,0) DEFAULT 0 NOT NULL," .
//         "tab_status NUMERIC(1,0) DEFAULT 1 NOT NULL)";
//     $statement = oci_parse($conn, $sql_create_tab);
//     if (oci_execute($statement)) {
//         echo "<br>创建餐桌表成功！";
//         $statement = oci_parse($conn, "COMMENT ON TABLE res_table IS '餐桌表'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN res_table.table_id IS '餐桌ID'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN res_table.table_number IS '餐桌编号'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN res_table.default_number IS '规定人数'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN res_table.table_order_status IS '是否有人，0为无人，1为已预订，2为已上座'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN res_table.tab_status IS '状态，0已删除 1有效'");
//         oci_execute($statement);
//     } else {
//         echo $statement;
//     }

//     $sql_create_tab = "CREATE TABLE order_list(" .
//         "order_id VARCHAR(25) NOT NULL PRIMARY KEY," .
//         "table_id VARCHAR(20) NOT NULL," .
//         "dish_list VARCHAR(1000)," .
//         "total_price NUMERIC(6,2)," .
//         "pay_method NUMERIC(1,0)," .
//         "pay_status NUMERIC(1,0) DEFAULT 0 NOT NULL," .
//         "pay_time VARCHAR(20)," .
//         "order_note VARCHAR(120)," .
//         "ord_status NUMERIC(1,0) DEFAULT 1 NOT NULL," .
//         "FOREIGN KEY (table_id) REFERENCES res_table(table_id) ON DELETE CASCADE)";
//     $statement = oci_parse($conn, $sql_create_tab);
//     if (oci_execute($statement)) {
//         echo "<br>创建订单表成功！";
//         $statement = oci_parse($conn, "COMMENT ON TABLE order_list IS '订单表'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN order_list.table_id IS '餐桌ID（外键）'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN order_list.order_id IS '订单ID'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN order_list.dish_list IS '点菜列表，dish_id用逗号分隔'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN order_list.total_price IS '总价'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN order_list.pay_method IS '付款方式，1为现金，2为支付宝，3为微信'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN order_list.pay_status IS '付款状态，0为未付款，1为已付款'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN order_list.pay_time IS '结账时间'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN order_list.order_note IS '备注'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN order_list.ord_status IS '订单状态，0已删除 1有效'");
//         oci_execute($statement);
//     } else {
//         echo $statement;
//     }

//     $sql_create_tab = "CREATE TABLE pre_order(" .
//         "preorder_id VARCHAR(25) NOT NULL PRIMARY KEY," .
//         "preorder_time VARCHAR(20) NOT NULL," .
//         "arrive_time VARCHAR(20) NOT NULL," .
//         "order_id VARCHAR(25) NOT NULL," .
//         "dish_list VARCHAR(1000)," .
//         "table_id VARCHAR(20) NOT NULL," .
//         "pre_status NUMERIC(1,0) DEFAULT 1 NOT NULL," .
//         "FOREIGN KEY (table_id) REFERENCES res_table(table_id) ON DELETE CASCADE," .
//         "FOREIGN KEY (order_id) REFERENCES order_list(order_id) ON DELETE CASCADE)";
//     $statement = oci_parse($conn, $sql_create_tab);
//     if (oci_execute($statement)) {
//         echo "<br>创建预定表成功！";
//         $statement = oci_parse($conn, "COMMENT ON TABLE pre_order IS '预定表'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN pre_order.preorder_id IS '预订ID'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN pre_order.preorder_time IS '预订下单时间'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN pre_order.arrive_time IS '预约上座时间'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN pre_order.order_id IS '订单ID（外键）'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN pre_order.dish_list IS '点菜列表，dish_id用逗号分隔'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN order_list.table_id IS '餐桌ID（外键）'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN pre_order.pre_status IS '预订状态，0已删除 >1有效 1为已预订还没到 2为已上座 3为已取消预订'");
//         oci_execute($statement);
//     } else {
//         echo $statement;
//     }

//     $sql_create_tab = "CREATE TABLE sales(" .
//         "sales_id VARCHAR(30) NOT NULL PRIMARY KEY," .
//         "dish_id VARCHAR(20) NOT NULL," .
//         "dish_price NUMERIC(5,2) NOT NULL," .
//         "order_id VARCHAR(25) NOT NULL," .
//         "sal_status NUMERIC(1,0) DEFAULT 1 NOT NULL," .
//         "FOREIGN KEY (dish_id) REFERENCES dish(dish_id) ON DELETE CASCADE," .
//         "FOREIGN KEY (order_id) REFERENCES order_list(order_id) ON DELETE CASCADE)";
//     $statement = oci_parse($conn, $sql_create_tab);
//     if (oci_execute($statement)) {
//         echo "<br>创建销售表成功！";
//         $statement = oci_parse($conn, "COMMENT ON TABLE sales IS '销售表'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN sales.sales_id IS '销售ID'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN sales.dish_id IS '菜品ID'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN sales.dish_price IS '下单时菜价'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN sales.order_id IS '订单编号（外键）'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN sales.sal_status IS '状态，0已删除 >=1有效 1已下单 2正在做 3做完了'");
//         oci_execute($statement);
//     } else {
//         echo $statement;
//     }

//     $sql_create_tab = "CREATE TABLE evaluate(" .
//         "evaluate_id VARCHAR(25) NOT NULL PRIMARY KEY," .
//         "order_id VARCHAR(25) NOT NULL," .
//         "rating NUMERIC(2,1) DEFAULT 0.0 NOT NULL," .
//         "evaluate_note VARCHAR(100)," .
//         "eva_status NUMERIC(1,0) DEFAULT 1 NOT NULL," .
//         "FOREIGN KEY (order_id) REFERENCES order_list(order_id) ON DELETE CASCADE)";
//     $statement = oci_parse($conn, $sql_create_tab);
//     if (oci_execute($statement)) {
//         echo "<br>创建评价表成功！";
//         $statement = oci_parse($conn, "COMMENT ON TABLE evaluate IS '评价表'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN evaluate.evaluate_id IS '评价ID'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN evaluate.order_id IS '订单编号（外键）'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN evaluate.rating IS '评价星级'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN evaluate.evaluate_note IS '评价内容'");
//         oci_execute($statement);
//         $statement = oci_parse($conn, "COMMENT ON COLUMN evaluate.eva_status IS '状态，0已删除 1有效'");
//         oci_execute($statement);
//     } else {
//         echo $statement;
//     }

//     $sql_create_tab = "CREATE TABLE app_update(" .
//         "update_id VARCHAR(20) NOT NULL PRIMARY KEY," .
//         "version VARCHAR(10) NOT NULL," .
//         "update_time VARCHAR(25) NOT NULL," .
//         "update_status NUMERIC(1,0) DEFAULT 0 NOT NULL," .
//         "update_note VARCHAR(100))";
//     $statement = oci_parse($conn, $sql_create_tab);
//     if (oci_execute($statement)) {
//         echo "<br>创建更新管理表成功！";
//         $statement = oci_parse($conn, "COMMENT ON TABLE app_update IS '更新表'");
//         oci_execute($statement);
//     } else {
//         echo $statement;
//     }

//     $conn = oci_connect('system', '123456', 'localhost:1521/ORCL', "AL32UTF8");



//     $user = array("adm_admin", "emp_admin", "fin_admin", "inv_admin", "ord_admin", "dis_admin", "tab_admin");
//     for ($i = 0; $i < count($user); $i++) {
//         $sql_create_user = "CREATE USER $user[$i]" .
//             " IDENTIFIED BY 123456";
//         echo "$sql_create_user<br>";
//         $statement = oci_parse($conn, $sql_create_user);
//         if (oci_execute($statement)) {
//             echo "<br>创建用户$user[$i]成功！";
//         }
//         $sql_grant = "grant connect to $user[$i]";
//         $statement = oci_parse($conn, $sql_grant);
//         if (oci_execute($statement)) {
//             echo "<br>授予用户$user[$i]连接权限成功！";
//         }
//     }
//     $user = array("emp_admin", "emp_admin", "fin_admin", "fin_admin", "inv_admin", "inv_admin", "inv_admin", "inv_admin", "ord_admin", "ord_admin", "ord_admin", "ord_admin", "dis_admin", "tab_admin");
//     $table = array("employee", "presence", "finance", "overhead", "overhead", "inventory", "loss", "goods", "pre_order", "order_list", "dish", "res_table", "dish", "res_table");
//     for ($i = 0; $i < count($user); $i++) {
//         $sql_grant = "grant select on SCOTT.$table[$i] to $user[$i]";
//         $statement = oci_parse($conn, $sql_grant);
//         if (oci_execute($statement)) {
//             echo "<br>授予用户$user[$i]对$table[$i]表的查询权限成功！";
//         }
//     }
//     // $user = array("emp_admin", "fin_admin", "fin_admin", "inv_admin", "inv_admin", "inv_admin", "ord_admin", "dis_admin", "tab_admin");
//     // $table = array("employee", "finance", "overhead", "inventory", "loss", "goods", "order_list", "dish", "res_table");

//     // for ($i = 0; $i < count($user); $i++) {

//     //     $sql_grant = "grant insert on SCOTT.$table[$i] to $user[$i]";
//     //     $statement = oci_parse($conn, $sql_grant);
//     //     if (oci_execute($statement)) {
//     //         echo "<br>授予用户$user[$i]对$table[$i]表的插入权限成功！";
//     //     }

//     // }

    // $user = array("adm_admin","emp_admin", "fin_admin", "fin_admin", "inv_admin", "inv_admin", "inv_admin", "ord_admin", "dis_admin", "tab_admin");
    // $table = array("admin","employee", "finance", "overhead", "inventory", "loss", "goods", "order_list", "dish", "res_table");
    // for ($i = 0; $i < count($user); $i++) {
    //     $sql_grant = "grant update on SCOTT.$table[$i] to $user[$i]";
    //     $statement = oci_parse($conn, $sql_grant);
    //     if (oci_execute($statement)) {
    //         echo "<br>授予用户$user[$i]对$table[$i]表的更新权限成功！";
    //     }
    // }


// //employee
//     $sql_ins = "CREATE OR REPLACE PROCEDURE updateEmployee 
//     (v_employee_id IN employee.employee_id%TYPE, 
//     v_name IN employee.name%TYPE,
//     v_gender IN employee.gender%TYPE,
//     v_age IN employee.age%TYPE,
//     v_salary IN employee.salary%TYPE,
//     v_phone_num IN employee.phone_num%TYPE,
//     v_employee_type IN employee.employee_type%TYPE) 
//     IS 
//     BEGIN 
//     UPDATE SCOTT.EMPLOYEE SET name=v_name,gender=v_gender,age=v_age,salary=v_salary,phone_num=v_phone_num,employee_type=v_employee_type WHERE EMPLOYEE_ID=v_employee_id;
//     END updateEmployee; 
//     ";
//     $statement = oci_parse($conn, $sql_ins);
//     if (oci_execute($statement)) {
//         echo "y";
//     } else
//         echo "n";

//     $sql_ins = "CREATE OR REPLACE PROCEDURE addEmployee 
//     (v_employee_id IN employee.employee_id%TYPE, 
//     v_name IN employee.name%TYPE,
//     v_gender IN employee.gender%TYPE,
//     v_age IN employee.age%TYPE,
//     v_salary IN employee.salary%TYPE,
//     v_phone_num IN employee.phone_num%TYPE,
//     v_employee_type IN employee.employee_type%TYPE,
//     v_employ_time IN employee.employ_time%TYPE) 
//     IS 
//     BEGIN 
//     INSERT INTO SCOTT.EMPLOYEE VALUES (v_employee_id, v_name, v_gender, 0, v_age, v_salary, v_phone_num, v_employee_type, v_employ_time,'../../src/employee_pic/default.png', 1);
//     END addEmployee; 
//     ";
//     $statement = oci_parse($conn, $sql_ins);
//     if (oci_execute($statement)) {
//         echo "y";
//     } else
//         echo "n";

//     $sql_ins = "CREATE OR REPLACE PROCEDURE deleteEmployee 
//     (v_employee_id IN employee.employee_id%TYPE) 
//     IS 
//     BEGIN 
//     UPDATE SCOTT.EMPLOYEE SET emp_status=0 WHERE EMPLOYEE_ID=v_employee_id;
//     END deleteEmployee; 
//     ";
//     $statement = oci_parse($conn, $sql_ins);
//     if (oci_execute($statement)) {
//         echo "y";
//     } else
//         echo "n";

// //dish
//     $sql_ins = "CREATE OR REPLACE PROCEDURE updateDish 
//     (v_dish_id IN dish.dish_id%TYPE, 
//     v_dish_name IN dish.dish_name%TYPE,
//     v_dish_price IN dish.dish_price%TYPE,
//     v_dish_type IN dish.dish_type%TYPE) 
//     IS 
//     BEGIN 
//     UPDATE SCOTT.dish SET dish_name=v_dish_name,dish_price=v_dish_price,dish_type=v_dish_type WHERE dish_id=v_dish_id;
//     END updateDish; 
//     ";
//     $statement = oci_parse($conn, $sql_ins);
//     if (oci_execute($statement)) {
//         echo "y";
//     } else
//         echo "n";

//     $sql_ins = "CREATE OR REPLACE PROCEDURE addDish 
//     (v_dish_id IN dish.dish_id%TYPE, 
//     v_dish_name IN dish.dish_name%TYPE,
//     v_dish_price IN dish.dish_price%TYPE,
//     v_dish_type IN dish.dish_type%TYPE) 
//     IS 
//     BEGIN 
//     INSERT INTO SCOTT.dish VALUES (v_dish_id, v_dish_name,'../../src/dish_pic/default.php', v_dish_price, v_dish_type, 1);
//     END addDish; 
//     ";
//     $statement = oci_parse($conn, $sql_ins);
//     if (oci_execute($statement)) {
//         echo "y";
//     } else
//         echo "n";

//     $sql_ins = "CREATE OR REPLACE PROCEDURE deleteDish 
//     (v_dish_id IN dish.dish_id%TYPE) 
//     IS 
//     BEGIN 
//     UPDATE SCOTT.dish SET dis_status=0 WHERE dish_id=v_dish_id;
//     END deleteDish; 
//     ";
//     //grant execute on scott.addEmployee to emp_admin;
//     $statement = oci_parse($conn, $sql_ins);
//     if (oci_execute($statement)) {
//         echo "y";
//     } else
//         echo "n";

// //table
//     $sql_ins = "CREATE OR REPLACE PROCEDURE updateTable 
// (v_table_id IN res_table.table_id%TYPE, 
// v_default_number IN res_table.default_number%TYPE) 
// IS 
// BEGIN 
// UPDATE SCOTT.res_table SET default_number=v_default_number WHERE table_id=v_table_id;
// END updateTable; 
// ";
//     $statement = oci_parse($conn, $sql_ins);
//     if (oci_execute($statement)) {
//         echo "y";
//     } else
//         echo "n";

//     $sql_ins = "CREATE OR REPLACE PROCEDURE addTable 
// (v_table_id IN res_table.table_id%TYPE, 
// v_table_number IN res_table.table_number%TYPE,
// v_default_number IN res_table.default_number%TYPE) 
// IS 
// BEGIN 
// INSERT INTO SCOTT.res_table VALUES (v_table_id, v_table_number,v_default_number, 0, 1);
// END addTable; 
// ";
//     $statement = oci_parse($conn, $sql_ins);
//     if (oci_execute($statement)) {
//         echo "y";
//     } else
//         echo "n";

//     $sql_ins = "CREATE OR REPLACE PROCEDURE deleteTable 
// (v_table_id IN res_table.table_id%TYPE) 
// IS 
// BEGIN 
// UPDATE SCOTT.res_table SET tab_status=0 WHERE table_id=v_table_id;
// END deleteTable; 
// ";
//     $statement = oci_parse($conn, $sql_ins);
//     if (oci_execute($statement)) {
//         echo "y";
//     } else
//         echo "n";



//     $sql_ins = "CREATE OR REPLACE PROCEDURE addInventory 
//         (v_inventory_id IN inventory.inventory_id%TYPE, 
//         v_goods_name IN goods.goods_name%TYPE,
//         v_quantity IN inventory.quantity%TYPE) 
//         IS 
//         v_goods_id goods.goods_id%TYPE;
//         BEGIN 
//         select goods_id into v_goods_id from scott.goods where goods_name=v_goods_name;
//         INSERT INTO SCOTT.inventory VALUES (v_inventory_id, v_goods_id,v_quantity,1);

//         UPDATE SCOTT.GOODS SET quantity=quantity+v_quantity WHERE goods_id=v_goods_id;
//         END addInventory; 
//         ";
//     $statement = oci_parse($conn, $sql_ins);
//     if (oci_execute($statement)) {
//         echo "addInventory";
//     } else
//         echo "n";


// //admin

//     $sql_ins = "CREATE OR REPLACE PROCEDURE addAdmin 
// (v_admin_id IN admin.admin_id%TYPE, 
// v_admin_name IN admin.admin_name%TYPE,
// v_admin_passwd IN admin.admin_passwd%TYPE,
// v_admin_type IN admin.admin_type%TYPE,
// v_create_time IN admin.create_time%TYPE) 
// IS 
// BEGIN 
// INSERT INTO SCOTT.admin VALUES (v_admin_id, v_admin_name,v_admin_passwd,v_admin_type, v_create_time,'../../src/admin_pic/default.png', 1);
// END addAdmin; 
// ";
//     $statement = oci_parse($conn, $sql_ins);
//     if (oci_execute($statement)) {
//         echo "y";
//     } else
//         echo "n";


//     $sql_ins = "CREATE OR REPLACE PROCEDURE deleteAdmin 
// (v_admin_name IN admin.admin_name%TYPE) 
// IS 
// BEGIN 
// UPDATE SCOTT.admin SET adm_status=0 WHERE admin_name=v_admin_name;
// END deleteAdmin; 
// ";
//     $statement = oci_parse($conn, $sql_ins);
//     if (oci_execute($statement)) {
//         echo "y";
//     } else
//         echo "n";




//     $sql = "grant execute on scott.updateEmployee to emp_admin;
//     grant execute on scott.addEmployee to emp_admin;
//     grant execute on scott.deleteEmployee to emp_admin;
//     grant execute on scott.updateDish to dis_admin;
//     grant execute on scott.addDish to dis_admin;
//     grant execute on scott.deleteDish to dis_admin;
//     grant execute on scott.updateTable to tab_admin;
//     grant execute on scott.addTable to tab_admin;
//     grant execute on scott.deleteTable to tab_admin;
//     grant execute on scott.addInventory to inv_admin;
//     grant execute on scott.addAdmin to adm_admin;
//     grant execute on scott.deleteAdmin to adm_admin";

//     $sql_grant = explode(";", $sql);
//     foreach ($sql_grant as $k => $v) {
//         $statement = oci_parse($conn, $sql_grant[$k]);
//         if (oci_execute($statement)) {
//             echo "y";
//         } else
//             echo "n";
//     }
//employee_id,name,gender,working_year,age,salary,phone_num,employee_type,employ_time ,employee_pic,emp_status
$sql_create_view = "create or replace view empRead as select * from scott.employee where emp_status>0 with read only";
$statement = oci_parse($conn, $sql_create_view);
if (oci_execute($statement)) {
    echo "y";
} else
    echo "n";

$sql_create_view = "create or replace view disRead as select * from scott.dish where dis_status>0 with read only";
$statement = oci_parse($conn, $sql_create_view);
if (oci_execute($statement)) {
    echo "y";
} else
    echo "n";

$sql_create_view = "create or replace view finRead as select * from scott.finance with read only";
$statement = oci_parse($conn, $sql_create_view);
if (oci_execute($statement)) {
    echo "y";
} else
    echo "n";

$sql_create_view = "create or replace view invRead as select * from scott.inventory where inv_status>0 with read only";
$statement = oci_parse($conn, $sql_create_view);
if (oci_execute($statement)) {
    echo "y";
} else
    echo "n";

$sql_create_view = "create or replace view gooRead as select * from scott.goods where goo_status>0 with read only";
$statement = oci_parse($conn, $sql_create_view);
if (oci_execute($statement)) {
    echo "y";
} else
    echo "n";

$sql_create_view = "create or replace view losRead as select loss_id,loss.GOODS_ID,loss.QUANTITY,loss_date,GOODS_NAME, GOODS_PRICE, GOODS_TYPE from scott.loss,scott.goods where loss.goods_id=goods.goods_id and goo_status>0 and los_status>0 with read only";
$statement = oci_parse($conn, $sql_create_view);
if (oci_execute($statement)) {
    echo "y";
} else
    echo "n";

$sql_create_view = "create or replace view ordRead as select order_id,scott.res_table.table_number,dish_list,total_price,pay_method,pay_time,order_note,pay_status FROM SCOTT.ORDER_LIST,SCOTT.RES_TABLE WHERE ORD_STATUS=1 AND TAB_STATUS=1 AND SCOTT.ORDER_LIST.TABLE_ID=SCOTT.RES_TABLE.TABLE_ID with read only";
$statement = oci_parse($conn, $sql_create_view);
if (oci_execute($statement)) {
    echo "y";
} else
    echo "n";

$sql_create_view = "create or replace view purRead as select * FROM SCOTT.overhead WHERE ove_status>0 with read only";
$statement = oci_parse($conn, $sql_create_view);
if (oci_execute($statement)) {
    echo "y";
} else
    echo "n";

$sql_create_view = "create or replace view porRead as select preorder_id,preorder_time,arrive_time,order_id,dish_list,table_number FROM SCOTT.pre_order,scott.res_table WHERE res_table.table_id=pre_order.table_id AND pre_status>0 AND tab_status>0 with read only";
$statement = oci_parse($conn, $sql_create_view);
if (oci_execute($statement)) {
    echo "y";
} else
    echo "n";

$sql_create_view = "create or replace view tabRead as select * FROM SCOTT.res_table WHERE tab_status>0 with read only";
$statement = oci_parse($conn, $sql_create_view);
if (oci_execute($statement)) {
    echo "y";
} else
    echo "n";

    $sql_create_view = "create or replace view preRead as select EMPLOYEE.EMPLOYEE_ID,NAME,GENDER,PHONE_NUM,EMPLOYEE_TYPE,PRESENCE_ID,SIGN_TIME,HASPRESENTED FROM SCOTT.PRESENCE,SCOTT.EMPLOYEE WHERE EMPLOYEE.EMPLOYEE_ID=PRESENCE.EMPLOYEE_ID AND EMP_STATUS>0 AND PRE_STATUS>0 with read only";
    $statement = oci_parse($conn, $sql_create_view);
    if (oci_execute($statement)) {
        echo "y";
    } else
        echo "n";
    

$sql = "grant select on scott.empRead to emp_admin;
grant select on scott.disRead to dis_admin;
grant select on scott.finRead to fin_admin;
grant select on scott.invRead to inv_admin;
grant select on scott.gooRead to inv_admin;
grant select on scott.losRead to inv_admin;
grant select on scott.ordRead to ord_admin;
grant select on scott.purRead to fin_admin;
grant select on scott.porRead to ord_admin;
grant select on scott.tabRead to tab_admin;
grant select on scott.preRead to emp_admin
";

$sql_grant = explode(";", $sql);
foreach ($sql_grant as $k => $v) {
    $statement = oci_parse($conn, $sql_grant[$k]);
    if (oci_execute($statement)) {
        echo "y";
    } else
        echo "n";
}
oci_free_statement($statement);
oci_close($conn);
    //$sql_create_tab = "CREATE TABLE employee(" .
    //         "employee_id VARCHAR(20) NOT NULL PRIMARY KEY," .
    //         "name VARCHAR(20) NOT NULL," .
    //         "gender NUMERIC(1,0) NOT NULL," .
    //         "working_year NUMBER(2,1) NOT NULL," .
    //         "age NUMERIC(2,0) NOT NULL," .
    //         "salary NUMERIC(10,2) NOT NULL," .
    //         "phone_num VARCHAR(11)," .
    //         "employee_type NUMERIC(1,0) NOT NULL," .
    //         "employ_time VARCHAR(10) NOT NULL," .
    //         "employee_pic VARCHAR(100) NOT NULL," .
    //         "emp_status NUMERIC(1,0) DEFAULT 1 NOT NULL)";
//}