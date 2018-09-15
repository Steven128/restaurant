<?php
$conn = oci_connect('scott', '123456', 'localhost:1521/ORCL','AL32UTF8');
if (!$conn) {
    $e = oci_error();
    print htmlentities($e['message']);
    exit;
} else {
    echo "连接oracle成功！";
    $sql_create_tab = "DROP TABLE finance";
    $statement = oci_parse($conn, $sql_create_tab);
    if (oci_execute($statement)) {
        echo "<br>删除财务表成功！";
    } else {
        echo $statement;
    }

    $sql_create_tab = "DROP TABLE admin";
    $statement = oci_parse($conn, $sql_create_tab);
    if (oci_execute($statement)) {
        echo "<br>删除管理员表成功！";
    } else {
        echo $statement;
    }
    $sql_create_tab = "DROP TABLE presence";
    $statement = oci_parse($conn, $sql_create_tab);
    if (oci_execute($statement)) {
        echo "<br>删除出勤表成功！";
    } else {
        echo $statement;
    }
    $sql_create_tab = "DROP TABLE employee";
    $statement = oci_parse($conn, $sql_create_tab);
    if (oci_execute($statement)) {
        echo "<br>删除员工表成功！";
    } else {
        echo $statement;
    }

    $sql_create_tab = "DROP TABLE inventory";
    $statement = oci_parse($conn, $sql_create_tab);
    if (oci_execute($statement)) {
        echo "<br>删除库存表成功！";
    } else {
        echo $statement;
    }

    $sql_create_tab = "DROP TABLE loss";
    $statement = oci_parse($conn, $sql_create_tab);
    if (oci_execute($statement)) {
        echo "<br>删除损失表成功！";
    } else {
        echo $statement;
    }

    $sql_create_tab = "DROP TABLE purchase";
    $statement = oci_parse($conn, $sql_create_tab);
    if (oci_execute($statement)) {
        echo "<br>删除进货表成功！";
    } else {
        echo $statement;
    }

    $sql_create_tab = "DROP TABLE goods";
    $statement = oci_parse($conn, $sql_create_tab);
    if (oci_execute($statement)) {
        echo "<br>删除原料表成功！";
    } else {
        echo $statement;
    }

    $sql_create_tab = "DROP TABLE sales";
    $statement = oci_parse($conn, $sql_create_tab);
    if (oci_execute($statement)) {
        echo "<br>删除销售表成功！";
    } else {
        echo $statement;
    }
    $sql_create_tab = "DROP TABLE dish";
    $statement = oci_parse($conn, $sql_create_tab);
    if (oci_execute($statement)) {
        echo "<br>删除菜品表成功！";
    } else {
        echo $statement;
    }

    $sql_create_tab = "DROP TABLE evaluate";
    $statement = oci_parse($conn, $sql_create_tab);
    if (oci_execute($statement)) {
        echo "<br>删除评价表成功！";
    } else {
        echo $statement;
    }

    $sql_create_tab = "DROP TABLE pre_order";
    $statement = oci_parse($conn, $sql_create_tab);
    if (oci_execute($statement)) {
        echo "<br>删除预定表成功！";
    } else {
        echo $statement;
    }
    $sql_create_tab = "DROP TABLE order_list";
    $statement = oci_parse($conn, $sql_create_tab);
    if (oci_execute($statement)) {
        echo "<br>删除订单表成功！";
    } else {
        echo $statement;
    }

    $sql_create_tab = "DROP TABLE res_table";
    $statement = oci_parse($conn, $sql_create_tab);
    if (oci_execute($statement)) {
        echo "<br>删除餐桌表成功！";
    } else {
        echo $statement;
    }

    $sql_create_tab = "DROP TABLE app_update";
    $statement = oci_parse($conn, $sql_create_tab);
    if (oci_execute($statement)) {
        echo "<br>删除更新管理表成功！";
    } else {
        echo $statement;
    }

    oci_free_statement($statement);
    oci_close($conn);
}
