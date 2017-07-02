<?php
//tell page to want javascript.
header('Content-Type: application/javascript');
//include connection string (PDO).
include 'PDO.php';
//get the persons id to later use in query3.
$getcorrectperson = $conn->prepare("SELECT id FROM person WHERE uuid = '".$_GET['uuid']."';");
$getcorrectperson->execute();

$idperson = $getcorrectperson->fetchColumn();

//get the pot id to later use in query3.
$getcorrectpot = $conn->prepare("SELECT id FROM pot WHERE mac = '".$_GET['mac']."';");
$getcorrectpot->execute();

$idpot = $getcorrectpot->fetchColumn();

//delete person and pot ids from link table. return success or error.
$query3 = $conn->exec("DELETE FROM pot_has_person WHERE pot_id='".$idpot."' AND person_id='".$idperson."';");
if($query3 !== false){
	echo $_GET['callback']."(".json_encode("success").");";
}else{
	echo $_GET['callback']."(".json_encode(array("error" => "Could not remove pot. Please restart the app and try again.")).");";
}
?>