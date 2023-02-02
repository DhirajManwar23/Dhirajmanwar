<?php
session_start();
// Include class definition
include("function.php");
$sign=new Signup();
include_once("commonFunctions.php");
$commonfunction=new Common();
$settingValuePemail= $commonfunction->getSettingValue("Primary Email");

$settingValueCollectionPointImagePathVerification= $commonfunction->getSettingValue("CollectionPointImagePathVerification");
$cp_id = $_SESSION["cp_id"];
/* $query = "select id from tw_collection_point_master where email = '".$settingValuePemail."'";
$retVal = $sign->SelectF($query,'email'); */

$querycpid = "select mobile_number from tw_collection_point_master where id = '".$cp_id."'";
$retValcpid = $sign->SelectF($querycpid,'mobile_number');


//upload.php
 define ("MAX_SIZE","5000000");
 if (file_exists($_FILES['Document_Proof']["tmp_name"]))
{
 
 $name = ($_FILES["Document_Proof"]["name"]);

 $location = $settingValueCollectionPointImagePathVerification.$retValcpid.'/'. $name;  
 move_uploaded_file($_FILES["Document_Proof"]["tmp_name"], $location);
 echo $name; 

}else{
	echo "not found";
} 
?>
