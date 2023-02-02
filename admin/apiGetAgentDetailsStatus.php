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
	
	$qry="select agntl.status from tw_agent_details agntd INNER JOIN tw_agent_login agntl ON agntd.id=agntl.agent_id where agntd.id= '".$requested_id."'";
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
	
	$qry="select vsm.id from tw_agent_details cpm INNER JOIN tw_verification_status_master vsm ON cpm.status=vsm.id where cpm.id= '".$requested_id."'";
	$verification_status=$sign->SelectF($qry,"id");
	
	$qry3 = "select ID,verification_status from tw_verification_status_master where visibility = 'true' ORDER by priority, description ASC";
	$retVal1 = $sign->FunctionOption($qry3,$verification_status,'verification_status','ID');
	
	$qry4="select reason from tw_agent_details where id='".$requested_id."' ";
	$RejectedReason=$sign->SelectF($qry4,"reason");
	
		
}
//echo $retVal1;
$responsearray=array();
array_push($responsearray,$retVal1,$RejectedReason,$verification_status,$option);
echo json_encode($responsearray);
	
?>