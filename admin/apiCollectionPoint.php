<?php
session_start();
// Include class definition
include_once "function.php";
$sign=new Signup();
include_once "commonFunctions.php";
$commonfunction=new Common();
//$requestid = $_REQUEST["id"];
$valverificationstatus=$_POST['valverificationstatus'];

$settingValueVerifyStatus=$commonfunction->getSettingValue("Verified Status");
$CommonDataValueCommonImagePath =$commonfunction->getCommonDataValue("CommonImagePath");
$VerifiedImage=$commonfunction->getCommonDataValue("Verified Image");

$qry="select id,collection_point_name,collection_point_type,ward,status from tw_collection_point_master order by id Desc";
$retVal = $sign->FunctionJSON($qry);



$qry1="Select count(*) as cnt from tw_collection_point_master";
$retVal1 = $sign->Select($qry1);

$decodedJSON2 = json_decode($retVal);
$count = 0;
$i = 1;
$x=$retVal1;
$table="";
$it=1;
$table.="<thead><tr><th>SR.NO</th><th>Collection Point Name</th><th>Collection Point Type</th><th>Ward</th><th>Status</th><th>Verification Status</th><th>Reset Password</th><th>Edit</th><th>Delete</th></tr></thead><tbody>";

while($x>=$i){
$id = $decodedJSON2->response[$count]->id;
$count=$count+1;
$collection_point_name = $decodedJSON2->response[$count]->collection_point_name;
$count=$count+1;
$collection_point_type = $decodedJSON2->response[$count]->collection_point_type;
$count=$count+1;
$ward  = $decodedJSON2->response[$count]->ward ;
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

$qryLoginStatus="select status as LoginStatus from tw_collection_point_login where collection_point_id='".$id."'";
$LoginStatus=$sign->SelectF($qryLoginStatus,"LoginStatus");

$qryVerificationStatus="select verification_status from tw_verification_status_master where id='".$status."'";
$VerificationStatus=$sign->SelectF($qryVerificationStatus,"verification_status");
/* $retValVerificationStatus = $sign->FunctionJSON($qryVerificationStatus);
$decodedJSON = json_decode($retValVerificationStatus);
$VerificationStatus  = $decodedJSON->response[$count]->VerificationStatus ;
$count=$count+1;
 */
$qry8="select collection_point_name from tw_collection_point_type_master where id ='".$collection_point_type."'";
$retVal8 = $sign->FunctionJSON($qry8);
$decodedJSON3 = json_decode($retVal8);
$collection_point_name_type = $decodedJSON3->response[0]->collection_point_name;

$qry7="select ward_name from tw_ward_master where id ='".$ward."'";
$retVal7 = $sign->FunctionJSON($qry7);
$decodedJSON3 = json_decode($retVal7);
$ward_name = $decodedJSON3->response[0]->ward_name;

	$table.="<tr>";
	$table.="<td>".$it."</td>"; 
	$table.="<td>".$collection_point_name."".$img."</td>";
	$table.="<td>".$collection_point_name_type."</td>";
	$table.="<td>".$ward_name."</td>";
	$table.="<td><a href='javascript:void(0)' onclick='editCPLoginStatus(".$id.")'>".$LoginStatus."</a></td>";
	$table.="<td><a href='javascript:void(0)' onclick='editCPVerificationStatus(".$id.")'>".$VerificationStatus."</a></td>";
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
	