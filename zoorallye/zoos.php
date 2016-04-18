<?php
	require_once("../lib/JsonDB.class.php");
	$tableDir = "../databases/zoorallye/zoos.db";
	$tableString = "";
	$handle = fopen($tableDir, "r");
	while($line = fgets($handle)) {
		$tableString .= $line;
	}
	$zoos = json_decode($tableString);
	header('Content-Type: application/json');
	
	$url = $_SERVER['REQUEST_URI'];
	list($_, $folder, $api, $zooId) = explode("/", $url);

	if($zooId != "") {
	
		for($i=0; $i < count($zoos); $i++) {
			$zoo = $zoos[$i];
			if($zoo->id == $zooId) {
				echo "[" . json_encode($zoo) . "]";
			}
		}
	} else {
		echo json_encode($zoos);
	}
?>