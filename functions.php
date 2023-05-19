<?php
require('config.php');

function get_pdo(){
    global $config;
    return new PDO('mysql:host='.$config['db']['host'].';'
                    .'dbname='.$config['db']['dbname'], 
                    $config['db']['username'], 
                    $config['db']['password'] );
}

// while ($row = $sth->fetch())
// {
//     var_dump($row). "\n". "\n";
// }