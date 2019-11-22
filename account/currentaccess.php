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

$mysqli = get_connection();
if ($mysqli->connect_errno) {
    set_error_message('failed to connect to the server',400);
    exit;
}
$stmt = $mysqli->prepare("SELECT allow_access FROM `account` WHERE id = ? ");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if($result->num_rows === 0 ){
    set_error_message('User Not Found',400);
    exit;
}

$account = $result->fetch_assoc();
echo json_encode($account);
$stmt->close();
$mysqli->close();
?>
