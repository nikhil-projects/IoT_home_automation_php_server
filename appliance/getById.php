<?php
if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    http_response_code(404);
    exit;
}
require_once '../helper_functions/helper.php';

header('Content-Type: application/json');

if(($user_id = get_user_id())===0){
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$is_active = true;

$mysqli = get_connection();
if ($mysqli->connect_errno) {
    set_error_message('failed to connect to the server',400);
    exit;
}
$stmt = $mysqli->prepare("SELECT * FROM `appliance` WHERE id = ? AND account_id = ? AND is_active = ?");
$stmt->bind_param("iii", $data['id'] , $user_id, $is_active);
$stmt->execute();
$result = $stmt->get_result();
if($result->num_rows === 0 ){
    set_error_message('Appliance Not Found',400);
    exit;
}

$appliance = $result->fetch_assoc();
$appliance['is_active'] = (boolean) $appliance['is_active'];
$appliance['logs'] = [];
// getting Appliance Logs
$stmt = $mysqli->prepare("SELECT * FROM `appliance_log` WHERE appliance_id = ? ");
$stmt->bind_param("i", $appliance['id']);
$stmt->execute();
$result = $stmt->get_result();
if($result->num_rows > 0 ){
    while($log = $result->fetch_assoc()){
        unset($log['appliance_id']);
        array_push($appliance['logs'],$log);
    }
}

echo json_encode($appliance);

$stmt->close();
$mysqli->close();
?>