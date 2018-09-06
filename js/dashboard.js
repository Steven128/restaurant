$(document).ready(() => {
    //检查用户是否已经登录
    function GetRandomNum(Min, Max) {
        var Range = Max - Min;
        var Rand = Math.random();
        return (Min + Math.round(Rand * Range));
    }
    var num = GetRandomNum(10000, 99999);
    $.ajax({
        type: "GET",
        url: "../php/check_login.php?request=check",
        dataType: "JSON",
        success: (e) => {
            console.log(e)
            if (e.message == "online") {
                var userPic = e.admin_pic;
                var userName = e.admin_name;
                var userType = e.admin_type;
                if (userType == 1) {
                    userType = "超级管理员";
                } else if (userType == 2) {
                    userType = "管理员";
                } else if (userType == 3) {
                    userType = "财务管理";
                } else if (userType == 4) {
                    userType = "港库管理";
                }
                if (userPic.indexOf("src/") > -1) {
                    $(".userPic").attr('src', userPic + "?" + num);
                    $(".user-name").html(userName);
                    $(".user-type").html(userType);
                    $(".user-online").css("display", "block");
                    $(".user-offline").css("display", "none");
                }

            } else if (e.message == "offline") {
                // $(".userPic").attr('src', '');
                // $(".user-name").html('');
                // $(".user-type").html('');
                // $(".user-online").css("display", "none");
                // $(".user-offline").css("display", "block");
            }
        },
        error: (err) => {}
    })
})