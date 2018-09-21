function barAppend(treeName) {
    $.ajax({
        type: "GET",
        url: "../../php/check_login.php?request=getUserInfo&admin_id=" + getUserInfo().admin_id,
        dataType: "JSON",
        success: (e) => {
            $(".userPic").attr('src', e.admin_pic + "?" + num);
            $(".user-name").html(e.admin_name);
            admin_type = e.admin_type;
            if (admin_type == 1) {
                admin_type = "超级管理员";
                addRootMgment();
            } else if ($admin_type == 2) {
                admin_type = "管理员";
                addAdminMgment();
            } else if ($admin_type == 3) {
                admin_type = "财务管理";
                addFinanceMgment();
            } else if ($admin_type == 4) {
                admin_type = "港库管理";
                addInventoryMgment();
            }
            $(".user-type").html(admin_type);
            $(".user-create-time").html("账号创建时间：" + e.create_time.substring(0, 4) + "年" + e.create_time.substring(5, 7) + "月" + e.create_time.substring(8, 10) + "日");
            $("." + treeName + "-tree").addClass("active");
        },
        error: (err) => {
            console.log(err)
        }
    })
}

function showInfoBox() {
    $(".info-box").show();
    $(".shelter").show();
}

function hideShelter() {
    $(".info-box").hide();
    $(".shelter").hide();
}

function addRootMgment() {
    var append_text = "<li class=\"treeview\"><a href=\"../../admin/\"><i class=\"iconfont icon-mgment\"></i><span>店面管理</span><span class=\"dash-pull-right\"><i class=\"iconfont icon-right-arrow\" style=\"font-size:12px;\"></i></span></a></li>";
    append_text += "<li class=\"treeview adminList-tree\"><a id=\"menu-adminList-item\" href=\"javascript:void(0);\"><i class=\"iconfont icon-account\"></i><span>管理员信息</span><span class=\"dash-pull-right\"><i class=\"iconfont icon-right-arrow\" style=\"font-size:12px;\"></i></span></a></li>";
    append_text += "<li class=\"treeview addAdmin-tree\"><a id=\"menu-addAdmin-item\" href=\"javascript:void(0);\"><i class=\"iconfont icon-account-plus\"></i><span>添加管理员</span><span class=\"dash-pull-right\"><i class=\"iconfont icon-right-arrow\" style=\"font-size:12px;\"></i></span></a></li>";
    $(".overview-tree").after(append_text);
}

function addFinanceMgment() {
    var append_text = "<li class=\"treeview\"><a href=\"../../fin_mgment/\"><i class=\"iconfont icon-mgment\"></i><span>财务管理</span><span class=\"dash-pull-right\"><i class=\"iconfont icon-right-arrow\" style=\"font-size:12px;\"></i></span></a></li>";
    $(".overview-tree").after(append_text);
}

function addInventoryMgment() {
    var append_text = "<li class=\"treeview\"><a href=\"../../inv_mgment/\"><i class=\"iconfont icon-mgment\"></i><span>库存管理</span><span class=\"dash-pull-right\"><i class=\"iconfont icon-right-arrow\" style=\"font-size:12px;\"></i></span></a></li>";
    $(".overview-tree").after(append_text);
}

function addAdminMgment() {
    var append_text = "<li class=\"treeview\"><a href=\"../../admin/\"><i class=\"iconfont icon-mgment\"></i><span>店面管理</span><span class=\"dash-pull-right\"><i class=\"iconfont icon-right-arrow\" style=\"font-size:12px;\"></i></span></a></li>";
    $(".overview-tree").after(append_text);
}

function update_admin(admin_id) {
    admin_id = Encrypt(admin_id, "admin_id")
    var href = encodeURIComponent("admin_id=" + admin_id);
    $.pjax({
        url: "updateAdmin?" + href,
        container: 'html'
    });
}