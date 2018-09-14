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
            } else if ($admin_type == 2) {
                admin_type = "管理员";
            } else if ($admin_type == 3) {
                admin_type = "财务管理";
            } else if ($admin_type == 4) {
                admin_type = "港库管理";
            }
            $(".user-type").html(admin_type);
            $(".user-create-time").html("账号创建时间：" + e.create_time.substring(0, 4) + "年" + e.create_time.substring(5, 7) + "月" + e.create_time.substring(8, 10) + "日");
        },
        error: (err) => {
            console.log(err)
        }
    })

    //     $("#addEmployee-form").validate({
    //         onsubmit: true, // 是否在提交是验证
    //         rules: { //规则
    //             name: {
    //                 required: true,
    //                 chinese: true,
    //             },
    //             age: {
    //                 required: true,
    //                 digits: true,
    //                 rangelength: [1, 2]
    //             },
    //             salary: {
    //                 required: true,
    //                 digits: true,
    //                 rangelength: [3, 5]
    //             },
    //             phone: {
    //                 required: true,
    //                 phone: true
    //             }
    //         },
    //         messages: { //验证错误信息

    //             name: {
    //                 required: "请输入姓名",
    //                 chinese: "请输入正确的姓名"
    //             },
    //             age: {
    //                 required: "请输入年龄",
    //                 digits: "请输入正确的年龄",
    //                 rangelength: "请输入正确的年龄"
    //             },
    //             salary: {
    //                 required: "请输入工资",
    //                 digits: "请输入正确的工资",
    //                 rangelength: "请输入正确的工资",
    //             },
    //             phone: {
    //                 required: "请输入手机号码",
    //                 phone: "请输入正确的手机号码"
    //             }
    //         },
    //         submitHandler: function(form) { //通过之后回调
    //             var userPicData = $("#previewResult")[0].src;
    //             if (userPicData.indexOf("data:") < 0) {
    //                 userPicData = '';
    //             }
    //             var name = $("#name").val();
    //             var gender = $("input[name='gender']:checked").val();
    //             var age = $("#age").val();
    //             var salary = $("#salary").val();
    //             var phone_num = $("#phone_num").val();
    //             var employee_type = $("#employee_type").val();
    //             var admin_id = getUserInfo().admin_id;
    //             $.ajax({
    //                 type: "POST",
    //                 url: "../../php/admin.add.php",
    //                 dataType: "JSON",
    //                 data: {
    //                     "request": "add_employee",
    //                     "admin_id": admin_id,
    //                     "name": name,
    //                     "gender": gender,
    //                     "age": age,
    //                     "salary": salary,
    //                     "phone_num": phone_num,
    //                     "employee_type": employee_type,
    //                     "userPicData": userPicData
    //                 },
    //                 success: (e) => {
    //                     console.log(e)
    //                 },
    //                 error: (err) => {
    //                     console.log(err)
    //                 }
    //             })
    //         }
    //     });
    //     $.validator.addMethod("chinese", function(value, element) {
    //         var chinese = /[\u4E00-\u9FA5\uF900-\uFA2D]/;
    //         return this.optional(element) || (chinese.test(value));
    //     }, "");

    //     $.validator.addMethod("phone", function(value, element) {
    //         var phone = /^1[34578]\d{9}$/;
    //         return this.optional(element) || (phone.test(value));
    //     }, "");

    $("#uploadpic").click(() => {
        var userPicData = picData;
        if (userPicData.indexOf("data:") > -1) {
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