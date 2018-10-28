var $employee_id = '';

function update_salary(employee_id, name, salary) {
    $employee_id = employee_id;
    $(".update-salary-box .emp_name").html(name);
    $(".update-salary-box #oldSalary").val(salary);
    $(".shelter").fadeIn(500, () => {
        $(".update-salary-box").show();
    });
}

function hideBox() {
    $(".update-salary-box").hide(() => {
        $(".shelter").fadeOut(500);
    });
}

function cancel() {
    $(".update-salary-box").hide(() => {
        $(".shelter").fadeOut(500);
    });
}

$(document).ready(() => {
    $("#updateSalary-form").validate({
        onsubmit: true, // 是否在提交是验证
        rules: { //规则
            salary: {
                required: true,
                digits: true,
            }
        },
        messages: { //验证错误信息
            salary: {
                required: "请输入修改后的工资",
                digits: "请输入正确的数字",
            }
        },
        submitHandler: function(form) { //通过之后回调
            var salary = $("#salary").val();
            var admin_id = getUserInfo().admin_id;
            $.ajax({
                type: "POST",
                url: "../../php/finance/salary.php",
                dataType: "JSON",
                data: {
                    "request": "updateEmployee",
                    "admin_id": admin_id,
                    "salary": salary,
                    "employee_id": $employee_id
                },
                success: (e) => {
                    console.log(e)
                    if (e.message == "success") {
                        $(".update-salary-box").hide(1, () => {
                            $(".shelter").hide(1, () => {
                                window.wxc.xcConfirm("修改成功！", window.wxc.xcConfirm.typeEnum.success, {
                                    onOk: function() {
                                        window.location.reload();
                                    },
                                    onClose: function() {
                                        window.location.reload();
                                    }
                                });
                            });
                        });
                    } else {
                        $(".update-salary-box").hide(1, () => {
                            $(".shelter").hide(1, () => {
                                window.wxc.xcConfirm("网络开小差啦~", window.wxc.xcConfirm.typeEnum.error);
                            });
                        });
                    }
                },
                error: (err) => {
                    console.log(err)
                }
            })
        }
    });
})