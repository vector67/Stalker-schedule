<?php	$query = "INSERT INTO `hits` (
	`IP` ,
	`Page` ,
	`Time`,
	`Client`
	)
	VALUES (
	'".$_SERVER['REMOTE_ADDR']."',
	'".$_SERVER['REQUEST_URI']."',
	CURRENT_TIMESTAMP,
	'".$_SERVER['HTTP_USER_AGENT']."'
	);";
	mysqli_query($mysqli, $query);?>