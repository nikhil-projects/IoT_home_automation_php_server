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
$is_active = true;

$allow_access = true;
$mysqli = get_connection();
if ($mysqli->connect_errno) {
    set_error_message('failed to connect to the server',400);
    exit;
}
$stmt=$mysqli->prepare("INSERT INTO notification(`message`, `is_active`, `account_id`,`schedule_id` ) VALUES(?,?,?,?)");
$stmt->bind_param("sisi","Optimised Schedule Available", $is_active, $user_id, $data['schedule_id']);
if(!$stmt->execute()){
    set_error_message('invalid input',400);
    exit;
}

$stmt=$mysqli->prepare("SELECT * FROM notification WHERE schedule_id=?");
$stmt->bind_param("i",$data['schedule_id']);
$stmt->execute();
$result = $stmt->get_result();
if($result->num_rows === 0 ){
    set_error_message('User Not Found',400);
    exit;
}

$account = $result->fetch_assoc();
$stmt=$mysqli->prepare("INSERT INTO optimized_schedule (`notification_id`, `schedule_id`, `periodicity`,`start_time`,`end_time`,`status` ) VALUES(?,?,?,?,?,?)");
$stmt->bind_param("iisss", $account['notification_id'], $data['schedule_id'],'2','2','4','ON');
if(!$stmt->execute()){
    set_error_message('invalid input',400);
    exit;
}

echo json_encode(array('message' => 'Notification Sent'));

$stmt->close();
$mysqli->close();
?>
