<?php
	require_once("connect.php"); 
	#error_reporting(0);

	#$headers = getallheaders();

	#if($headers["token"] != $zoorallye_token){
	#	exit('{"login":0,"errno":0,"error":"Invalid access token!"}');
	#}

	$slider = mysqli_query($db_zoo_app, "SELECT * FROM questions_slider");
	$slider_arr = array();
	while($row = mysqli_fetch_assoc($slider)){
		array_push($slider_arr, array(
			'id' => $row["id"],
			'question' => $row["question"],
			'min' => $row["min"],
			'max' => $row["max"],
			'step' => $row["step"],
			'answer' => $row["answer"],
			'enclosure' => utf8_encode($row["enclosure"]),
			'accepted' => filter_var($row["accepted"], FILTER_VALIDATE_BOOLEAN)
		));
	}
	$slider_out = json_encode($slider_arr);

	$radio = mysqli_query($db_zoo_app, "SELECT * FROM questions_radio");
	$radio_arr = array();
	while($row = mysqli_fetch_assoc($radio)){
		array_push($radio_arr, array(
			'id' => $row["id"],
			'question' => $row["question"],
			'answer' => $row["answer"],
			'falseAnswers' => $row["falseAnswers"],
			'enclosure' => utf8_encode($row["enclosure"]),
			'accepted' => filter_var($row["accepted"], FILTER_VALIDATE_BOOLEAN)
		));
	}
	$radio_out = json_encode($radio_arr);

	$checkbox = mysqli_query($db_zoo_app, "SELECT * FROM questions_checkbox");
	$checkbox_arr = array();
	while($row = mysqli_fetch_assoc($checkbox)){
		array_push($checkbox_arr, array(
			'id' => $row["id"],
			'question' => $row["question"],
			'answers' => $row["answers"],
			'falseAnswers' => $row["falseAnswers"],
			'enclosure' => utf8_encode($row["enclosure"]),
			'accepted' => filter_var($row["accepted"], FILTER_VALIDATE_BOOLEAN)
		));
	}
	$checkbox_out = json_encode($checkbox_arr);

	$trueFalse = mysqli_query($db_zoo_app, "SELECT * FROM questions_true_false");
	$trueFalse_arr = array();
	while($row = mysqli_fetch_assoc($trueFalse)){
		array_push($trueFalse_arr, array(
			'id' => $row["id"],
			'question' => $row["question"],
			'answer' => filter_var($row["answer"], FILTER_VALIDATE_BOOLEAN),
			'enclosure' => utf8_encode($row["enclosure"]),
			'accepted' => filter_var($row["accepted"], FILTER_VALIDATE_BOOLEAN)
		));
	}
	$trueFalse_out = json_encode($trueFalse_arr);

	$sort = mysqli_query($db_zoo_app, "SELECT * FROM questions_sort");
	$sort_arr = array();
	while($row = mysqli_fetch_assoc($sort)){
		array_push($sort_arr, array(
			'id' => $row["id"],
			'question' => $row["question"],
			'answers' => $row["answers"],
			'enclosure' => utf8_encode($row["enclosure"]),
			'accepted' => filter_var($row["accepted"], FILTER_VALIDATE_BOOLEAN)
		));
	}
	$sort_out = json_encode($sort_arr);
	
	$text = mysqli_query($db_zoo_app, "SELECT * FROM questions_text");
	$text_arr = array();
	while($row = mysqli_fetch_assoc($text)){
		array_push($text_arr, array(
			'id' => $row["id"],
			'question' => $row["question"],
			'answer' => $row["answer"],
			'enclosure' => utf8_encode($row["enclosure"]),
			'accepted' => filter_var($row["accepted"], FILTER_VALIDATE_BOOLEAN)
		));
	}
	$text_out = json_encode($text_arr);

	echo '{"login":1,"slider":' . $slider_out . ',"radio":' . $radio_out . ',"checkbox":' . $checkbox_out . ',"trueFalse":' . $trueFalse_out . ',"sort":' . $sort_out . ',"text":' . $text_out . '}';
?>
