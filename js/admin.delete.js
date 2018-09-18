var employee_name = "";
var global_employee_id = "";
$(document).ready(() => {
    $(".btn-delete").click(() => {
        let employee_id = $(".btn-delete").val();
        $.ajax({
            type: "GET",
            url: "../../../php/admin/employee.php?request=getEmployeeInfo&employee_id=" + employee_id + "&admin_id=" + getUserInfo().admin_id,
            dataType: "JSON",
            success: (e) => {
                console.log(e);
                global_employee_id = e.data.employee_id;
                employee_name = e.data.name;
                $(".shelter").fadeIn(500, () => {
                    $(".emp-del-confirm").show();
                });
            },
            error: (err) => { console.log(err) }
        })
    })
})


function cancel() {
    $(".emp-del-confirm").hide(() => {
        $(".shelter").fadeOut(500);
    });
}

function nextStep() {
    var input = $("#delete_name").val();
    if (input == "" && $("#delete_name").attr("class") == "") {
        $("#delete_name").addClass("error");
        $("#delete_name").after("<label id=\"name-error\" class=\"error\" for=\"name\">此为必填项</label>");
    } else if (input != "") {
        $("#delete_name").removeClass("error");
        $("#name-error").remove()
        console.log(input)
        if (employee_name != input) {
            $("#delete_name").val("");
            $("#delete_name").addClass("error");
            $("#delete_name").after("<label id=\"name-error\" class=\"error\" for=\"name\">输入错误</label>");
        } else {
            let employee_id = global_employee_id;
            $.ajax({
                type: "POST",
                url: "../../../php/admin/employee.php",
                dataType: "JSON",
                data: {
                    "request": "deleteEmployee",
                    "admin_id": getUserInfo().admin_id,
                    "employee_id": employee_id,
                },
                success: (e) => {
                    console.log(e);
                    if (e.message == "success") {
                        window.wxc.xcConfirm("删除成功", window.wxc.xcConfirm.typeEnum.success, {
                            onOk: function() {
                                window.location.replace("../");
                            },
                            onClose: function() {
                                window.location.replace("../");
                            }
                        });
                    } else {
                        window.wxc.xcConfirm("网络开小差啦~", window.wxc.xcConfirm.typeEnum.error);
                    }
                },
                error: (err) => {
                    console.log(err);
                }
            })
        }
    }
}