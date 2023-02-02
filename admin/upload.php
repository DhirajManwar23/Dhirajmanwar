<?php
session_start();
// Include class definition
include("function.php");
$sign=new Signup();
include_once("commonFunctions.php");
$commonfunction=new Common();
$settingValuePemail= $commonfunction->getSettingValue("Primary Email");
$UserImagePathVerification= $commonfunction->getSettingValue("UserImagePathVerification");
$company_id = $_POST["id"];
$query = "select value from tw_company_contact where company_id = '".$company_id."' and contact_field='".$settingValuePemail."'";
$retVal = $sign->SelectF($query,'value');

//upload.php
 define ("MAX_SIZE","5000000");
 if (file_exists($_FILES['Document_Proof']["tmp_name"]))
{
 
 $name = ($_FILES["Document_Proof"]["name"]);

 $location = $UserImagePathVerification.$retVal.'/'. $name;  
 move_uploaded_file($_FILES["Document_Proof"]["tmp_name"], $location);
 echo $name; 

}else{
	echo "not found";
} 
?>
