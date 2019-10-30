<?php
if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    http_response_code(404);
    exit;
}
require_once '../helper_functions/helper.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if(($user_id = get_user_id())===0){
    exit;
}

$mysqli = get_connection();
if ($mysqli->connect_errno) {
    set_error_message('failed to connect to the server',400);
    exit;
}
$stmt = $mysqli->prepare("UPDATE `power_source` SET `name`= ?,`type`= ?,`capacity`= ?,`start_time`= ?, `end_time` = ?,`price`= ? WHERE id = ? AND account_id = ?");
$stmt->bind_param("sidssdii", $data['name'] , $data['type'] , $data['capacity'] , $data['start_time'] , $data['end_time'] , $data['price'] , $data['id'] , $user_id);
if(!$stmt->execute()){
    set_error_message('invalid input',400);
}
$stmt->close();
$mysqli->close();
?>