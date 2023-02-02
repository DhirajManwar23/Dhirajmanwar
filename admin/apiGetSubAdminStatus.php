<?php
include_once "function.php";
$sign=new Signup();

$requested_id=$_POST['id'];
$type=$_POST['type'];

$RejectedReason="";
$verification_status="";
$option="";
$retVal1="";
if($type=="login"){
	$qry="select Status from tw_admin_login where admin_id= '".$requested_id."'";
	$status=$sign->SelectF($qry,"Status");
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
	
	$qry="select vsm.id from tw_sub_admin sd INNER JOIN tw_verification_status_master vsm ON sd.sub_admin_status=vsm.id where sd.id= '".$requested_id."'";
	$verification_status=$sign->SelectF($qry,"id");
	
	$qry3 = "select ID,verification_status from tw_verification_status_master where visibility = 'true' ORDER by priority, description ASC";
	$retVal1 = $sign->FunctionOption($qry3,$verification_status,'verification_status','ID');
	
	$qry4="select reason from tw_sub_admin where id='".$requested_id."' ";
	$RejectedReason=$sign->SelectF($qry4,"reason");
	
}
$responsearray=array();
array_push($responsearray,$RejectedReason,$retVal1,$verification_status,$option);
echo json_encode($responsearray);

?>