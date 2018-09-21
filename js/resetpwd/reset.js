$(document).ready(() => {
    var href = decodeURIComponent(window.location.search);
    var phpsessid = href.match(/\?wrap=restaurant2018(.*?)&/)[1];
    var name = decodeURI(href.match(/user=(.*?)$/)[1]);
    $(".user-area").html(name);
    $("#reset-passwd").validate({
        onsubmit: true, // 是否在提交是验证
        rules: { //规则
            passwd: {
                required: true
            },
            newPasswd: {
                required: true,
                equalTo: passwd
            }
        },
        messages: { //验证错误信息

            passwd: {
                required: "请输入新密码",
            },
            newPasswd: {
                required: "请再次输入密码",
                equalTo: "两次密码输入不一致"
            },
        },
        submitHandler: function(form) { //通过之后回调
            var passwd = $("#passwd").val();
            password = "restaurant" + name + passwd;
            password = hex_md5(password);
            $.ajax({
                type: "POST",
                url: "../php/reset_password.php",
                dataType: "JSON",
                data: {
                    "request": "reset",
                    "name": name,
                    "password": password,
                    "phpsessid": phpsessid
                },
                success: function(e) {
                    if (e.message == "success") {
                        window.location.replace("reset_success.html");
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
})