<?php
session_start();
include_once "function.php";	
$sign=new Signup();
include_once "commonFunctions.php";
include_once "mailFunction.php";
$commonfunction=new Common();
$employee_id=$_SESSION['employee_id'];
$request_company_id=$_POST['request_company_id'];
$recycler_company_id=$_POST['recycler_company_id'];
$po_id=$_POST['po_id'];
 date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");
$settingValuePrimaryEmail = $commonfunction->getSettingValue("Primary Email");
$settingValuePendingStatus= $commonfunction->getSettingValue("Pending Status");
$settingValuealloted= $commonfunction->getSettingValue("Alloted");
$settingValueEPRManager= $commonfunction->getSettingValue("EPR Manager");
$settingValueMailPath = $commonfunction->getSettingValue("MailPath");
$ip_address= $commonfunction->getIPAddress();


$empIdQry="select ec.value from tw_employee_registration er INNER JOIN tw_employee_contact ec ON er.id=ec.employee_id where er.company_id='".$request_company_id."' and er.employee_role='".$settingValueEPRManager."' AND ec.contact_field='".$settingValuePrimaryEmail."'";
$empId=$sign->FunctionJSON($empIdQry); 
$decodedJSON1 = json_decode($empId);
$CntQry="select count(*) as cnt from tw_employee_registration er INNER JOIN tw_employee_contact ec ON er.id=ec.employee_id where er.company_id='".$request_company_id."' and er.employee_role='".$settingValueEPRManager."' AND ec.contact_field='".$settingValuePrimaryEmail."'";
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
$Emailstr = substr($emails,0,strlen($emails)-1);


$str=$_POST['str'];
//print_r($str);
$arrStr = array();
$arrStr = explode(",",$str);
$valTotalQuantity=0.00;

for($i=0; $i<count($arrStr); $i++)
{ 
	$arrStrInner = array();
	$arrStrInner = explode("/",$arrStr[$i]);
	$total_quantity=$arrStrInner[1];
	$company_id=$arrStrInner[2];
	$valTotalQuantity = $valTotalQuantity+$total_quantity;
}
  $insertQry="insert into  tw_epr_material_assign_info (employee_id,po_id,requester_company_id,recycler_company_id,total_quantity,date,status,created_by,created_on,created_ip) values('".$employee_id."','".$po_id."','".$request_company_id."','".$recycler_company_id."','".$valTotalQuantity."','".$cur_date."','".$settingValuePendingStatus."','".$employee_id."','".$cur_date."','".$ip_address."')";
 $retVal = $sign->FunctionQuery($insertQry,true);
 if($retVal!=""){
	 for($i=0; $i<count($arrStr); $i++)
		{ 
			$arrStrInner = array();
			$arrStrInner = explode("/",$arrStr[$i]);
			
			$moi_id=$arrStrInner[0];
			$total_quantity=$arrStrInner[1];
			$company_id=$arrStrInner[2];
			$material_id=$arrStrInner[3];
			$state=$arrStrInner[4];
			$city=$arrStrInner[5];
			
			
			$insertQry1="insert into  tw_epr_material_assign_details (m_id,	alloted_company_id,quantity,date,state,city,outward_id,created_by,created_on,created_ip) values('".$retVal."','".$company_id."','".$total_quantity."','".$cur_date."','".$state."','".$city."','".$moi_id."','".$employee_id."','".$cur_date."','".$ip_address."')";
			$retVal2 = $sign->FunctionQuery($insertQry1);
			if($retVal2!="Success"){
				
				 
				echo "error";
			}
			$updateQry="UPDATE tw_material_outward_individual SET assign_status='".$settingValuealloted."'  where id='".$moi_id."'";
			$retVal4 = $sign->FunctionQuery($updateQry);
			
			$qry4="Select cd.CompanyName,cc.value from tw_company_details cd Inner join tw_company_contact cc ON cd.ID=cc.company_id where cd.ID='".$request_company_id."' and cc.contact_field='".$settingValuePrimaryEmail."' and cc.public_visible='true'" ;
			$retVal4 = $sign->FunctionJSON($qry4);
			$decodedJSON = json_decode($retVal4);
			$CompanyName = $decodedJSON->response[0]->CompanyName;
			$email = $decodedJSON->response[1]->value;
			
			$mailobj=new twMail();
			$subject = "New Material assign";
			$myfile = fopen($settingValueMailPath."PgAssignMaterial.html", "r");

			$message = fread($myfile,filesize($settingValueMailPath."PgAssignMaterial.html"));

			$message = str_replace("_Company_",$CompanyName,$message);
			//$message = str_replace("__PATH__",$CompanyName,$message);
			fclose($myfile);
			//
			 $mail_response = $mailobj->Mailsend($Emailstr,$subject,$message,$email); 

		}
		echo "Success";
 }
 else{
	 echo "error";
 }
?>