<?php
//tell page to want javascript.
header('Content-Type: application/javascript');
//include connection string (PDO).
include 'PDO.php';

$getplants = $conn->prepare("SELECT * FROM plant");
$getplants->execute();


?>