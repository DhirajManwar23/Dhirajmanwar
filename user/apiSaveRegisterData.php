<?php	
// Include class definition
include("function.php");
include("mailFunction.php");
include("commonFunctions.php");

$companyname = $_POST["companyname"];
$unenc_email = $_POST["email"];
$email = md5($unenc_email);
$password = md5($_POST["password"]);
$companytype = $_POST["companytype"];
$valToken = $_POST["valToken"];
$commonfunction=new Common();
$sign=new Signup();
$ip_address= $commonfunction->getIPAddress();
$settingValueTokenStatus= $commonfunction->getSettingValue("Token Status");
$settingValueTokenStatus=$sign->escapeString($settingValueTokenStatus);
$settingValueUserImagePathVerification= $commonfunction->getSettingValue("UserImagePathVerification");
$settingValueUserImagePathVerification=$sign->escapeString($settingValueUserImagePathVerification);
$settingValueUserImagePathVerified= $commonfunction->getSettingValue("UserImagePathVerified");
$settingValueUserImagePathVerified=$sign->escapeString($settingValueUserImagePathVerified);
$settingValueMailPath = $commonfunction->getSettingValue("MailPath");
$settingValueMailPath=$sign->escapeString($settingValueMailPath);
$settingValuePendingStatus = $commonfunction->getSettingValue("Pending Status");
date_default_timezone_set("Asia/Kolkata");
$date=date("Y-m-d h:i:sa");

//---------------------------------------------------------
$qry="Select count(*) as cnt from tw_company_contact where value='".$unenc_email."'";
$retVal = $sign->Select($qry);
if($retVal>0){
	echo "Exist";
}
else
{
	 $qry1="insert into tw_company_details (CompanyName,CompanyType,Status,compliance_status,created_on,created_ip) values('".$companyname."','".$companytype."','".$settingValuePendingStatus."','".$settingValuePendingStatus."','".$date."','".$ip_address."')";
	
		$retVal1 = $sign->FunctionQuery($qry1,true);

	   if($retVal1!=""){
			$created_by=$retVal1;
			$qry2="insert into tw_company_login (Username,company_id,Password,Type,Status) values('".$email."','".$created_by."','".$password."','Company','On')";
	
			$retVal2 = $sign->FunctionQuery($qry2);
			 if($retVal2=="Success"){
					$qry4="insert into tw_company_contact (company_id ,contact_field ,value ,public_visible,status,created_by,created_on,created_ip) values('".$created_by."','1','".$_POST["email"]."','true','".$settingValuePendingStatus."','".$created_by."','".$date."','".$ip_address."')";
	
					$retVal4 = $sign->FunctionQuery($qry4);
					if($retVal4=="Success"){
						$qry5 = "Update tw_company_invite set status = '".$settingValueTokenStatus."' where token = '".$valToken."'";
						$retVal5 = $sign->FunctionQuery($qry5);
						if($retVal5=="Success"){
							$mailobj=new twMail();
							
							//
							$mailobj=new twMail();
							$subject = "New Company Registration Mail";
							$myfile = fopen($settingValueMailPath."pgNewRegistration.html", "r");
	
							$message = fread($myfile,filesize($settingValueMailPath."pgNewRegistration.html"));
				
							$message = str_replace("_USERNAMEPLACEHOLDER_",$companyname,$message);
							fclose($myfile);
							//
							$mail_response = $mailobj->Mailsend($unenc_email,$subject,$message);
							
							 //--Mail function end (User) 
							//---
							$file_path = $settingValueUserImagePathVerification.$unenc_email;
							$file_path1 = $settingValueUserImagePathVerified.$unenc_email;
						
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
					else{
						echo "Error";
					}
			}
			else{
				echo "Error";
			}
			
		}
		else{
			echo "Error";
		} 
} 
	
	
?>
