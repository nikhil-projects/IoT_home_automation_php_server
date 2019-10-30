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
$stmt = $mysqli->prepare("SELECT * FROM `appliance_schedule` WHERE id = ? AND is_active = ?");
$stmt->bind_param("ii", $data['id'], $is_active);
$stmt->execute();
$result = $stmt->get_result();
if($result->num_rows === 0 ){
    set_error_message('Appliance Schedule Not Found',400);
    exit;
}

$schedule = $result->fetch_assoc();
$schedule['is_active'] = (boolean) $schedule['is_active'];


echo json_encode($schedule);

$stmt->close();
$mysqli->close();
?>