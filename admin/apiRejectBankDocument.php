<?php
$Reason=$_POST['Msg'];
$id=$_POST['id'];

include_once "function.php";
include_once "commonFunctions.php";
include_once "mailFunction.php";
$commonfunction=new Common();
$settingValueRejectedStatus = $commonfunction->getSettingValue("Rejected status");
$settingValueMailPath = $commonfunction->getSettingValue("MailPath");
$settingValueBankDoc=$commonfunction->getSettingValue("BankDoc");
$settingValueKycDoc=$commonfunction->getSettingValue("KycDoc");
$sign=new Signup();

$qry2="select company_id from  tw_company_bankdetails where id='".$id."' ";
$retVal2= $sign->SelectF($qry2,"company_id");	

$qry3="select CompanyName from tw_company_details where id='".$retVal2."' ";
$employee_name= $sign->SelectF($qry3,"CompanyName");
 
$qry="select value from  tw_company_contact where company_id='".$retVal2."'";
$employee_email=$sign->SelectF($qry,"value");

$qry4="select account_number,bank_name from tw_company_bankdetails where id='".$id."' ";
$bankinfo = $sign->FunctionJSON($qry4);
$decodedJSON2 = json_decode($bankinfo);
$account_number = $decodedJSON2->response[0]->account_number;
$bank_name = $decodedJSON2->response[1]->bank_name;
$rest = substr( $account_number, -4); 
	
$qry="UPDATE tw_company_bankdetails SET bank_account_status='".$settingValueRejectedStatus."' , remark='".$Reason."' WHERE id='".$id."' ";
$retVal = $sign->FunctionQuery($qry);
$reasonfetchQry="SELECT rm.reason FROM tw_rejected_reason_master rm INNER JOIN tw_company_bankdetails cd On cd.remark=rm.id where rm.panel='".$settingValueBankDoc."' AND rm.id='".$Reason."'";
$reasonfetch=$sign->SelectF($reasonfetchQry,"reason");

if($retVal=="Success"){
	$mailobj=new twMail();
	$subject = "Bank Document Verification Status";
	$myfile = fopen($settingValueMailPath."pgBankRejected.html", "r");
	$message1 = fread($myfile,filesize($settingValueMailPath."pgBankRejected.html"));
	$message2 = str_replace("__Employee__",$employee_name,$message1);
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