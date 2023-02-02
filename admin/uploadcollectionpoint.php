<?php
session_start();
// Include class definition
include("function.php");
include("commonFunctions.php");
$sign=new Signup();
$commonfunction=new Common();

$settingValueCollectionPointImagePathTemp = $commonfunction->getSettingValue("CollectionPointImagePathTemp");
	

//upload.php
 define ("MAX_SIZE","5000000");
 if (file_exists($_FILES['Document_Proof']["tmp_name"]))
{
 $name = ($_FILES["Document_Proof"]["name"]);
 $location = $settingValueCollectionPointImagePathTemp.$name;  
 move_uploaded_file($_FILES["Document_Proof"]["tmp_name"], $location);
 echo $name; 

}
else{
	echo "Error";
} 
?>
