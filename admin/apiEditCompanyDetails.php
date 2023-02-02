<?php
	session_start();
	// Include class definition
	include("function.php");
	$sign=new Signup();
	$companyname=$sign->escapeString($_POST["companyname"]);
	$status=$sign->escapeString($_POST["status"]);
	$email=$sign->escapeString($_POST["email"]);
	
	//whether ip is from share internet
	if (!empty($_SERVER['HTTP_CLIENT_IP'])){
		$ip_address = $_SERVER['HTTP_CLIENT_IP'];
	}
	//whether ip is from proxy
	elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))  {
		$ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	//whether ip is from remote address
	else{
		$ip_address = $_SERVER['REMOTE_ADDR'];
	}
	date_default_timezone_set("Asia/Kolkata");
	$date=date("Y-m-d h:i:sa");
	$created_by=$_SESSION["username"];
	$requestid=$_SESSION["requestid"];
	

		$qry="Select count(*) as cnt from tw_company_details where CompanyName='".$companyname."' and ID!='".$requestid."' ";
	
	
		$retVal = $sign->Select($qry);
		if($retVal>0){
		 	echo "Exist";
		}
		else
		{	
			
			$qry1="Update tw_company_details set CompanyName='".$companyname."',Status='".$status."',modified_by='".$created_by."',modified_on='".$date."',modified_ip='".$ip_address."' where id='".$requestid."' "; 
			$retVal1 = $sign->FunctionQuery($qry1);
			if($retVal1=="Success"){
				
				$qry2="Select count(*) as cnt from tw_company_contact where value='".$email."' and id!='".$requestid."' ";
				$retVal2 = $sign->Select($qry2);
				if($retVal2>0){
					echo "Exist";
				}
				else
				{	
					$qry3="Update tw_company_contact set value='".$email."',modified_by='".$created_by."',modified_on='".$date."',modified_ip='".$ip_address."' where id='".$requestid."' and contact_field='1' "; 
					$retVal3 = $sign->FunctionQuery($qry3);
					if($retVal3=="Success"){
						echo "Success";
					}
					else
					{
						echo "error";
					}
				}
				
			}
			else{
				echo "error";
			}
				
				
			   
		}
		
	
	
	
	
	
?>
