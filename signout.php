<?php
require_once('functions.php');
$pdo = get_pdo();
session_start();
if(isset($_SESSION['auth.user_id'])){
    $count = $pdo->exec("UPDATE users SET PHPSESSID = NULL WHERE id = ".$_SESSION['auth.user_id'].";");
    session_destroy();
    session_start();
    include('status.php');
    return;
}