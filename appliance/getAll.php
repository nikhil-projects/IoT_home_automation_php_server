<?php
if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    http_response_code(404);
    exit;
}
require_once '../helper_functions/helper.php';
$mysqli = get_connection();
header('Content-Type: application/json');

if ($mysqli->connect_errno) {
    set_error_message('failed to connect to the server',400);
    exit;
}
if(($user_id = get_user_id())===0){
    exit;
}
$is_active = true;
$stmt = $mysqli->prepare("SELECT * FROM `appliance` WHERE account_id = ? and is_active = ?");
$stmt->bind_param("ii", $user_id, $is_active);
$stmt->execute();
$result = $stmt->get_result();
$output =[];
while($appliance = $result->fetch_assoc()){
    $appliance['is_active'] = (boolean) $appliance['is_active'];
    array_push($output,$appliance);
}

echo json_encode($output);

$stmt->close();
$mysqli->close();
?>