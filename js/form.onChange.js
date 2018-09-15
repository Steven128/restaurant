(function($) {
    $.fn.onChange = function() {
        var $this = $(this);
        var inputArr = $this.find("input:text");
        var data = [];
        for (var i = 0; i < inputArr.length; i++) {
            var itemName = $(inputArr[i]).attr('name');
            var itemId = $(inputArr[i]).attr('id');
            var itemValue = $(inputArr[i]).val();
            data.push({ id: itemId, type: "text", name: itemName, value: itemValue });
        }
        var radioArr = $this.find("input:radio:checked");
        for (var i = 0; i < radioArr.length; i++) {
            var itemName = $(radioArr[i]).attr('name');
            var itemValue = $(radioArr[i]).val();
            data.push({ id: "", type: "radio", name: itemName, value: itemValue });
        }
        var selectArr = $this.find("select option:selected");
        for (var i = 0; i < selectArr.length; i++) {
            var itemName = $(selectArr[i]).parent("select").attr("name");
            var itemValue = $(selectArr[i]).val();
            data.push({ id: "", type: "select", name: itemName, value: itemValue });
        }
        $.each(data, (index, item) => {
            if (item.type == "text") {
                var $item = $("#" + item.id);
                $item.change(() => {
                    var newVal = $("#" + item.id).val();
                    if (newVal != item.value) {
                        if ($item.siblings(".update-form-changed").length == 0)
                            $item.after("<i class=\"iconfont icon-checked update-form-changed\"></i>");
                    } else
                        $item.siblings(".update-form-changed").remove();
                })
            } else if (item.type == "radio") {
                var $item = $("input:radio[name=" + item.name + "]");
                $item.change(() => {
                    var $radioItem = $("input:radio[name=" + item.name + "]:checked");
                    var newVal = $radioItem.val();
                    if (newVal != item.value) {
                        if ($radioItem.parents(".form-group").find(".update-form-changed").length == 0) {
                            $radioItem.parents(".form-group").append("<i class=\"iconfont icon-checked update-form-changed\"></i>");
                        }
                    } else
                        $radioItem.parents(".form-group").find(".update-form-changed").remove();
                })
            } else if (item.type == "select") {
                var $item = $("select[name=" + item.name + "]");
                $item.change(() => {
                    var $selectItem = $("select[name=" + item.name + "] option:selected")
                    var newVal = $selectItem.val();
                    if (newVal != item.value) {
                        if ($selectItem.parents(".form-group").find(".update-form-changed").length == 0) {
                            $selectItem.parents(".form-group").append("<i class=\"iconfont icon-checked update-form-changed\"></i>");
                        }
                    } else
                        $selectItem.parents(".form-group").find(".update-form-changed").remove();
                })
            }
        })

    }
})(jQuery);