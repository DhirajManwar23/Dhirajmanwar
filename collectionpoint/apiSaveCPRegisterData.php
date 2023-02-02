<?php	

// Include class definition
include("function.php");
include("mailFunction.php");
include("commonFunctions.php");
$collectionpointname = $_POST["collectionpointname"];
$collectionpointtype = $_POST["collectionpointtype"];
$contactpersonname = $_POST["contactpersonname"];
$mobile = $_POST["mobile"];
$password = md5($_POST["password"]);
$valToken = $_POST["valToken"];
$username = "";
$commonfunction=new Common();
$sign=new Signup();
$ip_address= $commonfunction->getIPAddress();
$settingValueTokenStatus= $commonfunction->getSettingValue("Token Status");
$settingValueTokenStatus=$sign->escapeString($settingValueTokenStatus);
$settingValueCollectionPointImagePathVerification=$commonfunction->getSettingValue("CollectionPointImagePathVerification");
$settingValueCollectionPointImagePathVerified= $commonfunction->getSettingValue("CollectionPointImagePathVerified");
$settingValueMailPath = $commonfunction->getSettingValue("MailPath");
$settingValueMailPath=$sign->escapeString($settingValueMailPath);
$settingValuePendingStatus = $commonfunction->getSettingValue("Pending Status");
date_default_timezone_set("Asia/Kolkata");
$date=date("Y-m-d h:i:sa");

//---------------------------------------------------------
$qry="Select count(*) as cnt from tw_collection_point_master where mobile_number='".$mobile."'";
$retVal = $sign->Select($qry);
if($retVal>0){
	echo "Exist";
}
else
{	
	$qry1="insert into tw_collection_point_master (collection_point_name,collection_point_type,contact_person_name,mobile_number,status,created_on,created_ip) values('".$collectionpointname."','".$collectionpointtype."','".$contactpersonname."','".$mobile."','".$settingValuePendingStatus."','".$date."','".$ip_address."')";

	$retVal1 = $sign->FunctionQuery($qry1,true);
	
	$queryCPid="Select id from tw_collection_point_master where mobile_number='".$mobile."'";
	$CPid=$sign->SelectF($queryCPid,'id');
		
		
	   if($retVal1!=""){
			$created_by=$retVal1;
			$qry2="insert into tw_collection_point_login (collection_point_id,username,password,Status) values('".$CPid."','".md5($mobile)."','".$password."','On')";
			$retVal2 = $sign->FunctionQuery($qry2);
				
							//----
							$file_path = $settingValueCollectionPointImagePathVerification.$mobile;
							$file_path1 = $settingValueCollectionPointImagePathVerified.$mobile;
						
							if (!file_exists($file_path))
							{
								@mkdir($file_path, 0777);
							}
							if (!file_exists($file_path1))
							{
								@mkdir($file_path1, 0777);
							}
							//---
							echo "Success";

		}
		
		else{
			echo "Error";
		} 
} 
	
	
?>
