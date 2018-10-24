$(document).ready(() => {
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
            } else if (admin_type == 2) {
                admin_type = "管理员";
            } else if (admin_type == 3) {
                admin_type = "财务管理";
            } else if (admin_type == 4) {
                admin_type = "库存管理";
            }
            $(".user-type").html(admin_type);
            $(".user-create-time").html("账号创建时间：" + e.create_time.substring(0, 4) + "年" + e.create_time.substring(5, 7) + "月" + e.create_time.substring(8, 10) + "日");
        },
        error: (err) => {
            console.log(err)
        }
    })

    $("#update-passwd").validate({
        onsubmit: true, // 是否在提交是验证
        rules: { //规则
            oldPasswd: {
                required: true
            },
            newPasswd: {
                required: true
            },
            rePasswd: {
                required: true,
                equalTo: newPasswd
            }
        },
        messages: { //验证错误信息
            oldPasswd: {
                required: "请输入旧密码"
            },
            newPasswd: {
                required: "请输入新密码"
            },
            rePasswd: {
                required: "请再次输入新密码",
                equalTo: "两次输入密码不一致"
            }
        },
        submitHandler: function(form) { //通过之后回调
            var name = $(".user-name").html();
            var oldPasswd = $("#oldPasswd").val();
            var newPasswd = $("#newPasswd").val();
            oldPasswd = "restaurant" + name + oldPasswd;
            oldPasswd = hex_md5(oldPasswd);
            newPasswd = "restaurant" + name + newPasswd;
            newPasswd = hex_md5(newPasswd);
            var admin_id = getUserInfo().admin_id;
            $.ajax({
                type: "POST",
                url: "../../php/dashboard/updatePasswd.php",
                dataType: "JSON",
                data: {
                    "request": "update_password",
                    "admin_id": getUserInfo().admin_id,
                    "old_password": oldPasswd,
                    "new_password": newPasswd
                },
                success: (e) => {
                    console.log(e);
                    if (e.message == "success") {
                        if (e.result == "update_passwd_success") {
                            window.wxc.xcConfirm("修改密码成功！", window.wxc.xcConfirm.typeEnum.success, {
                                onOk: function() {
                                    window.location.reload();
                                },
                                onClose: function() {
                                    window.location.reload();
                                }
                            });
                        } else if (e.result == "wrong_oldPasswd") {
                            window.wxc.xcConfirm("原密码不正确", window.wxc.xcConfirm.typeEnum.error);
                        } else {
                            window.wxc.xcConfirm("网络开小差啦~", window.wxc.xcConfirm.typeEnum.error);
                        }
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
    $.validator.addMethod("chinese", function(value, element) {
        var chinese = /[\u4E00-\u9FA5\uF900-\uFA2D]/;
        return this.optional(element) || (chinese.test(value));
    }, "");

    $.validator.addMethod("phone", function(value, element) {
        var phone = /^1[34578]\d{9}$/;
        return this.optional(element) || (phone.test(value));
    }, "");

    $("#uploadpic").click(() => {
        var userPicData = picData;
        if (userPicData.indexOf("data:") < 0) {
            window.wxc.xcConfirm("请先选择图片哟~", window.wxc.xcConfirm.typeEnum.warning);
        } else {
            $.ajax({
                type: "POST",
                url: "../../php/uploadPic.php",
                dataType: "JSON",
                data: {
                    "request": "upload_admin_pic",
                    "admin_id": getUserInfo().admin_id,
                    "PicData": userPicData
                },
                success: (e) => {
                    if (e.message == "success") {
                        window.wxc.xcConfirm("修改成功！", window.wxc.xcConfirm.typeEnum.success, {
                            onOk: function() {
                                window.location.reload();
                            },
                            onClose: function() {
                                window.location.reload();
                            }
                        });
                    }
                },
                error: (err) => {}
            })
        }
    })
});

function showBox(obj) {
    var $box = $(obj).siblings(".box-inner-hide");
    $box.slideToggle(200);
    $(obj).find(".box-down-arrow").fadeToggle(100);
    $(obj).find(".box-up-arrow").fadeToggle(100);
}