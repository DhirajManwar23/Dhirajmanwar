<?php
session_start();
// Include class definition
include("function.php");
include("commonFunctions.php");
$sign=new Signup();
$commonfunction=new Common();
$id=$_POST["id"];
$settingValuePemail= $commonfunction->getSettingValue("Primary Email");
$settingValueEmployeeImagePath = $commonfunction->getSettingValue("EmployeeImagePathVerification ");
$query = "select value from  tw_employee_contact where  employee_id = '".$id."' and contact_field='".$settingValuePemail."'";
$retVal = $sign->SelectF($query,'value');	

//upload.php
 define ("MAX_SIZE","5000000");
 if (file_exists($_FILES['Document_Proof']["tmp_name"]))
{
 $name = ($_FILES["Document_Proof"]["name"]);
 $location = $settingValueEmployeeImagePath.$retVal.'/'.$name;  
 move_uploaded_file($_FILES["Document_Proof"]["tmp_name"], $location);
 echo $name; 

}else{
	echo "not found";
} 
?>
