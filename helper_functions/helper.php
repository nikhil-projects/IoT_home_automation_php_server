<?php
// gets the connection for the database
function get_connection() {
    return new mysqli('localhost', 'user', 'Sai!250195', 'iot_project');
}

function set_error_message($message,$code) {
    $error = (object) ['message' => $message];
    echo json_encode($error);
    http_response_code($code);
}

// gets the user id from the header
function get_user_id(){
    $headers = apache_request_headers();
    if(array_key_exists('user_id',$headers)){
        return (int) $headers['user_id'];
    } else{
        http_response_code(401);
        return 0;
    }
}

?>