<?php
if($_SERVER['REQUEST_METHOD'] !== 'GET'){
    http_response_code(404);
    exit;
}
require_once '../helper_functions/helper.php';

header('Content-Type: application/json');

if(($user_id = get_user_id())===0){
    exit;
}
$is_active = true;
$mysqli = get_connection();
if ($mysqli->connect_errno) {
    set_error_message('failed to connect to the server',400);
    exit;
}
$stmt = $mysqli->prepare("SELECT * FROM `account` WHERE id = ? AND is_active = ?");
$stmt->bind_param("ii", $user_id, $is_active);
$stmt->execute();
$result = $stmt->get_result();
if($result->num_rows === 0 ){
    set_error_message('User Not Found',400);
    exit;
}

$account = $result->fetch_assoc();
$account['is_active'] = (boolean) $account['is_active'];


echo json_encode($account);

$stmt->close();
$mysqli->close();
?>