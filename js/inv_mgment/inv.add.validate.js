$(document).ready(() => {
    /**
     * 获取登录信息
     */
    $.ajax({
        type: "GET",
        url: "../../php/check_login.php?request=check",
        dataType: "JSON",
        success: (e) => {
            $(".userPic").attr('src', e.admin_pic + "?" + num);
            $(".online-user").html(e.admin_name);
            admin_type = e.admin_type;
            if (admin_type == 1) {
                admin_type = "超级管理员";
            } else if (admin_type == 2) {
                admin_type = "管理员";
            } else if (admin_type == 3) {
                admin_type = "财务管理";
            } else if (admin_type == 4) {
                admin_type = "仓库管理";
            }
            $(".user-type").html(admin_type);
        },
        error: (err) => {
            console.log(err)
        }
    })
})

function addGoods(_this) {
    var $quantity = $(_this).parent().siblings(".goods-quantity-wrap").find("input");
    if ($quantity.val() == "") {
        if (!$quantity.hasClass("error"))
            $quantity.addClass("error");
    } else {
        $quantity.removeClass("error");
        var html = '<div class="subform col-xs-12">' +
            '<div class="goods-select-wrap form-group col-xs-12 col-sm-6">' +
            '<div class="section_title">原料</div>' +
            '<select id="goods-select-1" class="goods-select form-control">' +
            '' +
            '</select>' +
            '</div>' +
            '<div class="goods-quantity-wrap form-group col-xs-12 col-sm-6">' +
            '<div class="section_title">数量（公斤）</div>' +
            '<input type="text" class="goods-quantity input-text form-control" name="default_number" placeholder="请输入数量" maxlength="2" />' +
            '</div>' +
            '<div class="form-group col-xs-12">' +
            '<a class="goods-add-btn" onclick="addGoods(this)">' +
            '<i class="iconfont icon-add"></i>' +
            '</a>' +
            '</div>' +
            '</div>';
        $(".subform").last().after(html);
    }
}