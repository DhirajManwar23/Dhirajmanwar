<?php
include_once "function.php";
$sign=new Signup();
$type=$_POST['type'];
$requested_id=$_POST['id'];
$ID="";
$RejectedReason="";
$verification_status="";
$option="";
$retVal1="";
if($type=="login"){
	$qry="select status from tw_company_login where id= '".$requested_id."'";
	$Status=$sign->SelectF($qry,"status");
	
	if($Status=="On"){
		$selectedOn="selected";
		$selectedOff="";
	}
	else{
		$selectedOn="";
		$selectedOff="selected";
	}
	
	$option = "<option value='On' ".$selectedOn." >On</option>
			   <option value='Off' ".$selectedOff.">Off</option>";
}
else if($type=="Compliance") {
	$qry="select vsm.id from tw_company_details cd INNER JOIN tw_verification_status_master vsm ON cd.compliance_status=vsm.id where cd.id= '".$requested_id."'";
	$verification_status=$sign->SelectF($qry,"id");
	
	$qry3 = "select ID,verification_status from tw_verification_status_master where visibility = 'true' ORDER by priority, description ASC";
	$retVal1 = $sign->FunctionOption($qry3,$verification_status,'verification_status','ID');
	
	$qry4="select reason from tw_company_details where id='".$requested_id."' ";
	$RejectedReason=$sign->SelectF($qry4,"reason");
	
}
else{
	$qry="select vsm.id from tw_company_details cd INNER JOIN tw_verification_status_master vsm ON cd.Status=vsm.id where cd.id= '".$requested_id."'";
	$verification_status=$sign->SelectF($qry,"id");
	
	$qry3 = "select ID,verification_status from tw_verification_status_master where visibility = 'true' ORDER by priority, description ASC";
	$retVal1 = $sign->FunctionOption($qry3,$verification_status,'verification_status','ID');
	
	$qry4="select reason from tw_company_details where id='".$requested_id."' ";
	$RejectedReason=$sign->SelectF($qry4,"reason");
} 

$responsearray=array();
array_push($responsearray,$RejectedReason,$retVal1,$verification_status,$option);
echo json_encode($responsearray);

?>