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
    })
    addEmployeeValidate();
    addDishValidate();
    addTableValidate();

    $.validator.addMethod("chinese", function(value, element) {
        var chinese = /[\u4E00-\u9FA5\uF900-\uFA2D]/;
        return this.optional(element) || (chinese.test(value));
    }, "");

    $.validator.addMethod("phone", function(value, element) {
        var phone = /^1[34578]\d{9}$/;
        return this.optional(element) || (phone.test(value));
    }, "");
});

/**
 *添加员工
 *
 */
function addEmployeeValidate() {
    $("#addEmployee-form").validate({
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
                url: "../../php/admin/employee.php",
                dataType: "JSON",
                data: {
                    "request": "add_employee",
                    "admin_id": admin_id,
                    "name": name,
                    "gender": gender,
                    "age": age,
                    "salary": salary,
                    "phone_num": phone_num,
                    "employee_type": employee_type,
                    "employeePicData": picData
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
}

/**
 *添加菜品
 *
 */
function addDishValidate() {
    $("#addDish-form").validate({
        onsubmit: true, // 是否在提交是验证
        rules: { //规则
            dish_name: {
                required: true
            },
            dish_price: {
                required: true,
                number: true
            }
        },
        messages: { //验证错误信息

            dish_name: {
                required: "请输入菜名"
            },
            dish_price: {
                required: "请输入价格",
                number: "请输入正确的价格"
            }
        },
        submitHandler: function(form) { //通过之后回调
            var dish_name = $("#dish_name").val();
            var dish_price = $("#dish_price").val();
            var dish_type = $("#dish_type").val();
            var admin_id = getUserInfo().admin_id;
            $.ajax({
                type: "POST",
                url: "../../php/admin/dish.php",
                dataType: "JSON",
                data: {
                    "request": "add_dish",
                    "admin_id": admin_id,
                    "dish_name": dish_name,
                    "dish_price": dish_price,
                    "dish_type": dish_type,
                    "dishPicData": picData
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
}

function addTableValidate() {
    $("#addTable-form").validate({
        onsubmit: true, // 是否在提交是验证
        rules: { //规则
            table_number: {
                required: true,
                digits: true
            },
            default_number: {
                required: true,
                digits: true
            }
        },
        messages: { //验证错误信息

            table_number: {
                required: "请输入编号",
                digits: "请输入正确的编号"
            },
            default_number: {
                required: "请输入人数",
                digits: "请输入正确的人数"
            }
        },
        submitHandler: function(form) { //通过之后回调
            var table_number = $("#table_number").val();
            var default_number = $("#default_number").val();
            var admin_id = getUserInfo().admin_id;
            $.ajax({
                type: "POST",
                url: "../../php/admin/res_table.php",
                dataType: "JSON",
                data: {
                    "request": "add_table",
                    "admin_id": admin_id,
                    "table_number": table_number,
                    "default_number": default_number,
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
}