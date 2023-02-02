<?php
include_once "function.php";
$sign=new Signup();
$requested_id=$_POST['id'];
$Reason=$_POST['Reason'];
$txtAdminStatus=$_POST['txtAdminStatus'];
$valcreated_by=$_POST['valcreated_by'];
$valcreated_on=$_POST['valcreated_on'];
$valcreated_ip=$_POST['valcreated_ip'];

$qry="select sub_admin_status as subadminstatus from  tw_sub_admin where id= '".$requested_id."'";
$subadminstatus=$sign->SelectF($qry,"subadminstatus");

	if($subadminstatus==$txtAdminStatus){
		echo "Exist";
	}else{


	$qry="Update tw_sub_admin set sub_admin_status='".$txtAdminStatus."',reason='".$Reason."',modified_by='".$valcreated_by."',modified_on='".$valcreated_on."',modified_ip='".$valcreated_ip."' where id = '".$requested_id."'";
	//$ID=$sign->SelectF($qry,"employee_status");
	$ID = $sign->FunctionQuery($qry);
	if($ID="Success"){
			echo "Success";
		}else{
			echo "Error";
		}
	
	}

?>