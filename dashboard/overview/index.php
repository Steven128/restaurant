<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
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
                    <div class="chart-box col-xs-12 col-md-6 col-lg-4">
                        <div class="chart-box-inner">
                            <h4 class="chart-title">员工性别</h4>
                            <hr>
                            <canvas id="genderChart"></canvas>
                            <script>
                                var ctx = document.getElementById("genderChart");
                                var myChart = new Chart(ctx, {
                                    type: 'pie',
                                    data: {
                                        <?php
                                        $sql_query = "SELECT * FROM SCOTT.EMPLOYEE WHERE EMP_STATUS>0 AND gender=1";
                                        $statement = oci_parse($conn, $sql_query);
                                        oci_execute($statement);
                                        $male = 0;
                                        while ($row = oci_fetch_array($statement, OCI_RETURN_NULLS)) { //查询结果集
                                            $male++;
                                        }

                                        $sql_query = "SELECT * FROM SCOTT.EMPLOYEE WHERE EMP_STATUS>0 AND gender=0";
                                        $statement = oci_parse($conn, $sql_query);
                                        oci_execute($statement);
                                        $female = 0;
                                        while ($row = oci_fetch_array($statement, OCI_RETURN_NULLS)) { //查询结果集
                                            $female++;
                                        }

                                        $sql_query = "SELECT * FROM SCOTT.EMPLOYEE WHERE EMP_STATUS>0 AND gender>1";
                                        $statement = oci_parse($conn, $sql_query);
                                        oci_execute($statement);
                                        $other = 0;
                                        while ($row = oci_fetch_array($statement, OCI_RETURN_NULLS)) { //查询结果集
                                            $other++;
                                        }
                                        echo "labels: [\"男\",\"女\",\"其他\"],datasets: [{label: '性别',data:[$male,$female,$other"
                                        ?>
                                    ],
                                    backgroundColor: [
                                        'rgba(255, 99, 132, 0.2)',
                                        'rgba(54, 162, 235, 0.2)',
                                        'rgba(255, 206, 86, 0.2)',
                                    ],
                                    borderColor: [
                                        'rgba(255,99,132,1)',
                                        'rgba(54, 162, 235, 1)',
                                        'rgba(255, 206, 86, 1)',
                                    ],
                                    borderWidth: 1
                                }]
                                },
                                options: {}
                                });
                            </script>
                        </div>
                    </div>
                    <div class="chart-box col-xs-12 col-md-6 col-lg-4">
                        <div class="chart-box-inner">
                            <h4 class="chart-title">员工年龄</h4>
                            <hr>
                            <canvas id="ageChart"></canvas>
                            <script>
                                var ctx = document.getElementById("ageChart");
                                var myChart = new Chart(ctx, {
                                    type: 'bar',
                                    data: {
                                        <?php
                                        $sql_query = "SELECT * FROM SCOTT.EMPLOYEE WHERE EMP_STATUS>0 AND age>=20 AND age<30";
                                        $statement = oci_parse($conn, $sql_query);
                                        oci_execute($statement);
                                        $age_below_30 = 0;
                                        while ($row = oci_fetch_array($statement, OCI_RETURN_NULLS)) { //查询结果集
                                            $age_below_30++;
                                        }

                                        $sql_query = "SELECT * FROM SCOTT.EMPLOYEE WHERE EMP_STATUS>0 AND age>=30 AND age<40";
                                        $statement = oci_parse($conn, $sql_query);
                                        oci_execute($statement);
                                        $age_below_40 = 0;
                                        while ($row = oci_fetch_array($statement, OCI_RETURN_NULLS)) { //查询结果集
                                            $age_below_40++;
                                        }

                                        $sql_query = "SELECT * FROM SCOTT.EMPLOYEE WHERE EMP_STATUS>0 AND age>=40 AND age<50";
                                        $statement = oci_parse($conn, $sql_query);
                                        oci_execute($statement);
                                        $age_below_50 = 0;
                                        while ($row = oci_fetch_array($statement, OCI_RETURN_NULLS)) { //查询结果集
                                            $age_below_50++;
                                        }

                                        $sql_query = "SELECT * FROM SCOTT.EMPLOYEE WHERE EMP_STATUS>0 AND age<20 OR age>=50";
                                        $statement = oci_parse($conn, $sql_query);
                                        oci_execute($statement);
                                        $age_other = 0;
                                        while ($row = oci_fetch_array($statement, OCI_RETURN_NULLS)) { //查询结果集
                                            $age_other++;
                                        }
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

                    </div>
                    <div class="chart-box col-xs-12 col-md-6 col-lg-4">

                    </div>
                    <div class="chart-box col-xs-12 col-md-6 col-lg-4">

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