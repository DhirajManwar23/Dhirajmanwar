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
	$status = "";
  
	$valCPID = $sign->escapeString($_POST["valCPID"]);	
	$collection_point_name = $sign->escapeString($_POST["collection_point_name"]);
	$collection_point_type = $sign->escapeString($_POST["collection_point_type"]);
	$collection_point_logo = $sign->escapeString($_POST["collection_point_logo"]);
	$contact_person_name = $sign->escapeString($_POST["contact_person_name"]);
	$mobile_number = $sign->escapeString($_POST["mobile_number"]);
	$alternative_mobile_number =$sign->escapeString($_POST["alternative_mobile_number"]);
	$email =$sign->escapeString($_POST["email"]);
	$ward = $sign->escapeString($_POST["ward"]);
	$address_line_1 =$sign->escapeString($_POST["address_line_1"]);
	$address_line_2 =$sign->escapeString($_POST["address_line_2"]);
	$location = $sign->escapeString($_POST["location"]);
	$pincode = $sign->escapeString($_POST["pincode"]);;
	$city =$sign->escapeString($_POST["city"]);
	$state =$sign->escapeString($_POST["state"]);
	$country = $sign->escapeString($_POST["country"]);
	$geo_coordinate = $sign->escapeString($_POST["geo_coordinate"]);
	$is_registered = $sign->escapeString($_POST["is_registered"]);
	$reg_number = $sign->escapeString($_POST["reg_number"]);
	$valstatus = $sign->escapeString($_POST["valstatus"]);
	
	
	$ip_address= $commonfunction->getIPAddress();
	
	$settingValueCollectionPointImagePathVerification = $commonfunction->getSettingValue("CollectionPointImagePathVerification");
	$settingValueCollectionPointImagePathVerification=$sign->escapeString($settingValueCollectionPointImagePathVerification);
	$settingValueCollectionPointImagePathVerified= $commonfunction->getSettingValue("CollectionPointImagePathVerified");
	$settingValueCollectionPointImagePathVerified=$sign->escapeString($settingValueCollectionPointImagePathVerified);

	$settingValueCollectionPointImagePathTemp = $commonfunction->getSettingValue("CollectionPointImagePathTemp");
	$settingValueCollectionPointImagePathTemp=$sign->escapeString($settingValueCollectionPointImagePathTemp); 
	
	$settingValuePendingStatus  = $commonfunction->getSettingValue("Pending Status");
		
	date_default_timezone_set("Asia/Kolkata");
	$date=date("Y-m-d h:i:sa");
	
	if($requesttype=="add"){
	
		$qry="Select count(*) as cnt from tw_collection_point_master where mobile_number='".$mobile_number."'";
		$retVal = $sign->Select($qry);
		if($retVal>0){
			echo "Exist";
		}
		else
		{	
			
		 $qry1="insert into tw_collection_point_master (collection_point_name,collection_point_type,collection_point_logo,contact_person_name,mobile_number,alternative_mobile_number,email,ward,address_line_1,address_line_2,location,pincode,city,state,country,geo_coordinate,is_registered,reg_number,status,created_by,created_on,created_ip) values('".$collection_point_name."','".$collection_point_type."','".$collection_point_logo."','".$contact_person_name."','".$mobile_number."','".$alternative_mobile_number."','".$email."','".$ward."','".$address_line_1."','".$address_line_2."','".$location."','".$pincode."','".$city."','".$state."','".$country."','".$geo_coordinate."','".$is_registered."','".$reg_number."','".$settingValuePendingStatus."','".$collection_point_name."','".$date."','".$ip_address."')";
		$retVal1 = $sign->FunctionQuery($qry1,true);
		
		//echo "Success";
		 if($retVal1!=""){
				$CollectionPointid=$retVal1;
				$qry2="insert into tw_collection_point_login (collection_point_id,username,password,status) values('".$CollectionPointid."','".md5($mobile_number)."','".md5($password)."','On')";
				$retVal2 = $sign->FunctionQuery($qry2);
				
				 if($retVal2=="Success"){
						
								//---
								$file_path = $settingValueCollectionPointImagePathVerification.$mobile_number;
								$file_path1 = $settingValueCollectionPointImagePathVerified.$mobile_number;
							
								if (!file_exists($file_path))
								{
									@mkdir($file_path, 0777);
								}
								if (!file_exists($file_path1))
								{
									@mkdir($file_path1, 0777);
								}
								//---
								if($collection_point_logo!=""){
									$org_image=$settingValueCollectionPointImagePathTemp.$collection_point_logo;
									$destination=$settingValueCollectionPointImagePathVerification.$mobile_number;

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
 
		$emp_id=$valCPID;
		$qry="Select count(*) as cnt from tw_collection_point_master where mobile_number='".$mobile_number."' and id!='".$valCPID."'";

		$retVal = $sign->Select($qry);
		if($retVal>0){
			echo "Exist";
		}
		else
		{	
			if($collection_point_logo!="" ){
				$qry1="Update tw_collection_point_master set collection_point_name='".$collection_point_name."',collection_point_type='".$collection_point_type."',collection_point_logo='".$collection_point_logo."',contact_person_name='".$contact_person_name."',mobile_number='".$mobile_number."',alternative_mobile_number='".$alternative_mobile_number."',email='".$email."',ward='".$ward."',address_line_1='".$address_line_1."',address_line_2='".$address_line_2."',location='".$location."',pincode='".$pincode."',city='".$city."',state='".$state."',country='".$country."',geo_coordinate='".$geo_coordinate."',is_registered='".$is_registered."',reg_number='".$reg_number."',modified_by='".$collection_point_name."',modified_on='".$date."',modified_ip='".$ip_address."' where id='".$valCPID."' "; 
			}
			else{
				$qry1="Update tw_collection_point_master set collection_point_name='".$collection_point_name."',collection_point_type='".$collection_point_type."',contact_person_name='".$contact_person_name."',mobile_number='".$mobile_number."',alternative_mobile_number='".$alternative_mobile_number."',email='".$email."',ward='".$ward."',address_line_1='".$address_line_1."',address_line_2='".$address_line_2."',location='".$location."',pincode='".$pincode."',city='".$city."',state='".$state."',country='".$country."',geo_coordinate='".$geo_coordinate."',is_registered='".$is_registered."',reg_number='".$reg_number."',modified_by='".$collection_point_name."',modified_on='".$date."',modified_ip='".$ip_address."' where id='".$valCPID."' "; 
			}
				$retVal1 = $sign->FunctionQuery($qry1);
				
				if($retVal1=="Success"){
					
							if($collection_point_logo!=""){
									$org_image=$settingValueCollectionPointImagePathTemp.$collection_point_logo;
									$destination=$settingValueCollectionPointImagePathVerification.$mobile_number;

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
