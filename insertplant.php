<?php
//tell page to want javascript.
header('Content-Type: application/javascript');
//include connection string (PDO).
include 'PDO.php';

//gets the id of the pot that needs to be edited.
$getcorrectpot = $conn->prepare("SELECT id FROM pot WHERE mac = '".$_GET['mac']."';");
$getcorrectpot->execute();
$idpot = $getcorrectpot->fetchColumn();
	
if($_GET['plantname'] != '0'){
	//gets the correct plant based on selected in list.
	$getcorrectplant = $conn->prepare("SELECT * FROM plant WHERE name = '".$_GET['plantname']."';");
	$getcorrectplant->execute();
	$idplant = $getcorrectplant->fetchColumn();

	//same query 2 times solves weird duplication bug.
	$getcorrectplantagain = $conn->prepare("SELECT * FROM plant WHERE name = '".$_GET['plantname']."';");
	$getcorrectplantagain->execute();	
	

	$name = array();
	$moistureneed = array();
	$interval = array();
	$image = array();
	
	//loop through plants and store in array.
	while ($row = $getcorrectplantagain->fetch(PDO::FETCH_ASSOC)) {		
		$name[] = $row['name'];
		$moistureneed[] = $row['moisture_need'];
		$interval[] = $row['interval'];
		$image[] = $row['image'];
	}

	//deletes previous plant that was in the pot.
	$query2 = $conn->exec("DELETE FROM pot_has_plant WHERE pot_id='".$idpot."';");
	//insert plant and pot ids into link table. return array with plant data(to be send to mqtt) or error.
	$query3 = $conn->exec("INSERT IGNORE INTO pot_has_plant(`pot_id`,`plant_id`,`plant_name`)VALUES ('".$idpot."','".$idplant."','".$_GET['userpotname']."');");
	if($query3 !== false){
		echo $_GET['callback']."(".json_encode(array("name" => $name, "moistureneed" => $moistureneed, "interval" => $interval, "image" => $image)).");";
	}else{
		echo $_GET['callback']."(".json_encode(array("error" => "Could not add plant to the pot. Please restart the app and try again.")).");";
	}
}else{
	//when chosen for no plant, delete from link table.
	$query3 = $conn->exec("DELETE FROM pot_has_plant WHERE pot_id='".$idpot."';");
	if($query3 !== false){
		echo $_GET['callback']."(".json_encode("deleted").");";
	}else{
		echo $_GET['callback']."(".json_encode(array("error" => "Could not remove the plant from the pot. Please restart the app and try again.")).");";
	}
}
?>