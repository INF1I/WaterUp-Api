<?php
//tell page to want javascript.
header('Content-Type: application/javascript');
//include connection string (PDO).
include 'PDO.php';
//insert person if that person never added a pot before. else ignore insert.	
$query1 = $conn->exec("INSERT IGNORE INTO person(`uuid`)VALUES ('".$_GET["uuid"]."');");

//get the persons id to later use in query3.
$getcorrectperson = $conn->prepare("SELECT id FROM person WHERE uuid = '".$_GET['uuid']."';");
$getcorrectperson->execute();

$idperson = $getcorrectperson->fetchColumn();

//if query1 fails return failed.
if($query1 == false){
	echo $_GET['callback']."(".json_encode("failed").");";
}

//insert pot if that pot has not been added already. else ignore insert.
$query2 = $conn->exec("INSERT IGNORE INTO pot(`mac`,`deleted`)VALUES ('".$_GET["mac"]."','0');"); 

//get the pot id to later use in query3.
$getcorrectpot = $conn->prepare("SELECT id FROM pot WHERE mac = '".$_GET['mac']."';");
$getcorrectpot->execute();

$idpot = $getcorrectpot->fetchColumn();

//if query2 fails return failed.
if($query2 == false){
	echo $_GET['callback']."(".json_encode("failed").");";
}

//insert person and pot ids into link table. return success or failed.
$query3 = $conn->exec("INSERT INTO pot_has_person(`pot_id`,`person_id`)VALUES ('".$idpot."','".$idperson."');");
if($query3 !== false){
	echo $_GET['callback']."(".json_encode("success").");";
}else{
	echo $_GET['callback']."(".json_encode("failed").");";
}
?>