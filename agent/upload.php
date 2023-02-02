<?php
session_start();
// Include class definition
include("function.php");
$sign=new Signup();
include_once("commonFunctions.php");
$commonfunction=new Common();
$settingValuePemail= $commonfunction->getSettingValue("Primary Email");

$settingValueAgentImagePathVerification= $commonfunction->getSettingValue("AgentImagePathVerification");
$agent_id = $_SESSION["agent_id"];

$queryagentid = "select mobilenumber from tw_agent_details where id = '".$agent_id."'";
$retValagentid = $sign->SelectF($queryagentid,'mobilenumber');


//upload.php
 define ("MAX_SIZE","5000000");
 if (file_exists($_FILES['Document_Proof']["tmp_name"]))
{
 
 $name = ($_FILES["Document_Proof"]["name"]);

 $location = $settingValueAgentImagePathVerification.$retValagentid.'/'. $name;  
 move_uploaded_file($_FILES["Document_Proof"]["tmp_name"], $location);
 echo $name; 

}else{
	echo "not found";
} 
?>
