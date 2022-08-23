<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$json = json_encode($_POST);
	$file = fopen(__DIR__ . "/messages/" . time(), "w");
	fwrite($file, $json);
	fclose($file);

	header("Content-Type: application/json");

	//Load Composer's autoloader
	require '../vendor/autoload.php';

	//Create an instance; passing `true` enables exceptions
	$mail = new PHPMailer(true);

	try {
		//Server settings
		$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
		$mail->isSMTP();                                            //Send using SMTP
		$mail->Host       = getenv("SMTP_HOST");                     //Set the SMTP server to send through
		$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
		$mail->Username   = getenv("SMTP_USERNAME");                 //SMTP username
		$mail->Password   = getenv("SMTP_PASSWORD");                 //SMTP password
		$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
		$mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

		//Recipients
		$mail->setFrom('hauke@schnau-lilienthal.de', $_POST["name"]);
		$mail->addAddress('hauke@schnau-lilienthal.de');
		$mail->addReplyTo($_POST["email"], $_POST["name"]);

		//Content
		$mail->isHTML(true);                                  //Set email format to HTML
		$mail->Subject = $_POST["subject"];
		$mail->Body    = $_POST["message"];
		$mail->AltBody = $_POST["message"];

		$mail->send();
		echo '{"success": true}';
	} catch (Exception $e) {
		echo "{\"success\": false, \"message\": \"{$mail->ErrorInfo}\"}";
	}
}
