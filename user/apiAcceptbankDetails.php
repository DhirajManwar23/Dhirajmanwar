<?php
 $id=$_POST['employee_id'];
 $Reason=$_POST['Msg'];

include("function.php");
  include("commonFunctions.php");
  include("mailFunction.php");
  $commonfunction=new Common();
  $sign=new Signup();
	$settingValueVstatus=$commonfunction->getSettingValue("Verified Status");
	$settingValueMailPath=$commonfunction->getSettingValue("MailPath");
	$qry2="select employee_id from  tw_employee_bankdetails where id='".$id."' ";
    $retVal2= $sign->SelectF($qry2,"employee_id");
	
	$qry3="select employee_name from tw_employee_registration where id='".$retVal2."' ";
    $employee_name= $sign->SelectF($qry3,"employee_name");
	
	$qry1="select value from tw_employee_contact where employee_id='".$retVal2."'";
    $employee_email=$sign->SelectF($qry1,"value");
	
	$qry4="select account_number,bank_name from tw_employee_bankdetails where id='".$id."' ";
	 $bankinfo = $sign->FunctionJSON($qry4);
	  $decodedJSON2 = json_decode($bankinfo);
	   $account_number = $decodedJSON2->response[0]->account_number;
	   $bank_name = $decodedJSON2->response[1]->bank_name;
	   $rest = substr( $account_number, -4); 
	
	 $qry="UPDATE tw_employee_bankdetails SET bank_account_status='".$settingValueVstatus."' WHERE id='".$id."' ";
	 
	 $retVal = $sign->FunctionQuery($qry);
	 if($retVal=="Success"){
		 
	$mailobj=new twMail();
	$subject = "Document Verification Result";
	$myfile = fopen($settingValueMailPath."pgAccept.html", "r");
	$message1 = fread($myfile,filesize($settingValueMailPath."pgAccept.html"));
	
	$message2 = str_replace("_Employee_",$employee_name,$message1);
	$message3 = str_replace("_BANK_",$bank_name,$message2);
	$message = str_replace("_ACCNUMBER_",$rest,$message3);
	fclose($myfile);
	$mail_response = $mailobj->Mailsend($employee_email,$subject,$message);
	echo "Success";
	 }
	 else{
		 echo "error";
	 }

?>
