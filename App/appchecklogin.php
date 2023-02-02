<?php

// array for JSON response
$response = array();
 
// check for required fields
if (isset($_REQUEST['MobileNumber']) && isset($_REQUEST['Password'])  ) {
 
    
   
	$MobileNumber = $_REQUEST['MobileNumber'];
	$Password = $_REQUEST['Password'];


	// Include class definition
	require "function.php";
	$sign=new Signup();
	$qry="SELECT COUNT(*) as cnt from tw_ragpicker_login WHERE Username='".$MobileNumber."' and Password ='".$Password."' and Status='On'";
	$retVal = $sign->Select($qry);
	
	$qry1="SELECT COUNT(*) as cnt from tw_vendor_login WHERE Username='".$MobileNumber."' and Password ='".$Password."' and Status='On'";
	$retVal1 = $sign->Select($qry1);

		if($retVal==1){
			
				
			$qry2="SELECT Status from tw_ragpicker_login WHERE Username='".$MobileNumber."' and Password ='".$Password."' and Status='On'";
			$retVal2 = $sign->SelectF($qry2,"Status");
			if($retVal2=="On")
			{
				$qry3="SELECT ID from tw_ragpicker_login WHERE Username='".$MobileNumber."' and Password ='".$Password."' and Status='On'";
				$retVal3 = $sign->SelectF($qry3,"ID");
				$response["success"] = 1;
				$response["message"] = "Login Successful";
				$response["type"] = "Rp";
				$response["id"] = $retVal3;
				echo json_encode($response);
			}
			else{
				$response["success"] = 2;
				$response["message"] = "Account status blocked. Please contact Admin.";
				echo json_encode($response);
			}
				
			
		}
		else if($retVal1==1){
			$qry2="SELECT Status from tw_vendor_login WHERE Username='".$MobileNumber."' and Password ='".$Password."' and Status='On'";
			$retVal2 = $sign->SelectF($qry2,"Status");
			if($retVal2=="On")
			{
				$qry3="SELECT ID from tw_vendor_login WHERE Username='".$MobileNumber."' and Password ='".$Password."' and Status='On'";
				$retVal3 = $sign->SelectF($qry3,"ID");
				$response["success"] = 1;
				$response["message"] = "Login Successful";
				$response["type"] = "Vendor";
				$response["id"] = $retVal3;
				echo json_encode($response);
			}
			else{
				$response["success"] = 2;
				$response["message"] = "Account status blocked. Please contact Admin.";
				echo json_encode($response);
			}
		}
		else{
				$response["success"] = 3;
				$response["message"] = "Invalid Username and password";
				echo json_encode($response);
		}
		

}else {
		// required field is missing
		$response["success"] = 0;
		$response["message"] = "Required field(s) is missing.";
		echo json_encode($response);
	} 
		

 
?>