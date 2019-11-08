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
$is_active = true;

$allow_access = true;
$mysqli = get_connection();
if ($mysqli->connect_errno) {
    set_error_message('failed to connect to the server',400);
    exit;
}
$stmt = $mysqli->prepare("SELECT * FROM `account` WHERE allow_access = ? AND is_active = ?");
$stmt->bind_param("ii",$allow_access, $is_active);
$stmt->execute();
$result = $stmt->get_result();
$users=[];
if($result->num_rows > 0 ){
    while($user = $result->fetch_assoc()){
        array_push($users,$user);
    }
}


echo json_encode(array('users' => $users));

$stmt->close();
$mysqli->close();
?>