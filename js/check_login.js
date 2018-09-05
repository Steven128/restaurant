$(document).ready(() => {
    //检查用户是否已经登录
    $.ajax({
        type: "GET",
        url: "../php/check_login.php?request=check",
        dataType: "JSON",
        success: (e) => {
            console.log(e)
            if (e.message == "online") {
                console.log("online")
            }
        },
        error: (err) => {

        }
    })
})