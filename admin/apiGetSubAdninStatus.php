<?php
include_once "function.php";
$sign=new Signup();

$requested_id=$_POST['id'];
$type=$_POST['type'];
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
	echo $option = "<option value='On' ".$selectedOn.">On</option>
					<option value='Off' ".$selectedOff.">Off</option>";
}

else{
	
	$qry="select sub_admin_status from tw_sub_admin where id= '".$requested_id."'";
	$verification_status=$sign->SelectF($qry,"sub_admin_status");
	
	$qry3 = "select ID,verification_status from tw_verification_status_master where visibility = 'true' ORDER by priority, description ASC";
	$retVal1 = $sign->FunctionOption($qry3,$verification_status,'verification_status','ID');
	
	echo $retVal1;
		
}

?>