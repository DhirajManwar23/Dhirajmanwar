<?php
session_start();
// Include class definition
include("function.php");
include("commonFunctions.php");
$sign=new Signup();
$commonfunction=new Common();

$settingValueEmployeeImagePathTemp = $commonfunction->getSettingValue("EmployeeImagePathTemp");
	

//upload.php
 define ("MAX_SIZE","5000000");
 if (file_exists($_FILES['Document_Proof']["tmp_name"]))
{
 $name = ($_FILES["Document_Proof"]["name"]);
 $location = $settingValueEmployeeImagePathTemp.'/'.$name;  
 move_uploaded_file($_FILES["Document_Proof"]["tmp_name"], $location);
 echo $name; 

}else{
	echo "not found";
} 
?>
