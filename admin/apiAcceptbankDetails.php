<?php
 $id=$_POST['reqid'];
 $Reason=$_POST['Msg'];

include("function.php");
  include("commonFunctions.php");
  include("mailFunction.php");
  $commonfunction=new Common();
  $sign=new Signup();
	$settingValueVstatus=$commonfunction->getSettingValue("Verified Status");
	$settingValueMailPath=$commonfunction->getSettingValue("MailPath");
	$qry2="select company_id from  tw_company_bankdetails where id='".$id."' ";
    $retVal2= $sign->SelectF($qry2,"company_id");
	
	$qry3="select CompanyName from tw_company_details where id='".$retVal2."' ";
    $employee_name= $sign->SelectF($qry3,"CompanyName");
	
	$qry1="select value from tw_company_contact where company_id='".$retVal2."'";
    $employee_email=$sign->SelectF($qry1,"value");
	
	$qry4="select account_number,bank_name from tw_company_bankdetails where id='".$id."' ";
	 $bankinfo = $sign->FunctionJSON($qry4);
	  $decodedJSON2 = json_decode($bankinfo);
	   $account_number = $decodedJSON2->response[0]->account_number;
	   $bank_name = $decodedJSON2->response[1]->bank_name;
	   $rest = substr( $account_number, -4); 
	
	 $qry="UPDATE tw_company_bankdetails SET bank_account_status='".$settingValueVstatus."' WHERE id='".$id."' ";
	 
	 $retVal = $sign->FunctionQuery($qry);
	 if($retVal=="Success"){
		 
	$mailobj=new twMail();
	$subject = "Bank Document Verification Status";
	$myfile = fopen($settingValueMailPath."pgBankAcceptDoc.html", "r");
	$message1 = fread($myfile,filesize($settingValueMailPath."pgBankAcceptDoc.html"));
	
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
