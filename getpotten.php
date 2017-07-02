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
$pot_id = array();

//loop through results and fill arrays accordingly.
while ($row = $query1->fetch(PDO::FETCH_ASSOC)) {		
	$mac[] = $row['mac'];
	$deleted[] = $row['deleted'];
	$pot_id[] = $row['id'];
}

//has potten
if ($query1->rowCount() > 0) {
	$new_pot_id = implode(", ", $pot_id);
	$query2 = $conn->prepare("SELECT * FROM pot_has_plant WHERE pot_id IN ('".$new_pot_id."');");
	$query2->execute();

	//create empty arrays to store results in.
	$potid = array();
	$plantname = array();
	$plantid = array();
	
	//loop through results and fill arrays accordingly.
	while ($row = $query2->fetch(PDO::FETCH_ASSOC)) {		
		$potid[] = $row['pot_id'];
		$potname[] = $row['plant_name'];
		$plantid[] = $row['plant_id'];
	}

	//potten have plants
	if($query2->rowCount() > 0) {
	
		//get all the plants info
		$new_plant_id = implode(", ", $plantid);
		$query3 = $conn->prepare("SELECT * FROM plant WHERE id IN ('".$new_plant_id."');");
		$query3->execute();

		//create empty arrays to store results in.
		$image = array();
		
		//loop through results and fill arrays accordingly.
		while ($row = $query3->fetch(PDO::FETCH_ASSOC)) {		
			$image[] = $row['image'];
		}
	
		if ($query3->rowCount() > 0) {
			echo $_GET['callback']."(".json_encode(array("hasplants" => 1, "id" => $potid, "image" => $image, "name" => $potname, "mac" => $mac, "deleted" => $deleted)).");";
		}else{
			echo $_GET['callback']."(".json_encode(array("error" => "Could not get pot data. Please restart the app and try again.")).");";
		}
		
	}else{
		echo $_GET['callback']."(".json_encode(array("hasplants" => 0, "mac" => $mac, "deleted" => $deleted)).");";
	}
}else{
	echo $_GET['callback']."(".json_encode(array("error" => "Your device does not have any pots added. Tap the plus icon to add a new pot.")).");";
}
?>