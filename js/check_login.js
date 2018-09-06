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
                console.log("online")
                var userPic = e.admin_pic;
                if (userPic.indexOf("src/") > -1) {
                    $(".userPic").attr('src', userPic + "?" + num);
                } else {
                    $(".userPic").attr('src', '');
                }

            }
        },
        error: (err) => {

        }
    })
})