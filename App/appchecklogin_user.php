<?php
include("db_connect.php");
	$db=new DB_connect();
	$con=$db->connect();
	
	// array for JSON response
$response = array();
 
// check for required fields
if (isset($_REQUEST['MobileNumber']) && isset($_REQUEST['Password'])  ) {
 
    
   
	$MobileNumber = $_REQUEST['MobileNumber'];
	$Password = $_REQUEST['Password'];
	

	
		$qry="SELECT COUNT(*) as cnt from user_registration WHERE MobileNumber='".$MobileNumber."' and Password ='".$Password."' and Status='On'";
		//echo $qry;

        $asd=mysqli_query($con,$qry);
        $zxc=mysqli_fetch_array($asd);


			if($zxc["cnt"]==1){
				
					
					$qry1="SELECT * from user_registration WHERE MobileNumber='".$MobileNumber."' and Password ='".$Password."' and Status='On'";
					//echo $qry;

					$asd1=mysqli_query($con,$qry1);
					$zxc1=mysqli_fetch_array($asd1);
					
					$response["success"] = 1;
					$response["message"] = "Login Successful-".$zxc1["ID"];
					echo json_encode($response);
				
			}
			else{
					$response["success"] = 0;
					$response["message"] = "Invalid Username and password";
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