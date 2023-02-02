<?php
 $id=$_POST['id'];
 $Reason=$_POST['Msg'];
include_once "function.php";
include_once "commonFunctions.php";
include_once "mailFunction.php";
$commonfunction=new Common();
$sign=new Signup();
$settingValueVstatus=$commonfunction->getSettingValue("Verified Status");
$settingValueMailPath=$commonfunction->getSettingValue("MailPath");

    $qry2="select company_id from tw_compliance_document where id='".$id."' ";
    $retVal2= $sign->SelectF($qry2,"company_id");
	
	$qry3="select CompanyName from tw_company_details where id='".$retVal2."' ";
    $employee_name= $sign->SelectF($qry3,"CompanyName");
	
	$qry1="select value from tw_company_contact where company_id='".$retVal2."'";
    $employee_email=$sign->SelectF($qry1,"value");
	
	$qry4="select document_type,document_number from tw_compliance_document where id='".$id."' ";
	$bankinfo = $sign->FunctionJSON($qry4);
	$decodedJSON2 = json_decode($bankinfo);
	$document_type_master = $decodedJSON2->response[0]->document_type;
	$document_number = $decodedJSON2->response[1]->document_number;
	$rest = substr( $document_number, -4); 
   
	$qry5="select document_type from tw_compliance_document where document_type='".$document_type_master."'";
	$Document_type=$sign->SelectF($qry5,"document_type");
	
	$ComplianeDocQry="SELECT cm.compliance_document_type FROM tw_compliance_document cd INNER JOIN tw_compliance_type_master cm ON cd.document_type=cm.id where cd.id='".$id."'";
	$ComplianeDoc=$sign->SelectF($ComplianeDocQry,"compliance_document_type");
	
	$qry="UPDATE tw_compliance_document SET document_verification_status='".$settingValueVstatus."' WHERE id='".$id."' ";
 
   $retVal = $sign->FunctionQuery($qry);
   if($retVal=="Success"){
		 
	$mailobj=new twMail();
	$subject = "Compliance Document Verification Status";
	$myfile = fopen($settingValueMailPath."pgComplianceDocumentAccept.html", "r");
	$message1 = fread($myfile,filesize($settingValueMailPath."pgComplianceDocumentAccept.html"));
	$message2 = str_replace("_Employee_",$employee_name,$message1);
	$message3 = str_replace("_documentname_",$ComplianeDoc,$message2);
	//$message = str_replace("_ACCNUMBER_",$rest,$message3);

	fclose($myfile);
	$mail_response = $mailobj->Mailsend($employee_email,$subject,$message3);
	echo "Success";
	}
	else{
	 echo "error";
	}

?>
