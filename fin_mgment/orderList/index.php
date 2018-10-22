<!-- /fin_mgment/orderList -->

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
    <script type="text/javascript" src="../../js/jquery.tabledisplay.js"></script>
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
    $conn = oci_connect('ord_admin', '123456', 'localhost:1521/ORCL', "AL32UTF8"); //连接oracle数据库
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
                            <li class="treeview">
                                <a href="javascript:void(0);">
                                    <i class="iconfont icon-overview"></i>
                                    <span>总览</span>
                                    <span class="pull-right">
                                        <i class="iconfont icon-down-arrow" style="font-size:12px;"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li>
                                        <a id="menu-finOverview-item" href="javascript:void(0);">
                                            <i class="iconfont icon-list"></i>财务总览</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="treeview">
                                <a href="javascript:void(0);">
                                    <i class="iconfont icon-finance"></i>
                                    <span>开销管理</span>
                                    <span class="pull-right">
                                        <i class="iconfont icon-down-arrow" style="font-size:12px;"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li>
                                        <a id="menu-overheadList-item" href="javascript:void(0);">
                                            <i class="iconfont icon-overhead-list"></i>今日开销</a>
                                    </li>
                                    <li>
                                        <a id="menu-overheadHistory-item" href="javascript:void(0);">
                                            <i class="iconfont icon-list-search"></i>历史查询</a>
                                    </li>
                                    <li>
                                        <a id="menu-addOverhead-item" href="javascript:void(0);">
                                            <i class="iconfont icon-overhead-add"></i>增加开销</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="treeview active">
                                <a href="javascript:void(0);">
                                    <i class="iconfont icon-sale"></i>
                                    <span>销售管理</span>
                                    <span class="pull-right">
                                        <i class="iconfont icon-down-arrow" style="font-size:12px;"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu" menu-open>
                                    <li>
                                        <a id="menu-orderList-item" href="javascript:void(0);" class="innerActive">
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
                        <h4 class="title-left">查看今日订单</h4>
                    </div>
                    <div class="box-wrap">
                        <div class="box">
                            <div class="inner-top-wrap"></div>
                            <div class="inner-box">
                            <table class="orderListTable">
                                    <thead>
                                        <tr>
                                            <th>操作</th>
                                            <th>订单号</th>
                                            <th>下单时间</th>
                                            <th>总金额</th>
                                            <th>是否付款</th>
                                            <th>付款时间</th>
                                            <th>付款方式</th>
                                            <th>菜单</th>
                                            <th>餐桌</th>
                                            <th>备注</th>
                                        </tr>
                                    </thead>
                                    <tbody class="orderListTableBody">
                                        <?php
                                        $sql_query = "SELECT order_id,table_id,dish_list,total_price,pay_method,pay_time,order_note,pay_status FROM SCOTT.ORDER_LIST WHERE ORD_STATUS=1 AND SUBSTR(ORDER_ID,9,8)='".date("Ymd")."'";
                                        $statement = oci_parse($conn, $sql_query);
                                        oci_execute($statement);
                                        $count = 0;
                                        while ($row = oci_fetch_array($statement, OCI_RETURN_NULLS)) { //查询结果集
                                            $count++;
                                            $order_id = $row[0];
                                            $order_time=substr($order_id, 8, 4)."-".substr($order_id, 12, 2)."-".substr($order_id, 14, 2);
                                            $table_id = $row[1];
                                            $dish_list = $row[2];
                                            $total_price = $row[3];
                                            $pay_method = $row[4];
                                            if ($pay_method==1) {
                                                $pay_method = "现金";
                                            } elseif ($pay_method==2) {
                                                $pay_method="支付宝";
                                            } elseif ($pay_method==3) {
                                                $pay_method="微信";
                                            } else {
                                                $pay_method="未知方式";
                                            }
                                            $pay_time = $row[5];
                                            $order_note = $row[6];
                                            $pay_status = $row[7];
                                            if ($pay_status==1) {
                                                $pay_status="已付款";
                                            } elseif ($pay_status==0) {
                                                $pay_status="未付款";
                                            } else {
                                                $pay_status="未知";
                                            }
                                            $sql_query2 = "SELECT table_number FROM SCOTT.res_table WHERE table_id='$table_id'";
                                            $statement2 = oci_parse($conn, $sql_query2);
                                            oci_execute($statement2);
                                            $row2=oci_fetch_array($statement2, OCI_RETURN_NULLS);
                                            echo "<tr><td class='display-info'><i class=\"iconfont icon-down-arrow\"></i></td><td class='order_id'>$order_id</td><td>$order_time</td><td>$total_price</td><td>$pay_status</td><td>$pay_time</td><td>$pay_method</td><td>详情</td><td>$row2[0]</td><td>$order_note</td></tr>";
                                        }
                                        ?>
                                        <script>
                                        $(document).ready(() => {
                                            $(".orderListTable").DataTable({
                                                autoWidth: true,
                                                responsive: true
                                            });
                                            $(".orderListTable").displayInfo();
                                            $(".orderListTable").show();
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
                            changeMainBar("overheadHistory");
                            changeMainBar("addOverhead");
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