<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>管理员系统-餐饮店管理系统</title>
    <link type="text/css" rel="stylesheet" href="../../css/bootstrap.css" />
    <link type="text/css" rel="stylesheet" href="../../css/iconfont.css" />
    <link type="text/css" rel="stylesheet" href="../../css/page.css" />
    <link type="text/css" rel="stylesheet" href="../../css/admin.css" />
    <link type="text/css" rel="stylesheet" href="../../css/sidebar-menu.css" />
    <link type="text/css" rel="stylesheet" href="../../css/tablesorter.css" />


    <script type="text/javascript" src="../../js/jQuery/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="../../js/bootstrap.min.js"></script>
    <script type="text/javascript" src="../../js/page.js"></script>
    
    <script type="text/javascript" src="../../js/xcConfirm.js"></script>
    <script type="text/javascript" src="../../js/jquery.tablesorter.min.js"></script>
    <script type="text/javascript" src="../../js/jquery.filtertable.js"></script>
    <script type="text/javascript" src="../../js/jquery.pjax.js"></script>
    <script type="text/javascript" src="main.js"></script>
    <style>
        .display-box-hide,.display-box {
                width: 0px;
                height: 0px;
                position: absolute;
                left: 50px !important;
                top: 0px;
                border: 0px solid #666;
                border-radius: 5px;
                background-color: rgba(255,255,255,0.85);
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
} else if ($_SESSION['admin_type'] != 1 && $_SESSION['admin_type'] != 2) {
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
} else if ($admin_type == 2) {
    $admin_type = "管理员";
} else if ($admin_type == 3) {
    $admin_type = "财务管理";
} else if ($admin_type == 4) {
    $admin_type = "港库管理";
}
echo "<img class=\"userPic\" src=\"" . $_SESSION['admin_pic'] . "?" . mt_rand(10000, 99999) . "\" /><h4 class=\"online-user\">" . $_SESSION['admin_name'] . "</h4><i class=\"iconfont icon-certificated\" style=\"color: #1afa29;\"></i><h5 class=\"user-type\">" . $admin_type . "</h5>";
?>
                    </div>
                    <section class="sidebar">
                        <ul class="sidebar-menu">
                            <!-- <li class="header">导航</li> -->
                            <li class="treeview active">
                                <a href="javascript:void(0);">
                                    <i class="iconfont icon-employee"></i>
                                    <span>员工管理</span>
                                    <span class="pull-right">
                                        <i class="iconfont icon-down-arrow" style="font-size:12px;"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu menu-open">
                                    <li>
                                        <a id="menu-employeeList-item" href="javascript:void(0);" class="innerActive">
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
                        <h4 class="title-left">员工列表</h4>
                    </div>
                    <div class='box-wrap'>
                        <div class="box">
                            <div class="inner-top-wrap"></div>
                            <div class="inner-box">
                                <table class="employeeListTable tablesorter result">
                                    <thead>
                                        <tr>
                                        <th>序号</th>
                                            <th>姓名</th>
                                            <th>性别</th>
                                            <th>工龄（年）</th>
                                            <th>年龄</th>
                                            <th>工资</th>
                                            <th>手机号</th>
                                            <th>类别</th>
                                            <th>聘用日期</th>
                                            <th>修改</th>
                                            <th>删除</th>
                                        </tr>
                                    </thead>
                                    <tbody class="employeeListTableBody">
<?php
$sql_query = "SELECT EMPLOYEE_ID,NAME,GENDER,WORKING_YEAR,AGE,SALARY,PHONE_NUM,EMPLOYEE_TYPE,EMPLOY_TIME FROM EMPLOYEE WHERE EMP_STATUS>0 ORDER BY EMPLOYEE_TYPE ASC,EMPLOY_TIME DESC,WORKING_YEAR DESC";
$statement = oci_parse($conn, $sql_query);
oci_execute($statement);
$count = 0;
while ($row = oci_fetch_array($statement, OCI_RETURN_NULLS)) { //查询结果集
    $count++;
    $employee_id = $row[0];
    $name = $row[1];
    $gender = $row[2];
    $working_year = $row[3];
    $age = $row[4];
    $salary = $row[5];
    $phone_num = $row[6];
    $employee_type = $row[7];
    $employ_time = $row[8];
    //
    if ($gender == 1) {
        $gender = "男";
    } else if ($gender == 0) {
        $gender = "女";
    }
    //
    if (strpos($working_year, ".") == 0) {
        $working_year = "0" . $working_year;
    }
    //
    if ($employee_type == 1) {
        $employee_type = "管理人员";
    } else if ($employee_type == 2) {
        $employee_type = "服务员";
    } else if ($employee_type == 3) {
        $employee_type = "前台";
    } else if ($employee_type == 4) {
        $employee_type = "厨师";
    } else if ($employee_type == 5) {
        $employee_type = "保洁";
    } else if ($employee_type == 6) {
        $employee_type = "仓库管理员";
    } else if ($employee_type == 7) {
        $employee_type = "会计";
    } else if ($employee_type == 8) {
        $employee_type = "其他";
    }
    //
    echo "<tr><td>$count</td><td><a href = \"javascript:void(0);\" onclick =\"display_employee(this,'" . $employee_id . "')\">$name</td><td>$gender</td><td>$working_year</td><td>$age</td><td>$salary</td><td>$phone_num</td><td>$employee_type</td><td>$employ_time</td><td><a class=\"table-update-btn update-employee\" href = \"javascript:void(0);\" onclick=\"update_employee('" . $employee_id . "')\"><i class=\"iconfont icon-update\"></i></a></td><td><a class=\"table-delete-btn delete-employee\" href = \"javascript:void(0);\" onclick=\"delete_employee('" . $employee_id . "')\"><i class=\"iconfont icon-delete\"></i></a></td></tr>";
}
?>
                                        <script>
                                        $(() => {
                                            $(".employeeListTable").tablesorter();
                                        });
                                        $(() => {
                                            $(".employeeListTable").filterTable();
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
                            function changeMainBar(itemName) {
                                $("#menu-" + itemName + "-item").click(() => {
                                    $.pjax({
                                        url: "../" + itemName,
                                        container: 'html'
                                    });
                                });
                            }
                            //
                            changeMainBar('addEmployee');
                            changeMainBar("financeList");
                            changeMainBar("financeHistory");
                            changeMainBar("inventoryList");
                            changeMainBar("purchaseList");
                            changeMainBar("lossList");
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
