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
$stmt = $mysqli->prepare("SELECT * FROM `notification` WHERE account_id = ? and is_active = ?");
$stmt->bind_param("ii", $user_id, $is_active);
$stmt->execute();
$result = $stmt->get_result();
$output =[];
while($notification = $result->fetch_assoc()){
    $notification['is_active'] = (boolean) $notification['is_active'];
    array_push($output,$notification);
}

echo json_encode($output);

$stmt->close();
$mysqli->close();
?>