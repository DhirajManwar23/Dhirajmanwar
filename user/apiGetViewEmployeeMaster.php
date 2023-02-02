<?php
session_start();
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();

$company_id=$_SESSION["company_id"];
$settingValuePrimaryEmail = $commonfunction->getSettingValue("Primary Email");
$settingValueEmployeeImage = $commonfunction->getSettingValue("Employee Image");
$settingValueEmployeeImagePathVerification = $commonfunction->getSettingValue("EmployeeImagePathVerification");
$settingValueEmployeeImagePathOther = $commonfunction->getSettingValue("EmployeeImagePathOther");
$qry="SELECT er.id,er.employee_photo,er.employee_name,td.department_name,tdgn.designation_value,tr.role_name,er.status,trs.verification_status
FROM ((tw_employee_registration er INNER JOIN tw_employee_login el ON er.id = el.employee_id) 
INNER JOIN tw_department_master td ON er.employee_department = td.id 
INNER JOIN tw_designation_master tdgn ON er.employee_designation = tdgn.id 
INNER JOIN tw_role_master tr ON er.employee_role = tr.id 
INNER JOIN tw_verification_status_master trs ON er.status = trs.id) WHERE er.company_id='".$company_id."'";

$retVal = $sign->FunctionJSON($qry);

$qry1="Select count(*) as cnt from tw_employee_registration where company_id = '".$company_id."'";
$retVal1 = $sign->Select($qry1);

$decodedJSON2 = json_decode($retVal);
$count = 0;
$i = 1;
$x=$retVal1;
$table="";
$it=1;
$table.="<thead><tr><th>SR.NO</th><th>Employee Photo</th><th>Employee Name</th><th>Department Name</th><th>Designation</th><th>Role Name</th><th>Status</th><th>Verification Status</th><th>Edit</th><th>Reset Password</th></tr></thead><tbody>";

while($x>=$i){
$id = $decodedJSON2->response[$count]->id;
$count=$count+1;
$employee_photo = $decodedJSON2->response[$count]->employee_photo;
$count=$count+1;
$employee_name = $decodedJSON2->response[$count]->employee_name;
$count=$count+1;
$department_name  = $decodedJSON2->response[$count]->department_name ;
$count=$count+1;
$designation_value  = $decodedJSON2->response[$count]->designation_value ;
$count=$count+1;
$role_name  = $decodedJSON2->response[$count]->role_name ;
$count=$count+1;
$empStatus  = $decodedJSON2->response[$count]->status ;
$count=$count+1;
$verification_status  = $decodedJSON2->response[$count]->verification_status ;
$count=$count+1;

$qry2="SELECT el.status,ec.value FROM tw_employee_login el INNER JOIN tw_employee_contact ec ON el.employee_id = ec.employee_id WHERE el.employee_id = '".$id."' and ec.contact_field='".$settingValuePrimaryEmail."'";
$retVal2 = $sign->FunctionJSON($qry2);

$decodedJSON = json_decode($retVal2);

$status = $decodedJSON->response[0]->status; 
$value = $decodedJSON->response[1]->value; 

		$verifyStatus=$commonfunction->getSettingValue("Verified Status");
		$VerifiedImage=$commonfunction->getSettingValue("Verified Image");
		$UserImagePathOther=$commonfunction->getSettingValue("UserImagePathOther");

if($employee_photo==""){
	$employee_photo=$settingValueEmployeeImage;
	$path = $settingValueEmployeeImagePathOther.$employee_photo;

}
else{
	$employee_photo=$employee_photo;
	$path = $settingValueEmployeeImagePathVerification.$value."/".$employee_photo;

}
$img="";
		if ($empStatus==$verifyStatus) { 
		$img = "<img class='verified-img' src='".$UserImagePathOther."".$VerifiedImage."'/>";
		}
		else{
		$img="";
		}

	$table.="<tr>";
	$table.="<td>".$it."</td>"; 
	$table.="<td><img src='".$path."' class='img-lg rounded-circle mb-3' /></td>";
	$table.="<td>".$employee_name."".$img."</td>";
	$table.="<td>".$department_name."</td>";
	$table.="<td>".$designation_value."</td>";
	$table.="<td>".$role_name."</td>";
	$table.="<td><a href='javascript:void(0)' onclick='editEmployeeLoginStatus(".$id.")'>".$status."</a></td>";
	$table.="<td><a href='javascript:void(0)' onclick='editEmployeeVerificationStatus(".$id.")'>".$verification_status."</a></td>";
	$table.="<td><a href='javascript:void(0)' onclick='editRecord(".$id.")'>Edit</a></td>";
	//$table.="<td><a href='javascript:void(0)' onclick='deleteRecord(".$id.")'>Delete</a></td>";
	$table.="<td><a href='javascript:void(0)' id='Reset".$id."' onclick='ResetPassword(".$id.")'>Reset Password</a></td>";
	$it++;
	$table.="</tr>";
	

$i=$i+1;
}
$table.="</tbody>";
echo $table;
?>
	