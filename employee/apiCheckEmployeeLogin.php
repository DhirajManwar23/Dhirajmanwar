<?php
    session_start();
    include_once "function.php";
	include_once "commonFunctions.php";	
	$sign=new Signup();
	$commonfunction=new Common();
    $unenc_email=$sign->escapeString($_POST["username"]);
    $username=md5($unenc_email);
	$u=$sign->escapeString($_POST["username"]);
	$password=md5($sign->escapeString($_POST["password"]));
	$settingValueAuditorRoleID= $commonfunction->getSettingValue("AuditorRoleID");
	$settingValueEPRManager= $commonfunction->getSettingValue("EPR Manager");
	$settingValueSalesManager= $commonfunction->getSettingValue("SalesManager");
	$settingValuePurchaseManager= $commonfunction->getSettingValue("PurchaseManager");
	$settingValuePartner= $commonfunction->getSettingValue("Partner");
    $qry="select count(*) as cnt from tw_employee_login where username='".$username."' and password='".$password."'" ;
    $retVal = $sign->Select($qry);
	if($retVal==1){
		$qry1="select el.employee_id,el.forced_password_change,el.status,er.company_id,er.employee_role from tw_employee_login el INNER JOIN tw_employee_registration er ON el.employee_id=er.id where username='".$username."' and password='".$password."'";
		$retVal1 = $sign->FunctionJSON($qry1);
		$decodedJSON1 = json_decode($retVal1);
		$employee_id = $decodedJSON1->response[0]->employee_id;
		$forced_password_change = $decodedJSON1->response[1]->forced_password_change;
		$status = $decodedJSON1->response[2]->status;
		$company_id = $decodedJSON1->response[3]->company_id;
		$employee_role = $decodedJSON1->response[4]->employee_role;
		$qryEmpRights="select role_id from tw_company_rights_management where company_id='".$company_id."' and role_id='".$employee_role."'";
		$EmpRights= $sign->SelectF($qryEmpRights,'role_id');
		if($status=="On")
		{
			$_SESSION["employeeusername"]=$unenc_email;
			$_SESSION["employee_id"]=$employee_id;
			$_SESSION["company_id"]=$company_id;
			if($forced_password_change=="true"){
				echo "ForcePassword";
			}
			else{
				
				//---
				$qrySelModulename="Select count(*) as cnt from tw_company_rights_management where company_id='".$company_id."' and role_id='".$employee_role."'";
				$Modulename = $sign->Select($qrySelModulename);
				
				if($Modulename==0){
					echo "NoRightsAssigned";
						
				}else{
					if($employee_role==$EmpRights && $employee_role!=$settingValueAuditorRoleID && $employee_role!=$settingValueEPRManager && $employee_role!=$settingValueSalesManager && $employee_role!=$settingValuePurchaseManager){ 
						echo "NormalRights";
					}
					else if($employee_role==$settingValueSalesManager){
						 echo "SalesManager";
					 }
					 else if($employee_role==$settingValuePurchaseManager){
						echo "PurchaseManager"; 
					 }
					else if($employee_role==$EmpRights && $employee_role!=$settingValueAuditorRoleID){ 
						
						echo "EprRights";
						
					}
					else if($employee_role==$settingValuePartner){ 
						
						echo "Partner";
					}
					
				}
				//---
			}
		
		}
		else{
			echo "Blocked";
		}	
		
	}
    else{
		
		echo "Invalid";
	}
  
 


?>