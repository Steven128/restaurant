<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>管理员系统-餐饮店管理系统</title>
    <link type="text/css" rel="stylesheet" href="../../css/bootstrap.css" />
    <link type="text/css" rel="stylesheet" href="../../css/iconfont.css" />
    <link type="text/css" rel="stylesheet" href="../../css/page.css" />
    <link type="text/css" rel="stylesheet" href="../../css/sidebar-menu.css" />
    <link type="text/css" rel="stylesheet" href="../../css/datatables.css" />
    <link type="text/css" rel="stylesheet" href="../../css/table_plugins/responsive.bootstrap.css" />

    <script type="text/javascript" src="../../js/jQuery/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="../../js/bootstrap.min.js"></script>
    <script type="text/javascript" src="../../js/page.js"></script>
    <script type="text/javascript" src="../../js/adm_mgment/admin.js"></script>
    <script type="text/javascript" src="../../js/xcConfirm.js"></script>
    <script type="text/javascript" src="../../js/jquery.pjax.js"></script>
    <script type="text/javascript" src="../../js/plugins/datatables.js"></script>
    <script type="text/javascript" src="../../js/plugins/dataTables/dataTables.responsive.js"></script>
    <script type="text/javascript" src="../../js/plugins/dataTables/responsive.bootstrap.js"></script>
    <script type="text/javascript" src="../../js/jquery.tabledisplay.js"></script>
    <style>
        .display-box-hide,
        .display-box {
            width: 0px;
            height: 0px;
            position: absolute;
            left: 50px !important;
            top: 0px;
            border: 0px solid #666;
            border-radius: 5px;
            background-color: rgba(255, 255, 255, 0.85);
            z-index: 10;
        }

        .display-box:before {
            position: absolute;
            content: "";
            width: 0;
            height: 0;
            left: 30px;
            top: -29px;
            border-bottom: 30px solid #666;
            border-left: 10px solid transparent;
            border-right: 10px solid transparent;
        }

        .display-box:after {
            position: absolute;
            content: "";
            width: 0;
            height: 0;
            left: 30px;
            top: -26px;
            border-bottom: 30px solid #fff;
            border-left: 10px solid transparent;
            border-right: 10px solid transparent;
        }

        .display-box-hide:before {
            display: "none";
            content: "";
        }

        .display-box-hide:after {
            display: "none";
            content: "";
        }

        @media(min-width:768px) {
            .display-box:before {
                left: -30px;
                top: 23px;
                border-right: 30px solid #666;
                border-top: 10px solid transparent;
                border-bottom: 10px solid transparent;
            }

            .display-box:after {
                left: -27px;
                top: 23px;
                border-right: 30px solid #fff;
                border-top: 10px solid transparent;
                border-bottom: 10px solid transparent;
            }
        }
    </style>
    <?php
    if (!isset($_SESSION['admin_id'])) {
        echo "<script>$(document).ready(() => {window.location.replace(\"../../login\");});</script>";
    } elseif ($_SESSION['admin_type'] != 1 && $_SESSION['admin_type'] != 2) {
        echo "<script>$(document).ready(() => {
                window.location.replace(\"../../dashboard\");});</script>";
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
                    <h5>管理员系统</h5>
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
                            <!-- <li class="header">导航</li> -->
                            <li class="treeview">
                                <a href="javascript:void(0);">
                                    <i class="iconfont icon-employee"></i>
                                    <span>员工管理</span>
                                    <span class="pull-right">
                                        <i class="iconfont icon-down-arrow" style="font-size:12px;"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li>
                                        <a id="menu-employeeList-item" href="javascript:void(0);">
                                            <i class="iconfont icon-list"></i>员工列表</a>
                                    </li>
                                    <li>
                                        <a id="menu-presenceList-item" href="javascript:void(0);">
                                            <i class="iconfont icon-presence"></i>出勤查询</a>
                                    </li>
                                    <li>
                                        <a id="menu-addEmployee-item" href="javascript:void(0);">
                                            <i class="iconfont icon-add-paper"></i>增加员工</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="treeview">
                                <a href="javascript:void(0);">
                                    <i class="iconfont icon-finance"></i>
                                    <span>财务管理</span>
                                    <span class="pull-right">
                                        <i class="iconfont icon-down-arrow" style="font-size:12px;"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li>
                                        <a id="menu-financeList-item" href="javascript:void(0);">
                                            <i class="iconfont icon-list"></i>查看财务信息</a>
                                    </li>
                                    <li>
                                        <a id="menu-financeHistory-item" href="javascript:void(0);">
                                            <i class="iconfont icon-list-search"></i>查询历史财务</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="treeview">
                                <a href="javascript:void(0);">
                                    <i class="iconfont icon-inventory"></i>
                                    <span>仓库管理</span>
                                    <span class="pull-right">
                                        <i class="iconfont icon-down-arrow" style="font-size:12px;"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li>
                                        <a id="menu-inventoryList-item" href="javascript:void(0);">
                                            <i class="iconfont icon-list"></i>查看仓库信息</a>
                                    </li>
                                    <li>
                                        <a id="menu-purchaseList-item" href="javascript:void(0);">
                                            <i class="iconfont icon-display"></i>查看进货信息</a>
                                    </li>
                                    <li>
                                        <a id="menu-lossList-item" href="javascript:void(0);">
                                            <i class="iconfont icon-display"></i>查看损耗信息 </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="treeview active">
                                <a href="javascript:void(0);">
                                    <i class="iconfont icon-order"></i>
                                    <span>订单管理</span>
                                    <span class="pull-right">
                                        <i class="iconfont icon-down-arrow" style="font-size:12px;"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu menu-open">
                                    <li>
                                        <a id="menu-preOrderList-item" href="javascript:void(0);">
                                            <i class="iconfont icon-pre-order"></i>预定信息</a>
                                    </li>
                                    <li>
                                        <a id="menu-orderList-item" href="javascript:void(0);">
                                            <i class="iconfont icon-display"></i>今日订单
                                        </a>
                                    </li>
                                    <li>
                                        <a id="menu-orderHistory-item" href="javascript:void(0);" class="innerActive">
                                            <i class="iconfont icon-history"></i>历史订单</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="treeview">
                                <a href="javascript:void(0);">
                                    <i class="iconfont icon-dish"></i>
                                    <span>菜单管理</span>
                                    <span class="pull-right">
                                        <i class="iconfont icon-down-arrow" style="font-size:12px;"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li>
                                        <a id="menu-dishList-item" href="javascript:void(0);">
                                            <i class="iconfont icon-list"></i>查看菜单</a>
                                    </li>
                                    <li>
                                        <a id="menu-addDish-item" href="javascript:void(0);">
                                            <i class="iconfont icon-add"></i>增加菜品</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="treeview">
                                <a href="javascript:void(0);">
                                    <i class="iconfont icon-table"></i>
                                    <span>餐桌管理</span>
                                    <span class="pull-right">
                                        <i class="iconfont icon-down-arrow" style="font-size:12px;"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li>
                                        <a id="menu-tableList-item" href="javascript:void(0);">
                                            <i class="iconfont icon-list"></i>查看餐桌信息</a>
                                    </li>
                                    <li>
                                        <a id="menu-addTable-item" href="javascript:void(0);">
                                            <i class="iconfont icon-update"></i>增加餐桌</a>
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
                        <h4 class="title-left">历史订单查询</h4>
                    </div>
                    <div class="box-wrap">
                        <div class="box">
                            <div class="inner-top-wrap"></div>
                            <div class="inner-box">
                            <table class="orderListTable" style="display: none;">
                                    <thead>
                                        <tr>
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
                                    <tbody class="preOrderListTableBody">
                                        <?php
                                        $sql_query = "SELECT order_id,scott.res_table.table_number,dish_list,total_price,pay_method,pay_time,order_note,pay_status FROM SCOTT.ORDER_LIST,SCOTT.RES_TABLE WHERE ORD_STATUS=1 AND TAB_STATUS=1 AND SCOTT.ORDER_LIST.TABLE_ID=SCOTT.RES_TABLE.TABLE_ID AND SUBSTR(ORDER_ID,9,8)!='".date("Ymd")."' ORDER BY pay_time DESC";                                        
                                        $statement = oci_parse($conn, $sql_query);
                                        oci_execute($statement);
                                        $count = 0;
                                        while ($row = oci_fetch_array($statement, OCI_RETURN_NULLS)) { //查询结果集
                                            $count++;
                                            $order_id = $row[0];
                                            $order_time=substr($order_id,8,4)."-".substr($order_id,12,2)."-".substr($order_id,14,2);
                                            $table_number=$row[1];
                                            $dish_list = $row[2];
                                            $total_price = $row[3];
                                            $pay_method = $row[4];
                                            if($pay_method==1)
                                                $pay_method = "现金";
                                            elseif($pay_method==2)
                                                $pay_method="支付宝";
                                            elseif($pay_method==3)
                                                $pay_method="微信";
                                            else
                                                $pay_method="未知方式";
                                            $pay_time = $row[5];
                                            $order_note = $row[6];
                                            $pay_status = $row[7];
                                            if($pay_status==1)
                                                $pay_status="已付款";
                                            elseif($pay_status==0)
                                                $pay_status="未付款";
                                            else
                                                $pay_status="未知";
                                            echo "<tr><td>$order_id</td><td>$order_time</td><td>$total_price</td><td>$pay_status</td><td>$pay_time</td><td>$pay_method</td><td>详情</td><td>$table_number</td><td>$order_note</td></tr>";
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
                                    <!-- <div class="display-box-hide"></div> -->
                                </table>
                                <div id="back_to_top">
                                    <i class="iconfont icon-up-arrow"></i>
                                </div>
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
                            changeMainBar('addEmployee');
                            changeMainBar('presenceList');
                            changeMainBar("financeList");
                            changeMainBar("financeHistory");
                            changeMainBar("inventoryList");
                            changeMainBar("purchaseList");
                            changeMainBar("lossList");
                            changeMainBar("preOrderList");
                            changeMainBar("orderList");
                            changeMainBar("orderHistory");
                            changeMainBar("dishList");
                            changeMainBar("addDish");
                            changeMainBar("tableList");
                            changeMainBar("addTable");
                        });
                    </script>
                    <div class="shelter" onclick="hideBox()"></div>
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