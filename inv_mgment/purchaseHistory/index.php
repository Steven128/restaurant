<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>仓库管理-餐饮店管理系统</title>
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
    } elseif ($_SESSION['admin_type'] != 4) {
        echo "<script>$(document).ready(() => {window.location.replace(\"../../dashboard\");});</script>";
    }
    ?>
</head>

<body>
    <?php
    $conn = oci_connect('inv_admin', '123456', '47.95.212.18/ORCL', "AL32UTF8"); //连接oracle数据库
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
                    <h5>仓库管理系统</h5>
                </div>
            </div>
        </header>
        <div class="main-content">
            <div class="bar-box">
                <aside class="left-bar">
                    <div class="admin-box">
                        <img class="userPic" />
                        <h4 class="online-user"></h4>
                        <i class="iconfont icon-certificated" style="color: #1afa29;"></i>
                        <h5 class="user-type"></h5>
                    </div>
                    <section class="sidebar">
                        <ul class="sidebar-menu">
                            <li class="treeview">
                                <a href="javascript:void(0);">
                                    <i class="iconfont icon-inventory"></i>
                                    <span>库存管理</span>
                                    <span class="pull-right">
                                        <i class="iconfont icon-down-arrow" style="font-size:12px;"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li>
                                        <a id="menu-inventoryList-item" href="javascript:void(0);">
                                            <i class="iconfont icon-search"></i>当前库存查询</a>
                                    </li>
                                    <li>
                                        <a id="menu-inventoryWarring-item" href="javascript:void(0);">
                                            <i class="iconfont icon-warning"></i>库存预警</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="treeview active">
                                <a href="javascript:void(0);">
                                    <i class="iconfont icon-inventory2"></i>
                                    <span>入库管理</span>
                                    <span class="pull-right">
                                        <i class="iconfont icon-down-arrow" style="font-size:12px;"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu menu-open">
                                    <li>
                                        <a id="menu-addPurchase-item" href="javascript:void(0);">
                                            <i class="iconfont icon-add"></i>入库登记</a>
                                    </li>
                                    <li>
                                        <a id="menu-purchaseHistory-item" href="javascript:void(0);" class="innerActive">
                                            <i class="iconfont icon-list-search"></i>历史查询</a>
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
                        <h4 class="title-left">查看进货信息</h4>
                    </div>
                    <div class="box-wrap">
                        <div class="box">
                            <div class="inner-top-wrap"></div>
                            <div class="inner-box">
                <table class="purchaseListTable">
                                    <thead>
                                        <tr>
                                            <th>序号</th>
                                            <th>进货ID</th>
                                            <th>名字</th>
                                            <th>数量</th>
                                        </tr>
                                    </thead>
                                    <tbody class="inventoryListTableBody">
                                        <?php
                                        $sql_query = "SELECT inventory_id,goods_name,invRead.quantity FROM SCOTT.gooRead,scott.invRead WHERE invRead.goods_id=gooRead.goods_id";
                                        $statement = oci_parse($conn, $sql_query);
                                        oci_execute($statement);
                                        $count = 0;
                                        while ($row = oci_fetch_array($statement, OCI_RETURN_NULLS)) { //查询结果集
                                            $count++;
                                            $inventory_id = $row[0];
                                            $goods_name = $row[1];
                                            $quantity = $row[2];
                                            echo "<tr><td>$count</td><td>$inventory_id</td><td>$goods_name</td><td>$quantity</td></tr>";
                                        }
                                        ?>
                                        <script>
                                        $(document).ready(() => {
                                            $(".purchaseListTable").DataTable({
                                                autoWidth: true,
                                                responsive: true
                                            });
                                            $(".purchaseListTable").displayInfo();
                                            $(".purchaseListTable").show();
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
                            changeMainBar("inventoryList");
                            changeMainBar("inventoryWarring");
                            changeMainBar("addPurchase");
                        });
                    </script>
                    <div class="shelter" onclick="hideBox()" style="background-color: rgba(0,0,0,0.5);z-index: 100;"></div>
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