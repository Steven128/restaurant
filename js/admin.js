$(document).ready(function() {
    $(window).resize(function() {
        var width = $(window).width();
        if (width > 768) {
            $(".left-bar").css("left", "0");
            $(".mask").css("display", "none");
        } else {
            $(".left-bar").css("left", "-180px");
            $(".mask").css("display", "none");
        }
    });

    let $btn = $(".avatar-small"),
        $mask = $(".mask"),
        $nav = $(".left-bar");
    $btn.click(function() {
        console.log("click")
        if ($(".left-bar").css("left") == "-180px") {
            $mask.css("display", "block");
            $nav.css("left", "0");
        } else {
            $mask.css("display", "none");
            $nav.css("left", "-180px");
        }
    });

    $mask.click(function() {
        $mask.css("display", "none");
        $nav.css("left", "-180px");
    });
})