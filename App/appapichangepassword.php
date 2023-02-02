<?php
// array for JSON response
$response = array();
 
// check for required fields
if (isset($_REQUEST['OldPassword']) && isset($_REQUEST['NewPassword'])  && isset($_REQUEST['ID']) && isset($_REQUEST['type']) )
 {
    $OldPassword = $_REQUEST['OldPassword'];
	$NewPassword = $_REQUEST['NewPassword'];
	$ID = $_REQUEST['ID'];
	$type = $_REQUEST['type'];
	
	// Include class definition
	require "function.php";
	$sign=new Signup();
	
	if($type=="Rp"){
		$qry="SELECT COUNT(*) as cnt from tw_ragpicker_login WHERE Password ='".$OldPassword."' and ID='".$ID."' ";
		$retVal = $sign->Select($qry);
		if($retVal==1){
			  
				if ($OldPassword ==  $NewPassword){
					$response["success"] = 1;
					$response["message"] = "OldPassword and NewPassword cannot be same";
					echo json_encode($response);
				} 
				else{
					$qry1=" UPDATE tw_ragpicker_login SET Password ='".$NewPassword."' Where ID= '".$ID."' ";
					$retVal1 = $sign->FunctionQuery($qry1);
						if($retVal1=="Success"){
							$response["success"] = 3;
							$response["message"] = "Password changed Successful ";
							echo json_encode($response);
							
						}
						else{
								$response["success"] = 4;
								$response["message"] = "Something went wrong";
								echo json_encode($response);
						}
				}

		}else{
				$response["success"] = 2;
				$response["message"] = "Invalid old password";
				echo json_encode($response);
			
		}
	}
	else{
		$qry="SELECT COUNT(*) as cnt from tw_vendor_login  WHERE Password ='".$OldPassword."' and ID='".$ID."' ";
		$retVal = $sign->Select($qry);
		if($retVal==1){
			  
				if ($OldPassword ==  $NewPassword){
					$response["success"] = 1;
					$response["message"] = "OldPassword and NewPassword cannot be same";
					echo json_encode($response);
				} 
				else{
					$qry1=" UPDATE tw_vendor_login  SET Password ='".$NewPassword."' Where ID= '".$ID."' ";
					$retVal1 = $sign->FunctionQuery($qry1);
						if($retVal1=="Success"){
							$response["success"] = 3;
							$response["message"] = "Password changed Successful ";
							echo json_encode($response);
							
						}
						else{
								$response["success"] = 4;
								$response["message"] = "Something went wrong";
								echo json_encode($response);
						}
				}

		}else{
				$response["success"] = 2;
				$response["message"] = "Invalid old password";
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