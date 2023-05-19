<?php
session_start();
if(isset($_SESSION['auth.user_id'])){
    $response = [
        'is_auth' => true,
        'user' => json_decode($_SESSION['auth.to_front']),
    ];
}else{
    $response = [
        'is_auth' => false,
        'user' => 'null',
    ];
}
echo(json_encode($response));
return;