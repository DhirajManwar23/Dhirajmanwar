<?php
session_start();
include_once "function.php";
include_once "commonFunctions.php";	
$sign=new Signup();
$commonfunction=new Common();
$employee_id=$_SESSION["employee_id"];
$company_id=$_SESSION["company_id"];
$po_id=$_POST["id"];
$settingValueNodatafoundImage=$commonfunction->getSettingValue("NodatafoundImage");
$settingValueEmployeeImagePathOther=$commonfunction->getSettingValue("EmployeeImagePathOther");
$settingValueApprovedStatus=$commonfunction->getSettingValue("Approved Status");
$settingValueCompletedStatus=$commonfunction->getSettingValue("Completed Status");
$settingValueUserImagePathVerification= $commonfunction->getSettingValue("UserImagePathVerification");

$settingValueCompanyImage= $commonfunction->getSettingValue("Company Image");
$settingValuePemail= $commonfunction->getSettingValue("Primary Email");
$verifyStatus=$commonfunction->getSettingValue("Verified Status");
$VerifiedImage=$commonfunction->getSettingValue("Verified Image");
$UserImagePathOther=$commonfunction->getSettingValue("UserImagePathOther");
$settingValueAcceptImage = $commonfunction->getSettingValue("Accept Image");
$settingValueRejectImage = $commonfunction->getSettingValue("Reject Image");
$table="";

$qry=" SELECT mad.id FROM tw_epr_material_assign_details mad INNER JOIN tw_epr_material_assign_info mai ON mad.m_id=mai.id where mai.po_id='".$po_id."' AND mai.status='".$settingValueApprovedStatus."' ";
$retValQry = $sign->FunctionJSON($qry);

$decodedJSON = json_decode($retValQry);
$qrycnt="SELECT COUNT(*) as cnt FROM tw_epr_material_assign_details mad INNER JOIN tw_epr_material_assign_info mai ON mad.m_id=mai.id where mai.po_id='".$po_id."' AND mai.status='".$settingValueApprovedStatus."'";

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
	  <th class='center-text'>Quantity</th>
	  <th class='center-text'>Product</th>
	  <th class='center-text'>Assign Date</th>";

while($x>=$i){
 

	$assign_details_id=$decodedJSON->response[$count]->id;
	$count=$count+1;

	$detailsqry="SELECT alloted_company_id,quantity,date as assign_date FROM tw_epr_material_assign_details where id='".$assign_details_id."'";
	$retVal = $sign->FunctionJSON($detailsqry);
	$decodedJSON2 = json_decode($retVal);
	$alloted_company_id = $decodedJSON2->response[0]->alloted_company_id;
	$quantity = $decodedJSON2->response[1]->quantity;
	$date = $decodedJSON2->response[2]->assign_date;

	$qry2="Select CompanyName,Status,Company_Logo from tw_company_details where ID='".$alloted_company_id."'";
	$retVal1 = $sign->FunctionJSON($qry2);
	$decodedJSON1 = json_decode($retVal1);
	$CompanyName = $decodedJSON1->response[0]->CompanyName; 
	$companyStatus = $decodedJSON1->response[1]->Status; 
	$Company_Logo = $decodedJSON1->response[2]->Company_Logo; 



	$qry3="Select value from tw_company_contact where company_id='".$alloted_company_id."'";
	$retVal3 = $sign->SelectF($qry3,"value");
	

	$productNameQry="SELECT pm.product_name FROM tw_epr_material_assign_details msd INNER JOIN tw_material_outward_individual moi ON moi.id=msd.outward_id INNER JOIN tw_product_management pm on pm.id=moi.material_id where msd.id='".$assign_details_id."'";
	$productName=$sign->SelectF($productNameQry,"product_name");

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
		
	$table.="
	  
	  <tr>
				<td class='center-text'>".$it1."</td>
				<td class='left-text'><img src=".$Company_Logo." width='50px' > <strong>".$CompanyName." ".$img."</strong></td>
				<th class='right-text'>".number_format($quantity,2)."</th>
				<td class='left-text'>". $productName."</td>
				<td class='left-text'>".date("d-m-Y", strtotime($date))."</td>
	  </tr> 
	 "; 


				  $i=$i+1;
				  $it1=$it1+1;
}

$table.="</table>";
echo $table;
}
?>