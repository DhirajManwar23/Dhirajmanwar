<?php
session_start();
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();

$qry="SELECT cd.id,cd.CompanyName,cd.Status,cd.compliance_status,ctm.company_type FROM tw_company_details cd LEFT JOIN tw_company_type_master ctm ON cd.CompanyType = ctm.id order by cd.id Desc";
$retVal = $sign->FunctionJSON($qry);

$verifyStatus=$commonfunction->getSettingValue("Verified Status");
$CommonDataValueCommonImagePath =$commonfunction->getCommonDataValue("CommonImagePath");
$VerifiedImage=$commonfunction->getCommonDataValue("Verified Image");
$ComplianceImage=$commonfunction->getCommonDataValue("Compliance Image");

$qry1="Select count(*) as cnt from tw_company_details order by id Desc";
$retVal1 = $sign->Select($qry1);

$decodedJSON2 = json_decode($retVal);
$count = 0;
$i = 1;
$x=$retVal1;
$table="";
$it=1;
$table.="<thead><tr><th>SR.NO</th><th>Company Name</th><th>KYC Status</th><th>Company Login Status</th><th>Compliance Status</th><th>Company Type</th><th>Edit</th></tr></thead><tbody>";

while($x>=$i){
$id = $decodedJSON2->response[$count]->id;
$count=$count+1;
$CompanyName = $decodedJSON2->response[$count]->CompanyName;
$count=$count+1;
$Status = $decodedJSON2->response[$count]->Status;
$count=$count+1;
$compliance_status = $decodedJSON2->response[$count]->compliance_status;
$count=$count+1;
$company_type  = $decodedJSON2->response[$count]->company_type ;
$count=$count+1;

$img="";
		if ($Status==$verifyStatus) { 
		$img = "<img class='verified-img' src='".$CommonDataValueCommonImagePath."".$VerifiedImage."'/>";
		}
		else{
		$img="";
		}
		
$complianceimg="";
		if ($compliance_status==$verifyStatus) { 
		$complianceimg = "<img class='verified-img' src='".$CommonDataValueCommonImagePath."".$ComplianceImage."'/>";
		}
		else{
		$complianceimg="";
		}

$qry2="Select Status from tw_company_login where company_id='".$id."' order by id Desc";
$retVal2 = $sign->SelectF($qry2,"Status");

$qry4="Select verification_status from tw_verification_status_master where ID='".$Status."' order by id Desc";
$retVal4 = $sign->SelectF($qry4,"verification_status");

$qry5="Select verification_status from tw_verification_status_master where ID='".$compliance_status."' order by id Desc";
$retVal5 = $sign->SelectF($qry5,"verification_status");

	$table.="<tr>";
	$table.="<td>".$it."</td>"; 
	$table.="<td>".$CompanyName."".$img."".$complianceimg."</td>";
	$table.="<td><a href='javascript:void(0)' onclick='editCompanyStatus(".$id.")'>".$retVal4."</a></td>";
	$table.="<td><a href='javascript:void(0)' onclick='editCompanyLoginStatus(".$id.")'>".$retVal2."</a></td>";
	$table.="<td><a href='javascript:void(0)' onclick='editComplianceStatus(".$id.")'>".$retVal5."</a></td>";
	$table.="<td>".$company_type."</td>";
	$table.="<td><a href='javascript:void(0)' onclick='editRecord(".$id.")'>Edit</a></td>";
	//$table.="<td><a href='javascript:void(0)' onclick='deleteRecord(".$id.")'>Delete</a></td>";
	$it++;
	$table.="</tr>";

$i=$i+1;
}
$table.="</tbody>";
echo $table;
?>
	
