<?php

// array for JSON response
$response = array();
 
// check for required fields
if (isset($_REQUEST['Name']) && isset($_REQUEST['Username']) && isset($_REQUEST['Password']) && isset($_REQUEST['Usertype'])  && isset($_REQUEST['UnencUsername']) && isset($_REQUEST['otp']) ) {
 
    
    $Name = $_REQUEST['Name'];
	$Username = $_REQUEST['Username'];
	$Password = $_REQUEST['Password'];
	$Usertype = $_REQUEST['Usertype'];
	$UnencUsername = $_REQUEST['UnencUsername'];
	$otp = $_REQUEST['otp'];
	$Status = 'Pending';

	// Include class definition
	require "function.php";
	$sign=new Signup();
	require "commonFunctions.php";
	$commonfunction=new Common();
	$retipaddress = $commonfunction->getIPAddress();
	date_default_timezone_set("Asia/Kolkata");
	$date=date("Y-m-d h:i:sa");
	
	$qryi="SELECT COUNT(*) as cnt from tw_otp WHERE value='".$Username."' and otp ='".$otp."'";
	$retVali = $sign->Select($qryi);
		if($retVali==1){
		
			if($Usertype=="Rp"){
				//---RP Start
				$qry="SELECT COUNT(*) as cnt from tw_ragpicker_login WHERE Username ='".$Username."' and Status='On'";
				$retVal = $sign->Select($qry);
				if($retVal==1){
				  
					$response["success"] = 3;
					$response["message"] = "Ragpicker already exists ";
					echo json_encode($response);
				
				}
				else{
					$qry1="insert into tw_ragpicker_registration (name,status,created_by,created_on,created_ip) values ('".$Name."', '".$Status."', '".$UnencUsername."', '".$date."', '".$retipaddress."')";
					$retVal1 = $sign->FunctionQuery($qry1);
						if($retVal1=="Success"){
							$qry2="insert into tw_ragpicker_login (Username,Password,Status) values('".$Username."','".$Password."','On')";
							$retVal2 = $sign->FunctionQuery($qry2);
							if($retVal2=="Success"){
								$qry3="Select ID from tw_ragpicker_login where Username='".$Username."'";
								$retVal3 = $sign->SelectF($qry3,"ID");
								$created_by=$retVal3;
								$qry4="insert into tw_ragpicker_contact (rp_id ,contact_field ,value ,status,created_by,created_on,created_ip) values('".$created_by."','3','".$UnencUsername."','Pending','".$created_by."','".$date."','".$retipaddress."')";
								$retVal4 = $sign->FunctionQuery($qry4);
								if($retVal4=="Success"){
									$response["success"] = 1;
									$response["message"] = "Registrtation Successful ";
									echo json_encode($response);
								}
								else{
									$response["success"] = 2;
									$response["message"] = "Something went wrong";
									echo json_encode($response);
								}
							}
							else{
								$response["success"] = 2;
								$response["message"] = "Something went wrong";
								echo json_encode($response);
							}
							
						}
						else{
								$response["success"] = 2;
								$response["message"] = "Something went wrong";
								echo json_encode($response);
						}
					

					}
				//---RP End
			}
			else{
				//---Vendor Start
				$qry="SELECT COUNT(*) as cnt from tw_vendor_login WHERE Username ='".$Username."' and Status='On'";
				$retVal = $sign->Select($qry);
				if($retVal==1){
				  
					$response["success"] = 3;
					$response["message"] = "Vendor already exists ";
					echo json_encode($response);
				
				}
				else{
					$qry1="insert into tw_vendor_registration (name,status,created_by,created_on,created_ip) values ('".$Name."', '".$Status."', '".$Username."', '".$date."', '".$retipaddress."')";
					$retVal1 = $sign->FunctionQuery($qry1);
						if($retVal1=="Success"){
							$qry2="insert into tw_vendor_login (Username,Password,Status) values('".$Username."','".$Password."','On')";
							$retVal2 = $sign->FunctionQuery($qry2);
							if($retVal2=="Success"){
								$qry3="Select ID from tw_vendor_login where Username='".$Username."'";
								$retVal3 = $sign->SelectF($qry3,"ID");
								$created_by=$retVal3;
								$qry4="insert into tw_vendor_contact (vendor_id ,contact_field ,value ,status,created_by,created_on,created_ip) values('".$created_by."','3','".$UnencUsername."','Pending','".$created_by."','".$date."','".$retipaddress."')";
								$retVal4 = $sign->FunctionQuery($qry4);
								if($retVal4=="Success"){
									$response["success"] = 1;
									$response["message"] = "Registrtation Successful ";
									echo json_encode($response);
								}
								else{
									$response["success"] = 2;
									$response["message"] = "Something went wrong";
									echo json_encode($response);
								}
							}
							else{
								$response["success"] = 2;
								$response["message"] = "Something went wrong";
								echo json_encode($response);
							}
							
						}
						else{
								$response["success"] = 2;
								$response["message"] = "Something went wrong";
								echo json_encode($response);
						}
					

					} 
				//---Vendor End
			}
		}
		else{
			$response["success"] = 4;
			$response["message"] = "Invalid Otp";
			// echoing JSON response
			echo json_encode($response);
		}
    }else {
			// required field is missing
			$response["success"] = 0;
			$response["message"] = "Required field(s) is missing.";
			// echoing JSON response
			echo json_encode($response);
	} 
 
?>