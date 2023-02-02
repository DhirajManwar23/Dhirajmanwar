<?php
session_start();
include_once "function.php";
include_once "commonFunctions.php";	
$sign=new Signup();
$commonfunction=new Common();
$employee_id=$_SESSION["employee_id"];
$company_id=$_SESSION["company_id"];
$mo_id=$_POST["id"];
$settingValueNodatafoundImage=$commonfunction->getSettingValue("NodatafoundImage");
$settingValueEmployeeImagePathOther=$commonfunction->getSettingValue("EmployeeImagePathOther");
$settingValuePendingStatus=$commonfunction->getSettingValue("Pending Status");
$settingValueUserImagePathVerification=$commonfunction->getSettingValue("UserImagePathVerification");
$settingValueEmployeeImagePathVerification =$commonfunction->getSettingValue("EmployeeImagePathVerification ");
$verifyStatus=$commonfunction->getSettingValue("Verified Status");
$VerifiedImage=$commonfunction->getSettingValue("Verified Image");
$UserImagePathOther=$commonfunction->getSettingValue("UserImagePathOther");
$settingValueCompanyImage= $commonfunction->getSettingValue("Company Image");
$settingValuePemail= $commonfunction->getSettingValue("Primary Email");

$table="";
$selectIdQry="SELECT material_outward_id from tw_material_outward_individual where id='".$mo_id."'";
$material_outward_id=$sign->SelectF($selectIdQry,"material_outward_id");

$qry="SELECT company_id,customer_id,po_id,created_on,employee_id FROM `tw_material_outward` where id='".$material_outward_id."' ";
$retValQry = $sign->FunctionJSON($qry);

$decodedJSON = json_decode($retValQry);
$qrycnt="SELECT COUNT(*) as cnt FROM `tw_material_outward` where id='".$material_outward_id."' ";

 $Cnt = $sign->Select($qrycnt);
if($Cnt==0)	{
	$table.="
		<div class='card'>
		  
			<div class='card-body text-center'>
					<img src='".$settingValueEmployeeImagePathOther."".$settingValueNodatafoundImage."' width='250px' />
				</div>
			</div>
			
		  </div><br>";	
		 
	  
		echo $table;
				 
	
}
else{
	$i = 1;
	$it1=1;
	$count = 0;
	$x=$Cnt;
	$table="<table class='printtbl'>
		  <th class='center-text'>#</th>
		  <th class='center-text'>Company Name</th>
		  <th class='center-text'>Assign Date</th>
		  <th class='center-text'>PO</th>
		  <th class='center-text'>Tax Invoice</th>";

while($x>=$i){
	
	$company_id=$decodedJSON->response[$count]->company_id;
	$count=$count+1;
	$customer_id=$decodedJSON->response[$count]->customer_id;
	$count=$count+1;
	$po_id=$decodedJSON->response[$count]->po_id;
	$count=$count+1;
	$created_on=$decodedJSON->response[$count]->created_on;
	$count=$count+1;
	$employee_id=$decodedJSON->response[$count]->employee_id;
	$count=$count+1;
	
	


	$qry2="Select CompanyName,Status,Company_Logo from tw_company_details where ID='".$company_id."'";
	$retVal1 = $sign->FunctionJSON($qry2);
	$decodedJSON1 = json_decode($retVal1);
	$CompanyName = $decodedJSON1->response[0]->CompanyName; 
	$companyStatus = $decodedJSON1->response[1]->Status; 
	$Company_Logo = $decodedJSON1->response[2]->Company_Logo; 

	 $qry3="Select value from tw_company_contact where company_id='".$company_id."'";
	 $retVal3 = $sign->SelectF($qry3,"value"); 
	 
	$EmailQry="Select value from tw_employee_contact where employee_id='".$employee_id."' AND contact_field='".$settingValuePemail."'";
	 $EmpEmail = $sign->SelectF($EmailQry,"value");

	

	if(empty($Company_Logo)){
				$Company_Logo=$UserImagePathOther.$settingValueCompanyImage;
			} 
			else{
				$Company_Logo=$settingValueUserImagePathVerification.$retVal3."/".$Company_Logo;
			}
			$img="";
			if ($companyStatus==$verifyStatus) { 
				$img = "<img src='".$UserImagePathOther."".$VerifiedImage."'/>";
			}
			else{
				$img="";
			}
			
		$table.="  <tr>
					<td class='center-text'>".$it1."</td>
					<td class='left-text'><img src=".$Company_Logo." width='50px' > <strong>".$CompanyName." ".$img."</strong></td>
					<td class='center-text'>".date("d-m-Y", strtotime($created_on))."</td>
					<td class='center-text'><a target = '_blank' href='pgPODocument.php?po_id=".$po_id."'>View Po</a></td>";	
		  
		
			
		$DocqryCnt="SELECT COUNT(*) as cnt FROM tw_material_outward_documents where outward_id='".$material_outward_id."' AND type='Invoice'";	
		$DocCnt=$sign->Select($DocqryCnt);	
		"<br>";
		$TaxDocqryCnt="SELECT COUNT(*) as cnt FROM tw_tax_invoice where outward_id='".$material_outward_id."' ";	
		$TaxDocCnt=$sign->Select($TaxDocqryCnt);
		
		if($DocCnt>0){
			$SelectDocQry="SELECT document FROM tw_material_outward_documents where outward_id='".$material_outward_id."' and type='Invoice'";
			$SelectDoc=$sign->SelectF($SelectDocQry,"document");
			
			$table.="<td class='left-text'><a target = '_blank' href='".$settingValueEmployeeImagePathVerification.$EmpEmail."/".$SelectDoc."'>View tax Info</a></td>" ; 
		}
		else if($TaxDocCnt>0){
			$TaxIdQry="SELECT id FROM tw_tax_invoice where outward_id='".$material_outward_id."' ";
			$TaxId=$sign->SelectF($TaxIdQry,"id");
			
			$table.="<td class='left-text'><a target = '_blank' href='pgTaxInvoiceDocuments.php?id=".$TaxId."&voutward_id=".$material_outward_id."'>View Tax Info</a></td>";
			
		}else{
			
			$table.="
		  <td> --</td>
		 ";   
		}	
		
		
		
		$table.="
		  </tr> 
		 ";   


		  $i=$i+1;
		  $it1=$it1+1;
	}

	$table.="</table>";
	echo $table;
}
?>