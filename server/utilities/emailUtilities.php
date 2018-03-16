<?php

$root = realpath($_SERVER['DOCUMENT_ROOT']);
require_once "$root/server/config.php";

function sendHtmlFormattedEmail($toAddress, $subject, $body, $fromAddress = 'noreply@vita.unl.edu') {
	$headers = "From: $fromAddress\r\n";
	$headers .= 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1';

	if (PROD) {
		mail($toAddress, $subject, $body, $headers);
	}
}
