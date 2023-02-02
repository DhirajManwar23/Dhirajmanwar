<?php
// array for JSON response
$response = array();
 
// check for required fields
if (isset($_REQUEST['NewPassword'])  && isset($_REQUEST['MobileNumber']) && isset($_REQUEST['type']) )
{
	$NewPassword = $_REQUEST['NewPassword'];
	$MobileNumber = $_REQUEST['MobileNumber'];
	$type = $_REQUEST['type'];
	
	// Include class definition
	require "function.php";
	$sign=new Signup();
	
	if($type=="Rp"){	
		$qry1=" UPDATE tw_ragpicker_login SET Password ='".$NewPassword."' Where Username= '".$MobileNumber."' ";
		$retVal1 = $sign->FunctionQuery($qry1);
			if($retVal1=="Success"){
				$response["success"] = 1;
				$response["message"] = "Password Reset Successfully ";
				echo json_encode($response);
				
			}
			else{
					$response["success"] = 1;
					$response["message"] = "Something went wrong";
					echo json_encode($response);
			}
	}
	else{
		$qry1=" UPDATE tw_vendor_login  SET Password ='".$NewPassword."' Where Username= '".$MobileNumber."' ";
		$retVal1 = $sign->FunctionQuery($qry1);
			if($retVal1=="Success"){
				$response["success"] = 1;
				$response["message"] = "Password Reset Successfully ";
				echo json_encode($response);
				
			}
			else{
					$response["success"] = 1;
					$response["message"] = "Something went wrong";
					echo json_encode($response);
			}
	}
}
else{
    $response["success"] = 0;
    $response["message"] = "Required field missing";
    echo json_encode($response);
}		
?>