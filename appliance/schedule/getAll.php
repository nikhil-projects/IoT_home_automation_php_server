<?php
if($_SERVER['REQUEST_METHOD'] !== 'GET'){
    http_response_code(404);
    exit;
}
require_once '../../helper_functions/helper.php';

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
$is_active = true;
$stmt = $mysqli->prepare("SELECT * FROM `appliance_schedule` WHERE appliance_id = ? and is_active = ?");
$stmt->bind_param("ii", $data['id'], $is_active);
$stmt->execute();
$result = $stmt->get_result();
$output =[];
while($schedule = $result->fetch_assoc()){
    $schedule['is_active'] = (boolean) $schedule['is_active'];
    array_push($output,$schedule);
}

echo json_encode($output);

$stmt->close();
$mysqli->close();
?>