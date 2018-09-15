//获取referer，兼容IE
function goTo(url) {
    var ua = navigator.userAgent;
    if (ua.indexOf('MSIE') >= 0) {
        var rl = document.createElement('a');
        rl.href = url;
        document.body.appendChild(rl);
        rl.click();
    } else {
        location.href = url;
    }
}

function getReferer() {
    if (document.referrer) {
        return document.referrer;
    } else {
        return false;
    }
}

//获取五位随机数
function GetRandomNum(Min, Max) {
    var Range = Max - Min;
    var Rand = Math.random();
    return (Min + Math.round(Rand * Range));
}
var num = GetRandomNum(10000, 99999);

//在本地缓存中存取用户信息
function setUserInfo(admin_id, admin_type) {
    localStorage.setItem("userInfo", JSON.stringify({
        "admin_id": admin_id,
        "admin_type": admin_type
    }));
}

function getUserInfo() {
    return JSON.parse(localStorage.getItem("userInfo"));
}

$(document).ready(() => {
    $(function() {
        $(".inner-box").scroll(function() {
            if ($(".inner-box").scrollTop() >= 50) {
                $('#back_to_top').fadeIn();
            } else {
                $('#back_to_top').fadeOut();
            }
        });
    });
    $('#back_to_top').click(function() {
        $('.inner-box').animate({ scrollTop: 0 }, 500);
    });
})