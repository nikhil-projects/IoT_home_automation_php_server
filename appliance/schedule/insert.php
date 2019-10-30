<?php
if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    http_response_code(404);
    exit;
}
require_once '../../helper_functions/helper.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);


$is_active = true;

$mysqli = get_connection();
if ($mysqli->connect_errno) {
    set_error_message('failed to connect to the server',400);
    exit;
}
$stmt = $mysqli->prepare("INSERT INTO `appliance_schedule`(`name`, `periodicity`, `start_time`, `end_time`, `status`, `is_active`, `appliance_id`) VALUES (?,?,?,?,?,?,?)");
$stmt->bind_param("sssssii", $data['name'] , $data['periodicity'] , $data['start_time'] , $data['end_time'] , $data['status'] , $is_active, $data['appliance_id']);
if(!$stmt->execute()){
    set_error_message('invalid input',400);
}
$stmt->close();
$mysqli->close();
?>