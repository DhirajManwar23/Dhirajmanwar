<?php 
session_start();
include_once "function.php";
include_once "commonFunctions.php";	
$sign=new Signup();
$commonfunction=new Common();
include_once "mailFunction.php";

$po_id=$_REQUEST["po_id"];
$settingValuePrimaryEmail = $commonfunction->getSettingValue("Primary Email");
$settingValueMailPath = $commonfunction->getSettingValue("MailPath");

$qry1="Select requester_company_id,recycler_company_id from tw_epr_material_assign_info where po_id='".$po_id."'";
$retVal1 = $sign->FunctionJSON($qry1);
$decodedJSON2 = json_decode($retVal1);
$buyer_id = $decodedJSON2->response[0]->requester_company_id;
$supplier_id = $decodedJSON2->response[1]->recycler_company_id;


$aggEmailQry="select value from tw_company_contact where company_id='".$supplier_id."' and contact_field='".$settingValuePrimaryEmail."' ";
$AggEmail=$sign->SelectF($aggEmailQry,"value"); 

$qry4="Select cd.CompanyName,cc.value from tw_company_details cd Inner join tw_company_contact cc ON cd.ID=cc.company_id where cd.ID='".$buyer_id."' and cc.contact_field='".$settingValuePrimaryEmail."' and cc.public_visible='true'" ;
$retVal4 = $sign->FunctionJSON($qry4);
$decodedJSON = json_decode($retVal4);
$CompanyName = $decodedJSON->response[0]->CompanyName;
$email = $decodedJSON->response[1]->value;

$tableDataQry="SELECT id,total_quantity FROM tw_po_info where id='".$po_id."' ";
$tableDataretVal = $sign->FunctionJSON($tableDataQry);
$decodedJSON3 = json_decode($tableDataretVal);
$id = $decodedJSON3->response[0]->id;
$total_quantity = $decodedJSON3->response[1]->total_quantity;



 

	//
	$mailobj=new twMail();
	$subject = "Purchase Order Accept";
	$myfile = fopen($settingValueMailPath."pgEprPOAccept.html", "r");
    
	$message = fread($myfile,filesize($settingValueMailPath."pgEprPOAccept.html"));
    
	$message = str_replace("_Company_",$CompanyName,$message);
	$message1 = str_replace("_ID_",$id,$message);
	$message2 = str_replace("_QTY_",number_format($total_quantity,2),$message1);

	
	$header=$AggEmail;
	fclose($myfile);
	//
	echo  $mail_response = $mailobj->Mailsend($email,$subject,$message2,$header); 
	
	 //--Mail function end (User) 
	 //echo "Success";
			 
			 
?>