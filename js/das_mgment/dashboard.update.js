$(document).ready(() => {
    $.ajax({
        type: "GET",
        url: "../../../php/check_login.php?request=getUserInfo&admin_id=" + getUserInfo().admin_id,
        dataType: "JSON",
        success: (e) => {
            $(".userPic").attr('src', "../" + e.admin_pic + "?" + num);
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
})

function showBox(obj) {
    var $box = $(obj).siblings(".box-inner-hide");
    $box.slideToggle(200);
    $(obj).find(".box-down-arrow").fadeToggle(100);
    $(obj).find(".box-up-arrow").fadeToggle(100);
}