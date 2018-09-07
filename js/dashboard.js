$(document).ready(() => {
    var admin_type = getUserInfo().admin_type;
    if (admin_type == 1) {
        addRootMgment();
    } else if (admin_type == 2) {
        addAdminMgment();
    } else if (admin_type == 3) {
        addFinanceMgment();
    } else if (admin_type == 4) {
        addInventoryMgment();
    }
})

function showInfoBox() {
    $(".info-box").css("display", "block");
    $(".shelter").css("display", "block");
}

function hideShelter() {
    $(".info-box").css("display", "none");
    $(".shelter").css("display", "none");
}

function addRootMgment() {
    var append_text = "<li class=\"treeview\"><a href=\"../../admin/\"><i class=\"iconfont icon-mgment\"></i><span>店面管理</span><span class=\"dash-pull-right\"><i class=\"iconfont icon-right-arrow\" style=\"font-size:12px;\"></i></span></a></li>";
    append_text += "<li class=\"treeview\"><a id=\"menu-adminList-item\" href=\"javascript:void(0);\"><i class=\"iconfont icon-account\"></i><span>管理员信息</span><span class=\"dash-pull-right\"><i class=\"iconfont icon-right-arrow\" style=\"font-size:12px;\"></i></span></a></li>";
    append_text += "<li class=\"treeview\"><a id=\"menu-addAdmin-item\" href=\"javascript:void(0);\"><i class=\"iconfont icon-account-plus\"></i><span>添加管理员</span><span class=\"dash-pull-right\"><i class=\"iconfont icon-right-arrow\" style=\"font-size:12px;\"></i></span></a></li>";
    $(".overview-treeview").after(append_text);
}

function addFinanceMgment() {
    var append_text = "<li class=\"treeview\"><a href=\"../../fin_mgment/\"><i class=\"iconfont icon-mgment\"></i><span>财务管理</span><span class=\"dash-pull-right\"><i class=\"iconfont icon-right-arrow\" style=\"font-size:12px;\"></i></span></a></li>";
    $(".overview-treeview").after(append_text);
}

function addInventoryMgment() {
    var append_text = "<li class=\"treeview\"><a href=\"../../inv_mgment/\"><i class=\"iconfont icon-mgment\"></i><span>库存管理</span><span class=\"dash-pull-right\"><i class=\"iconfont icon-right-arrow\" style=\"font-size:12px;\"></i></span></a></li>";
    $(".overview-treeview").after(append_text);
}

function addAdminMgment() {
    var append_text = "<li class=\"treeview\"><a href=\"../../admin/\"><i class=\"iconfont icon-mgment\"></i><span>店面管理</span><span class=\"dash-pull-right\"><i class=\"iconfont icon-right-arrow\" style=\"font-size:12px;\"></i></span></a></li>";
    $(".overview-treeview").after(append_text);
}