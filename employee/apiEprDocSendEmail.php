<?php 
session_start();
include_once "function.php";
include_once "commonFunctions.php";	
$sign=new Signup();
$commonfunction=new Common();
include_once "mailFunction.php";

$po_id=$_POST["po_id"];
$date=$_POST["date"];
$settingValuePrimaryEmail = $commonfunction->getSettingValue("Primary Email");
$settingValueMailPath = $commonfunction->getSettingValue("MailPath");
$settingValueEPRManager= $commonfunction->getSettingValue("EPR Manager");
$qry1="Select requester_company_id,recycler_company_id from tw_epr_material_assign_info where po_id='".$po_id."'";
$retVal1 = $sign->FunctionJSON($qry1);
$decodedJSON2 = json_decode($retVal1);
$buyer_id = $decodedJSON2->response[0]->requester_company_id;
$supplier_id = $decodedJSON2->response[1]->recycler_company_id;


$empIdQry="select ec.value from tw_employee_registration er INNER JOIN tw_employee_contact ec ON er.id=ec.employee_id where er.company_id='".$supplier_id."' and er.employee_role='".$settingValueEPRManager."' AND ec.contact_field='".$settingValuePrimaryEmail."'";
$empId=$sign->FunctionJSON($empIdQry); 
$decodedJSON1 = json_decode($empId);
$CntQry="select count(*) as cnt from tw_employee_registration er INNER JOIN tw_employee_contact ec ON er.id=ec.employee_id where er.company_id='".$supplier_id."' and er.employee_role='".$settingValueEPRManager."' AND ec.contact_field='".$settingValuePrimaryEmail."'";
$cnt=$sign->Select($CntQry);
$i1 = 1;
$x1=$cnt;
$it=1;
$count1 = 0;
$emails = "";
while($x1>=$i1){
	$value = $decodedJSON1->response[$count1]->value;
	$emails.=$value.",";
	$count1=$count1+1;
	 $i1=$i1+1;
 }
$str = substr($emails,0,strlen($emails)-1);

$qry4="Select cd.CompanyName,cc.value from tw_company_details cd Inner join tw_company_contact cc ON cd.ID=cc.company_id where cd.ID='".$buyer_id."' and cc.contact_field='".$settingValuePrimaryEmail."' and cc.public_visible='true'" ;
$retVal4 = $sign->FunctionJSON($qry4);
$decodedJSON = json_decode($retVal4);
$CompanyName = $decodedJSON->response[0]->CompanyName;
$email = $decodedJSON->response[1]->value;

$tableDataQry="SELECT id as id,total_quantity FROM tw_po_info where id='".$po_id."' ";
$tableDataretVal = $sign->FunctionJSON($tableDataQry);
$decodedJSON3 = json_decode($tableDataretVal);
$id = $decodedJSON3->response[0]->id;
$total_quantity = $decodedJSON3->response[1]->total_quantity;



 

	//
	$mailobj=new twMail();
	$subject = "Purchase Order Accept";
	$myfile = fopen($settingValueMailPath."pgEPRPODocumentsAccepted.html", "r");
    
	$message = fread($myfile,filesize($settingValueMailPath."pgEPRPODocumentsAccepted.html"));
    
	$message = str_replace("_Company_",$CompanyName,$message);
	$message2 = str_replace("_month_",$date ,$message);

	
	//$header=$AggEmail;
	fclose($myfile);
	//
	 $mail_response = $mailobj->Mailsend($str,$subject,$message2,$email); 
	
	 //--Mail function end (User) 
	 echo "Success";
			 
			 
?>