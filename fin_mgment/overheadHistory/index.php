<!-- /fin_mgment/overheadList -->

<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>财务管理-餐饮店管理系统</title>
    <link type="text/css" rel="stylesheet" href="../../css/bootstrap.css" />
    <link type="text/css" rel="stylesheet" href="../../css/iconfont.css" />
    <link type="text/css" rel="stylesheet" href="../../css/page.css" />
    <link type="text/css" rel="stylesheet" href="../../css/sidebar-menu.css" />
    <link type="text/css" rel="stylesheet" href="../../css/datatables.css" />
    <link type="text/css" rel="stylesheet" href="../../css/table_plugins/responsive.bootstrap.css" />

    <script type="text/javascript" src="../../js/jQuery/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="../../js/bootstrap.min.js"></script>
    <script type="text/javascript" src="../../js/page.js"></script>
    <script type="text/javascript" src="../../js/xcConfirm.js"></script>
    <script type="text/javascript" src="../../js/jquery.pjax.js"></script>
    <script type="text/javascript" src="../../js/plugins/datatables.js"></script>
    <script type="text/javascript" src="../../js/plugins/dataTables/dataTables.responsive.js"></script>
    <script type="text/javascript" src="../../js/plugins/dataTables/responsive.bootstrap.js"></script>
    <script type="text/javascript" src="../../js/plugins/dataTables/jquery.tabledisplay.js"></script>
    <?php
    if (!isset($_SESSION['admin_id'])) {
        echo "<script>$(document).ready(() => {window.location.replace(\"../../login\");});</script>";
    } elseif ($_SESSION['admin_type'] != 3) {
        echo "<script>$(document).ready(() => {window.location.replace(\"../../dashboard\");});</script>";
    }
    ?>
</head>

