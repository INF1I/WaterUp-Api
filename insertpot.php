<?php
header('Content-Type: application/javascript');
include 'PDO.php';	
$query1 = $conn->exec("INSERT IGNORE INTO person(`uuid`)VALUES ('".$_GET["uuid"]."');");
$lastperson = $conn->lastInsertId(); 
if($query1 !== false){
	$query2 = $conn->exec("INSERT IGNORE INTO pot(`mac`,`deleted`)VALUES ('".$_GET["mac"]."','0');");
	$lastpot = $conn->lastInsertId(); 
	if($query2 !== false){
		$query3 = $conn->exec("INSERT INTO pot_has_person(`pot_id`,`person_id`)VALUES ('".$lastpot."','".$lastperson."');");
		if($query3 !== false){
			echo $_GET['callback']."(".json_encode("success").");";
		}else{
			echo $_GET['callback']."(".json_encode("failed").");";
		}
	}else{
		echo $_GET['callback']."(".json_encode("failed").");";
	}
}else{
	echo $_GET['callback']."(".json_encode("failed").");";
}
?>