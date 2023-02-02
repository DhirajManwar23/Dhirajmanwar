<?php

include_once('../assets/phpqrcode/qrlib.php');

class TwQr{
	function GetQrcode($textQR,$path){
		
		$text = $textQR;
		//$path = '../assets/QR/';
		$file = $path."QR.png";
		  
		// $ecc stores error correction capability('L')
		$ecc = 'L';
		$pixel_Size = 10;
		$frame_Size = 10;
		  
		// Generates QR Code and Stores it in directory given
		QRcode::png($text, $file, $ecc, $pixel_Size);
		// Displaying the stored QR code from directory
		Return $file;
	}
	
	
}

?>