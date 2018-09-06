//获取referer，兼容IE
admin_id = "";

function goTo(url) {
    var ua = navigator.userAgent;
    if (ua.indexOf('MSIE') >= 0) {
        var rl = document.createElement('a');
        rl.href = url;
        document.body.appendChild(rl);
        rl.click();
    } else {
        location.href = url;
    }
}

function getReferer() {
    if (document.referrer) {
        return document.referrer;
    } else {
        return false;
    }
}

function GetRandomNum(Min, Max) {
    var Range = Max - Min;
    var Rand = Math.random();
    return (Min + Math.round(Rand * Range));
}
var num = GetRandomNum(10000, 99999);

$(document).ready(() => {
    //首先检查有无权限（用户是否登录及是否有管理员权限）
    $.ajax({
        type: "GET", //GET请求
        url: "../php/check_login.php?request=check", //后端接口
        dataType: "JSON", //返回的数据类型
        success: function(e) { //请求成功，e为返回的数据
            if (e.message == "online") {
                if (!getReferer()) { //IE不能直接得到referer，需要单独判断
                    goTo('?x=3&r=' + num + "/");
                } else {
                    var pre = "localhost";
                    if (getReferer().indexOf(pre) < 0) { //页面来自非本站，送回原来的位置
                        admin_id = "";
                        window.location.href = getReferer();
                        window.event.returnValue = false;
                    } else { //页面来自本站
                        if (e.admin_type > 2) {
                            //不是管理员
                            admin_id = "";
                            window.location.href = "../";
                            window.event.returnValue = false;
                        } else { //是管理员，下面加载各种数据
                            admin_id = e.admin_id;
                            var userPic = e.admin_pic;
                            var userName = e.admin_name;
                            var userType = e.admin_type;
                            if (userType == 1) {
                                userType = "超级管理员";
                            } else if (userType == 2) {
                                userType = "管理员";
                            } else if (userType == 3) {
                                userType = "财务管理";
                            } else if (userType == 4) {
                                userType = "港库管理";
                            }
                            if (userPic.indexOf("src/") > -1) {
                                $(".userPic").attr('src', userPic + "?" + num);
                                $(".online-user").html(userName);
                                $(".user-type").html(userType);
                            }
                            if (e.admin_type == 1) {
                                //是超级管理员，加载修改管理员信息的功能
                                addAdminMgment();
                            }
                            getEmployeeList();
                        }
                    }
                }
            } else if (e.message == "offline") {
                admin_id = "";
                window.location.href = "../";
                window.event.returnValue = false;
            }
        },
        error: function(err) {
            console.log(err);
        }
    });
});

function addAdminMgment() {
    var append_text = "<li class=\"treeview\"><a href=\"#\"><i class=\"iconfont icon-admin\"></i><span>管理员信息</span><span class=\"pull-right\"><i class=\"iconfont icon-down-arrow\" style=\"font-size:12px;\"></i></span></a><ul class=\"treeview-menu\" style=\"display: none;\"><li><a id=\"menu-adminList-item\" href=\"#adminList\"><i class=\"iconfont icon-list\"></i>管理员列表</a></li><li class=\"li-not-allowed\"><a id=\"menu-updateAdmin-item\" href=\"#updateAdmin\"><i class=\"iconfont icon-update-round\"></i>更新信息<i class=\"iconfont icon-not-allowed\"></i></a></li><li><a id=\"menu-addAdmin-item\" href=\"#addAdmin\"><i class=\"iconfont icon-add-paper\"></i>增加管理员</a></li></ul></li>";
    $(".employee-treeview").after(append_text);
    append_text = "<a name=\"#\"></a><div id=\"adminList-tab\" class=\"box\"><div class=\"title col-xs-12\"><h4 class=\"title-left\">查看管理员信息</h4></div></div><a name=\"#\"></a><div id=\"updateAdmin-tab\" class=\"box\"><div class=\"title col-xs-12\"><h4 class=\"title-left\">更新管理员信息</h4></div></div><a name=\"#\"></a><div id=\"addAdmin-tab\" class=\"box\"><div class=\"title col-xs-12\"><h4 class=\"title-left\">增加管理员</h4></div></div>"
    $("#addEmployee-tab").after(append_text);
}


function getEmployeeList() { //获得员工列表
    $.ajax({
        type: "GET",
        url: "../php/admin.php?request=getEmployeeList&admin_id=" + admin_id,
        dataType: "JSON",
        success: (emp_data) => {
            var i = 0;
            var append_text = "";
            while (emp_data[i]) {
                var j = i + 1;
                var employee_id = emp_data[i]['employee_id'];
                var name = emp_data[i]['name'];
                var gender = emp_data[i]['gender'];
                if (gender == 1) {
                    gender = "男"
                } else if (gender == 0) {
                    gender = "女"
                }
                var working_year = emp_data[i]['working_year'];
                if (working_year.indexOf(".") == 0) {
                    working_year = "0" + working_year;
                }
                var age = emp_data[i]['age'];
                var salary = emp_data[i]['salary'];
                var phone_num = emp_data[i]['phone_num'];
                var employee_type = emp_data[i]['employee_type'];
                if (employee_type == 1) {
                    employee_type = "管理人员";
                } else if (employee_type == 2) {
                    employee_type = "服务员";
                } else if (employee_type == 3) {
                    employee_type = "前台";
                } else if (employee_type == 4) {
                    employee_type = "厨师";
                } else if (employee_type == 5) {
                    employee_type = "保洁";
                } else if (employee_type == 6) {
                    employee_type = "仓库管理员";
                } else if (employee_type == 7) {
                    employee_type = "会计";
                } else if (employee_type == 8) {
                    employee_type = "其他";
                }
                var employ_time = emp_data[i]['employ_time'];
                append_text += "<tr><td>" + j + "</td><td><a href = \"javascript:void(0);\" onclick =\"display_employee('" + employee_id + "')\">" + name + "</a></td><td>" + gender + "</td><td>" + working_year + "</td><td>" + age + "</td><td>" + salary + "</td><td>" + phone_num + "</td><td>" + employee_type + "</td><td>" + employ_time + "</td><td><a class=\"table-update-btn update-employee\" href = \"javascript:void(0);\" onclick=\"update_employee('" + employee_id + "')\"><i class=\"iconfont icon-update\"></i></a></td></tr>"




                i++;
            }
            $(".employeeListTableBody").append(append_text);
            $(function() {
                $(".employeeListTable").tablesorter();
            });
            $(function() {
                $(".employeeListTable").filterTable();
            });
        }
    })
}