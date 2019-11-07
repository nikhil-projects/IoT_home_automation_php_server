<?php
require_once './helper_functions/helper.php';
$mysqli = get_connection();
header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);
if ($mysqli->connect_errno) {
    set_error_message('failed to connect to the server',200);
    exit;
}

$stmt = $mysqli->prepare("SELECT * FROM account WHERE user_name = ? AND password = ? ");
$stmt->bind_param("ss", $data['user_name'],$data['password']);
$stmt->execute();
$result = $stmt->get_result();
if($result->num_rows === 0) {
    set_error_message('invalid username or password',200);
    exit;
}

$user = $result->fetch_assoc();
$output = (object) [
    'id'=> (int) $user['id'],
    'type'=> (int) $user['type']
];

echo json_encode($output);

$stmt->close();
$mysqli->close();
?>