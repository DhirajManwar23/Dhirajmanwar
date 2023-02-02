<?php
session_start();
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();

$verifyStatus=$commonfunction->getSettingValue("Verified Status");
$CommonDataValueCommonImagePath =$commonfunction->getCommonDataValue("CommonImagePath");
$VerifiedImage=$commonfunction->getCommonDataValue("Verified Image");
$AdminImagePathOther=$commonfunction->getSettingValue("AdminImagePathOther");
//$settingValueEmployeeImagePathVerification = $commonfunction->getSettingValue("EmployeeImagePathVerification");
$settingValueAdminImagePathOther  = $commonfunction->getSettingValue("AdminImagePathOther ");

$qry="select sa.id,sa.name,sa.email,rm.role_name,sa.sub_admin_status from tw_sub_admin sa INNER JOIN tw_role_master rm ON sa.role = rm.id order by sa.id Desc";

$retVal = $sign->FunctionJSON($qry);

$qry1="Select count(*) as cnt from tw_sub_admin";
$retVal1 = $sign->Select($qry1);

$decodedJSON2 = json_decode($retVal);
$count = 0;
$i = 1;
$x=$retVal1;
$table="";
$it=1;
$table.="<thead><tr><th>SR.NO</th><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th>Sub Admin Status</th><th>Edit</th><th>Delete</th><th>Reset Password</th></tr></thead><tbody>";

while($x>=$i){
$id = $decodedJSON2->response[$count]->id;
$count=$count+1;
$name = $decodedJSON2->response[$count]->name;
$count=$count+1;
$email = $decodedJSON2->response[$count]->email;
$count=$count+1;
$role_name  = $decodedJSON2->response[$count]->role_name ;
$count=$count+1;
$sub_admin_status  = $decodedJSON2->response[$count]->sub_admin_status ;
$count=$count+1;

$img="";
		if ($sub_admin_status==$verifyStatus) { 
		$img = "<img class='verified-img' src='".$CommonDataValueCommonImagePath."".$VerifiedImage."'/>";
		}
		else{
		$img="";
		}

$qryLoginStatus="select Status from tw_admin_login where admin_id='".$id."'";
$loginStatus=$sign->SelectF($qryLoginStatus,"Status");

$status_qry="SELECT verification_status FROM tw_verification_status_master where id='".$sub_admin_status."'";
$sub_admin_status = $sign->SelectF($status_qry,"verification_status"); 




	$table.="<tr>";
	$table.="<td>".$it."</td>"; 
	$table.="<td>".$name."".$img."</td>";
	$table.="<td>".$email."</td>";
	$table.="<td>".$role_name."</td>";
	$table.="<td><a href='javascript:void(0)' onclick='editLoginStatus(".$id.")'>".$loginStatus."</a></td>";
	$table.="<td><a href='javascript:void(0)' onclick='editAdminStatus(".$id.")'>".$sub_admin_status."</a></td>";
	$table.="<td><a href='javascript:void(0)' onclick='editRecord(".$id.")'>Edit</a></td>";
	$table.="<td><a href='javascript:void(0)' onclick='deleteRecord(".$id.")'>Delete</a></td>";
	$table.="<td><a href='javascript:void(0)' id='Reset".$id."' onclick='ResetPassword(".$id.")'>Reset Password</a></td>";
	$it++;
	$table.="</tr>";
	

$i=$i+1;
}
$table.="</tbody>";
echo $table;
?>
	