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
$is_active = true;

$mysqli = get_connection();
if ($mysqli->connect_errno) {
    set_error_message('failed to connect to the server',400);
    exit;
}

// update the appliance table
$stmt = $mysqli->prepare("UPDATE `appliance` SET `status`= ? WHERE id = ? AND account_id = ?");
$stmt->bind_param("sii",$data['status'] , $data['id'] , $user_id);
if(!$stmt->execute()){
    set_error_message('invalid input',400);
    exit;
}

// insert into logs
$stmt = $mysqli->prepare("INSERT INTO `appliance_log`(`description`, `appliance_id`) VALUES (?,?)");
$stmt->bind_param("si",$data['status'] , $data['id']);
if(!$stmt->execute()){
    set_error_message('invalid input',400);
    exit;
}
echo json_encode(array('message' => "Updated"));
// need to calculate the power consumption and update the table power_consumption

$stmt->close();
$mysqli->close();
?>