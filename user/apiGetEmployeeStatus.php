<?php
include_once "function.php";
$sign=new Signup();

$requested_id=$_POST['id'];
$type=$_POST['type'];
if($type=="login"){
$qry="select el.status from tw_employee_registration er INNER JOIN tw_employee_login el ON er.id=el.employee_id where er.id= '".$requested_id."'";
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
echo $option = "<option value='On' ".$selectedOn.">On</option>
				<option value='Off' ".$selectedOff.">Off</option>";
}

else{
	
	$qry="select vsm.id from tw_employee_registration er INNER JOIN tw_verification_status_master vsm ON er.status=vsm.id where er.id= '".$requested_id."'";
	$verification_status=$sign->SelectF($qry,"id");
	
	$qry3 = "select ID,verification_status from tw_verification_status_master where visibility = 'true' ORDER by priority, description ASC";
	$retVal1 = $sign->FunctionOption($qry3,$verification_status,'verification_status','ID');
	
	echo $retVal1;
		
}

?>