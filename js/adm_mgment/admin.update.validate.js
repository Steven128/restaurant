var data = {};
$(document).ready(() => {
    var href = decodeURIComponent(window.location);
    var reason = href.match(/\/update(.*?)\//)[1].toLowerCase();
    $.ajax({
            type: "GET",
            url: "../../../php/check_login.php?request=check",
            dataType: "JSON",
            success: (e) => {
                $(".userPic").attr('src', "../" + e.admin_pic + "?" + num);
                $(".online-user").html(e.admin_name);
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
                if (reason == "employee") {
                    var employee_id = Decrypt(href.match(/\?employee_id=(.*?)&/)[1], "employee_id");
                    getEmployeeInfo(employee_id);
                } else if (reason == "dish") {
                    var dish_id = Decrypt(href.match(/\?dish_id=(.*?)&/)[1], "dish_id");
                    getDishInfo(dish_id);
                } else if (reason == "table") {
                    var table_id = Decrypt(href.match(/\?table_id=(.*?)&/)[1], "table_id");
                    getTableInfo(table_id);
                }

            },
            error: (err) => {
                console.log(err)
            }
        })
        /**
         * 加载员工信息
         * @param {*} employee_id 
         */
    function getEmployeeInfo(employee_id) {
        $.ajax({
            type: "GET",
            url: "../../../php/admin/employee.php?request=getEmployeeInfo&employee_id=" + employee_id + "&admin_id=" + getUserInfo().admin_id,
            dataType: "JSON",
            success: (e) => {
                if (e.message = "success") {
                    data = e.data;
                    if (data.gender == 1) {
                        data.gender = "男";
                    } else if (data.gender == 0) {
                        data.gender = "女";
                    }
                    //
                    if (data.working_year.indexOf(".") == 0) {
                        data.working_year = "0" + data.working_year;
                    }
                    //
                    if (data.employee_type == 1) {
                        data.employee_type = "管理人员";
                    } else if (data.employee_type == 2) {
                        data.employee_type = "服务员";
                    } else if (data.employee_type == 3) {
                        data.employee_type = "前台";
                    } else if (data.employee_type == 4) {
                        data.employee_type = "厨师";
                    } else if (data.employee_type == 5) {
                        data.employee_type = "保洁";
                    } else if (data.employee_type == 6) {
                        data.employee_type = "仓库管理员";
                    } else if (data.employee_type == 7) {
                        data.employee_type = "会计";
                    } else if (data.employee_type == 8) {
                        data.employee_type = "其他";
                    }

                    $(".employeePic").attr("src", "../" + data.employee_pic);
                    //$(".employeePic").attr("src", "../../../src/employee_pic/default.png");
                    $(".employee-name").html(data.name);
                    $(".employee-type").html(data.employee_type);
                    $(".btn-delete").val(data.employee_id);
                    $(".emp-gender").html("性别：" + data.gender);
                    $(".emp-age").html("年龄：" + data.age);
                    $(".emp-working-year").html("工龄：" + data.working_year + " 年");
                    $(".emp-salary").html("工资：" + data.salary + " 元");
                    $(".emp-time").html("聘用日期：" + data.employ_time.substring(0, 4) + " 年 " + data.employ_time.substring(5, 7) + " 月 " + data.employ_time.substring(8, 10) + " 日 ");
                    $(".emp-phone").html("手机号：" + data.phone_num);
                    var html = "<div class=\"subform\"><div class=\"form-group\"><div class=\"section_title\">姓名</div><input id=\"name\" type=\"text\" class=\"input-text form-control\" name=\"name\" placeholder=\"请输入姓名\" value=\"" + data.name + "\" maxlength=15 /></div><div class=\"form-group\"><div class=\"section_title\">性别</div>";
                    if (data.gender == "男") {
                        html += "<label class=\"gender\"><input type=\"radio\" id=\"male\" class=\".radio-inline\" name=\"gender\" value=\"1\" checked=\"checked\" />男</label><label class=\"gender\"><input type=\"radio\" id=\"female\" class=\".radio-inline\" name=\"gender\" value=\"0\" />女</label>";
                    } else if (data.gender == "女") {
                        html += "<label class=\"gender\"><input type=\"radio\" id=\"male\" class=\".radio-inline\" name=\"gender\" value=\"1\" />男</label><label class=\"gender\"><input type=\"radio\" id=\"female\" class=\".radio-inline\" name=\"gender\" value=\"0\" checked=\"checked\" />女</label>";
                    }
                    html += "</div><div class=\"form-group\"><div class=\"section_title\">年龄</div><input id=\"age\" type=\"text\" class=\"input-text form-control\" name=\"age\" placeholder=\"请输入年龄\" maxlength=2 value=\"" + data.age + "\" /></div><div class=\"form-group\"><div class=\"section_title\">工资</div><input id=\"salary\" type=\"text\" class=\"input-text form-control\" name=\"salary\" placeholder=\"请输入工资\" maxlength=5 value=\"" + data.salary + "\" /></div><div class=\"form-group\"><div class=\"section_title\">手机号码</div><input id=\"phone_num\" type=\"text\" class=\"input-text form-control\" name=\"phone_num\" placeholder=\"请留下手机号码\" value=\"" + data.phone_num + "\" /></div><div class=\"form-group\"><div class=\"section_title\">员工类型</div><select id=\"employee_type\" class=\"select form-control\" name=\"employee_type\">";

                    if (data.employee_type == "管理人员") {
                        html += "<option class=\"option\" value=\"1\" selected=\"selected\">管理人员</option><option class=\"option\" value=\"2\">服务员</option><option class=\"option\" value=\"3\">前台</option><option class=\"option\" value=\"4\">厨师</option><option class=\"option\" value=\"5\">保洁</option><option class=\"option\" value=\"6\">仓库管理员</option><option class=\"option\" value=\"7\">会计</option><option class=\"option\" value=\"8\">其他</option>";
                    } else if (data.employee_type == "服务员") {
                        html += "<option class=\"option\" value=\"1\">管理人员</option><option class=\"option\" value=\"2\" selected=\"selected\">服务员</option><option class=\"option\" value=\"3\">前台</option><option class=\"option\" value=\"4\">厨师</option><option class=\"option\" value=\"5\">保洁</option><option class=\"option\" value=\"6\">仓库管理员</option><option class=\"option\" value=\"7\">会计</option><option class=\"option\" value=\"8\">其他</option>";
                    } else if (data.employee_type == "前台") {
                        html += "<option class=\"option\" value=\"1\">管理人员</option><option class=\"option\" value=\"2\">服务员</option><option class=\"option\" value=\"3\" selected=\"selected\">前台</option><option class=\"option\" value=\"4\">厨师</option><option class=\"option\" value=\"5\">保洁</option><option class=\"option\" value=\"6\">仓库管理员</option><option class=\"option\" value=\"7\">会计</option><option class=\"option\" value=\"8\">其他</option>";
                    } else if (data.employee_type == "厨师") {
                        html += "<option class=\"option\" value=\"1\">管理人员</option><option class=\"option\" value=\"2\">服务员</option><option class=\"option\" value=\"3\">前台</option><option class=\"option\" value=\"4\" selected=\"selected\">厨师</option><option class=\"option\" value=\"5\">保洁</option><option class=\"option\" value=\"6\">仓库管理员</option><option class=\"option\" value=\"7\">会计</option><option class=\"option\" value=\"8\">其他</option>";
                    } else if (data.employee_type == "保洁") {
                        html += "<option class=\"option\" value=\"1\">管理人员</option><option class=\"option\" value=\"2\">服务员</option><option class=\"option\" value=\"3\">前台</option><option class=\"option\" value=\"4\">厨师</option><option class=\"option\" value=\"5\" selected=\"selected\">保洁</option><option class=\"option\" value=\"6\">仓库管理员</option><option class=\"option\" value=\"7\">会计</option><option class=\"option\" value=\"8\">其他</option>";
                    } else if (data.employee_type == "仓库管理员") {
                        html += "<option class=\"option\" value=\"1\">管理人员</option><option class=\"option\" value=\"2\">服务员</option><option class=\"option\" value=\"3\">前台</option><option class=\"option\" value=\"4\">厨师</option><option class=\"option\" value=\"5\">保洁</option><option class=\"option\" value=\"6\" selected=\"selected\">仓库管理员</option><option class=\"option\" value=\"7\">会计</option><option class=\"option\" value=\"8\">其他</option>";
                    } else if (data.employee_type == "会计") {
                        html += "<option class=\"option\" value=\"1\">管理人员</option><option class=\"option\" value=\"2\">服务员</option><option class=\"option\" value=\"3\">前台</option><option class=\"option\" value=\"4\">厨师</option><option class=\"option\" value=\"5\">保洁</option><option class=\"option\" value=\"6\">仓库管理员</option><option class=\"option\" value=\"7\" selected=\"selected\">会计</option><option class=\"option\" value=\"8\">其他</option>";
                    } else if (data.employee_type == "其他") {
                        html += "<option class=\"option\" value=\"1\">管理人员</option><option class=\"option\" value=\"2\">服务员</option><option class=\"option\" value=\"3\">前台</option><option class=\"option\" value=\"4\">厨师</option><option class=\"option\" value=\"5\">保洁</option><option class=\"option\" value=\"6\">仓库管理员</option><option class=\"option\" value=\"7\">会计</option><option class=\"option\" value=\"8\" selected=\"selected\">其他</option>";
                    }
                    html += "</select></div></div>";
                    $("#updateEmployee-form").prepend(html);
                    $("#updateEmployee-form").onChange();
                }
            },
            error: (err) => { console.log(err) }
        })
    }

    /**
     * 加载菜品信息
     * @param {*} dish_id 
     */
    function getDishInfo(dish_id) {
        $.ajax({
            type: "GET",
            url: "../../../php/admin/dish.php?request=getDishInfo&dish_id=" + dish_id + "&admin_id=" + getUserInfo().admin_id,
            dataType: "JSON",
            success: (e) => {
                console.log(e)
                if (e.message = "success") {
                    data = e.data;
                    $(".dishPic").attr("src", "../" + data.dish_pic);
                    $(".dish-name").html(data.dish_name);
                    var type = data.dish_type;
                    if (type == 1) {
                        type = "早餐";
                    } else if (type == 2) {
                        type = "主食";
                    } else if (type == 3) {
                        type = "甜品/饮料";
                    } else if (type == 4) {
                        type = "小食";
                    }
                    $(".dish-type").html(type);
                    $(".dish-price").html(data.dish_price + "元");
                    $(".btn-delete").val(data.dish_id);

                    var appendText =
                        '<div class="subform">' +
                        '<div class="form-group">' +
                        '<div class="section_title">名称</div>' +
                        '<input id="dish_name" type="text" class="input-text form-control" name="dish_name" placeholder="请输入菜名" maxlength="15" value="' + data.dish_name + '">' +
                        '</div>' +
                        '<div class="form-group">' +
                        '<div class="section_title">售价</div>' +
                        '<input id="dish_price" type="text" class="input-text form-control" name="dish_price" placeholder="请输入售价" maxlength="7" value="' + data.dish_price + '">' +
                        '</div>' +
                        '<div class="form-group">' +
                        '<div class="section_title">类别</div>' +
                        '<select id="dish_type" class="select form-control" name="dish_type">';
                    if (data.dish_type == 1) {
                        appendText += '<option class="option" value="1" selected="selected">早餐</option>' +
                            '<option class="option" value="2">主食</option>' +
                            '<option class="option" value="3">甜品/饮料</option>' +
                            '<option class="option" value="4">小食</option>';
                    } else if (data.dish_type == 2) {
                        appendText += '<option class="option" value="1">早餐</option>' +
                            '<option class="option" value="2" selected="selected">主食</option>' +
                            '<option class="option" value="3">甜品/饮料</option>' +
                            '<option class="option" value="4">小食</option>';
                    } else if (data.dish_type == 3) {
                        appendText += '<option class="option" value="1">早餐</option>' +
                            '<option class="option" value="2">主食</option>' +
                            '<option class="option" value="3" selected="selected">甜品/饮料</option>' +
                            '<option class="option" value="4">小食</option>';
                    } else if (data.dish_type == 4) {
                        appendText += '<option class="option" value="1">早餐</option>' +
                            '<option class="option" value="2">主食</option>' +
                            '<option class="option" value="3">甜品/饮料</option>' +
                            '<option class="option" value="4" selected="selected">小食</option>';
                    }
                    appendText +=
                        '</select>' +
                        '</div>' +
                        '</div>';
                    $("#updateDish-form").prepend(appendText);
                    $("#updateEmployee-form").onChange();
                }
            },
            error: (err) => { console.log(err) }
        })
    }

    /**
     * 加载餐桌信息
     * @param {*} table_id 
     */
    function getTableInfo(table_id) {
        $.ajax({
            type: "GET",
            url: "../../../php/admin/res_table.php?request=getTableInfo&table_id=" + table_id + "&admin_id=" + getUserInfo().admin_id,
            dataType: "JSON",
            success: (e) => {
                console.log(e)
                data = e.data;
                $(".table-number").html(data.table_number);
                $(".table-defNum").html("规定人数：" + data.default_number);
                $(".btn-delete").val(data.table_id);
                var status = null;
                if (data.table_order_status == 0) {
                    status = "无人";
                } else if (data.table_order_status == 1) {
                    status = "已预订";
                } else if (data.table_order_status == 2) {
                    status = "已上座";
                }
                $(".table-ordSta").html("状态：" + status);

                var appendText =
                    '<div class="form-group">' +
                    '<div class="section_title">规定人数</div>' +
                    '<input id="default_number" type="text" class="input-text form-control" name="default_number" placeholder="请输入人数" maxlength="2" value="' + data.default_number + '" />' +
                    '</div>';
                $("#updateTable-form").prepend(appendText);
                $("#updateTable-form").onChange();
            },
            error: (err) => { console.log(err) }
        })
    }
});

function showBox(obj) {
    var $box = $(obj).siblings(".box-inner-hide");
    $box.slideToggle(200);
    $(obj).find(".box-down-arrow").fadeToggle(100);
    $(obj).find(".box-up-arrow").fadeToggle(100);
}


$(document).ready(() => {
    /**
     * 更新员工信息 - 表单
     */
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
            var employee_id = data.employee_id;
            var name = $("#name").val();
            var gender = $("input[name='gender']:checked").val();
            var age = $("#age").val();
            var salary = $("#salary").val();
            var phone_num = $("#phone_num").val();
            var employee_type = $("#employee_type").val();
            var admin_id = getUserInfo().admin_id;
            $.ajax({
                type: "POST",
                url: "../../../php/admin/employee.php",
                dataType: "JSON",
                data: {
                    "request": "updateEmployee",
                    "admin_id": admin_id,
                    "employee_id": employee_id,
                    "name": name,
                    "gender": gender,
                    "age": age,
                    "salary": salary,
                    "phone_num": phone_num,
                    "employee_type": employee_type
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
    $.validator.addMethod("chinese", function(value, element) {
        var chinese = /[\u4E00-\u9FA5\uF900-\uFA2D]/;
        return this.optional(element) || (chinese.test(value));
    }, "");
    $.validator.addMethod("phone", function(value, element) {
        var phone = /^1[34578]\d{9}$/;
        return this.optional(element) || (phone.test(value));
    }, "");

    /**
     * 更新员工照片
     */
    $("#emp-uploadpic").click(() => {
        if (picData == "") {} else {
            var employee_id = data.employee_id;
            console.log(employee_id)
            $.ajax({
                type: "POST",
                url: "../../../php/admin/employee.php",
                dataType: "JSON",
                data: {
                    "request": "updateEmployeePic",
                    "admin_id": getUserInfo().admin_id,
                    "employee_id": employee_id,
                    "employeePicData": picData
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

    /**
     * 更新菜品信息 - 表单
     */
    $("#updateDish-form").validate({
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
                url: "../../../php/admin/dish.php",
                dataType: "JSON",
                data: {
                    "request": "update_dish",
                    "admin_id": admin_id,
                    "dish_name": dish_name,
                    "dish_price": dish_price,
                    "dish_type": dish_type
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

    /**
     * 更新菜品图片
     */
    $("#dis-uploadpic").click(() => {
        if (picData == "") {} else {
            var dish_id = data.dish_id;
            console.log(dish_id)
            $.ajax({
                type: "POST",
                url: "../../../php/admin/dish.php",
                dataType: "JSON",
                data: {
                    "request": "updateDishPic",
                    "admin_id": getUserInfo().admin_id,
                    "dish_id": dish_id,
                    "dishPicData": picData
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

    /**
     * 更新餐桌信息
     */
    $("#updateTable-form").validate({
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
                url: "../../../php/admin/res_table.php",
                dataType: "JSON",
                data: {
                    "request": "update_table",
                    "admin_id": admin_id,
                    "table_number": table_number,
                    "default_number": default_number,
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
})