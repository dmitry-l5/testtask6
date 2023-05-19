<?php
//ndex):66 {"email":"qwe@qwe.qwe","user_name":"ccccc","password":"ccccccccc"}

require_once('functions.php');
$pdo = get_pdo();
if(empty( $_POST['email']) || empty($_POST['name'])){
    $response = [
        'err' =>[
            "name or email is empty",
        ]
    ];
    echo(json_encode($response));
    return;
}
$stmt = $pdo->prepare("SELECT count(*) FROM users WHERE name = :name OR email = :email");
$stmt->bindParam(':email',$_POST['email'], PDO::PARAM_STR, 50);
$stmt->bindParam(':name', $_POST['name'], PDO::PARAM_STR, 50);
$stmt->execute();
if($stmt->fetch()[0] > 0){
    $response = [
        'err' =>[
            "user already exist",
        ]
    ];
    echo(json_encode($response));
    return;
}else{
    $file_name = null;
    if(isset($_FILES['photo']) && !empty($_FILES['photo'])){
        echo(json_encode($_FILES));
        return
        $name_tmp = $_FILES['photo']['tmp_name'];
        $file_name = 'img/'.$_FILES['photo']['name'];
        move_uploaded_file($name_tmp, $file_name);
    }
    $stmt = $pdo->prepare("INSERT INTO users (email, name, password, photo) 
    VALUES (:email, :name, :password, :photo) ");
    // $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $stmt->bindParam(':email',  $_POST['email'], PDO::PARAM_STR);
    $stmt->bindParam(':name',   $_POST['name'], PDO::PARAM_STR);
    $stmt->bindParam(':photo',  $file_name, PDO::PARAM_STR);
    $stmt->bindParam(':password', password_hash($_POST['password'], PASSWORD_DEFAULT), PDO::PARAM_STR, 100);
    if($stmt->execute()){
        session_start();
        //_________________________________
        $stmt = $pdo->prepare("SELECT * FROM users WHERE name = :name;");
        $stmt->bindParam(':name', $_POST['name'], PDO::PARAM_STR, 50);
        $stmt->execute();
        
        if($user = $stmt->fetch()){
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
        }
                //_________________________________
                //_________________________________
        
        
        return;
    }else{
        $response = [
            'err' =>[
                "have errors",
            ]
        ];
        echo(json_encode($response));
        return;
    }

}