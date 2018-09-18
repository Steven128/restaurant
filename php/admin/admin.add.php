<?php
session_start();
$request = $_POST['request'];
$admin_id = $_POST['admin_id'];
if (isset($_SESSION['admin_id'])) {
    if ($admin_id == $_SESSION['admin_id'] && ($_SESSION['admin_type'] == 1 || $_SESSION['admin_type'] == 2)) {
        if ($request == "add_employee") {
            $name = $_POST['name'];
            $gender = $_POST['gender'];
            $age = $_POST['age'];
            $salary = $_POST['salary'];
            $phone_num = $_POST['phone_num'];
            $employee_type = $_POST['employee_type'];
            echo json_encode(array('name' => $name, 'gender' => $gender, 'age' => $age, 'salary' => $salary, 'phone_num' => $phone_num, 'employee_type' => $employee_type));
        }
    }
}
