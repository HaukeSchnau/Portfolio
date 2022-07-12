<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$json = json_encode($_POST);
	$file = fopen(__DIR__ . "/messages/" . time(), "w");
	fwrite($file, $json);
	fclose($file);

	header("Content-Type: application/json");

	echo '{"success": true}';
}
