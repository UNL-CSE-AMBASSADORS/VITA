<?php

downloadFile('2021_F4868.pdf');

function downloadFile($fileName) {
	$root = realpath($_SERVER['DOCUMENT_ROOT']);
	$filePath = "$root/server/download/documents/$fileName";
	
	if (file_exists($filePath)) {
		@ob_clean();
		@ob_end_clean();
		
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($filePath));
		readfile($filePath);
		
		die();
	} else {
		die('The requested file does not exist.');
	}
}