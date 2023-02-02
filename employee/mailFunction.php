<?php 
use PHPMailer\PHPMailer\PHPMailer; 
	use PHPMailer\PHPMailer\Exception; 
	 
	require 'PHPMailer/Exception.php'; 
	require 'PHPMailer/PHPMailer.php'; 
	require 'PHPMailer/SMTP.php'; 
  class twMail{
	// Import PHPMailer classes into the global namespace 
	
   
   public function Mailsend($email,$subject,$message,$header='')
   {
   
	$mail = new PHPMailer; 
	 
	$mail->isSMTP();                      // Set mailer to use SMTP 
	$mail->Host = 'mail.trace-waste.com';       // Specify main and backup SMTP servers 
	$mail->SMTPAuth = true;               // Enable SMTP authentication 
	$mail->Username = 'no-reply@trace-waste.com';   // SMTP username 
	$mail->Password = 'No-Reply@2022';   // SMTP password 
	$mail->SMTPSecure = 'tls';            // Enable TLS encryption, `ssl` also accepted 
	$mail->Port = 587;                    // TCP port to connect to 
	
	// Sender info 
	$mail->setFrom('no-reply@trace-waste.com', 'Trace Waste'); 
	$mail->addReplyTo('no-reply@trace-waste.com', 'Trace Waste'); 
	 
	// Add a recipient 
	$searchForValue = ',';
	$stringValue = $email;

if( strpos($stringValue, $searchForValue) !== false ) {
    

	$emails=explode(',' , $email);
	foreach($emails as $i =>$key) {
	
		$mail->addAddress($emails[$i]);

	}
}
else{
	$mail->addAddress($email);
}	
	
	if($header!=''){
		$mail->AddCC($header);
	}
	
	 
	// Set email format to HTML 
	$mail->isHTML(true); 
	 
	// Mail subject 
	$mail->Subject = $subject; 
	 
	// Mail body content 
	$bodyContent = $message; 
	 
	$mail->Body    = $bodyContent; 
	 
	// Send email 
	if($mail->send()) { 
		return 'success'; 
	}
	else { 
		return 'error'.$mail->ErrorInfo; 
	} 
   }

  }
 
?>