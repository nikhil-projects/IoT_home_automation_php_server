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

$logs = [];
// getting Appliance Logs
$stmt = $mysqli->prepare("SELECT * FROM `appliance_log` WHERE appliance_id = ? ORDER BY id DESC " );
$stmt->bind_param("i", $data['id']);
$stmt->execute();
$result = $stmt->get_result();
if($result->num_rows > 0 ){
    while($log = $result->fetch_assoc()){
        unset($log['appliance_id']);
        array_push($logs,$log);
    }
}

echo json_encode(array('logs' => $logs));
$stmt->close();
$mysqli->close();
?>