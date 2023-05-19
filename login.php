<?php 
session_start();
require_once('functions.php');
if(isset($_SESSION['auth.user_id'])){
    $response = [
        'err' =>[
            'Please logout before login',
            ' user id = '.$_SESSION['auth.user_id'],
        ]
        ];
        echo(json_encode($response));
    return;
}
$timeout = 60;
if(isset($_SESSION['auth.try_timeout'])){
    if((time() - $_SESSION['auth.try_timeout']) > $timeout){
        $_SESSION["auth.try_count"] = 0;
        unset($_SESSION['auth.try_timeout']);
    }else{
        $response = [
            'err' =>[
                'timout error',
                'wait : '.$timeout - (time() - $_SESSION['auth.try_timeout'])."second",
            ]
            ];
        echo(json_encode($response));
        return;
    }
}

$_SESSION["auth.try_count"] = isset($_SESSION["auth.try_count"])?($_SESSION["auth.try_count"] + 1): 1;
if($_SESSION["auth.try_count"] > 3){
    $_SESSION['auth.try_timeout'] = time();
    $response = [
        'err' =>[
            'timout error : try again later',
            'wait : '.$timeout - (time() - $_SESSION['auth.try_timeout'])."second",
        ]
        ];
    echo(json_encode($response));
    return;
}

$pdo = get_pdo();
$stmt = $pdo->prepare("SELECT * FROM users WHERE name = :name;");
$stmt->bindParam(':name', $_POST['name'], PDO::PARAM_STR, 50);
$stmt->execute();

if($user = $stmt->fetch()){
    if(password_verify($_POST['password'], $user['password'])){
        if($user['PHPSESSID'] != NULL){
            session_destroy();
            session_id($user['PHPSESSID']);
            session_start();
        }else{
            $stmt = $pdo->prepare("UPDATE users SET PHPSESSID = :session_id WHERE id = ".$user['id'].";");
            $stmt->bindParam(':session_id', session_id(), PDO::PARAM_STR);
            $stmt->execute();
        }
        
        $_SESSION['auth.user_id'] = $user['id'];
        $user_arr = array();
        $user_arr['id'] = $user['id'];
        $user_arr['name'] = $user['name'];
        $user_arr['email'] = $user['email'];
        $user_arr['photo'] = $user['photo'];
        $_SESSION['auth.to_front'] = json_encode($user_arr);

        $_SESSION["auth.try_count"] = 0;
        unset($_SESSION['auth.try_timeout']);
        include('status.php');
        return;

    }else{
        $response = [
            'err' =>[
                'uncorect password',
            ]
            ];
        echo(json_encode($response));
        return;
    }
}else{
    $response = [
        'err' =>[
            "user dos't exist",
        ]
        ];
    echo(json_encode($response));
    return;
    echo("user dos't exist");
}