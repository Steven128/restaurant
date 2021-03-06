//返回主页
function goBack() {
    window.location.href = "../dashboard/";
}

function update_employee(employee_id) {
    employee_id = Encrypt(employee_id, "employee_id");
    var admin_id = Encrypt(getUserInfo().admin_id, "admin_id");
    var href = encodeURIComponent("employee_id=" + employee_id + "&adm=" + admin_id);
    $.pjax({
        url: "updateEmployee?" + href,
        container: '.main-bar'
    });
}

function update_dish(dish_id) {
    dish_id = Encrypt(dish_id, "dish_id");
    var admin_id = Encrypt(getUserInfo().admin_id, "admin_id");
    var href = encodeURIComponent("dish_id=" + dish_id + "&adm=" + admin_id);
    $.pjax({
        url: "updateDish?" + href,
        container: '.main-bar'
    });
}

function update_table(table_id) {
    table_id = Encrypt(table_id, "table_id");
    var admin_id = Encrypt(getUserInfo().admin_id, "admin_id");
    var href = encodeURIComponent("table_id=" + table_id + "&adm=" + admin_id);
    $.pjax({
        url: "updateTable?" + href,
        container: '.main-bar'
    });
}