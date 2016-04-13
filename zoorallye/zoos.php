<?php
	require_once("./lib/JsonDB.class.php");

	$tableDir = "./databases/zoorallye/zoos.db";

	$tableString = "";

	$handle = fopen($tableDir, "r");
	while($line = fgets($handle)) {
		$tableString .= $line;
	}

	$table = json_decode($tableString);

	header('Content-Type: application/json');
	echo json_encode($table);
?>