<?php
	session_start();
	// Include class definition
	include("function.php");
	include("mailFunction.php");
	include("commonFunctions.php");
	$sign=new Signup();
	$commonfunction=new Common();
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $password = substr( str_shuffle( $chars ), 0, 16 );
	$status = "";
	
	$employee_photo=$sign->escapeString($_POST["employee_photo"]);
	$employee_name=$sign->escapeString($_POST["employee_name"]);
	$employee_department=$sign->escapeString($_POST["employee_department"]);
	$employee_designation=$sign->escapeString($_POST["employee_designation"]);
	$employee_role=$sign->escapeString($_POST["employee_role"]);
	$employee_salary=$sign->escapeString($_POST["employee_salary"]);
	$employee_hire_date=$sign->escapeString($_POST["employee_hire_date"]);
	$employee_type=$sign->escapeString($_POST["employee_type"]);
	$employee_status=$sign->escapeString($_POST["employee_status"]);
	$is_working=$sign->escapeString($_POST["is_working"]);
	$employee_gender=$sign->escapeString($_POST["employee_gender"]);
	$employee_dob=$sign->escapeString($_POST["employee_dob"]);
	$employee_marital_status=$sign->escapeString($_POST["employee_marital_status"]);
	$forced_password_change=$sign->escapeString($_POST["forced_password_change"]);
	
	$primary_email=$sign->escapeString($_POST["primary_email"]);
	$primary_contact=$sign->escapeString($_POST["primary_contact"]);
	
	$ip_address= $commonfunction->getIPAddress();
	$enc_email=$commonfunction->CommonEnc($primary_email);
	$enc_password=$commonfunction->CommonEnc($password);

	$settingValuePemail= $commonfunction->getSettingValue("Primary Email");
	$settingValuePemail=$sign->escapeString($settingValuePemail);
	$settingValuePcontact= $commonfunction->getSettingValue("Primary Contact");
	$settingValuePcontact=$sign->escapeString($settingValuePcontact);
	
	$settingValuePendingStatus= $commonfunction->getSettingValue("Pending Status");
	$settingValuePendingStatus=$sign->escapeString($settingValuePendingStatus);
	
	
	$settingValueEmployeeImagePathVerification = $commonfunction->getSettingValue("EmployeeImagePathVerification");
	$settingValueEmployeeImagePathVerification=$sign->escapeString($settingValueEmployeeImagePathVerification);
	$settingValueEmployeeImagePathVerified= $commonfunction->getSettingValue("EmployeeImagePathVerified");
	$settingValueEmployeeImagePathVerified=$sign->escapeString($settingValueEmployeeImagePathVerified);
	$settingValueMailPath = $commonfunction->getSettingValue("MailPath");
	$settingValueMailPath=$sign->escapeString($settingValueMailPath);
	$settingValueEmployeeImagePathTemp = $commonfunction->getSettingValue("EmployeeImagePathTemp");
	$settingValueEmployeeImagePathTemp=$sign->escapeString($settingValueEmployeeImagePathTemp);
	
	$settingValueEmployeeUrl  = $commonfunction->getSettingValue("EmployeeUrl ");
	$settingValueEmployeeUrl=$sign->escapeString($settingValueEmployeeUrl);
		
	date_default_timezone_set("Asia/Kolkata");
	$date=date("Y-m-d h:i:sa");
	$created_by=$_SESSION["company_id"];
	$requesttype=$_SESSION["requesttype"];
	$requestid=$_SESSION["requestid"];
	
	if($requesttype=="add"){
	
		$qry="Select count(*) as cnt from tw_employee_contact where value='".$primary_email."'";
		$retVal = $sign->Select($qry);
		if($retVal>0){
			echo "Exist";
		}
		else
		{	
			
		$qry1="insert into tw_employee_registration (company_id,employee_photo,employee_name,employee_gender,employee_marital_status,date_of_birth,employee_department,employee_designation,employee_role,employee_salary,employee_hire_date,employee_type,is_working,status,created_by,created_on,created_ip) values('".$created_by."','".$employee_photo."','".$employee_name."','".$employee_gender."','".$employee_marital_status."','".$employee_dob."','".$employee_department."','".$employee_designation."','".$employee_role."','".$employee_salary."','".$employee_hire_date."','".$employee_type."','".$is_working."','".$employee_status."','".$created_by."','".$date."','".$ip_address."')";
		$retVal1 = $sign->FunctionQuery($qry1,true);
		
		if($retVal1!=""){
				$emp_id=$retVal1;
				$qry2="insert into tw_employee_login (employee_id,username,password,status,forced_password_change,created_by,created_on,created_ip) values('".$emp_id."','".md5($primary_email)."','".md5($password)."','On','".$forced_password_change."','".$created_by."','".$date."','".$ip_address."')";
				$retVal2 = $sign->FunctionQuery($qry2);
				
				 if($retVal2=="Success"){
						$qry3="insert into tw_employee_contact (employee_id,contact_field,value,status,created_by,created_on,created_ip) values('".$emp_id."','".$settingValuePemail."','".$primary_email."','".$settingValuePendingStatus."','".$created_by."','".$date."','".$ip_address."')";
						$retVal3 = $sign->FunctionQuery($qry3);
						
						 if($retVal3=="Success"){
						 $qry4="insert into tw_employee_contact (employee_id,contact_field,value,status,created_by,created_on,created_ip) values('".$emp_id."','".$settingValuePcontact."','".$primary_contact."','".$settingValuePendingStatus."','".$created_by."','".$date."','".$ip_address."')";
						 $retVal4 = $sign->FunctionQuery($qry4);
							if($retVal4=="Success"){
							
								 $mailobj=new twMail();
								 $subject = "New Employee Registration Mail";

								 $myfile = fopen($settingValueMailPath."pgEmployeeRegistration.html", "r");

								 $message1 = fread($myfile,filesize($settingValueMailPath."pgEmployeeRegistration.html"));

								 $message2 = str_replace("_ID_",$primary_email,$message1);
								 $message3 = str_replace("_PASSWORD_",$password,$message2);
								 $message4 = str_replace("_PATH_",$settingValueEmployeeUrl,$message3);
								 $mail_response = $mailobj->Mailsend($primary_email,$subject,$message4);
								 fclose($myfile);
								//---
								$file_path = $settingValueEmployeeImagePathVerification.$primary_email;
								$file_path1 = $settingValueEmployeeImagePathVerified.$primary_email;
							
								if (!file_exists($file_path))/* Check folder exists or not */
								{
									@mkdir($file_path, 0777);/* Create folder by using mkdir function */
								}
								if (!file_exists($file_path1))/* Check folder exists or not */
								{
									@mkdir($file_path1, 0777);/* Create folder by using mkdir function */
								}
								//---
								if($employee_photo!=""){
									$org_image=$settingValueEmployeeImagePathTemp.$employee_photo;
									$destination=$settingValueEmployeeImagePathVerification.$primary_email;

									$img_name=basename($org_image);

									if(rename( $org_image , $destination."/".$img_name )){
									} else {
									 echo "failed";
									}  
								}
								echo "Success";

						}
						else{
							echo "Error";
						}
				}
				else{
					echo "Error";
				}
				
			}
			else{
				echo "Error";
			} 
			
		}
		else{
			echo "Error";
		} 
	}
}
	 else{
 
		$emp_id=$requestid;
		$qry="Select count(*) as cnt from tw_employee_contact where value='".$primary_email."' and employee_id!='".$emp_id."'";

		$retVal = $sign->Select($qry);
		if($retVal>0){
			echo "Exist";
		}
		else
		{	
			if($employee_photo!="" ){
				$qry1="Update tw_employee_registration set employee_photo='".$employee_photo."',employee_name='".$employee_name."',employee_department='".$employee_department."',employee_designation='".$employee_designation."',employee_role='".$employee_role."',employee_salary='".$employee_salary."',employee_hire_date='".$employee_hire_date."',employee_type='".$employee_type."',employee_status='".$employee_status."',is_working='".$is_working."',modified_by='".$created_by."',modified_on='".$date."',modified_ip='".$ip_address."' where id='".$requestid."' "; 
			}
			else{
				$qry1="Update tw_employee_registration set employee_name='".$employee_name."',employee_department='".$employee_department."',employee_designation='".$employee_designation."',employee_role='".$employee_role."',employee_salary='".$employee_salary."',employee_hire_date='".$employee_hire_date."',employee_type='".$employee_type."',employee_status='".$employee_status."',is_working='".$is_working."',modified_by='".$created_by."',modified_on='".$date."',modified_ip='".$ip_address."' where id='".$requestid."' "; 
			}
				$retVal1 = $sign->FunctionQuery($qry1);
				
				if($retVal1=="Success"){
					$qry3="Update tw_employee_contact set value='".$primary_email."',modified_by='".$created_by."',modified_on='".$date."',modified_ip='".$ip_address."' where employee_id='".$emp_id."' and contact_field='".$settingValuePemail."'"; 
					$retVal3 = $sign->FunctionQuery($qry3);
					if($retVal3=="Success"){
						
						$qry4="Update tw_employee_contact set value='".$primary_contact."',modified_by='".$created_by."',modified_on='".$date."',modified_ip='".$ip_address."' where employee_id='".$emp_id."' and contact_field='".$settingValuePcontact."'"; 
						$retVal4=$sign->FunctionQuery($qry4);
							if($retVal4=="Success"){
							
								$qry5="Update tw_employee_login set forced_password_change='".$forced_password_change."' where employee_id='".$emp_id."'"; 
								$retVal5 = $sign->FunctionQuery($qry5);
								if($retVal5=="Success"){
									echo "Success";
								}
								else{
									echo "error";
								}
							}
							else{
								echo "error";
							}
									
						}
						else{
							echo "error";
						}		
				}
				else{
					echo "error";
				}   
		}
	}
?>
