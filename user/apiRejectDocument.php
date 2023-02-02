<?php
include_once "function.php";
include_once "commonFunctions.php";
include_once "mailFunction.php";
$commonfunction=new Common();

$Reason=$_POST['Msg'];
$id=$_POST['id'];

$settingValueRejectedStatus =$commonfunction->getSettingValue("Rejected status"); 
$settingValueMailPath = $commonfunction->getSettingValue("MailPath");
$sign=new Signup();

$qry2="select employee_id from  tw_employee_document where id='".$id."' ";
$retVal2= $sign->SelectF($qry2,"employee_id");	

$qry3="select employee_name from tw_employee_registration where id='".$retVal2."' ";
$employee_name= $sign->SelectF($qry3,"employee_name");
 
$qry="select value from tw_employee_contact where employee_id='".$retVal2."'";
$employee_email=$sign->SelectF($qry,"value");
$qry4="select document_type,document_number from tw_employee_document where id='".$id."' ";
$bankinfo = $sign->FunctionJSON($qry4);
$decodedJSON2 = json_decode($bankinfo);
$document_type_master = $decodedJSON2->response[0]->document_type;
$document_number = $decodedJSON2->response[1]->document_number;
$rest = substr( $document_number, -4); 


$qry5="select document_type_value from tw_document_type_master where id='".$document_type_master."'";
$Document_type=$sign->SelectF($qry5,"document_type_value");
	
$qry="UPDATE tw_employee_document SET document_verification_status='".$settingValueRejectedStatus."' , remark='".$Reason."' WHERE id='".$id."' ";
$retVal = $sign->FunctionQuery($qry);

$reasonfetchQry="SELECT rm.reason FROM tw_rejected_reason_master rm INNER JOIN tw_company_document cd On cd.remark=rm.id where rm.panel='Kyc Doc' AND rm.id='".$Reason."'";
$reasonfetch=$sign->SelectF($reasonfetchQry,"reason");
if($retVal=="Success"){
	$mailobj=new twMail();
	$subject = "Document Verification Result";
	$myfile = fopen($settingValueMailPath."pgRejectionDoc.html", "r");
	$message1 = fread($myfile,filesize($settingValueMailPath."pgRejectionDoc.html"));
	$message2 = str_replace("_Employee_",$employee_name,$message1);
	$message3 = str_replace("_BANK_",$Document_type,$message2);
	$message4 = str_replace("_ACCNUMBER_",$rest,$message3);
	$message = str_replace("_REASON_",$reasonfetchQry,$message4);
	fclose($myfile);
	$mail_response = $mailobj->Mailsend($employee_email,$subject,$message);
	echo "Success";
}
else{
	echo "error";
}


?>