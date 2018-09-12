$.sidebarMenu = function(menu) {
    var animationSpeed = 300;

    $(menu).on("click", "li a", function(e) {
        var $this = $(this);
        var $list = $(".sidebar-menu").children();
        $list.each(function() {
            $(this).find(".pull-right i").removeClass("icon-up-arrow").addClass("icon-down-arrow");
        })
        var $item = $(this).find(".pull-right i");
        $item.addClass("icon-up-arrow");
        $item.removeClass("icon-down-arrow");

        var checkElement = $this.next();
        if (checkElement.is(".treeview-menu") && checkElement.is(":visible")) {
            checkElement.slideUp(animationSpeed, function() {
                checkElement.removeClass("menu-open");
            });
            checkElement.parent("li").removeClass("active");

        } else if (checkElement.is(".treeview-menu") && !checkElement.is(":visible")) {
            //如果菜单是隐藏的，寻找父元素
            var parent = $this.parents("ul").first();
            //关闭父元素下的所有菜单
            var ul = parent.find("ul:visible").slideUp(animationSpeed);
            ul.removeClass("menu-open");
            //获得父元素
            var parent_li = $this.parent("li");

            //打开目标菜单并添加menu-open class
            checkElement.slideDown(animationSpeed, function() {
                //向父元素添加class active
                checkElement.addClass("menu-open");
                parent.find("li.active").removeClass("active");
                parent_li.addClass("active")

            });
        }
        //如果不是<a>, 阻止页面重定向
        if (checkElement.is(".treeview-menu")) {
            e.preventDefault();
        }

    });
};



$(document).ready(function() {
    $(window).resize(function() {
        var width = $(window).width();
        if (width > 768) {
            $(".left-bar").css("left", "0");
            $(".mask").hide();
        } else {
            $(".left-bar").css("left", "-180px");
            $(".mask").hide();
        }
    });

    let $btn = $(".avatar-small"),
        $mask = $(".mask"),
        $nav = $(".left-bar");
    $btn.click(function() {
        if ($(".left-bar").css("left") == "-180px") {
            $mask.show();
            $nav.css("left", "0");
        } else {
            $mask.hide();
            $nav.css("left", "-180px");
        }
    });

    $mask.click(function() {
        $mask.hide();
        $nav.css("left", "-180px");
    });

})

function goBack() {
    window.location.replace("../../dashboard");
}