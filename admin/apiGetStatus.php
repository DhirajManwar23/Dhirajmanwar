<?php
include_once "function.php";
$sign=new Signup();
$type=$_POST['Paneltype'];
$requested_id=$_POST['id'];
$Reason=$_POST['Reason'];

if($type=="Company"){
	$qry="select status from  tw_company_details where ID= '".$requested_id."'";
	$ID=$sign->SelectF($qry,"status");

}
else{
	$qry="select employee_status from  tw_employee_registration where id= '".$requested_id."'";
	$ID=$sign->SelectF($qry,"employee_status");
}
$qry3 = "select ID,verification_status from tw_verification_status_master where visibility = 'true' ORDER by priority, description ASC";
$retVal1 = $sign->FunctionOption($qry3,$ID,'verification_status','ID');
$response = array();

$qry4="select reason from tw_company_details where id='".$requested_id."' ";
$RejectedReason=$sign->SelectF($qry4,"reason");


echo $retVal1."-".$RejectedReason.",".$ID;

?>