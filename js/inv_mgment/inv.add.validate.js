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
            getGoodsList($("#goods-select-1"));
        },
        error: (err) => {
            console.log(err)
        }
    });
})

function getGoodsList($select) {
    var html = '';
    $.ajax({
        type: "GET",
        url: "../../php/admin/getGoodsName.php?request=getGoodsName&admin_id=" + getUserInfo().admin_id,
        dataType: "JSON",
        success: (e) => {
            console.log(e);
            var html = '';
            for (var i = 0; e[i]; i++) {
                html += '<option value="' + e[i] + '">' + e[i] + '</option>';
            }
            $select.append(html);
            $select.removeAttr("disabled");
        },
        error: (err) => {
            console.log(err)
        }
    });
}

function addGoods(_this) {

    var $quantity = $(_this).parent().last().find("input");
    if ($quantity.val() == "") {
        if (!$quantity.hasClass("error"))
            $quantity.addClass("error");
    } else {
        $quantity.removeClass("error");
        var $subform = $(_this).parent();
        $subform.find(".goods-add-btn").remove();
        $subform.append('<a class="goods-del-btn" onclick="delGoods(this)">' +
            '<i class="iconfont icon-delete-round"></i>' +
            '</a>')
        var length = $(".subform").length + 1;
        var html = '<div class="subform col-xs-12">' +
            '<div class="goods-select-wrap form-group col-xs-12 col-sm-6">' +
            '<div class="section_title">原料</div>' +
            '<select id="goods-select-' + length + '" class="goods-select form-control" disabled="disabled">' +
            '</select>' +
            '</div>' +
            '<div class="goods-quantity-wrap form-group col-xs-12 col-sm-6">' +
            '<div class="section_title">数量（公斤）</div>' +
            '<input type="text" class="goods-quantity input-text form-control" name="default_number" placeholder="请输入数量" maxlength="2" />' +
            '</div>' +
            '<a class="goods-add-btn" onclick="addGoods(this)">' +
            '<i class="iconfont icon-add"></i>' +
            '</a>' +
            '</div>';
        $(".subform").last().after(html);
        getGoodsList($(".subform").last().find("select"));
    }
}

function delGoods(_this) {
    if ($(".subform").length > 1) {
        $(_this).parent().remove();
    }
}

$(document).ready(() => {
    $("#submit").click(() => {
        var _subformList = $("#addPurchase-form").find(".subform");
        var data = [];
        for (var i = 0; i < _subformList.length; i++) {
            var goodsName = $(_subformList[i]).find("select").val();
            var goodsQuantity = $(_subformList[i]).find("input").val();
            if (goodsQuantity != "")
                data.push({ "name": goodsName, "quantity": goodsQuantity });
        }
        $.ajax({
            type: "POST",
            url: "",
            dataType: "JSON",
            data: {
                "request": "addInventory",
                "data": data
            },
            success: (e) => {
                console.log(e)
                if (e.message == "success") {
                    window.wxc.xcConfirm("添加成功！", window.wxc.xcConfirm.typeEnum.success, {
                        onOk: function() {
                            window.location.reload();
                        },
                        onClose: function() {
                            window.location.reload();
                        }
                    });
                } else {
                    window.wxc.xcConfirm("网络开小差啦~", window.wxc.xcConfirm.typeEnum.error);
                }
            }，
            error: (err) => { console.log(err) }
        })
    })
})