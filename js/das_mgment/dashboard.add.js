$("#addAdmin-form").validate({
    onsubmit: true, // 是否在提交是验证
    rules: { //规则
        admin_name: {
            required: true,
            chinese: true,
        }
    },
    messages: { //验证错误信息

        admin_name: {
            required: "请输入姓名",
            chinese: "请输入正确的姓名"
        }
    },
    submitHandler: function(form) { //通过之后回调
        var name = $("#admin_name").val();
        var type = $("#admin_type").val();
        var admin_id = getUserInfo().admin_id;
        $.ajax({
            type: "POST",
            url: "../../php/dashboard/administrator.php",
            dataType: "JSON",
            data: {
                "request": "add_admin",
                "admin_id": admin_id,
                "admin_name": name,
                "admin_type": type,
                "adminPicData": picData
            },
            success: (e) => {
                console.log(e)
                if (e.message == "success") {
                    window.wxc.xcConfirm("修改成功！", window.wxc.xcConfirm.typeEnum.success, {
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