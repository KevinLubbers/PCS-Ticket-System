<?php
require_once 'pdoconfig.php';
 
try {
    global $conn;
    $conn = new PDO("mysql:host=$host;port=8889;dbname=$dbname", $username, $password);
    
}
 catch (PDOException $pe) {
    die("Could not connect to the database $dbname :" . $pe->getMessage());
}

?>