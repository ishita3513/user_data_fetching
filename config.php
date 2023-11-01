<?php

$db_name = "mysql:host=localhost;dbname=user_data_db";
$username = "root";
$password = "";

$conn = new PDO($db_name, $username, $password);
if($conn){
    echo "Successfully connected";
}

?>