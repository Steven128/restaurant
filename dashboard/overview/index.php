<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>主页-餐饮店管理系统</title>
    <link type="text/css" rel="stylesheet" href="../../css/bootstrap.css" />
    <link type="text/css" rel="stylesheet" href="../../css/iconfont.css" />
    <link type="text/css" rel="stylesheet" href="../../css/page.css" />
    <link type="text/css" rel="stylesheet" href="../../css/dashboard.css" />
    <link type="text/css" rel="stylesheet" href="../../css/responsive-sidebar.css" />

    <script type="text/javascript" src="../../js/jQuery/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="../../js/bootstrap.min.js"></script>
    <script type="text/javascript" src="../../js/page.js"></script>
    <script type="text/javascript" src="../../js/das_mgment/dashboard.js"></script>
    <script type="text/javascript" src="../../js/xcConfirm.js"></script>
    <script type="text/javascript" src="../../js/Chart.js"></script>
    <script type="text/javascript" src="../../js/jquery.pjax.js"></script>
    <?php
    if (!isset($_SESSION['admin_id'])) {
        echo "<script>$(document).ready(() => {window.location.replace(\"../../login\");});</script>";
    }
    ?>
</head>

<body>
    <?php
    $conn = oci_connect('scott', '123456', 'localhost:1521/ORCL', "AL32UTF8"); //连接oracle数据库
    ?>
    <div class="container">
        <header class="head-content">
            <div class="site-branding">
                <div class="site-title">
                    <a herf="../../" rel="home">餐饮店管理系统</a>
                    <h5>主页</h5>
                </div>
            </div>
            <a class="user-box" href="javascript:void(0);" onclick="showInfoBox()">
                <?php echo "<div class=\"user-pic-wrap\"><img class=\"userPic\" src=\"" . $_SESSION['admin_pic'] . "?" . mt_rand(10000, 99999) . "\" /></div><div class=\"user-name\">" . $_SESSION['admin_name'] . "</div>"; ?>
            </a>
            <div class="info-box">
                <div class="info-box-block">
                    <div class="info-box-arrow"></div>
                    <?php
                    $admin_type = $_SESSION['admin_type'];
                    if ($admin_type == 1) {
                        $admin_type = "超级管理员";
                    } elseif ($admin_type == 2) {
                        $admin_type = "管理员";
                    } elseif ($admin_type == 3) {
                        $admin_type = "财务管理";
                    } elseif ($admin_type == 4) {
                        $admin_type = "库存管理";
                    }
                    echo "<div class=\"user-pic-wrap\"><img class=\"userPic\" src=\"" . $_SESSION['admin_pic'] . "?" . mt_rand(10000, 99999) . "\" /></div><div class=\"user-info-wrap\"><div class=\"user-name\">" . $_SESSION['admin_name'] . "</div><div class=\"user-type\">" . $admin_type . "</div></div>";
                    ?>
                    <div class="logout-wrap">
                        <a class="logout" href="../../php/check_login.php?request=logout"><i class="iconfont icon-logout"></i>退出登录</a>
                    </div>
                </div>
            </div>
            <a class="shelter" href="javascript:void(0);" onclick="hideShelter()"></a>
        </header>
        <div class="main-content">
            <div class="bar-box">
                <aside class="left-bar">
                    <section class="sidebar">
                        <ul class="sidebar-menu">
                            <li class="treeview overview-tree active">
                                <a id="menu-overview-item" href="javascript:void(0);">
                                    <i class="iconfont icon-overview"></i>
                                    <span>总览</span>
                                    <span class="dash-pull-right">
                                        <i class="iconfont icon-right-arrow " style="font-size:12px;"></i>
                                    </span>
                                </a>
                            </li>
                            <li class="treeview">
                                <a id="menu-userUpdate-item" href="javascript:void(0);">
                                    <i class="iconfont icon-user"></i>
                                    <span>个人信息</span>
                                    <span class="dash-pull-right">
                                        <i class="iconfont icon-right-arrow " style="font-size:12px;"></i>
                                    </span>
                                </a>
                            </li>
                            <li class="treeview">
                                <a id="menu-settings-item" href="javascript:void(0);">
                                    <i class="iconfont icon-settings"></i>
                                    <span>设置</span>
                                    <span class="dash-pull-right">
                                        <i class="iconfont icon-right-arrow " style="font-size:12px;"></i>
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </section>
                </aside>
                <div class="mask"></div>
                <div class="main-bar row">
                    <div class="chart-box col-xs-12">
                        <div class="chart-box-inner">
                            <h4 class="chart-title">今天是
                                <?php
                                echo date("Y", time())."年".date("m", time())."月".date("d", time())."日";
                                ?>
                            </h4>
                            <hr>
                            <div class="col-xs-12 col-md-6" style="text-align: center;">
                                <h4 class="chart-title">预定总数</h4>
                                <h1 style="font-size: 48px; color: #1296db;">
                                    <?php
                                    $sql_query = "SELECT COUNT(*) FROM SCOTT.PRE_ORDER WHERE PRE_STATUS=1 AND SUBSTR(PREORDER_ID,9,8)='".date("Ymd", time()-24*3600)."'";
                                    $statement = oci_parse($conn, $sql_query);
                                    oci_execute($statement);
                                    $row = oci_fetch_array($statement, OCI_RETURN_NULLS);
                                    if ($row[0] == "") {
                                        $row[0]=0;
                                    }
                                    echo $row[0];
                                ?>
                                </h1>
                            </div>
                            <div class="col-xs-12 col-md-6" style="text-align: center;">
                                <h4 class="chart-title">今日订单总数</h4>
                                <h1 style="font-size: 48px; color: #1296db;">
                                    <?php
                                    $sql_query = "SELECT COUNT(*) FROM SCOTT.ORDER_LIST WHERE ORD_STATUS=1 AND SUBSTR(ORDER_ID,9,8)='".date("Ymd", time()-2*24*3600)."'";
                                    $statement = oci_parse($conn, $sql_query);
                                    oci_execute($statement);
                                    $row = oci_fetch_array($statement, OCI_RETURN_NULLS);
                                    if ($row[0] == "") {
                                        $row[0]=0;
                                    }
                                    echo $row[0];
                                ?>
                                </h1>
                            </div>
                            <hr>
                            <canvas id="topChart"></canvas>
                            <script>
                                var ctx = document.getElementById("topChart");
                                var myChart = new Chart(ctx, {
                                    type: 'line',
                                    data: {
                                        <?php
                                        function getFinData($conn, $param, $date)
                                        {
                                            $sql_query = "SELECT $param FROM SCOTT.FINANCE WHERE FIN_DATE='$date'";
                                            $statement = oci_parse($conn, $sql_query);
                                            oci_execute($statement);
                                            $row = oci_fetch_array($statement, OCI_RETURN_NULLS);
                                            if ($row[0] == "") {
                                                $row[0]=0;
                                            }
                                            return $row[0];
                                        }
                                        ?>
                                        labels: [
                                            <?php
                                            $time = time()-7*24*3600;
                                            $echo_str = "";
                                            for ($i=0;$i<7;$i++) {
                                                $echo_str = $echo_str . "\"" . date("m.d", $time)."\",";
                                                $time += 24*3600;
                                            }
                                            echo substr($echo_str, 0, strlen($echo_str)-1);
                                            ?>
                                        ],
                                        datasets: [{
                                            label: '营业额',
                                            data: [
                                                <?php
                                                $time = time()-7*24*3600;
                                                $echo_str = "";
                                                for ($i=0;$i<7;$i++) {
                                                    $echo_str = $echo_str . getFinData($conn, "TURNOVER", date("Y-m-d", $time)) . ",";
                                                    $time += 24*3600;
                                                }
                                                echo substr($echo_str, 0, strlen($echo_str)-1);
                                                ?>
                                            ],
                                            fill: false,
                                            borderColor: ['rgba(255,99,132,1)']
                                        }, {
                                            label: '成本',
                                            data: [
                                                <?php
                                                $time = time()-7*24*3600;
                                                $echo_str = "";
                                                for ($i=0;$i<7;$i++) {
                                                    $echo_str = $echo_str . getFinData($conn, "COST", date("Y-m-d", $time)) . ",";
                                                    $time += 24*3600;
                                                }
                                                echo substr($echo_str, 0, strlen($echo_str)-1);
                                                ?>
                                            ],
                                            fill: false,
                                            borderColor: ['rgba(255, 159, 64, 1)']
                                        }, {
                                            label: '利润',
                                            data: [
                                                <?php
                                                $time = time()-7*24*3600;
                                                $echo_str = "";
                                                for ($i=0;$i<7;$i++) {
                                                    $echo_str = $echo_str . getFinData($conn, "PROFIT", date("Y-m-d", $time)) . ",";
                                                    $time += 24*3600;
                                                }
                                                echo substr($echo_str, 0, strlen($echo_str)-1);
                                                ?>
                                            ],
                                            fill: false,
                                            borderColor: ['rgb(54, 162, 235)']
                                        }]
                                    },
                                    options: {}
                                });
                            </script>
                        </div>
                    </div>
                    <div class="chart-box col-xs-12 col-md-6 col-lg-4">
                        <div class="chart-box-inner">
                            <h4 class="chart-title">当前营业额</h4>
                            <div style="font-size: 48px; color: #1296db;text-align: center;">
                                <?php
                                $sql_query = "SELECT SUM(DISH_PRICE) FROM SCOTT.SALES WHERE SAL_STATUS>=1 AND SUBSTR(ORDER_ID,9,8)='".date("Ymd", time()-2*24*3600)."'";
                                $statement = oci_parse($conn, $sql_query);
                                oci_execute($statement);
                                $row = oci_fetch_array($statement, OCI_RETURN_NULLS);
                                if ($row[0] == "") {
                                    $row[0]=0;
                                }
                                echo $row[0];
                                ?>
                                <span style="font-size: 36px;">元</span>
                            </div>
                        </div>
                    </div>
                    <div class="chart-box col-xs-12 col-md-6 col-lg-4">
                        <div class="chart-box-inner">
                            <h4 class="chart-title">出勤情况</h4>
                            <div style="font-size: 36px; color: #1296db;text-align: center;">
                                <?php
                                    $sql_query = "SELECT COUNT(UNIQUE(PRESENCE.EMPLOYEE_ID)) FROM SCOTT.EMPLOYEE,SCOTT.PRESENCE WHERE SCOTT.EMPLOYEE.EMPLOYEE_ID=SCOTT.PRESENCE.EMPLOYEE_ID AND EMP_STATUS=1 AND PRE_STATUS=1 AND HASPRESENTED=1 AND SUBSTR(SIGN_TIME,0,10)='".date("Y-m-d", time()-24*3600)."'";
                                    $statement = oci_parse($conn, $sql_query);
                                    oci_execute($statement);
                                    $row = oci_fetch_array($statement, OCI_RETURN_NULLS);
                                    $haspresenced = "$row[0]";
                                    $sql_query = "SELECT COUNT(*) FROM SCOTT.EMPLOYEE WHERE EMP_STATUS=1";
                                    $statement = oci_parse($conn, $sql_query);
                                    oci_execute($statement);
                                    $row = oci_fetch_array($statement, OCI_RETURN_NULLS);
                                    $quantity = "$row[0]";
                                    echo "$haspresenced/$quantity";
                                ?>
                                <span style="font-size: 24px;">人</span>
                            </div>
                            <hr>
                            <h4 class="chart-title">员工性别</h4>
                            <canvas id="genderChart"></canvas>
                            <script>
                                var ctx = document.getElementById("genderChart");
                                var myChart = new Chart(ctx, {
                                    type: 'pie',
                                    data: {
                                        <?php
                                        function getCount($conn, $table, $where)
                                        {
                                            $sql_query = "SELECT COUNT(*) FROM SCOTT.$table WHERE $where";
                                            $statement = oci_parse($conn, $sql_query);
                                            oci_execute($statement);
                                            $row = oci_fetch_array($statement, OCI_RETURN_NULLS);
                                            return $row[0];
                                        }
                                        $male = getCount($conn, "EMPLOYEE", "EMP_STATUS>0 AND gender=1");
                                        $female = getCount($conn, "EMPLOYEE", "EMP_STATUS>0 AND gender=0");
                                        // $other = getCount($conn, "EMPLOYEE", "EMP_STATUS>0 AND gender>1");
                                        ?>
                                        labels: ["男", "女"],
                                        datasets: [{
                                            label: '性别',
                                            data: [
                                                <?php
                                                echo "$male, $female";
                                                ?>
                                            ],
                                            backgroundColor: [
                                                'rgba(255, 99, 132, 0.2)',
                                                'rgba(54, 162, 235, 0.2)'
                                            ],
                                            borderColor: [
                                                'rgba(255,99,132,1)',
                                                'rgba(54, 162, 235, 1)'
                                            ],
                                            borderWidth: 1
                                        }]
                                    },
                                    options: {}
                                });
                            </script>
                            <hr>
                            <h4 class="chart-title">员工年龄</h4>
                            <canvas id="ageChart"></canvas>
                            <script>
                                var ctx = document.getElementById("ageChart");
                                var myChart = new Chart(ctx, {
                                    type: 'bar',
                                    data: {
                                        <?php
                                        $age_below_30 = getCount($conn, "EMPLOYEE", "EMP_STATUS>0 AND age>=20 AND age<30");
                                        $age_below_40 = getCount($conn, "EMPLOYEE", "EMP_STATUS>0 AND age>=30 AND age<40");
                                        $age_below_50 = getCount($conn, "EMPLOYEE", "EMP_STATUS>0 AND age>=40 AND age<50");
                                        $age_other = getCount($conn, "EMPLOYEE", "EMP_STATUS>0 AND age<20 OR age>=50");
                                        echo "labels: [\"20~30\",\"30~40\",\"40~50\",\"其他\"],datasets: [{label: '年龄',data:[$age_below_30,$age_below_40,$age_below_50,$age_other"
                                        ?>
                                    ],
                                    backgroundColor: [
                                        'rgba(255, 99, 132, 0.2)',
                                        'rgba(255, 159, 64, 0.2)',
                                        'rgba(255, 206, 86, 0.2)',
                                        'rgba(54, 162, 235, 0.2)'
                                    ],
                                    borderColor: [
                                        'rgba(255,99,132,1)',
                                        'rgba(255, 159, 64, 1)',
                                        'rgba(255, 206, 86, 1)',
                                        'rgb(54, 162, 235)'
                                    ],
                                    borderWidth: 1
                                }]
                                },
                                options: {
                                scales: {
                                    xAxes: [{
                                        gridLines: {
                                            offsetGridLines: true
                                        }
                                    }],
                                    yAxes: [{
                                        ticks: {
                                            beginAtZero: true
                                        }
                                    }]
                                }
                                }
                                });
                            </script>
                        </div>
                    </div>
                    <div class="chart-box col-xs-12 col-md-6 col-lg-4">
                        <div class="chart-box-inner">
                            <h4 class="chart-title">用户综合评价</h4>
                            <div style="font-size: 48px; color: #1296db;text-align: center;">
                                <?php
                                $sql_query = "SELECT SUM(RATING)，COUNT(RATING) FROM SCOTT.EVALUATE WHERE EVA_STATUS=1 AND SUBSTR(ORDER_ID,9,8)='".date("Ymd", time()-2*24*3600)."'";
                                $statement = oci_parse($conn, $sql_query);
                                oci_execute($statement);
                                $row = oci_fetch_array($statement, OCI_RETURN_NULLS);
                                if ($row[1] == 0) {
                                    $row[1]=1;
                                }
                                echo substr($row[0]/$row[1], 0, 3);
                                ?>
                                <span style="font-size: 36px;">星</span>
                            </div>
                            <hr>
                            <h4 class="chart-title">差评数</h4>
                            <h1 style="font-size: 48px; color: #1296db;text-align: center;">
                                <?php
                                $sql_query = "SELECT COUNT(RATING) FROM SCOTT.EVALUATE WHERE EVA_STATUS=1 AND RATING<=2 AND SUBSTR(ORDER_ID,9,8)='".date("Ymd", time()-2*24*3600)."'";
                                $statement = oci_parse($conn, $sql_query);
                                oci_execute($statement);
                                $row = oci_fetch_array($statement, OCI_RETURN_NULLS);
                                echo $row[0];
                                ?>
                            </h1>
                        </div>
                    </div>
                    <div class="chart-box col-xs-12 col-md-6 col-lg-4">
                        <div class="chart-box-inner"></div>
                    </div>
                    <script>
                        $(document).ready(() => {
                            barAppend("overview");

                            function changeMainBar(itemName) {
                                $("#menu-" + itemName + "-item").click(() => {
                                    $.pjax({
                                        url: "../" + itemName,
                                        container: 'html'
                                    });
                                });
                            }
                            //
                            changeMainBar("overview");
                            changeMainBar("userUpdate");
                            changeMainBar("settings");
                            if (getUserInfo().admin_type == 1) {
                                changeMainBar("adminList");
                                changeMainBar("addAdmin");
                            }
                        });
                    </script>
                </div>
            </div>
        </div>
        <footer class="foot-content">
            <div class="copyright">
                <h5 class="copyright-text">&copy;&nbsp;餐饮店管理系统&nbsp;&nbsp;2018</h5>
            </div>
        </footer>
    </div>
</body>


</html>