<?php
session_start();
include_once "function.php";
include_once "commonFunctions.php";	
$sign=new Signup();
$commonfunction=new Common();
$employee_id=$_SESSION["employee_id"];
$company_id=$_SESSION["company_id"];
$po_id=$_POST["id"];


$qry=" SELECT id FROM `tw_epr_material_assign_info` where po_id='".$po_id."'";

$qrycnt="SELECT COUNT(*) as cnt FROM `tw_epr_material_assign_info` where po_id='".$po_id."'";
$Cnt = $sign->Select($qrycnt);

$retVal11 = $sign->FunctionJSON($qry);
$decodedJSON3 = json_decode($retVal11);
$i = 1;
$it1=1;
$count = 0;
$x=$Cnt;
$table="<table class='printtbl'>
	  <th>Sr.No</th>
	   <th>Company Logo</th>
	  <th>Company Name</th>
	  <th>Quantity</th>
	  <th>Product</th>
	  <th>Assign Date</th>";

while($x>=$i){
 $tableID= $decodedJSON3->response[$count]->id;
 $count=$count+1;


 
 $settingValuePendingStatus= $commonfunction->getSettingValue("Pending Status");
$settingValueEmployeeImagePathOther=$commonfunction->getSettingValue("EmployeeImagePathOther");
$settingValueNodatafoundImage=$commonfunction->getSettingValue("NodatafoundImage");

$settingValueApprovedStatus= $commonfunction->getSettingValue("Approved Status");
$settingValueRejectedStatus= $commonfunction->getSettingValue("Rejected status");
$settingValueVerifiedStatus= $commonfunction->getSettingValue("Verified Status");
$settingValueCompletedStatus= $commonfunction->getSettingValue("Completed Status");
$settingValueUserImagePathVerification= $commonfunction->getSettingValue("UserImagePathVerification");
$settingValueUserImagePathOther = $commonfunction->getSettingValue("UserImagePathOther");


$settingValueCompanyImage= $commonfunction->getSettingValue("Company Image");
$settingValuePemail= $commonfunction->getSettingValue("Primary Email");
$detailsqry="SELECT alloted_company_id,quantity,date FROM tw_epr_material_assign_details where m_id='".$tableID."'";
$retVal = $sign->FunctionJSON($detailsqry);
$decodedJSON2 = json_decode($retVal);
$alloted_company_id = $decodedJSON2->response[0]->alloted_company_id;
$quantity = $decodedJSON2->response[1]->quantity;
$date = $decodedJSON2->response[2]->date;

$qry2="Select CompanyName,Status,Company_Logo from tw_company_details where ID='".$alloted_company_id."'";
$retVal1 = $sign->FunctionJSON($qry2);
$decodedJSON = json_decode($retVal1);
$CompanyName = $decodedJSON->response[0]->CompanyName;
$companyStatus = $decodedJSON->response[1]->Status;
$Company_Logo = $decodedJSON->response[2]->Company_Logo;

$qry4="SELECT quantity FROM  tw_epr_material_assign_details WHERE m_id='".$tableID."' ";
$retVal4 = $sign->SelectF($qry4,"quantity");


$retVal5 = ""; 
		

$qry3="Select value from tw_company_contact where company_id='".$alloted_company_id."'";
$retVal3 = $sign->SelectF($qry3,"value");
$verifyStatus=$commonfunction->getSettingValue("Verified Status");
$VerifiedImage=$commonfunction->getSettingValue("Verified Image");
$UserImagePathOther=$commonfunction->getSettingValue("UserImagePathOther");
$settingValueAcceptImage = $commonfunction->getSettingValue("Accept Image");
$settingValueRejectImage = $commonfunction->getSettingValue("Reject Image");

$productNameQry="SELECT pm.product_name FROM `tw_epr_material_assign_details` msd INNER JOIN tw_material_outward_individual moi ON moi.material_outward_id=msd.outward_id INNER JOIN tw_product_management pm on pm.id=moi.material_id where msd.m_id='".$tableID."'";
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
				<td class='left-text'><img src=".$Company_Logo." width='50px' ></td>
				<td class='center-text'><strong>".$CompanyName." ".$img."</strong></td>
				<th class='center-text'>".number_format($quantity,2)."</th>
				<td class='center-text'>". $productName."</td>
				<td class='center-text'>".date("d-m-Y", strtotime($date))."</td>
	  </tr> 
	 "; 


				  $i=$i+1;
				  $it1=$it1+1;
}

$table.="</table>";
echo $table;
?>