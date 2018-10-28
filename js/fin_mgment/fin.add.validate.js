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
    });

    $("#addOverhead-form").validate({
        onsubmit: true, // 是否在提交是验证
        rules: { //规则
            price: {
                required: true,
                number: true,
            },
            date: {
                required: true,
                date: true
            }
        },
        messages: { //验证错误信息
            price: {
                required: "请输入金额",
                number: "请输入正确的金额",
            },
            date: {
                required: "请选项日期",
                date: "请输入正确的日期"
            }
        },
        submitHandler: function(form) { //通过之后回调
            var type = $("#type").val();
            var price = $("#price").val();
            var date = $("#date").val();
            var admin_id = getUserInfo().admin_id;
            $.ajax({
                type: "POST",
                url: "../../php/repostory/purchase.php",
                dataType: "JSON",
                data: {
                    "request": "add_overhead",
                    "type": type,
                    "price": price,
                    "date": date
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
                },
                error: (err) => {
                    console.log(err)
                }
            })
        }
    });

})