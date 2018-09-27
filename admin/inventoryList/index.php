<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>管理员系统-餐饮店管理系统</title>
    <link type="text/css" rel="stylesheet" href="../../css/bootstrap.css" />
    <link type="text/css" rel="stylesheet" href="../../css/iconfont.css" />
    <link type="text/css" rel="stylesheet" href="../../css/page.css" />
    <link type="text/css" rel="stylesheet" href="../../css/sidebar-menu.css" />
    <link type="text/css" rel="stylesheet" href="../../css/tablesorter.css" />


    <script type="text/javascript" src="../../js/jQuery/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="../../js/bootstrap.min.js"></script>
    <script type="text/javascript" src="../../js/page.js"></script>

    <script type="text/javascript" src="../../js/xcConfirm.js"></script>
    <script type="text/javascript" src="../../js/plugins/jquery.tablesorter.min.js"></script>
    <script type="text/javascript" src="../../js/plugins/jquery.filtertable.js"></script>
    <script type="text/javascript" src="../../js/jquery.pjax.js"></script>
    <?php
    if (!isset($_SESSION['admin_id'])) {
        echo "<script>$(document).ready(() => {window.location.replace(\"../../login\");});</script>";
    } elseif ($_SESSION['admin_type'] != 1 && $_SESSION['admin_type'] != 2) {
        echo "<script>$(document).ready(() => {window.location.replace(\"../../dashboard\");});</script>";
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
                            <li class="treeview active">
                                <a href="javascript:void(0);">
                                    <i class="iconfont icon-inventory"></i>
                                    <span>仓库管理</span>
                                    <span class="pull-right">
                                        <i class="iconfont icon-down-arrow" style="font-size:12px;"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu menu-open">
                                    <li>
                                        <a id="menu-inventoryList-item" href="javascript:void(0);" class="innerActive">
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
                            <li class="treeview">
                                <a href="javascript:void(0);">
                                    <i class="iconfont icon-order"></i>
                                    <span>订单管理</span>
                                    <span class="pull-right">
                                        <i class="iconfont icon-down-arrow" style="font-size:12px;"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li>
                                        <a id="menu-preOrderList-item" href="javascript:void(0);">
                                            <i class="iconfont icon-pre-order"></i>预定信息</a>
                                    </li>
                                    <li>
                                        <a id="menu-orderList-item" href="javascript:void(0);">
                                            <i class="iconfont icon-display"></i>查询订单
                                        </a>
                                    </li>
                                    <li>
                                        <a id="menu-orderHistory-item" href="javascript:void(0);">
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
                        <h4 class="title-left">查看仓库信息</h4>
                    </div>
                    <div class='box-wrap'>
                        <div class="box">
                            <div class="inner-top-wrap"></div>
                            <div class="inner-box">
                                <table class="inventoryListTable tablesorter result">
                                    <thead>
                                        <tr>
                                            <th>序号</th>
                                            <th>原料</th>
                                            <th>数量</th>
                                            <th>价格</th>
                                            <th>类型</th>
                                        </tr>
                                    </thead>
                                    <tbody class="inventoryListTableBody">
                                        <?php
                                        $sql_query = "SELECT INVENTORY_ID,GOODS_ID,QUANTITY FROM SCOTT.INVENTORY WHERE INV_STATUS>0";
                                        $statement = oci_parse($conn, $sql_query);
                                        oci_execute($statement);
                                        $count = 0;
                                        while ($row = oci_fetch_array($statement, OCI_RETURN_NULLS)) { //查询结果集
                                            $count++;
                                            $sql_query2 = "SELECT GOODS_NAME, GOODS_PRICE, GOODS_TYPE FROM SCOTT.GOODS WHERE GOO_STATUS>0 AND GOODS_ID = '$row[1]'";
                                            $statement2 = oci_parse($conn, $sql_query2);
                                            oci_execute($statement2);
                                            $row2 = oci_fetch_array($statement2, OCI_RETURN_NULLS);
                                            // $inventory_id = $row[0];
                                            // $goods_id = $row[1];
                                            // $quantity = $row[2];
                                            // $goods_name = $row2[1];
                                            // $goods_price = $row2[2];
                                            // $goods_type = $row2[3];
                                            // //使用一个数组放入一个员工的信息
                                            // $data_single = array("inventory_id" => $inventory_id, "goods_id" => $goods_id, "quantity" => $quantity, "goods_name" => $goods_name, "goods_price" => $goods_price, "goods_type" => $goods_type);
                                            // array_push($inv_data_array, $data_single); //将单个员工信息的数组添加到$emp_data_array中
                                            //echo "<tr><td>$row[1]</td></td>";
                                            if ($row2[2] == 1) {
                                                $row2[2] = "粮食";
                                            } elseif ($row2[2] == 2) {
                                                $row2[2] = "调料";
                                            } elseif ($row2[2] == 3) {
                                                $row2[2] = "生鲜";
                                            }
                                            echo "<tr><td>$count</td><td>$row2[0]</td><td>$row[2]</td><td>$row2[1]</td><td>$row2[2]</td></tr>";
                                        }
                                        ?>
                                        <script>
                                        $(() => {
                                            $(".inventoryListTable").tablesorter();
                                        });
                                        $(() => {
                                            $(".inventoryListTable").filterTable();
                                        });
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
                                        container: 'html'
                                    });
                                });
                            }
                            //
                            changeMainBar("employeeList");
                            changeMainBar("addEmployee");
                            changeMainBar("financeList");
                            changeMainBar("financeHistory");
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