<?php
$conn = oci_connect('scott', '123456', 'localhost:1521/ORCL');
if (!$conn) {
    $e = oci_error();
    print htmlentities($e['message']);
    exit;
} else {
    echo "连接oracle成功！";

    // createFinanceData($conn,100);
    // echo "<br>写入财务表数据成功";

    createAdminData(100);

    // oci_close($conn);

}
// echo date("y_m_d", time());
// date("y_m_d", strtotime("-1 day"));
// echo date("Y-m-d", (strtotime("2009-01-01") - 3600 * 24));

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

function createAdminData($quantity)
{
    require 'rndChinaName.class.php';

    $name_obj = new rndChinaName();

    for ($i = 0; $i < $quantity; $i++) {
        
        $name = $name_obj->getName(2);
        echo $name;
        echo "<br>";
    }

}
