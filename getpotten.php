<?php
//tell page to want javascript.
header('Content-Type: application/javascript');
//include connection string (PDO).
include 'PDO.php';	
//select all pots from current device.
$query1 = $conn->prepare("SELECT * FROM pot JOIN pot_has_person pp on pp.pot_id = pot.id WHERE pp.person_id = (SELECT id FROM person WHERE `uuid` = '".$_GET['uuid']."');");
$query1->execute();

//create empty arrays to store results in.
$mac = array();
$deleted = array();

//loop through results and fill arrays accordingly.
while ($row = $query1->fetch(PDO::FETCH_ASSOC)) {		
	$mac[] = $row['mac'];
	$deleted[] = $row['deleted'];
}

//if query failed return false, else return the filled array as jsonp.
if($query1 !== false){
	if ($query1->rowCount() > 0) {
		echo $_GET['callback']."(".json_encode(array("mac" => $mac, "deleted" => $deleted)).");";
	}else{
		echo $_GET['callback']."(".json_encode("failed").");";
	}
}else{
	echo $_GET['callback']."(".json_encode("failed").");";
}
?>