<?php
$conn = oci_connect('scott', '123456', 'localhost:1521/ORCL');
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
    } else {
        echo $statement;
    }

    $sql_create_tab = "CREATE TABLE admin(".
        "admin_id VARCHAR(20) NOT NULL PRIMARY KEY,".
        "admin_name VARCHAR(20) NOT NULL,".
        "admin_passwd VARCHAR(32) NOT NULL,".
        "admin_status NUMBER(3) DEFAULT 1,".
        "create_time VARCHAR(25) NOT NULL)";
    $statement = oci_parse($conn, $sql_create_tab);
    if (oci_execute($statement)) {
        echo "<br>创建管理员表成功！";
    } else {
        echo $statement;
    }

    $sql_create_tab = "CREATE TABLE employee(".
        "employee_id VARCHAR(20) NOT NULL PRIMARY KEY,".
        "name VARCHAR(20) NOT NULL,".
        "gender NUMBER(3) NOT NULL,".
        "working_year INT NOT NULL,".
        "age INT NOT NULL,".
        "salary INT NOT NULL,".
        "phone VARCHAR(11),".
        "employee_type NUMBER(3) NOT NULL)";
    $statement = oci_parse($conn, $sql_create_tab);
    if (oci_execute($statement)) {
        echo "<br>创建员工表成功！";
    } else {
        echo $statement;
    }

    $sql_create_tab = "CREATE TABLE presence(".
        "presence_id VARCHAR(20) NOT NULL PRIMARY KEY,".
        "time VARCHAR(25) NOT NULL,".
        "month INT NOT NULL,".
        "employee_id VARCHAR(20) NOT NULL,".
        "hasPresented NUMBER(3) NOT NULL,".
        "FOREIGN KEY (employee_id) REFERENCES employee(employee_id) ON DELETE CASCADE)";
    $statement = oci_parse($conn, $sql_create_tab);
    if (oci_execute($statement)) {
        echo "<br>创建出勤表成功！";
    } else {
        echo $statement;
    }

    $sql_create_tab = "CREATE TABLE goods(".
        "goods_id VARCHAR(20) NOT NULL PRIMARY KEY,".
        "goods_name VARCHAR(20) NOT NULL,".
        "goods_price FLOAT NOT NULL,".
        "goods_type NUMBER(3) NOT NULL)";
    $statement = oci_parse($conn, $sql_create_tab);
    if (oci_execute($statement)) {
        echo "<br>创建原料表成功！";
    } else {
        echo $statement;
    }

    $sql_create_tab = "CREATE TABLE inventory(".
        "inventory_id VARCHAR(20) NOT NULL PRIMARY KEY,".
        "goods_id VARCHAR(20) NOT NULL,".
        "quantity FLOAT NOT NULL,".
        "FOREIGN KEY (goods_id) REFERENCES goods(goods_id) ON DELETE CASCADE)";
    $statement = oci_parse($conn, $sql_create_tab);
    if (oci_execute($statement)) {
        echo "<br>创建库存表成功！";
    } else {
        echo $statement;
    }

    $sql_create_tab = "CREATE TABLE purchase(".
        "purchase_id VARCHAR(20) NOT NULL PRIMARY KEY,".
        "goods_id VARCHAR(20) NOT NULL,".
        "purchase_quantity FLOAT NOT NULL,".
        "purchase_date VARCHAR(10) NOT NULL,".
        "FOREIGN KEY (goods_id) REFERENCES goods(goods_id) ON DELETE CASCADE)";
    $statement = oci_parse($conn, $sql_create_tab);
    if (oci_execute($statement)) {
        echo "<br>创建进货表成功！";
    } else {
        echo $statement;
    }

    $sql_create_tab = "CREATE TABLE dish(".
        "dish_id VARCHAR(20) NOT NULL PRIMARY KEY,".
        "dish_name VARCHAR(20),".
        "dish_pic BLOB,".
        "dish_price FLOAT NOT NULL,".
        "requires BLOB DEFAULT NULL,".
        "dish_status NUMBER(3) NOT NULL)";
    $statement = oci_parse($conn, $sql_create_tab);
    if (oci_execute($statement)) {
        echo "<br>创建菜品表成功！";
    } else {
        echo $statement;
    }

    $sql_create_tab = "CREATE TABLE sales(".
        "sales_id VARCHAR(20) NOT NULL PRIMARY KEY,".
        "dish_id VARCHAR(20) NOT NULL,".
        "sales_quantity INT NOT NULL,".
        "price FLOAT NOT NULL,".
        "sales_date VARCHAR(10) NOT NULL,".
        "FOREIGN KEY (dish_id) REFERENCES dish(dish_id) ON DELETE CASCADE)";
    $statement = oci_parse($conn, $sql_create_tab);
    if (oci_execute($statement)) {
        echo "<br>创建销售表成功！";
    } else {
        echo $statement;
    }

    $sql_create_tab = "CREATE TABLE res_table(".
        "table_id VARCHAR(20) NOT NULL PRIMARY KEY,".
        "table_number VARCHAR(20) NOT NULL,".
        "default_number INT NOT NULL,".
        "curr_number INT DEFAULT 0 NOT NULL,".
        "state NUMBER(3) DEFAULT 0 NOT NULL)";
    $statement = oci_parse($conn, $sql_create_tab);
    if (oci_execute($statement)) {
        echo "<br>创建餐桌表成功！";
    } else {
        echo $statement;
    }

    $sql_create_tab = "CREATE TABLE order_list(".
        "order_id VARCHAR(20) NOT NULL PRIMARY KEY,".
        "dish_list CLOB NOT NULL,".
        "order_status NUMBER(3) DEFAULT 0 NOT NULL,".
        "total_price FLOAT NOT NULL,".
        "order_method NUMBER(3),".
        "payment_status NUMBER(3) DEFAULT 0 NOT NULL,".
        "table_id VARCHAR(20) NOT NULL,".
        "order_time VARCHAR(20) NOT NULL,".
        "order_note VARCHAR(120),".
        "FOREIGN KEY (table_id) REFERENCES res_table(table_id) ON DELETE CASCADE)";
    $statement = oci_parse($conn, $sql_create_tab);
    if (oci_execute($statement)) {
        echo "<br>创建订单表成功！";
    } else {
        echo $statement;
    }

    $sql_create_tab = "CREATE TABLE evaluate(".
        "evaluate_id VARCHAR(20) NOT NULL PRIMARY KEY,".
        "order_id VARCHAR(20) NOT NULL,".
        "order_type NUMBER(3) NOT NULL,".
        "rating FLOAT DEFAULT 0.0 NOT NULL,".
        "evaluate_note VARCHAR(100),".
        "FOREIGN KEY (order_id) REFERENCES order_list(order_id) ON DELETE CASCADE)";
    $statement = oci_parse($conn, $sql_create_tab);
    if (oci_execute($statement)) {
        echo "<br>创建评价表成功！";
    } else {
        echo $statement;
    }

    $sql_create_tab = "CREATE TABLE app_update(".
        "update_id VARCHAR(20) NOT NULL PRIMARY KEY,".
        "version FLOAT NOT NULL,".
        "update_time VARCHAR(25) NOT NULL,".
        "update_status NUMBER(3) DEFAULT 0 NOT NULL,".
        "update_note VARCHAR(100))";
    $statement = oci_parse($conn, $sql_create_tab);
    if (oci_execute($statement)) {
        echo "<br>创建更新管理表成功！";
    } else {
        echo $statement;
    }

    oci_free_statement($statement);
    oci_close($conn);
}
