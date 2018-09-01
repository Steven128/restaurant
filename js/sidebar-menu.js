$.sidebarMenu = function(menu) {
    var animationSpeed = 300;

    $(menu).on("click", "li a", function(e) {
        var $this = $(this);
        var idAttr = $this.attr("id");
        if (idAttr != undefined && !idAttr.match("update") && !idAttr.match("display")) {
            idAttr = idAttr.match(/menu-(.*?)-item/)[1];
            var $box = $(".main-bar").children();
            $box.each(function() {
                var $thisID = $(this).attr("id")
                if ($thisID != undefined) {
                    if ($(this).hasClass("box-active")) {
                        $(this).removeClass("box-active");
                    }
                    if ($thisID.match(idAttr)) {
                        $("li a").each(function() {
                            $(this).removeClass("outerActive");
                            $(this).removeClass("innerActive");
                        });
                        if ($this.attr("id") == "menu-overview-item") {
                            $this.addClass("outerActive");
                        } else {
                            $this.addClass("innerActive");
                        }
                        $(this).addClass("box-active");
                        if ($(window).width() < 768) {
                            $(".left-bar").css("left", "-180px");
                            $(".mask").css("display", "none");
                        }



                    }
                }
            });
        }
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
                parent_li.addClass("active");

            });
        }
        //如果不是<a>, 阻止页面重定向
        if (checkElement.is(".treeview-menu")) {
            e.preventDefault();
        }
    });
};