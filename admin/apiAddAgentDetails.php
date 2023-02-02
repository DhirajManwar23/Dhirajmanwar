<?php
	session_start();
	 $requesttype =$_SESSION["request_type"];
	// Include class definition
	include("function.php");
	include("mailFunction.php");
	include("commonFunctions.php");
	$sign=new Signup();
	$commonfunction=new Common();
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $password = substr( str_shuffle( $chars ), 0, 16 );
	//$status = "";
  
	$valAgentID = $sign->escapeString($_POST["valAgentID"]);	
	$agent_name = $sign->escapeString($_POST["agent_name"]);
	$agent_gender = $sign->escapeString($_POST["agent_gender"]);
	$agent_marital_status = $sign->escapeString($_POST["agent_marital_status"]);
	$agent_dob = $sign->escapeString($_POST["agent_dob"]);
	$agent_photo = $sign->escapeString($_POST["agent_photo"]);
	$mobilenumber = $sign->escapeString($_POST["mobilenumber"]);
	$alternative_mobilenumber = $sign->escapeString($_POST["alternative_mobilenumber"]);
	$email = $sign->escapeString($_POST["email"]);
	$address_line_1 =$sign->escapeString($_POST["address_line_1"]);
	$address_line_2 =$sign->escapeString($_POST["address_line_2"]);
	$location = $sign->escapeString($_POST["location"]);
	$pincode = $sign->escapeString($_POST["pincode"]);;
	$city =$sign->escapeString($_POST["city"]);
	$state =$sign->escapeString($_POST["state"]);
	$country = $sign->escapeString($_POST["country"]);
	$valstatus = $sign->escapeString($_POST["valstatus"]);
	
	
	$ip_address= $commonfunction->getIPAddress();
	
	$settingValueAgentImagePathVerification = $commonfunction->getSettingValue("AgentImagePathVerification");
	$settingValueAgentImagePathVerification=$sign->escapeString($settingValueAgentImagePathVerification);

	$settingValueAgentImagePathVerified= $commonfunction->getSettingValue("AgentImagePathVerified");
	$settingValueAgentImagePathVerified=$sign->escapeString($settingValueAgentImagePathVerified);

	$settingValueAgentImagePathTemp = $commonfunction->getSettingValue("AgentImagePathTemp");
	$settingValueAgentImagePathTemp=$sign->escapeString($settingValueAgentImagePathTemp);
	$settingValuePendingStatus  = $commonfunction->getSettingValue("Pending Status");	
		
	date_default_timezone_set("Asia/Kolkata");
	$date=date("Y-m-d h:i:sa");
	
	if($requesttype=="add"){
	
		$qry="Select count(*) as cnt from tw_agent_details where mobilenumber='".$mobilenumber."'";
		$retVal = $sign->Select($qry);
		if($retVal>0){
			echo "Exist";
		}
		else
		{		
		$qry1="insert into tw_agent_details (agent_name,agent_gender,agent_marital_status,agent_dob,agent_photo,mobilenumber,alternative_mobilenumber,email,address_line_1,address_line_2,location,pincode,city,state,country,status,created_by,created_on,created_ip) values('".$agent_name."','".$agent_gender."','".$agent_marital_status."','".$agent_dob."','".$agent_photo."','".$mobilenumber."','".$alternative_mobilenumber."','".$email."','".$address_line_1."','".$address_line_2."','".$location."','".$pincode."','".$city."','".$state."','".$country."','".$settingValuePendingStatus."','".$valAgentID."','".$date."','".$ip_address."')";
		$retVal1 = $sign->FunctionQuery($qry1,true);
		
		//echo "Success";
		 if($retVal1!=""){
				$Agentid=$retVal1;
				$qry2="insert into tw_agent_login (agent_id,username,password,status) values('".$Agentid."','".md5($mobilenumber)."','".md5($password)."','On')";
				$retVal2 = $sign->FunctionQuery($qry2);
				
				 if($retVal2=="Success"){
						
								//---
								$file_path = $settingValueAgentImagePathVerification.$mobilenumber;
								$file_path1 = $settingValueAgentImagePathVerified.$mobilenumber;
							
								if (!file_exists($file_path))
								{
									@mkdir($file_path, 0777);
								}
								if (!file_exists($file_path1))
								{
									@mkdir($file_path1, 0777);
								}
								//---
								if($agent_photo!=""){
									$org_image=$settingValueAgentImagePathTemp.$agent_photo;
									$destination=$settingValueAgentImagePathVerification.$mobilenumber;

									$img_name=basename($org_image);

									if(rename( $org_image , $destination."/".$img_name )){
									} else {
									 echo "failed";
									}  
								}
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
			
	}
else{
 
		//$emp_id=$valCPID;
		$qry="Select count(*) as cnt from tw_agent_details where mobilenumber='".$mobilenumber."' and id!='".$valAgentID."'";

		$retVal = $sign->Select($qry);
		if($retVal>0){
			echo "Exist";
		}
		else
		{	
			if($agent_photo!="" ){
				$qry1="Update tw_agent_details set agent_name='".$agent_name."',agent_gender='".$agent_gender."',agent_marital_status='".$agent_marital_status."',agent_dob='".$agent_dob."',agent_photo='".$agent_photo."',mobilenumber='".$mobilenumber."',alternative_mobilenumber='".$alternative_mobilenumber."',email='".$email."',address_line_1='".$address_line_1."',address_line_2='".$address_line_2."',location='".$location."',pincode='".$pincode."',city='".$city."',state='".$state."',country='".$country."',modified_by='".$valAgentID."',modified_on='".$date."',modified_ip='".$ip_address."' where id='".$valAgentID."' "; 
			}
			else{
				$qry1="Update tw_agent_details set agent_name='".$agent_name."',agent_gender='".$agent_gender."',agent_marital_status='".$agent_marital_status."',agent_dob='".$agent_dob."',mobilenumber='".$mobilenumber."',alternative_mobilenumber='".$alternative_mobilenumber."',email='".$email."',address_line_1='".$address_line_1."',address_line_2='".$address_line_2."',location='".$location."',pincode='".$pincode."',city='".$city."',state='".$state."',country='".$country."',modified_by='".$valAgentID."',modified_on='".$date."',modified_ip='".$ip_address."' where id='".$valAgentID."' "; 
			}
				$retVal1 = $sign->FunctionQuery($qry1);
				
				if($retVal1=="Success"){
					
							if($agent_photo!=""){
									$org_image=$settingValueAgentImagePathTemp.$agent_photo;
									$destination=$settingValueAgentImagePathVerification.$mobilenumber;

									$img_name=basename($org_image);

									if(rename( $org_image , $destination."/".$img_name )){
									} else {
									 echo "failed";
									}  
								}
						
						echo "Success";	
				}
				else{
					echo "error";
				}   
		}
	}  
?>
