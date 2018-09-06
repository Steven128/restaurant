$(document).ready(() => {
    //检查用户是否已经登录
    function GetRandomNum(Min, Max) {
        var Range = Max - Min;
        var Rand = Math.random();
        return (Min + Math.round(Rand * Range));
    }
    var num = GetRandomNum(10000, 99999);
    $.ajax({
        type: "GET",
        url: "../php/check_login.php?request=check",
        dataType: "JSON",
        success: (e) => {
            console.log(e)
            if (e.message == "online") {
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
                    userType = "库存管理";
                }
                if (userPic.indexOf("src/") > -1) {
                    $(".userPic").attr('src', userPic + "?" + num);
                    $(".user-name").html(userName);
                    $(".user-type").html(userType);
                    $(".user-online").css("display", "block");
                    $(".user-offline").css("display", "none");
                }
                if (userType == "超级管理员") {
                    addRootMgment();
                } else if (userType == "管理员") {
                    addRootMgment();
                } else if (userType == "财务管理") {
                    addRootMgment();
                } else if (userType == "库存管理") {
                    addRootMgment();
                }

            } else if (e.message == "offline") {
                $(".userPic").attr('src', '');
                $(".user-name").html('');
                $(".user-type").html('');
                $(".user-online").css("display", "none");
                $(".user-offline").css("display", "block");
                window.location.href = "../login/";
            }
        },
        error: (err) => {}
    })
});

function showInfoBox() {
    $(".info-box").css("display", "block");
    $(".shelter").css("display", "block");
}

function hideShelter() {
    $(".info-box").css("display", "none");
    $(".shelter").css("display", "none");
}

function logout() {
    $.ajax({
        type: "GET",
        url: "../php/check_login.php?request=logout",
        dataType: "JSON",
        success: (e) => {
            if (e.message == "success logout") {
                window.location.href = "../login/";
            }
        },
        error: (err) => {}
    })
}

function addRootMgment() {
    var append_text = "<li class=\"treeview\"><a href=\"../admin/\"><i class=\"iconfont icon-mgment\"></i><span>店面管理</span><span class=\"dash-pull-right\"><i class=\"iconfont icon-right-arrow\" style=\"font-size:12px;\"></i></span></a></li>";
    append_text += "<li class=\"treeview\"><a id=\"menu-adminList-item\" href=\"#\"><i class=\"iconfont icon-account\"></i><span>管理员信息</span><span class=\"dash-pull-right\"><i class=\"iconfont icon-right-arrow\" style=\"font-size:12px;\"></i></span></a><ul class=\"treeview-menu\" style=\"display: none;\"></ul></li>";
    append_text += "<li class=\"treeview\"><a id=\"menu-addAdmin-item\" href=\"#\"><i class=\"iconfont icon-account-plus\"></i><span>添加管理员</span><span class=\"dash-pull-right\"><i class=\"iconfont icon-right-arrow\" style=\"font-size:12px;\"></i></span></a><ul class=\"treeview-menu\" style=\"display: none;\"></ul></li>";
    $(".overview-treeview").after(append_text);
    append_text = "<a name=\"#\"></a><div id=\"adminList-tab\" class=\"box\"><div class=\"title col-xs-12\"><h4 class=\"title-left\">查看管理员信息</h4></div></div><a name=\"#\"></a><div id=\"addAdmin-tab\" class=\"box\"><div class=\"title col-xs-12\"><h4 class=\"title-left\">增加管理员</h4></div></div>"
    $("#overview-tab").after(append_text);
}

function addFinanceMgment() {
    var append_text = "<li class=\"treeview\"><a href=\"../fin_mgment/\"><i class=\"iconfont icon-mgment\"></i><span>财务管理</span><span class=\"dash-pull-right\"><i class=\"iconfont icon-right-arrow\" style=\"font-size:12px;\"></i></span></a></li>";
    $(".overview-treeview").after(append_text);
}

function addInventoryMgment() {
    var append_text = "<li class=\"treeview\"><a href=\"../inv_mgment/\"><i class=\"iconfont icon-mgment\"></i><span>库存管理</span><span class=\"dash-pull-right\"><i class=\"iconfont icon-right-arrow\" style=\"font-size:12px;\"></i></span></a></li>";
    $(".overview-treeview").after(append_text);
}

function addAdminMgment() {
    var append_text = "<li class=\"treeview\"><a href=\"../admin/\"><i class=\"iconfont icon-mgment\"></i><span>店面管理</span><span class=\"dash-pull-right\"><i class=\"iconfont icon-right-arrow\" style=\"font-size:12px;\"></i></span></a></li>";
    $(".overview-treeview").after(append_text);
}