<!-- /fin_mgment/salaryList -->

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
    <style>
    .update-salary {
        margin-left: 9px;
        padding-left: 9px;
        border-left: 2px solid #ccc !important;
    }
    
    .update-salary-box {
        position: absolute;
        left: 50%;
        top: 50%;
        margin-left: -160px;
        margin-top: -200px;
        z-index: 100;
        width: 320px;
        padding: 20px;
        border-radius: 1px;
        color: #000;
        background-color: #fff;
    }

    .update-salary-box .subform {
        padding: 10px 10px 20px 10px;
    }

    .update-salary-box h4 {
        padding: 0 10px;
    }

    .update-salary-box .form-group input {
        margin: auto;
        border: 0;
        width: 100%;
    }

    .update-salary-box .form-group input#salary {
        border-bottom: 1px solid #4b9cfa;
    }

    .update-salary-box input.error {
        border-bottom: 1px solid red !important;
    }

    .update-salary-box label.error {
        width: 100%;
    }

    .update-salary-box .btn-area {
        text-align: right;
        margin: 0;
        padding: 15px 0 !important;
    }

    .update-salary-box button {
        display: inline-block;
        border-radius: 1px;
        border: 0.5px solid #cccccc;
        border-bottom: 0.5px solid #b2b2b2;
        background-color: rgba(0, 0, 0, 0.2);
        text-align: center;
        margin-top: 10px;
        height: 30px;
        width: 100px;
        color: #303a4d;
    }

    .update-salary-box .submit {
        display: inline-block;
        width: 100px !important;
        color: #fff;
        background-color: #0067b8 !important;
    }
    </style>
</head>

<body>
    <?php
    $conn = oci_connect('emp_admin', '123456', '47.95.212.18/ORCL', "AL32UTF8"); //连接oracle数据库
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
                            <li class="treeview active">
                                <a href="javascript:void(0);">
                                    <i class="iconfont icon-salary"></i>
                                    <span>工资管理</span>
                                    <span class="pull-right">
                                        <i class="iconfont icon-down-arrow" style="font-size:12px;"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu menu-open">
                                    <li>
                                        <a id="menu-salaryList-item" href="javascript:void(0);" class="innerActive">
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
                        <h4 class="title-left">员工工资管理</h4>
                    </div>
                    <div class="box-wrap">
                        <div class="box">
                            <div class="inner-top-wrap"></div>
                            <div class="inner-box">
                                <table class="employeeListTable" style="display: none;">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>姓名</th>
                                            <th>性别</th>
                                            <th>工龄（年）</th>
                                            <th>年龄</th>
                                            <th>手机号</th>
                                            <th>类别</th>
                                            <th>聘用日期</th>
                                            <th>工资</th>
                                        </tr>
                                    </thead>
                                    <tbody class="employeeListTableBody">
                                        <?php
                                        $sql_query = "SELECT EMPLOYEE_ID,NAME,GENDER,WORKING_YEAR,AGE,SALARY,PHONE_NUM,EMPLOYEE_TYPE,EMPLOY_TIME FROM SCOTT.empRead ORDER BY EMPLOYEE_TYPE ASC,EMPLOY_TIME DESC,WORKING_YEAR DESC";
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
                                            } elseif ($gender == 0) {
                                                $gender = "女";
                                            }
                                            //
                                            if (strpos($working_year, ".") == 0) {
                                                $working_year = "0" . $working_year;
                                            }
                                            //
                                            if ($employee_type == 1) {
                                                $employee_type = "管理人员";
                                            } elseif ($employee_type == 2) {
                                                $employee_type = "服务员";
                                            } elseif ($employee_type == 3) {
                                                $employee_type = "前台";
                                            } elseif ($employee_type == 4) {
                                                $employee_type = "厨师";
                                            } elseif ($employee_type == 5) {
                                                $employee_type = "保洁";
                                            } elseif ($employee_type == 6) {
                                                $employee_type = "仓库管理员";
                                            } elseif ($employee_type == 7) {
                                                $employee_type = "会计";
                                            } elseif ($employee_type == 8) {
                                                $employee_type = "其他";
                                            }
                                            //
                                            echo "<tr><td>$employee_id</td><td>$name</td><td>$gender</td><td>$working_year</td><td>$age</td><td>$phone_num</td><td>$employee_type</td><td>$employ_time</td><td>$salary<a class=\"table-update-btn update-salary\" href = \"javascript:void(0);\" onclick=\"update_salary('" . $employee_id . "','".$name."','".$salary."')\"><i class=\"iconfont icon-update\"></i></a></td></tr>";
                                        }
                                        ?>
                                        <script>
                                        $(document).ready(() => {
                                            $(".employeeListTable").DataTable({
                                                autoWidth: true,
                                                responsive: true
                                            });
                                            $(".employeeListTable").displayInfo();
                                            $(".employeeListTable").show();
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
                            changeMainBar("orderList");
                            changeMainBar("orderHistory");
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
        <div class="shelter" onclick="hideBox()" style="background-color: rgba(0, 0, 0, 0.5); z-index: 100; display: none;"></div>
    </div>
    <script type="text/javascript" src="../../js/plugins/jquery.validate.min.js"></script>
    <script type="text/javascript" src="../../js/fin_mgment/fin.update.validate.js"></script>
    <div class="update-salary-box" style="display: none;">
        <h3>修改工资</h3>
        <hr>
        <h4 class="emp_name"></h4>
        <form id="updateSalary-form" method="POST">
            <div class="subform">
                <div class="form-group">
                    <div class="section_title">原工资</div>
                    <input id="oldSalary" type="text" class="input-text form-control" name="oldSalary" value="" disabled="disabled" />
                </div>
                <div class="form-group">
                    <div class="section_title">修改后工资</div>
                    <input id="salary" type="text" class="input-text form-control" name="salary" placeholder="请输入工资" />
                </div>
                <div class="clear"></div>
                <div class="btn-area">
                    <button id="cancel" type="button" class="cancel" onclick="cancel()">取消</button>
                    <button id="submit" type="submit" class="submit">确认修改</button>
                </div>
            </div>
        </form>
    </div>
</body>


</html>