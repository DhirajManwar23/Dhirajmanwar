<?php
$passsword= md5($_POST["password"]);
$username= $_POST["username"];
//echo $username;
$token= $_POST["token"];
$email= $_POST["email"];

date_default_timezone_set("Asia/Kolkata");
$date=date("Y-m-d h:i:sa");
//echo $passsword;
//echo $confirmpassword;
  
  include("function.php");
  include("commonFunctions.php");
  
	
     $qry="UPDATE tw_employee_login SET Password='".$passsword."' WHERE Username='".$username."' ";
	 $sign=new Signup();
	 $retVal = $sign->FunctionQuery($qry);
	
   
    if($retVal=="Success"){
		$qry2="SELECT id FROM tw_employee_contact WHERE value='".$email."'";	
	    $retVal2= $sign->SelectF($qry2,"id");
		$commonfunction = new Common();
		$ip_address= $commonfunction->getIPAddress();	
		$qry3="UPDATE tw_employee_reset_password SET status='success' , reset_by='".$retVal2."',  reset_on='".$date."' ,reset_ip='".$ip_address."' WHERE token='".$token."' AND email='".$email."'";
		$retVal3 = $sign->FunctionQuery($qry3);
	  
		echo "Success";
	}
	else{
		echo "error";
	}




?>