var admin_name = "";
var delete_admin_id = "";

$(document).ready(() => {
    $(".shelter").click(() => {
        $(".adm-del-confirm").hide(() => {
            $(".shelter").fadeOut(500);
        });
    })
})

function delete_admin($this, admin_id) {
    delete_admin_id = admin_id;
    admin_name = $($this).parents("tr").find("td.admin_name").html();
    $(".shelter").fadeIn(500, () => {
        $(".adm-del-confirm").show();
    });
}

function cancel() {
    $(".adm-del-confirm").hide(() => {
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
        if (admin_name != input) {
            $("#delete_name").val("");
            $("#delete_name").addClass("error");
            $("#delete_name").after("<label id=\"name-error\" class=\"error\" for=\"name\">输入错误</label>");
        } else {
            $.ajax({
                type: "POST",
                url: "../../php/dashboard/administrator.php",
                dataType: "JSON",
                data: {
                    "request": "deleteAdmin",
                    "admin_id": getUserInfo().admin_id,
                    "admin_name": admin_name,
                },
                success: (e) => {
                    console.log(e);
                    if (e.message == "success") {
                        $(".emp-del-confirm").hide(() => {
                            $(".shelter").fadeOut(100, () => {
                                window.wxc.xcConfirm("删除成功", window.wxc.xcConfirm.typeEnum.success, {
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