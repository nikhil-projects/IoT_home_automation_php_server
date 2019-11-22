<?php
if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    http_response_code(404);
    exit;
}
require_once '../../helper_functions/helper.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if(($user_id = get_user_id())===0){
    exit;
}
$is_active = true;

$mysqli = get_connection();
if ($mysqli->connect_errno) {
    set_error_message('failed to connect to the server',400);
    exit;
}
$stmt = $mysqli->prepare("UPDATE `appliance_schedule` SET `name`=?,`periodicity`=?,`start_time`=?,`end_time`=?,`status`=? WHERE account_id = ? AND id = ?");
$stmt->bind_param("sssssii", $data['name'] , $data['periodicity'] , $data['start_time'] , $data['end_time'] , $data['status'] , $user_id, $data['id']);
if(!$stmt->execute()){
    set_error_message('invalid input',400);
}
echo json_encode(array('message' => "Schedule Updated"));
$stmt->close();
$mysqli->close();
?>
