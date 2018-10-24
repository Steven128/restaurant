(function($) {
    $.fn.displayInfo = function() {
        var $this = $(this);
        var $length = $this.find("thead").find("th").length;

        $("td.display-info").click(function() {;
            if ($(this).parents("tbody").find("td.display-info-row").length == 0) {
                var $tr = $(this).parent("tr");
                $tr.after("<tr><td class='display-info-row' colspan='" + $length + "'></td></tr>");
                $(".display-info-row").slideDown(() => {
                    var order_id = $(this).parents("tr").find("td.order_id").html();
                    $(".display-info-row").getOrder(order_id);
                });
                $(this).find(".icon-down-arrow").addClass("icon-up-arrow").removeClass("icon-down-arrow");
            } else {
                if ($(this).parent("tr").next().find("td").hasClass("display-info-row")) {
                    var $tr = $(this).parent("tr").next().find("td");
                    $(this).find(".icon-up-arrow").addClass("icon-down-arrow").removeClass("icon-up-arrow");
                    $tr.slideUp(() => {
                        $tr.parent("tr").remove();
                    });
                } else {
                    $(this).parents("tbody").find(".icon-up-arrow").addClass("icon-down-arrow").removeClass("icon-up-arrow");
                    var $tr = $(this).parents("tbody").find("td.display-info-row");
                    $(this).parents("tbody").find(".icon-up-arrow").addClass("icon-down-arrow").removeClass("icon-up-arrow");
                    $tr.slideUp(() => {
                        $tr.parent("tr").remove();
                    });
                    var $tr2 = $(this).parent("tr");
                    $tr2.after("<tr><td class='display-info-row' colspan='" + $length + "'></td></tr>");
                    $(".display-info-row").slideDown(() => {
                        var order_id = $(this).parents("tr").find("td.order_id").html();
                        console.log(order_id)
                        $(".display-info-row").getOrder(order_id);
                    });
                    $(this).find(".icon-down-arrow").addClass("icon-up-arrow").removeClass("icon-down-arrow");
                }
            }
        });

    }

    $.fn.getOrder = function(order_id) {
        var $this = $(this);
        $.ajax({
            type: "GET",
            url: "../../php_test/demo.php?order_id=" + order_id,
            dataType: "JSON",
            success: (e) => {
                console.log(e);

            },
            error: (err) => {
                console.log(err);

            }
        })
    }
})(jQuery);