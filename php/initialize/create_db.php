<?php
$conn = oci_connect('scott', '123456', 'localhost:1521/ORCL', "AL32UTF8");
if (!$conn) {
    $e = oci_error();
    print htmlentities($e['message']);
    exit;
} else {
    echo "连接oracle成功！";
    $sql_create_tab = "CREATE TABLE finance(" .
        "finance_id VARCHAR(20) NOT NULL PRIMARY KEY," .
        "fin_date VARCHAR(10) NOT NULL," .
        "month INT NOT NULL," .
        "turnover FLOAT NOT NULL," .
        "cost FLOAT NOT NULL," .
        "profit FLOAT NOT NULL)";
    $statement = oci_parse($conn, $sql_create_tab);
    if (oci_execute($statement)) {
        echo "<br>创建财务表成功！";
        $statement = oci_parse($conn, "COMMENT ON TABLE finance IS '财务表'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN finance.finance_id IS '财务ID'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN finance.fin_date IS '日期'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN finance.month IS '月份'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN finance.turnover IS '营业额'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN finance.cost IS '成本'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN finance.profit IS '利润'");
        oci_execute($statement);
    } else {
        echo $statement;
    }

    $sql_create_tab = "CREATE TABLE admin(" .
        "admin_id VARCHAR(20) NOT NULL PRIMARY KEY," .
        "admin_name VARCHAR(20) NOT NULL," .
        "admin_passwd VARCHAR(32) NOT NULL," .
        "admin_type NUMBER(1) NOT NULL," .
        "create_time VARCHAR(25) NOT NULL," .
        "admin_pic VARCHAR(100) NOT NULL," .
        "adm_status NUMBER(1) DEFAULT 1 NOT NULL)";
    $statement = oci_parse($conn, $sql_create_tab);
    if (oci_execute($statement)) {
        echo "<br>创建管理员表成功！";
        $statement = oci_parse($conn, "COMMENT ON TABLE admin IS '管理员表'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN admin.admin_id IS '管理员ID'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN admin.admin_name IS '姓名'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN admin.admin_passwd IS '密码'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN admin.admin_type IS '管理员类型，1为超级管理员，2为普通管理员，3为财务管理员，4为仓库管理员'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN admin.create_time IS '账号创建时间'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN admin.admin_pic IS '管理员头像'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN admin.adm_status IS '账号状态，1有效 0无效'");
        oci_execute($statement);
    } else {
        echo $statement;
    }

    $sql_create_tab = "CREATE TABLE employee(" .
        "employee_id VARCHAR(20) NOT NULL PRIMARY KEY," .
        "name VARCHAR(20) NOT NULL," .
        "gender NUMBER(1) NOT NULL," .
        "working_year NUMBER(2,1) NOT NULL," .
        "age INT NOT NULL," .
        "salary INT NOT NULL," .
        "phone_num VARCHAR(11)," .
        "employee_type NUMBER(1) NOT NULL," .
        "employ_time VARCHAR(10) NOT NULL," .
        "employee_pic VARCHAR(100) NOT NULL," .
        "emp_status NUMBER(1) DEFAULT 1 NOT NULL)";
    $statement = oci_parse($conn, $sql_create_tab);
    if (oci_execute($statement)) {
        echo "<br>创建员工表成功！";
        $statement = oci_parse($conn, "COMMENT ON TABLE employee IS '员工表'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN employee.employee_id IS '员工ID'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN employee.name IS '员工姓名'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN employee.gender IS '员工性别，1为男，0为女'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN employee.working_year IS '工龄，单位年'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN employee.age IS '年龄'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN employee.salary IS '工资'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN employee.phone_num IS '手机号'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN employee.employee_type IS '员工类别，1为餐厅管理人员，2为服务员，3为前台人员，4为厨师，5为保洁人员，6为仓库管理员，7为会计，8为其他'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN employee.employ_time IS '聘用日期'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN employee.employee_pic IS '员工照片'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN employee.emp_status IS '员工类别，0为已离职，1为在职，2为其他'");
        oci_execute($statement);
    } else {
        echo $statement;
    }

    $sql_create_tab = "CREATE TABLE presence(" .
        "presence_id VARCHAR(25) NOT NULL PRIMARY KEY," .
        "sign_time VARCHAR(25) NOT NULL," .
        "p_month INT NOT NULL," .
        "employee_id VARCHAR(20) NOT NULL," .
        "hasPresented NUMBER(1) NOT NULL," .
        "pre_status NUMBER(1) DEFAULT 1 NOT NULL," .
        "FOREIGN KEY (employee_id) REFERENCES employee(employee_id) ON DELETE CASCADE)";
    $statement = oci_parse($conn, $sql_create_tab);
    if (oci_execute($statement)) {
        echo "<br>创建出勤表成功！";
        $statement = oci_parse($conn, "COMMENT ON TABLE presence IS '出勤表'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN presence.presence_id IS '出勤ID'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN presence.sign_time IS '签到时间'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN presence.p_month IS '月份'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN presence.employee_id IS '员工ID（外键）'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN presence.hasPresented IS '是否出勤，0为未出勤，1为已出勤'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN presence.pre_status IS '状态，0为无效，1为有效'");
        oci_execute($statement);
    } else {
        echo $statement;
    }

    $sql_create_tab = "CREATE TABLE goods(" .
        "goods_id VARCHAR(20) NOT NULL PRIMARY KEY," .
        "goods_name VARCHAR(20) NOT NULL," .
        "goods_price FLOAT NOT NULL," .
        "goods_type NUMBER(1) NOT NULL," .
        "goo_status NUMBER(1) DEFAULT 1 NOT NULL)";
    $statement = oci_parse($conn, $sql_create_tab);
    if (oci_execute($statement)) {
        echo "<br>创建原料表成功！";
        $statement = oci_parse($conn, "COMMENT ON TABLE goods IS '原料表'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN goods.goods_id IS '原料ID'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN goods.goods_name IS '原料名称'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN goods.goods_price IS '单价'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN goods.goods_type IS '原料类别'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN goods.goo_status IS '状态，0为无效，1为有效'");
        oci_execute($statement);
    } else {
        echo $statement;
    }

    $sql_create_tab = "CREATE TABLE inventory(" .
        "inventory_id VARCHAR(20) NOT NULL PRIMARY KEY," .
        "goods_id VARCHAR(20) NOT NULL," .
        "quantity FLOAT NOT NULL," .
        "inv_status NUMBER(1) DEFAULT 1 NOT NULL," .
        "FOREIGN KEY (goods_id) REFERENCES goods(goods_id) ON DELETE CASCADE)";
    $statement = oci_parse($conn, $sql_create_tab);
    if (oci_execute($statement)) {
        echo "<br>创建库存表成功！";
        $statement = oci_parse($conn, "COMMENT ON TABLE inventory IS '库存表'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN inventory.inventory_id IS '库存ID'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN inventory.goods_id IS '原料ID（外键）'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN inventory.quantity IS '数量'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN inventory.inv_status IS '状态，0无效 1有效'");
        oci_execute($statement);
    } else {
        echo $statement;
    }

    $sql_create_tab = "CREATE TABLE loss(" .
        "loss_id VARCHAR(20) NOT NULL PRIMARY KEY," .
        "goods_id VARCHAR(20) NOT NULL," .
        "quantity FLOAT NOT NULL," .
        "loss_time VARCHAR(20) NOT NULL," .
        "los_status NUMBER(1) DEFAULT 1 NOT NULL," .
        "FOREIGN KEY (goods_id) REFERENCES goods(goods_id) ON DELETE CASCADE)";
    $statement = oci_parse($conn, $sql_create_tab);
    if (oci_execute($statement)) {
        echo "<br>创建损失表成功！";
        $statement = oci_parse($conn, "COMMENT ON TABLE loss IS '损失表'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN loss.loss_id IS '损失ID'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN loss.goods_id IS '原料ID（外键）'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN loss.quantity IS '损失数量'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN loss.loss_time IS '损失时间'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN loss.los_status IS '状态，0无效 1有效'");
        oci_execute($statement);
    } else {
        echo $statement;
    }

    $sql_create_tab = "CREATE TABLE purchase(" .
        "purchase_id VARCHAR(20) NOT NULL PRIMARY KEY," .
        "purchase_number VARCHAR(20) NOT NULL," .
        "goods_id VARCHAR(20) NOT NULL," .
        "purchase_quantity FLOAT NOT NULL," .
        "purchase_date VARCHAR(10) NOT NULL," .
        "pur_status NUMBER(1) DEFAULT 1 NOT NULL," .
        "FOREIGN KEY (goods_id) REFERENCES goods(goods_id) ON DELETE CASCADE)";
    $statement = oci_parse($conn, $sql_create_tab);
    if (oci_execute($statement)) {
        echo "<br>创建进货表成功！";
        $statement = oci_parse($conn, "COMMENT ON TABLE purchase IS '进货表'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN purchase.purchase_id IS '进货ID'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN purchase.purchase_number IS '进货单号'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN purchase.goods_id IS '原料ID（外键）'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN purchase.purchase_quantity IS '数量'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN purchase.purchase_date IS '进货日期'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN purchase.pur_status IS '状态，0无效 1有效'");
        oci_execute($statement);
    } else {
        echo $statement;
    }

    $sql_create_tab = "CREATE TABLE dish(" .
        "dish_id VARCHAR(20) NOT NULL PRIMARY KEY," .
        "dish_name VARCHAR(100) NOT NULL," .
        "dish_pic VARCHAR(100) NOT NULL," .
        "dish_price FLOAT NOT NULL," .
        "dish_type NUMBER(1) NOT NULL," .
        "dis_status NUMBER(1) DEFAULT 1 NOT NULL)";
    $statement = oci_parse($conn, $sql_create_tab);
    if (oci_execute($statement)) {
        echo "<br>创建菜品表成功！";
        $statement = oci_parse($conn, "COMMENT ON TABLE dish IS '菜品表'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN dish.dish_id IS '菜品ID'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN dish.dish_name IS '菜名'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN dish.dish_pic IS '图片'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN dish.dish_price IS '售价'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN dish.dish_type IS '菜品类别，1为早餐，2为主食，3为甜品和饮料，4为小食'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN dish.dis_status IS '状态，0无效 1有效'");
        oci_execute($statement);

    } else {
        echo $statement;
    }

    $sql_create_tab = "CREATE TABLE res_table(" .
        "table_id VARCHAR(20) NOT NULL PRIMARY KEY," .
        "table_number VARCHAR(20) NOT NULL," .
        "default_number INT NOT NULL," .
        "table_order_status NUMBER(1) DEFAULT 0 NOT NULL," .
        "tab_status NUMBER(1) DEFAULT 0 NOT NULL)";
    $statement = oci_parse($conn, $sql_create_tab);
    if (oci_execute($statement)) {
        echo "<br>创建餐桌表成功！";
        $statement = oci_parse($conn, "COMMENT ON TABLE res_table IS '餐桌表'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN res_table.table_id IS '餐桌ID'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN res_table.table_number IS '餐桌编号'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN res_table.default_number IS '规定人数'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN res_table.table_order_status IS '是否有人，0为无人，1为已预订，2为已上座'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN res_table.tab_status IS '状态，0无效 1有效'");
        oci_execute($statement);
    } else {
        echo $statement;
    }

    $sql_create_tab = "CREATE TABLE order_list(" .
        "order_id VARCHAR(25) NOT NULL PRIMARY KEY," .
        "dish_list CLOB," .
        "total_price FLOAT," .
        "pay_method NUMBER(1)," .
        "pay_status NUMBER(1) DEFAULT 0 NOT NULL," .
        "table_id VARCHAR(20) NOT NULL," .
        "pay_time VARCHAR(20)," .
        "order_note VARCHAR(120)," .
        "ord_status NUMBER(1) DEFAULT 1 NOT NULL," .
        "FOREIGN KEY (table_id) REFERENCES res_table(table_id) ON DELETE CASCADE)";
    $statement = oci_parse($conn, $sql_create_tab);
    if (oci_execute($statement)) {
        echo "<br>创建订单表成功！";
        $statement = oci_parse($conn, "COMMENT ON TABLE order_list IS '订单表'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN order_list.order_id IS '订单ID'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN order_list.dish_list IS '点菜列表，dish_id用逗号分隔'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN order_list.total_price IS '总价'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN order_list.pay_method IS '付款方式，1为现金，2为支付宝，3为微信'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN order_list.pay_status IS '付款状态，0为未付款，1为已付款'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN order_list.table_id IS '餐桌ID（外键）'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN order_list.pay_time IS '结账时间'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN order_list.order_note IS '备注'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN order_list.ord_status IS '订单状态，0无效 1有效'");
        oci_execute($statement);
    } else {
        echo $statement;
    }

    $sql_create_tab = "CREATE TABLE pre_order(" .
        "preorder_id VARCHAR(20) NOT NULL PRIMARY KEY," .
        "preorder_time VARCHAR(20) NOT NULL," .
        "arrive_time VARCHAR(20) NOT NULL," .
        "order_id VARCHAR(20) NOT NULL," .
        "dish_list CLOB," .
        "pre_status NUMBER(1) DEFAULT 1 NOT NULL," .
        "FOREIGN KEY (order_id) REFERENCES order_list(order_id) ON DELETE CASCADE)";
    $statement = oci_parse($conn, $sql_create_tab);
    if (oci_execute($statement)) {
        echo "<br>创建预定表成功！";
        $statement = oci_parse($conn, "COMMENT ON TABLE pre_order IS '预定表'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN pre_order.preorder_id IS '预订ID'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN pre_order.preorder_time IS '预订下单时间'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN pre_order.arrive_time IS '预约上座时间'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN pre_order.order_id IS '订单ID（外键）'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN pre_order.dish_list IS '点菜列表，dish_id用逗号分隔'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN pre_order.pre_status IS '预订状态，0无效 1有效'");
        oci_execute($statement);
    } else {
        echo $statement;
    }

    $sql_create_tab = "CREATE TABLE sales(" .
        "sales_id VARCHAR(20) NOT NULL PRIMARY KEY," .
        "dish_id VARCHAR(20) NOT NULL," .
        "dish_price FLOAT NOT NULL," .
        "order_id VARCHAR(20) NOT NULL," .
        "sal_status NUMBER(1) DEFAULT 1 NOT NULL," .
        "FOREIGN KEY (dish_id) REFERENCES dish(dish_id) ON DELETE CASCADE," .
        "FOREIGN KEY (order_id) REFERENCES order_list(order_id) ON DELETE CASCADE)";
    $statement = oci_parse($conn, $sql_create_tab);
    if (oci_execute($statement)) {
        echo "<br>创建销售表成功！";
        $statement = oci_parse($conn, "COMMENT ON TABLE sales IS '销售表'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN sales.sales_id IS '销售ID'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN sales.dish_id IS '菜品ID'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN sales.dish_price IS '下单时菜价'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN sales.order_id IS '订单编号（外键）'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN sales.sal_status IS '状态，0无效 >=1有效 1已下单 2正在做 3做完了'");
        oci_execute($statement);
    } else {
        echo $statement;
    }

    $sql_create_tab = "CREATE TABLE evaluate(" .
        "evaluate_id VARCHAR(20) NOT NULL PRIMARY KEY," .
        "order_id VARCHAR(20) NOT NULL," .
        "rating FLOAT DEFAULT 0.0 NOT NULL," .
        "evaluate_note VARCHAR(100)," .
        "eva_status NUMBER(1) DEFAULT 1 NOT NULL," .
        "FOREIGN KEY (order_id) REFERENCES order_list(order_id) ON DELETE CASCADE)";
    $statement = oci_parse($conn, $sql_create_tab);
    if (oci_execute($statement)) {
        echo "<br>创建评价表成功！";
        $statement = oci_parse($conn, "COMMENT ON TABLE evaluate IS '评价表'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN evaluate.evaluate_id IS '评价ID'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN evaluate.order_id IS '订单编号（外键）'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN evaluate.rating IS '评价星级'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN evaluate.evaluate_note IS '评价内容'");
        oci_execute($statement);
        $statement = oci_parse($conn, "COMMENT ON COLUMN evaluate.eva_status IS '状态，0无效 1有效'");
        oci_execute($statement);
    } else {
        echo $statement;
    }

    $sql_create_tab = "CREATE TABLE app_update(".
        "update_id VARCHAR(20) NOT NULL PRIMARY KEY,".
        "version FLOAT NOT NULL,".
        "update_time VARCHAR(25) NOT NULL,".
        "update_status NUMBER(1) DEFAULT 0 NOT NULL,".
        "update_note VARCHAR(100))";
    $statement = oci_parse($conn, $sql_create_tab);
    if (oci_execute($statement)) {
        echo "<br>创建更新管理表成功！";
        $statement = oci_parse($conn, "COMMENT ON TABLE app_update IS '更新表'");oci_execute($statement);

    } else {
        echo $statement;
    }

    oci_free_statement($statement);
    oci_close($conn);
}
