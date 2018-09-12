$(document).ready(() => {
    var href = decodeURIComponent(window.location.search);
    var phpsessid = href.match(/\?wrap=restaurant2018(.*?)&/)[1];
    $("#validate").validate({
        onsubmit: true, // 是否在提交是验证
        rules: { //规则
            name: {
                required: true
            },
            passwd: {
                required: true
            }
        },
        messages: { //验证错误信息

            name: {
                required: "请输入用户名",
            },
            passwd: {
                required: "请输入密码"
            },
        },
        submitHandler: function(form) { //通过之后回调
            var name = $("#name").val();
            var passwd = $("#passwd").val();
            password = "restaurant" + name + passwd;
            password = hex_md5(password);
            $.ajax({
                type: "POST",
                url: "../php/reset_password.php",
                dataType: "JSON",
                data: {
                    "request": "validate",
                    "name": name,
                    "password": password,
                    "phpsessid": phpsessid
                },
                success: function(e) {
                    if (e.message == "success") {
                        $name = encodeURI(name);
                        var loc = "reset.html?" + encodeURIComponent("wrap=restaurant2018" + phpsessid + "&user=" + $name);
                        window.location.replace(loc);
                    } else if (e.message == "permission_denied") {
                        window.wxc.xcConfirm("验证失败", window.wxc.xcConfirm.typeEnum.error);
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