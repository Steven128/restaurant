//login

//获取referer，兼容IE
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

var name = "";
var name = "";
var isManager = "";


$(document).ready(function() {
    //检查用户是否已经登录
    // $.ajax({
    //     type: "POST",
    //     url: "../php/check_login.php",
    //     dataType: "JSON",
    //     data: {
    //         "request": "getname"
    //     },
    //     success: function(e) {
    //         if (e.name != '' && e.name != null) {
    //             if (!getReferer()) {
    //                 goTo('?x=3&r=' + Math.random());
    //             } else {
    //                 var pre = "47.95.212.18";
    //                 if (getReferer().indexOf(pre) < 0) {
    //                     window.location.href = getReferer();
    //                     window.event.returnValue = false;
    //                 } else {
    //                     window.location.href = "../";
    //                     window.event.returnValue = false;
    //                 }
    //             }
    //             window.location.href = "../user/";
    //             window.event.returnValue = false;
    //         } else {

    //         }
    //     },
    //     error: function(err) {

    //     }
    // });

    var width = $(window).width();
    var height = $(window).height();
    var foot_height = $(".foot-content").height();
    var picHeight = height - foot_height - 2.5;
    $(".main-content").css("height", picHeight);




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
                url: "",
                dataType: "JSON",
                data: {
                    "name": name,
                    "password": password,
                },
                success: function(e) {
                    if (e.message == "wrong passwd") {
                        window.wxc.xcConfirm("密码输入错误！", window.wxc.xcConfirm.typeEnum.error);
                    } else if (e.message == "does_not_exist") {
                        window.wxc.xcConfirm("该用户不存在！", window.wxc.xcConfirm.typeEnum.error, {
                            onOk: function() {
                                window.location.reload();
                            },
                            onClose: function() {
                                window.location.reload();
                            }
                        });
                    } else {

                        name = e.name;
                        name = e.name;
                        if (!getReferer()) {
                            goTo('?x=3&r=' + Math.random());
                        } else {
                            if (getReferer() == '') {
                                location.href = '../';
                            } else {
                                if (getReferer().indexOf('47.95.212.18') == -1) { //来自其它站点  
                                    location.href = '../';
                                } else if (getReferer().indexOf('') != -1) { //来自用户页面  
                                    location.href = '../';
                                } else if (getReferer().indexOf('') != -1) { //来自管理员页面  
                                    location.href = '../';
                                } else if (getReferer().indexOf('') != -1) { //新注册的用户
                                    location.href = '../';
                                } else {
                                    location.href = getReferer();
                                }
                            }
                        }
                    }
                },
                error: function(err) {
                    window.wxc.xcConfirm("出错了，稍后再试吧！", window.wxc.xcConfirm.typeEnum.error);
                    console.log(err);
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