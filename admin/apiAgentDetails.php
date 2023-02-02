<?php
session_start();
// Include class definition
include_once "function.php";
$sign=new Signup();
include_once "commonFunctions.php";
$commonfunction=new Common();

$settingValueVerifyStatus=$commonfunction->getSettingValue("Verified Status");
$CommonDataValueCommonImagePath =$commonfunction->getCommonDataValue("CommonImagePath");
$VerifiedImage=$commonfunction->getCommonDataValue("Verified Image");

$qry="select id,agent_name,mobilenumber,status from tw_agent_details order by id Desc";
$retVal = $sign->FunctionJSON($qry);

$qry1="Select count(*) as cnt from tw_agent_details";
$retVal1 = $sign->Select($qry1);

$decodedJSON2 = json_decode($retVal);
$count = 0;
$i = 1;
$x=$retVal1;
$table="";
$it=1;
$table.="<thead><tr><th>SR.NO</th><th>Agent Name</th><th>Mobile No</th><th>Status</th><th>Verification Status</th><th>Reset Password</th><th>Edit</th><th>Delete</th></tr></thead><tbody>";

while($x>=$i){
$id = $decodedJSON2->response[$count]->id;
$count=$count+1;
$agent_name = $decodedJSON2->response[$count]->agent_name;
$count=$count+1;
$mobilenumber = $decodedJSON2->response[$count]->mobilenumber;
$count=$count+1;
$status  = $decodedJSON2->response[$count]->status ;
$count=$count+1;

$img="";
		if ($status==$settingValueVerifyStatus) { 
		$img = "<img src='".$CommonDataValueCommonImagePath."".$VerifiedImage."'/>";
		}
		else{
		$img="";
		}

$qryLoginStatus="select status as LoginStatus from tw_agent_login where agent_id='".$id."'";
$LoginStatus=$sign->SelectF($qryLoginStatus,"LoginStatus");

$qryVerificationStatus="select verification_status from tw_verification_status_master where id='".$status."'";
$VerificationStatus=$sign->SelectF($qryVerificationStatus,"verification_status");

	$table.="<tr>";
	$table.="<td>".$it."</td>"; 
	$table.="<td>".$agent_name."".$img."</td>";
	$table.="<td>".$mobilenumber."</td>";
	$table.="<td><a href='javascript:void(0)' onclick='editAgentLoginStatus(".$id.")'>".$LoginStatus."</a></td>";
	$table.="<td><a href='javascript:void(0)' onclick='editAgentVerificationStatus(".$id.")'>".$VerificationStatus."</a></td>";
	//$table.="<td><a href='javascript:void(0)' onclick='editStatus(".$id.")'>".$status."</a></td>";
	$table.="<td><a href='javascript:void(0)' id='Reset".$id."' onclick='ResetPassword(".$id.")'>Reset Password</a></td>";
	$table.="<td><a href='javascript:void(0)' onclick='editRecord(".$id.")'>Edit</a></td>";
	$table.="<td><a href='javascript:void(0)' onclick='deleteRecord(".$id.")'>Delete</a></td>";
	$it++;
	$table.="</tr>";

$i=$i+1;
}
$table.="</tbody>";
echo $table;
?>
	