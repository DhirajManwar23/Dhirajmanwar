<?php	

// Include class definition
include("function.php");
include("mailFunction.php");
include("commonFunctions.php");
$agentname = $_POST["agentname"];
$mobile = $_POST["mobile"];
$password = md5($_POST["password"]);
$valToken = $_POST["valToken"];
$username = "";
$commonfunction=new Common();
$sign=new Signup();
$ip_address= $commonfunction->getIPAddress();
$settingValueTokenStatus= $commonfunction->getSettingValue("Token Status");
$settingValueTokenStatus=$sign->escapeString($settingValueTokenStatus);
$settingValueAgentImagePathVerification= $commonfunction->getSettingValue("AgentImagePathVerification");
//$settingValueUserImagePathVerification=$sign->escapeString($settingValueUserImagePathVerification);
$settingValueAgentImagePathVerified= $commonfunction->getSettingValue("AgentImagePathVerified");
//$settingValueUserImagePathVerified=$sign->escapeString($settingValueUserImagePathVerified);
$settingValueMailPath = $commonfunction->getSettingValue("MailPath");
$settingValueMailPath=$sign->escapeString($settingValueMailPath);
$settingValuePendingStatus = $commonfunction->getSettingValue("Pending Status");
date_default_timezone_set("Asia/Kolkata");
$date=date("Y-m-d h:i:sa");

//---------------------------------------------------------
$qry="Select count(*) as cnt from tw_agent_details where mobilenumber='".$mobile."'";
$retVal = $sign->Select($qry);
if($retVal>0){
	echo "Exist";
}
else
{	
	$qry1="insert into tw_agent_details (agent_name,mobilenumber,created_on,created_ip) values('".$agentname."','".$mobile."','".$date."','".$ip_address."')";
	$retVal1 = $sign->FunctionQuery($qry1,true);
	
	$queryAgentid="Select id from tw_agent_details where mobilenumber='".$mobile."'";
	$Agentid=$sign->SelectF($queryAgentid,'id');
		
		
	   if($retVal1!=""){
			$created_by=$retVal1;
			$qry2="insert into tw_agent_login (agent_id,username,password,status) values('".$Agentid."','".md5($mobile)."','".$password."','On')";
			$retVal2 = $sign->FunctionQuery($qry2);
			echo "Success";
			//----
			$file_path = $settingValueAgentImagePathVerification.$mobile;
			$file_path1 = $settingValueAgentImagePathVerified.$mobile;
		
			if (!file_exists($file_path))
			{
				@mkdir($file_path, 0777);
			}
			if (!file_exists($file_path1))
			{
				@mkdir($file_path1, 0777);
			}	
			//----
		}
		else{
			echo "Error";
		} 
} 
	
	
?>
