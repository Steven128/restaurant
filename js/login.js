$(document).ready(() => {
    //检查用户是否已经登录
    $.ajax({
        type: "GET",
        url: "../php/check_login.php?request=check",
        dataType: "JSON",
        success: (e) => {
            if (e.message == "online") {
                if (!getReferer()) {
                    goTo('?x=3&r=' + num + "/");
                } else {
                    if (getReferer().indexOf("admin") > -1) {
                        window.location.replace("../admin");
                    } else {
                        window.location.replace("../dashboard");
                    }
                }
            } else {
                setUserInfo("", "");
            }
        },
        error: (err) => {}
    });
    //form validate

    $("#login").validate({
        onsubmit: true, // 是否在提交是验证
        rules: { //规则

            name: {
                required: true
            },
            password: {
                required: true
            }
        },
        messages: { //验证错误信息

            name: {
                required: "请输入用户名",
            },
            password: {
                required: "请输入密码"
            },
        },
        submitHandler: function(form) { //通过之后回调
            var name = $("#name").val();
            var password = $("#password").val();
            password = "restaurant" + name + password;
            password = hex_md5(password);
            $.ajax({
                type: "POST",
                url: "../php/login.php",
                dataType: "JSON",
                data: {
                    "name": name,
                    "password": password,
                },
                success: function(e) {
                    if (e.message == "wrong_password") {
                        window.wxc.xcConfirm("密码输入错误！", window.wxc.xcConfirm.typeEnum.error);
                    } else if (e.message == "admin_not_found") {
                        window.wxc.xcConfirm("该用户不存在！", window.wxc.xcConfirm.typeEnum.error, {
                            onOk: function() {
                                window.location.reload();
                            },
                            onClose: function() {
                                window.location.reload();
                            }
                        });
                    } else if (e.message == "success_login") {
                        setUserInfo(e.admin_id, e.admin_type);
                        if (!getReferer()) {
                            goTo('?x=3&r=' + num + "/");
                        } else {
                            if (getReferer().indexOf("admin") > -1) {
                                window.location.replace("../admin");
                            } else {
                                window.location.replace("../dashboard");
                            }
                        }
                    }
                },
                error: function(err) {
                    window.wxc.xcConfirm("出错了，稍后再试吧！", window.wxc.xcConfirm.typeEnum.error);
                }
            });
        },
        invalidHandler: function(form, validator) {
            return false;
        }
    });

});

$(window).resize(function() {
    var width = $(window).width();
    var height = $(window).height();
    var foot_height = $(".foot-content").height();
    var picHeight = height - foot_height - 2.5;
    $(".main-content").css("height", picHeight);

})