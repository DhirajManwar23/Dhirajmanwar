<?php
session_start();
include("function.php");
$sign=new Signup();
include_once "commonFunctions.php";
$commonfunction=new Common();
$settingValuePemail= $commonfunction->getSettingValue("Primary Email");
$EmployeeImagePathVerification= $commonfunction->getSettingValue("EmployeeImagePathVerification");
$company_id = $_SESSION["employee_id"];
$query = "select value from tw_employee_contact where employee_id = '".$company_id."' and contact_field='".$settingValuePemail."'";
$retVal = $sign->SelectF($query,'value'); 
define ("MAX_SIZE","5000000");
if (file_exists($_FILES['Document_Proof']["tmp_name"]))
{

	$name = ($_FILES["Document_Proof"]["name"]);
 
$location = $EmployeeImagePathVerification.$retVal.'/'.$name;  
move_uploaded_file($_FILES["Document_Proof"]["tmp_name"], $location);
echo $name; 

}
else{
	echo "not found";
} 
?>