<body>
    <?php
    $conn = oci_connect('fin_admin', '123456', '47.95.212.18/ORCL', "AL32UTF8"); //连接oracle数据库
    ?>
    <div class="container">
        <header class="head-content">
            <div class="site-branding">
                <a href="javascript:void(0);" class="avatar-small">
                    <div class="menu-button">
                        <i class="iconfont icon-menu"></i>
                    </div>
                </a>
                <div class="site-title">
                    <a herf="../../dashboard" rel="home">餐饮店管理系统</a>
                    <h5>财务管理系统</h5>
                </div>
            </div>
        </header>
        <div class="main-content">
            <div class="bar-box">
                <aside class="left-bar">
                    <div class="admin-box">
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
                        echo "<img class=\"userPic\" src=\"" . $_SESSION['admin_pic'] . "?" . mt_rand(10000, 99999) . "\" /><h4 class=\"online-user\">" . $_SESSION['admin_name'] . "</h4><i class=\"iconfont icon-certificated\" style=\"color: #1afa29;\"></i><h5 class=\"user-type\">" . $admin_type . "</h5>";
                        ?>
                    </div>
                    <section class="sidebar">
                        <ul class="sidebar-menu">
                            <li class="treeview active">
                                <a href="javascript:void(0);">
                                    <i class="iconfont icon-finance"></i>
                                    <span>开销管理</span>
                                    <span class="pull-right">
                                        <i class="iconfont icon-down-arrow" style="font-size:12px;"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu menu-open">
                                    <li>
                                        <a id="menu-overheadList-item" href="javascript:void(0);">
                                            <i class="iconfont icon-overhead-list"></i>今日开销</a>
                                    </li>
                                    <li>
                                        <a id="menu-overheadHistory-item" href="javascript:void(0);" class="innerActive">
                                            <i class="iconfont icon-list-search"></i>历史查询</a>
                                    </li>
                                    <li>
                                        <a id="menu-addOverhead-item" href="javascript:void(0);">
                                            <i class="iconfont icon-overhead-add"></i>增加开销</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="treeview">
                                <a href="javascript:void(0);">
                                    <i class="iconfont icon-sale"></i>
                                    <span>销售管理</span>
                                    <span class="pull-right">
                                        <i class="iconfont icon-down-arrow" style="font-size:12px;"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li>
                                        <a id="menu-orderList-item" href="javascript:void(0);">
                                            <i class="iconfont icon-order"></i>查看今日订单</a>
                                    </li>
                                    <li>
                                        <a id="menu-orderHistory-item" href="javascript:void(0);">
                                            <i class="iconfont icon-list-search"></i>查询历史订单</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="treeview">
                                <a href="javascript:void(0);">
                                    <i class="iconfont icon-salary"></i>
                                    <span>工资管理</span>
                                    <span class="pull-right">
                                        <i class="iconfont icon-down-arrow" style="font-size:12px;"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li>
                                        <a id="menu-salaryList-item" href="javascript:void(0);">
                                            <i class="iconfont icon-list"></i>员工工资管理</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="treeview">
                                <a href="javascript:void(0);" onclick="goBack()">
                                    <i class="iconfont icon-back"></i>
                                    <span>返回主页</span>
                                </a>
                            </li>
                        </ul>
                    </section>
                    <script src="../../js/sidebar-menu.js"></script>
                    <script>
                        $.sidebarMenu($('.sidebar-menu'))
                    </script>
                </aside>
                <div class="mask"></div>
                <div class="main-bar">
                    <div class="title">
                        <h4 class="title-left">今日开销</h4>
                    </div>
                    <div class="box-wrap">
                        <div class="box">
                            <div class="inner-top-wrap"></div>
                            <div class="inner-box">
                                <table class="overheadHistoryTable" style="display: none;">
                                    <thead>
                                        <tr>
                                            <th>序号</th>
                                            <th>开销类型</th>
                                            <th>总金额</th>
                                            <th>开销日期</th>
                                        </tr>
                                    </thead>
                                    <tbody class="overheadHistoryTableBody">
                                    <?php
                                        $today=date("Y-m-d");
                                        $sql_query = "SELECT overhead_type,overhead_price,overhead_date,ove_invoice_pic FROM SCOTT.purRead ORDER BY OVERHEAD_DATE DESC";
                                        $statement = oci_parse($conn, $sql_query);
                                        oci_execute($statement);
                                        $count = 0;
                                        while ($row = oci_fetch_array($statement, OCI_RETURN_NULLS)) { //查询结果集
                                            $count++;
                                            $overhead_type = $row[0];
                                            if ($overhead_type == 1) {
                                                $overhead_type = "进货";
                                            } elseif ($overhead_type == 2) {
                                                $overhead_type = "水电费";
                                            } elseif ($overhead_type == 3) {
                                                $overhead_type = "房租";
                                            } elseif ($overhead_type == 4) {
                                                $overhead_type = "其他";
                                            }
                                            $overhead_price = $row[1];
                                            $overhead_date = $row[2];
                                            $ove_invoice_pic = $row[3];
                                            echo "<tr><td>$count</td><td>$overhead_type</td><td>$overhead_price</td><td>$overhead_date</td></tr>";
                                        }
                                        ?>
                                        <script>
                                        $(document).ready(() => {
                                            $(".overheadHistoryTable").DataTable({
                                                autoWidth: true,
                                                responsive: true
                                            });
                                            $(".overheadHistoryTable").displayInfo();
                                            $(".overheadHistoryTable").show();
                                        })
                                        </script>
                                    </tbody>
                                </table>
                                
                            </div>
                        </div>
                    </div>
                    <script>
                        $(document).ready(() => {
                            function changeMainBar(itemName) {
                                $("#menu-" + itemName + "-item").click(() => {
                                    $.pjax({
                                        url: "../" + itemName,
                                        container: '.main-bar'
                                    });
                                });
                            }
                            //
                            changeMainBar("overheadList");
                            changeMainBar("addOverhead");
                            changeMainBar("orderList");
                            changeMainBar("orderHistory");
                            changeMainBar("salaryList");
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