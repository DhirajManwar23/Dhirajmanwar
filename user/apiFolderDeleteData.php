<?php
	// Include class definition
	require "function.php";
	$sign=new Signup();
	include_once "commonFunctions.php";
    $commonfunction=new Common();
    $settingValuePemail= $commonfunction->getSettingValue("Primary Email");
    $settingBlockedStatus= $commonfunction->getSettingValue("Blocked Status");
	$UserImagePathVerification= $commonfunction->getSettingValue("EmployeeImagePathVerification ");
	$UserImagePathVerified= $commonfunction->getSettingValue("EmployeeImagePathVerified");
	$id=$_POST["id"];
	$query = "select value from  tw_employee_contact where employee_id = '".$id."' and contact_field='".$settingValuePemail."'";
	$retVal = $sign->SelectF($query,'value');

	$path = $UserImagePathVerification.$retVal;  
	$Verfiedpath = $UserImagePathVerified.$retVal;  
	
   // $folder = $path;
	$files = glob($path . '/*');
	foreach($files as $file){
		//Make sure that this is a file and not a directory.
		if(is_file($file)){
			 $files;
		//Use the unlink function to delete the file.
		unlink($file);
		}
	}
	//
	
	
	$Verifiedfiles = glob($Verfiedpath . '/*');
	foreach($Verifiedfiles as $Verifiedfile){
		//Make sure that this is a file and not a directory.
		if(is_file($Verifiedfile)){
			 $files;
		//Use the unlink function to delete the file.
		unlink($Verifiedfile);
		}
	}
	
	
	if(!rmdir($path)) {
	  echo "error";
	}else if(!rmdir($Verfiedpath)) {
	  echo "error";
	}else{
	$tablename = $sign->escapeString($_POST["tablename"]);
	$id = $sign->escapeString($_POST["id"]);
	
	$UpdateQry="update  tw_employee_registration set status='".$settingBlockedStatus."' where id='".$id."'";
	$Update = $sign->FunctionQuery($UpdateQry);
	 
	
	echo $Update;
	}
?>
