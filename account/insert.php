<?php
if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    http_response_code(404);
    exit;
}
require_once '../helper_functions/helper.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);


$is_active = true;

$mysqli = get_connection();
if ($mysqli->connect_errno) {
    set_error_message('failed to connect to the server',400);
    exit;
}
$stmt = $mysqli->prepare("INSERT INTO `account`(`name`, `user_name`, `password`, `email`, `phone`, `allow_access`, `type`, `is_active`) VALUES (?,?,?,?,?,?,?,?)");
$stmt->bind_param("sssssiii", $data['name'] , $data['user_name'] , $data['password'] , $data['email'] , $data['phone'] , $data['allow_access'] , $data['type'] , $is_active);
if(!$stmt->execute()){
    set_error_message('invalid input',400);
}
$stmt->close();
$mysqli->close();
?>