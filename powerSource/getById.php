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
$stmt = $mysqli->prepare("SELECT * FROM `power_source` WHERE id = ? AND account_id = ? AND is_active = ?");
$stmt->bind_param("iii", $data['id'] , $user_id, $is_active);
$stmt->execute();
$result = $stmt->get_result();
if($result->num_rows === 0 ){
    set_error_message('Power Source Not Found',400);
    exit;
}

$source = $result->fetch_assoc();
$source['is_active'] = (boolean) $source['is_active'];


echo json_encode($source);

$stmt->close();
$mysqli->close();
?>