//返回主页
function goBack() {
    window.location.href = "../dashboard/";
}

function update_employee(employee_id) {
    employee_id = Encrypt(employee_id, "employee_id")
    var href = encodeURIComponent("employee_id=" + employee_id);
    $.pjax({
        url: "updateEmployee?" + href,
        container: 'html'
    });
}

function update_dish(dish_id) {
    dish_id = Encrypt(dish_id, "dish_id")
    var href = encodeURIComponent("dish_id=" + dish_id);
    $.pjax({
        url: "updateDish?" + href,
        container: 'html'
    });
}

function update_table(table_id) {
    table_id = Encrypt(table_id, "table_id")
    var href = encodeURIComponent("table_id=" + table_id);
    $.pjax({
        url: "updateTable?" + href,
        container: 'html'
    });
}