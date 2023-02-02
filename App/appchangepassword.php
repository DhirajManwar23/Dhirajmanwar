<?php
include("db_connect.php");
	$db=new DB_connect();
	$con=$db->connect();
	
	// array for JSON response
$response = array();
 
// check for required fields
if (isset($_REQUEST['OldPassword']) && isset($_REQUEST['NewPassword'])  && isset($_REQUEST['ID']) )
 {
    
    $OldPassword = $_REQUEST['OldPassword'];
	$NewPassword = $_REQUEST['NewPassword'];
	//$ConfirmPassword = $_REQUEST['ConfirmPassword'];
	$ID = $_REQUEST['ID'];
    
	$qry1="SELECT COUNT(*) as cnt from user_registration WHERE Password ='".$OldPassword."' and ID='".$ID."' ";
    $asd=mysqli_query($con,$qry1);
    $zxc=mysqli_fetch_array($asd);
	if($zxc["cnt"]==1){
          
            if ($OldPassword ==  $NewPassword){
                $response["success"] = 1;
                $response["message"] = "OldPassword and NewPassword cannot be same";
                echo json_encode($response);
            }/* else if($NewPassword != $ConfirmPassword){
                $response["success"] = 2;
                $response["message"] = "Password Mismatched ";
                echo json_encode($response);	
            } */
            else{
                $qry1=" UPDATE user_registration SET Password ='".$NewPassword."' Where ID= '".$ID."' ";
            
                    if($runi=mysqli_query($con,$qry1)){
                            
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
                             $response["success"] = 5;
                            $response["message"] = "Invalid old password";
                            echo json_encode($response);
        
    }
  
		
}
else{
    
    $response["success"] = 0;
    $response["message"] = "Required field missing";
    echo json_encode($response);
	
}		
?>