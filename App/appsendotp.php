<?php

// array for JSON response
$response = array();
 
// check for required fields
if (isset($_REQUEST['MobileNumber']) && $_REQUEST['type']) {
 
    
   
	$MobileNumber = $_REQUEST['MobileNumber'];
	$type = $_REQUEST['type'];
	$Otp = rand(100000,999999);
	

	// Include class definition
	require "function.php";
	$sign=new Signup();

	$qry="SELECT COUNT(*) as cnt from tw_otp WHERE value='".$MobileNumber."' and contact_field ='3'";
	$retVal = $sign->Select($qry);
		if($retVal==1){
			$qry2="Update tw_otp Set otp='".$Otp."' where value='".$MobileNumber."' and contact_field ='3'";
			$retVal2 = $sign->FunctionQuery($qry2);
			if($retVal2=="Success"){
				$response["success"] = 1;
				$response["message"] = "OTP send Successfully ";
				echo json_encode($response);
			}
			else{
				$response["success"] = 2;
				$response["message"] = "Something went wrong";
				echo json_encode($response);
			}
			
		}
		
		else{
			$qry2="insert into tw_otp (user_type,contact_field,value,otp) values('".$type."','3','".$MobileNumber."','".$Otp."')";
			$retVal2 = $sign->FunctionQuery($qry2);
			if($retVal2=="Success"){
				$response["success"] = 1;
				$response["message"] = "OTP send Successfully ";
				echo json_encode($response);
			}
			else{
				$response["success"] = 2;
				$response["message"] = "Something went wrong";
				echo json_encode($response);
			}
		}
		

}else {
		// required field is missing
		$response["success"] = 0;
		$response["message"] = "Required field(s) is missing.";
		echo json_encode($response);
	} 
		

 
?>