<?php

$Reason=$_POST['Msg'];
$id=$_POST['id'];

include_once("function.php");
include_once("commonFunctions.php");
include_once("mailFunction.php");
$commonfunction=new Common();
$settingValueRejectedStatus = $commonfunction->getSettingValue("Rejected status");
$settingValueMailPath = $commonfunction->getSettingValue("MailPath");
$sign=new Signup();

$qry2="select employee_id from  tw_employee_bankdetails where id='".$id."' ";
$retVal2= $sign->SelectF($qry2,"employee_id");	

$qry3="select employee_name from tw_employee_registration where id='".$retVal2."' ";
$employee_name= $sign->SelectF($qry3,"employee_name");
 
$qry="select value from tw_employee_contact where employee_id='".$retVal2."'";
$employee_email=$sign->SelectF($qry,"value");
$qry4="select account_number,bank_name from tw_employee_bankdetails where id='".$id."' ";
$bankinfo = $sign->FunctionJSON($qry4);
$decodedJSON2 = json_decode($bankinfo);
$account_number = $decodedJSON2->response[0]->account_number;
$bank_name = $decodedJSON2->response[1]->bank_name;
$rest = substr( $account_number, -4); 
	
$qry5="UPDATE tw_employee_bankdetails SET bank_account_status='".$settingValueRejectedStatus."' , remark='".$Reason."' WHERE id='".$id."' ";
$retVal5 = $sign->FunctionQuery($qry5);
$reasonfetchQry="SELECT rm.reason FROM tw_rejected_reason_master rm INNER JOIN tw_company_document cd On cd.remark=rm.id where rm.panel='Bank Doc' AND rm.id='".$Reason."'";
$reasonfetch=$sign->SelectF($reasonfetchQry,"reason");

if($retVal5=="Success"){
	$mailobj=new twMail();
	$subject = "Document Verification Result";
	$myfile = fopen($settingValueMailPath."pgRejected.html", "r");
	$message1 = fread($myfile,filesize($settingValueMailPath."pgRejected.html"));
	$message2 = str_replace("_Employee_",$employee_name,$message1);
	$message3 = str_replace("_BANK_",$bank_name,$message2);
	$message4 = str_replace("_ACCNUMBER_",$rest,$message3);
	$message = str_replace("_REASON_",$reasonfetch,$message4);
	fclose($myfile);
	$mail_response = $mailobj->Mailsend($employee_email,$subject,$message);
	echo "Success";
}
else{
	echo "error";
}


?>