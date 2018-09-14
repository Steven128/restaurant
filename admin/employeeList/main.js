function display_employee(obj, employee_id) {}

function update_employee(employee_id) {
    var href = encodeURIComponent("employee_id=" + employee_id);
    window.location.href = "updateEmployee?" + href;
}

function delete_employee(employee_id) {

}