function nextStep() {
    var $name = $("#name").val();
    if ($name == "" && $("#name").attr("class") == "") {
        $("#name").addClass("error");
        $("#name").after("<label id=\"name-error\" class=\"error\" for=\"name\">此为必填项</label>");
    } else if ($name != "") {
        $("#name").removeClass("error");
        $("#name-error").remove()
        $.ajax({
            type: "POST",
            url: "../php/reset_password.php",
            dataType: "JSON",
            data: {
                "request": "check",
                "name": $name
            },
            success: (e) => {
                console.log(e)
                if (e.message == "exists") {
                    var phpsessid = e.session;
                    $name = encodeURI($name);
                    var loc = "permissions_validate.html?" + encodeURIComponent("wrap=restaurant2018" + phpsessid + "&user=" + $name);
                    window.location.replace(loc);
                } else if (e.message == "does_not_exist") {
                    $("#name").val("");
                    $("#name").addClass("error");
                    $("#name").after("<label id=\"name-error\" class=\"error\" for=\"name\">此用户不存在</label>");
                }
            },
            error: (err) => {}
        })
    }
}