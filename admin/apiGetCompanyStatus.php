<?php
include_once "function.php";
$sign=new Signup();

$requested_id=$_POST['id'];
$type=$_POST['type'];
$retVal1="";
$RejectedReason="";
$verification_status="";
$option="";

if($type=="login"){
$qry="select status from  tw_company_login where company_id= '".$requested_id."'";
$status=$sign->SelectF($qry,"status");

$selectedOn="";
$selectedOff="";

if($status=="On"){
	$selectedOn="selected";
	$selectedOff="";
}
else{
	$selectedOn="";
	$selectedOff="selected";
}
	$option = "<option value='On' ".$selectedOn.">On</option>
				<option value='Off' ".$selectedOff.">Off</option>";
}

else{
	
	$qry="select Status from tw_company_details where ID= '".$requested_id."'";
	$verification_status=$sign->SelectF($qry,"Status");
	
	$qry3 = "select ID,verification_status from tw_verification_status_master where visibility = 'true' ORDER by priority, description ASC";
	$retVal1 = $sign->FunctionOption($qry3,$verification_status,'verification_status','ID');
	
	$qry4="select reason from tw_company_details where ID='".$requested_id."'";
	$RejectedReason=$sign->SelectF($qry4,"reason");

}
$responsearray=array();
array_push($responsearray,$retVal1,$RejectedReason,$verification_status,$option);
echo json_encode($responsearray);
?>