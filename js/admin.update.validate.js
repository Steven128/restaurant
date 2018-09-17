$(document).ready(() => {
    var href = decodeURIComponent(window.location.search);
    var employee_id = Decrypt(href.match(/\?employee_id=(.*?)$/)[1], "employee_id");
    $.ajax({
        type: "GET",
        url: "../../../php/check_login.php?request=check",
        dataType: "JSON",
        success: (e) => {
            $(".userPic").attr('src', "../" + e.admin_pic + "?" + num);
            $(".online-user").html(e.admin_name);
            $admin_type = e.admin_type;
            if ($admin_type == 1) {
                $admin_type = "超级管理员";
            } else if ($admin_type == 2) {
                $admin_type = "管理员";
            } else if ($admin_type == 3) {
                $admin_type = "财务管理";
            } else if ($admin_type == 4) {
                $admin_type = "港库管理";
            }
            $(".user-type").html($admin_type);
            getEmployeeInfo(employee_id);
        },
        error: (err) => {
            console.log(err)
        }
    })

    var employee_info = {};

    function getEmployeeInfo(employee_id) {
        $.ajax({
            type: "GET",
            url: "../../php/admin.update.php?request=getEmployeeInfo&employee_id=" + employee_id + "&admin_id=" + getUserInfo().admin_id,
            dataType: "JSON",
            success: (e) => {
                console.log(e);
                employee_info = e.data;
            },
            error: (err) => { console.log(err) }
        })
    }


    $("#updateEmployee-form").validate({
        onsubmit: true, // 是否在提交是验证
        rules: { //规则
            name: {
                required: true,
                chinese: true,
            },
            age: {
                required: true,
                digits: true,
                rangelength: [1, 2]
            },
            salary: {
                required: true,
                digits: true,
                rangelength: [3, 5]
            },
            phone_num: {
                required: true,
                phone: true
            }
        },
        messages: { //验证错误信息

            name: {
                required: "请输入姓名",
                chinese: "请输入正确的姓名"
            },
            age: {
                required: "请输入年龄",
                digits: "请输入正确的年龄",
                rangelength: "请输入正确的年龄"
            },
            salary: {
                required: "请输入工资",
                digits: "请输入正确的工资",
                rangelength: "请输入正确的工资",
            },
            phone_num: {
                required: "请输入手机号码",
                phone: "请输入正确的手机号码"
            }
        },
        submitHandler: function(form) { //通过之后回调
            var name = $("#name").val();
            var gender = $("input[name='gender']:checked").val();
            var age = $("#age").val();
            var salary = $("#salary").val();
            var phone_num = $("#phone_num").val();
            var employee_type = $("#employee_type").val();
            var admin_id = getUserInfo().admin_id;
            $.ajax({
                type: "POST",
                url: "../../../php/admin.update.php",
                dataType: "JSON",
                data: {
                    "request": "update_employee",
                    "admin_id": admin_id,
                    "name": name,
                    "gender": gender,
                    "age": age,
                    "salary": salary,
                    "phone_num": phone_num,
                    "employee_type": employee_type,
                },
                success: (e) => {
                    console.log(e)
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
});

function showBox(obj) {
    var $box = $(obj).siblings(".box-inner-hide");
    $box.slideToggle(200);
    $(obj).find(".box-down-arrow").fadeToggle(100);
    $(obj).find(".box-up-arrow").fadeToggle(100);
}


$(document).ready(() => {

})