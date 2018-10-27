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
    <link type="text/css" rel="stylesheet" href="../css/bootstrap.css" />
    <link type="text/css" rel="stylesheet" href="../css/iconfont.css" />
    <link type="text/css" rel="stylesheet" href="../css/page.css" />
    <link type="text/css" rel="stylesheet" href="../css/sidebar-menu.css" />

    <script type="text/javascript" src="../js/jQuery/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="../js/bootstrap.min.js"></script>
    <script type="text/javascript" src="../js/page.js"></script>
    <script type="text/javascript" src="../js/xcConfirm.js"></script>
    <script type="text/javascript" src="../js/jquery.pjax.js"></script>
    <?php
    if (!isset($_SESSION['admin_id'])) {
        echo "<script>$(document).ready(() => {window.location.replace(\"../login\");});</script>";
    } elseif ($_SESSION['admin_type'] != 3) {
        echo "<script>$(document).ready(() => {window.location.replace(\"../dashboard\");});</script>";
    }
    ?>
</head>

<body>
    <?php
    $conn = oci_connect('scott', '123456', '47.95.212.18/ORCL', "AL32UTF8"); //连接oracle数据库
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
                    <a herf="../dashboard" rel="home">餐饮店管理系统</a>
                    <h5>财务管理系统</h5>
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
                            <li class="treeview active">
                                <a href="javascript:void(0);">
                                    <i class="iconfont icon-overview"></i>
                                    <span>总览</span>
                                    <span class="pull-right">
                                        <i class="iconfont icon-down-arrow" style="font-size:12px;"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu menu-open">
                                    <li>
                                        <a id="menu-finOverview-item" href="javascript:void(0);" class="innerActive">
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
                    <script src="../js/sidebar-menu.js"></script>
                    <script>
                        $.sidebarMenu($('.sidebar-menu'))
                    </script>
                </aside>
                <div class="mask"></div>
                <div class="main-bar">
                    <div class="title">
                        <h4 class="title-left">更新员工信息</h4>
                    </div>
                    <div class="box-wrap">

                    </div>
                    <script>
                        $(document).ready(() => {
                            window.location.replace("finOverview");

                            function changeMainBar(itemName) {
                                $("#menu-" + itemName + "-item").click(() => {
                                    $.pjax({
                                        url: "" + itemName,
                                        container: '.main-bar'
                                    });
                                });
                            }
                            //
                            changeMainBar("finOverview");
                            changeMainBar("overheadList");
                            changeMainBar("overheadHistory");
                            changeMainBar("addOverhead");
                            changeMainBar("orderList");
                            changeMainBar("orderHistory");
                            changeMainBar("salaryList");
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