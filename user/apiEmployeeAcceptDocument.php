<?php
$id=$_POST['id'];
$Reason=$_POST['Msg'];

include_once("function.php");
include_once("commonFunctions.php");
include_once("mailFunction.php");
$commonfunction=new Common();
$sign=new Signup();
$settingValueVstatus=$commonfunction->getSettingValue("Verified Status");
$settingValueMailPath = $commonfunction->getSettingValue("MailPath");
$qry2="select employee_id from  tw_employee_document where id='".$id."' ";
$retVal2= $sign->SelectF($qry2,"employee_id");

$qry3="select employee_name from tw_employee_registration where id='".$retVal2."' ";
$employee_name= $sign->SelectF($qry3,"employee_name");

$qry1="select value from tw_employee_contact where employee_id='".$retVal2."'";
$employee_email=$sign->SelectF($qry1,"value");

$qry4="select document_type,document_number from tw_employee_document where id='".$id."' ";
$bankinfo = $sign->FunctionJSON($qry4);
$decodedJSON2 = json_decode($bankinfo);
$document_type_master = $decodedJSON2->response[0]->document_type;
$document_number = $decodedJSON2->response[1]->document_number;
$rest = substr( $document_number, -4); 
$qry5="select document_type_value from tw_document_type_master where id='".$document_type_master."'";
$Document_type=$sign->SelectF($qry5,"document_type_value"); 

$qry="UPDATE tw_employee_document SET document_verification_status='".$settingValueVstatus."' WHERE id='".$id."' ";

$retVal = $sign->FunctionQuery($qry);
if($retVal=="Success"){
	 
$mailobj=new twMail();
$subject = "Document Verification Result";
$myfile = fopen($settingValueMailPath ."pgAcceptDoc.html", "r");
$message1 = fread($myfile,filesize($settingValueMailPath."pgAcceptDoc.html"));
$message2 = str_replace("_Employee_",$employee_name,$message1);
$message3 = str_replace("_BANK_",$Document_type,$message2);
$message = str_replace("_ACCNUMBER_",$rest,$message3);

fclose($myfile);
$mail_response = $mailobj->Mailsend($employee_email,$subject,$message);
echo "Success";
}
else{
 echo "error";
}

?>
