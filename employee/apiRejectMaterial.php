<?php
session_start();
include_once("function.php");
include_once "commonFunctions.php";
$commonfunction=new Common();
$sign=new Signup();
$valquery=$_POST["valquery"];
$TableID=$_POST["TableID"];
$textreason=$_POST["reason"];
$created_by=$_SESSION["employee_id"];
$ip_address= $commonfunction->getIPAddress();
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d ");
$blank=$commonfunction->getSettingValue("NotAllotted");

$RejMatIdQry="SELECT mad.outward_id,mai.po_id,mad.alloted_company_id,mad.quantity,mai.reason,moi.material_id from tw_epr_material_assign_info mai INNER JOIN tw_epr_material_assign_details mad ON mad.m_id=mai.id INNER JOIN tw_material_outward_individual moi ON moi.material_outward_id=mad.outward_id where mai.id='".$TableID."'";

$rejectmaterial=$sign->FunctionJSON($RejMatIdQry);
$cntQry="SELECT count(*) as cnt from tw_epr_material_assign_info mai INNER JOIN tw_epr_material_assign_details mad ON mad.m_id=mai.id INNER JOIN tw_material_outward_individual moi ON moi.material_outward_id=mad.outward_id where mai.id='".$TableID."'";
$cnt=$sign->Select($cntQry);

$decodedJSON2 = json_decode($rejectmaterial);
$count = 0;
$i1 = 1;
$x1=$cnt;
$insert="";
while($x1>=$i1){

	$Material_outward_id = $decodedJSON2->response[$count]->outward_id;
	$count=$count+1;
	$po_id = $decodedJSON2->response[$count]->po_id;
	$count=$count+1;
	$alloted_company_id = $decodedJSON2->response[$count]->alloted_company_id;
	$count=$count+1;
	$quantity = $decodedJSON2->response[$count]->quantity;
	$count=$count+1;
	$reason = $decodedJSON2->response[$count]->reason;
	$count=$count+1;
	$material_id = $decodedJSON2->response[$count]->material_id;
	$count=$count+1;


	$insertqry="insert into tw_rejected_material (outward_id,po_id,alloted_company_id,material_id,quantity,reason,created_by,created_on,created_ip) values('".$Material_outward_id."','".$po_id."','".$alloted_company_id."','".$material_id."','".$quantity."','".$textreason."','".$created_by."','".$cur_date."','".$ip_address."')";
	$insert=$sign->FunctionQuery($insertqry); 
	
	$updagteQry="update tw_material_outward_individual set assign_status='".$blank."' where material_outward_id='".$Material_outward_id."' ";
	$update=$sign->FunctionQuery($updagteQry);
	
	$i1=$i1+1;
	
}
	$retVal1 = $sign->FunctionQuery($valquery);

	if($retVal1=="Success" && $insert=="Success" ){
		echo "Success";
	}
	else{
		echo "error";
	}	 
?>
