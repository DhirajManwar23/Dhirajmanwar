<?php
$Reason=$_POST['Msg'];
$id=$_POST['id'];

include_once "function.php";
include_once "commonFunctions.php";
include_once "mailFunction.php";
$commonfunction=new Common();
$settingValueRejectedStatus =$commonfunction->getSettingValue("Rejected status"); 
$sign=new Signup();
$settingValueMailPath = $commonfunction->getSettingValue("MailPath");
$settingValueKycDoc=$commonfunction->getSettingValue("KycDoc");
$settingValueComplianceDoc=$commonfunction->getSettingValue("ComplianceDoc");

$qry2="select company_id from tw_compliance_document where id='".$id."' ";
$retVal2= $sign->SelectF($qry2,"company_id");	

$qry3="select CompanyName from tw_company_details where id='".$retVal2."' ";
$company_name= $sign->SelectF($qry3,"CompanyName");
 
$qry="select value from tw_company_contact where company_id='".$retVal2."'";
$employee_email=$sign->SelectF($qry,"value");

$qry4="select document_type,document_number from tw_compliance_document where id='".$id."' ";
$bankinfo = $sign->FunctionJSON($qry4);
$decodedJSON2 = json_decode($bankinfo);
$document_type_master = $decodedJSON2->response[0]->document_type;
$document_number = $decodedJSON2->response[1]->document_number;
$rest = substr( $document_number, -4); 

$qry5="select document_type from tw_compliance_document where id='".$document_type_master."'";
$Document_type=$sign->SelectF($qry5,"document_type");

$ComplianeDocQry="SELECT cm.compliance_document_type FROM tw_compliance_document cd INNER JOIN tw_compliance_type_master cm ON cd.document_type=cm.id where cd.id='".$id."'";
$ComplianeDoc=$sign->SelectF($ComplianeDocQry,"compliance_document_type");
	
$qry="UPDATE tw_compliance_document SET document_verification_status='".$settingValueRejectedStatus."' , remark='".$Reason."' WHERE id='".$id."'";
$retVal = $sign->FunctionQuery($qry);

$reasonfetchQry="SELECT rm.reason FROM tw_rejected_reason_master rm INNER JOIN tw_compliance_document cd On cd.remark=rm.id where rm.panel='".$settingValueComplianceDoc."' AND rm.id='".$Reason."'";
$reasonfetch=$sign->SelectF($reasonfetchQry,"reason");

if($retVal=="Success"){
	$mailobj=new twMail();
	$subject = "Compliance Document Verification Status";
	$myfile = fopen($settingValueMailPath."pgComplianceDocumentReject.html", "r");
	$message1 = fread($myfile,filesize($settingValueMailPath."pgComplianceDocumentReject.html"));
	$message2 = str_replace("_Employee_",$company_name,$message1);
	$message3 = str_replace("_documentname_",$ComplianeDoc,$message2);
	//$message4 = str_replace("_ACCNUMBER_",$rest,$message3);
	$message = str_replace("_REASON_",$reasonfetch,$message3);
	fclose($myfile);
	$mail_response = $mailobj->Mailsend($employee_email,$subject,$message);
	echo "Success";
}
else{
	echo "error";
}

?>