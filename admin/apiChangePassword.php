<?php
session_start();
$OldPassword = md5($_POST['oldpswd']);
$NewPassword = md5($_POST['newpswd']);
$Username = $_SESSION["username"];

// Include class definition
include_once "function.php";
$qry="SELECT COUNT(*) as cnt from tw_admin_login WHERE Password ='".$OldPassword."' and Username='".$Username."' ";
$sign=new Signup();
$retVal = $sign->Select($qry);
if($retVal==1){
	  
	$qry1="UPDATE tw_admin_login SET Password ='".$NewPassword."' Where Username= '".$Username."' ";
	$retVal1 = $sign->FunctionQuery($qry1);
	if($retVal1=="Success")
	{
		echo "Success";
	}
	else{
		echo "error";
	} 
	
		
}else{               
	  echo "Invalid";
} 

	
?>