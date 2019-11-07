<?php
if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    http_response_code(404);
    exit;
}
require_once '../helper_functions/helper.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);


$is_active = true;
$allow_access = false;
$data['type'] = (int)$data['type'];

$mysqli = get_connection();
if ($mysqli->connect_errno) {
    set_error_message('failed to connect to the server',200);
    exit;
}
$stmt = $mysqli->prepare("INSERT INTO `account`(`name`, `user_name`, `password`, `email`, `phone`, `allow_access`, `type`, `is_active`) VALUES (?,?,?,?,?,?,?,?)");
$stmt->bind_param("sssssiii", $data['name'] , $data['user_name'] , $data['password'] , $data['email'] , $data['phone'] , $allow_access , $data['type'] , $is_active);
if(!$stmt->execute()){
    set_error_message('invalid input',200);
    exit;
}

echo json_encode(array('message' => "User registered"));
$stmt->close();
$mysqli->close();
?>