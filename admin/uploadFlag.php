<?php
session_start();
// Include class definition
include("function.php");
$sign=new Signup();
include_once("commonFunctions.php");
$commonfunction=new Common();
$AdminImagePathFlag = $commonfunction->getSettingValue("AdminImagePathFlag");

 define ("MAX_SIZE","5000000");
 if (file_exists($_FILES['Document_Proof']["tmp_name"]))
{
 
 $name = ($_FILES["Document_Proof"]["name"]);
 $location = $AdminImagePathFlag. $name;  
 move_uploaded_file($_FILES["Document_Proof"]["tmp_name"], $location);
 echo $name; 

}else{
	echo "not found";
} 
?>
