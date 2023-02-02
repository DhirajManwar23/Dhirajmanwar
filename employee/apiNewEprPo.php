<?php 
session_start();
include_once "function.php";
include_once "commonFunctions.php";	
$sign=new Signup();
$commonfunction=new Common();
include_once "mailFunction.php";
$LogIncompany_id=$_SESSION['company_id'];
$supplier_id=$_POST['supplier_id'];
$EMP_EMAIL=$_POST['EMP_EMAIL'];

$settingValuePrimaryEmail = $commonfunction->getSettingValue("Primary Email");
$settingValueMailPath = $commonfunction->getSettingValue("MailPath");


$qry4="Select cd.CompanyName,cc.value from tw_company_details cd Inner join tw_company_contact cc ON cd.ID=cc.company_id where cd.ID='".$LogIncompany_id."' and cc.contact_field='".$settingValuePrimaryEmail."' and cc.public_visible='true'" ;
$retVal4 = $sign->FunctionJSON($qry4);
$decodedJSON = json_decode($retVal4);
$CompanyName = $decodedJSON->response[0]->CompanyName;
$companyemail = $decodedJSON->response[1]->value;

// echo $emailqry="SELECT value FROM tw_employee_contact where id='".$employee_id."' AND contact_field='".$settingValuePrimaryEmail."'";
// $email=$sign->SelectF($emailqry,"value");

$suplierCompanyQry="SELECT CompanyName FROM `tw_company_details` where id='".$supplier_id."'";
$suplierCompany=$sign->SelectF($suplierCompanyQry,"CompanyName");
	//
	$mailobj=new twMail();
	$subject = "New Epr po created";
	$myfile = fopen($settingValueMailPath."pgNewEPRPO.html", "r");

	$message = fread($myfile,filesize($settingValueMailPath."pgNewEPRPO.html"));

	$message1 = str_replace("_Company_",$CompanyName,$message);
	$message2 = str_replace("_Supplier_",$suplierCompany,$message1);
	
	fclose($myfile);
	//
	 $mail_response = $mailobj->Mailsend($EMP_EMAIL,$subject,$message2); 
	
	 //--Mail function end (User) 
	// echo "Success";
			 
			 
?>