<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
<<<<<<< HEAD
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
=======
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
>>>>>>> d34d8d51ebc55e75be64474c6973e6cb5a78ad38
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
    <script type="text/javascript" src="../../js/plugins/jquery.tablesorter.min.js"></script>
    <script type="text/javascript" src="../../js/plugins/jquery.filtertable.js"></script>
    <?php
    if (!isset($_SESSION['admin_id'])) {
        echo "<script>$(document).ready(() => {window.location.replace(\"../../login\");});</script>";
    } elseif ($_SESSION['admin_type'] != 1) {
        echo "<script>$(document).ready(() => {window.location.replace(\"../overview\");});</script>";
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
                <?php echo "<div class=\"user-pic-wrap\"><img class=\"userPic\" src=\"" . $_SESSION['admin_pic'] . "\" /></div><div class=\"user-name\">" . $_SESSION['admin_name'] . "</div>"; ?>
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
                    echo "<div class=\"user-pic-wrap\"><img class=\"userPic\" src=\"" . $_SESSION['admin_pic'] . "\" /></div><div class=\"user-info-wrap\"><div class=\"user-name\">" . $_SESSION['admin_name'] . "</div><div class=\"user-type\">" . $admin_type . "</div></div>";
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
                <div class="main-bar">
                    <div class="title">
                        <h4 class="title-left">管理员列表</h4>
                    </div>
                    <div class='box-wrap'>
                        <div class="box">
                            <div class="inner-top-wrap"></div>
                            <div class="inner-box">
                                <table class="adminListTable tablesorter result">
                                    <thead>
                                        <tr>
                                            <th>序号</th>
                                            <th>头像</th>
                                            <th>姓名</th>
                                            <th>类别</th>
                                            <th>创建日期</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody class="employeeListTableBody">
                                        <?php
                                        $sql_query = "SELECT ADMIN_ID,ADMIN_NAME,ADMIN_TYPE,CREATE_TIME,ADMIN_PIC FROM SCOTT.ADMIN WHERE ADM_STATUS>0 ORDER BY ADMIN_TYPE ASC,ADMIN_NAME ASC";
                                        $statement = oci_parse($conn, $sql_query);
                                        oci_execute($statement);
                                        $count = 0;
                                        while ($row = oci_fetch_array($statement, OCI_RETURN_NULLS)) { //查询结果集
                                            $count++;
                                            $admin_id = $row[0];
                                            $name = $row[1];
                                            $admin_type = $row[2];
                                            $create_time = $row[3];
                                            $admin_pic = $row[4];
                                            //
                                            if ($admin_type == 1) {
                                                $admin_type = "超级管理员";
                                            } elseif ($admin_type == 2) {
                                                $admin_type = "管理员";
                                            } elseif ($admin_type == 3) {
                                                $admin_type = "财务管理员";
                                            } elseif ($admin_type == 4) {
                                                $admin_type = "仓库管理员";
                                            }
                                            //
                                            echo "<tr><td>$count</td><td><img src=\"$admin_pic\" / width=\"50px\" height=\"50px\"></td><td>$name</td><td>$admin_type</td><td>$create_time</td><td><a class=\"table-update-btn update-admin\" href = \"javascript:void(0);\" onclick=\"update_admin('" . $admin_id . "')\"><i class=\"iconfont icon-update\"></i></a></td></tr>";
                                        }
                                        ?>
                                        <script>
                                        $(() => {
                                            $(".adminListTable").tablesorter();
                                        });
                                        $(() => {
                                            $(".adminListTable").filterTable();
                                        });
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
                            barAppend("adminList");

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