<?php
header('Content-Type: application/javascript');
include 'PDO.php';	
$query1 = $conn->prepare("SELECT * FROM pot JOIN pot_has_person pp on pp.pot_id = pot.id WHERE pp.person_id = (SELECT id FROM person WHERE `uuid` = '".$_GET['uuid']."');");
$query1->execute();

$mac = array();
$deleted = array();

while ($row = $query1->fetch(PDO::FETCH_ASSOC)) {		
	$mac[] = $row['mac'];
	$deleted[] = $row['deleted'];
}

if($query1 !== false){
	echo $_GET['callback']."(".json_encode(array("mac" => $mac, "deleted" => $deleted)).");";
}else{
	echo $_GET['callback']."(".json_encode("failed").");";
}
?>