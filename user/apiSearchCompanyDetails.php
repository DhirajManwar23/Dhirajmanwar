<?php
session_start();
	
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";
$commonfunction=new Common();
$sign=new Signup();
$settingValuePendingStatus= $commonfunction->getSettingValue("Document Status");
$settingValuePendingStatus=$sign->escapeString($settingValuePendingStatus);

$settingValueUserImagePathVerification= $commonfunction->getSettingValue("UserImagePathVerification");

$settingValueCompanyImage= $commonfunction->getSettingValue("Company Image");
$settingValuePemail= $commonfunction->getSettingValue("Primary Email");

$CompanyName=$_POST["CompanyName"];
$company_id = $_SESSION["company_id"];
$qry="select cd.ID,cd.Company_Logo,cd.CompanyName,cd.CompanyType,cc.value,cc.contact_field from tw_company_details cd LEFT JOIN tw_company_contact cc ON cd.ID = cc.company_id where cd.CompanyName LIKE '%".$CompanyName."%'AND cc.contact_field='".$settingValuePemail."'  and cd.ID!='".$company_id."'";
$retVal = $sign->FunctionJSON($qry);$qry1="select count(*) as cnt from tw_company_details cd LEFT JOIN tw_company_contact cc ON cd.ID = cc.company_id where cd.CompanyName LIKE '%".$CompanyName."%'AND cc.contact_field='".$settingValuePemail."' and cd.ID!='".$company_id."'";
$qryCnt = $sign->Select($qry1);

$decodedJSON2 = json_decode($retVal);
$count = 0;
$i = 1;
$x=$qryCnt;
$table="";
while($x>=$i){
		
	$ID = $decodedJSON2->response[$count]->ID;
	$count=$count+1;
	$Company_Logo = $decodedJSON2->response[$count]->Company_Logo;
	$count=$count+1;
	$CompanyName = $decodedJSON2->response[$count]->CompanyName;
	$count=$count+1;
	$CompanyType  = $decodedJSON2->response[$count]->CompanyType ;
	$count=$count+1;
	$value  = $decodedJSON2->response[$count]->value ;
	$count=$count+1;
	$contact_field  = $decodedJSON2->response[$count]->contact_field ;
	$count=$count+1;
	
	$qry2="Select company_type from tw_company_type_master where id='".$CompanyType."'";
    $companyTypeValue = $sign->SelectF($qry2,'company_type');
	
	$qryStatus="select Status, compliance_status from tw_company_details where ID='".$ID."'";
	$companyStatus= $sign->SelectF($qryStatus,'Status');
	$compliance_status= $sign->SelectF($qryStatus,'compliance_status');
	$verifyStatus=$commonfunction->getSettingValue("Verified Status");
	$VerifiedImage=$commonfunction->getSettingValue("Verified Image");
	$ComplianceImage=$commonfunction->getSettingValue("Compliance Image");
	$UserImagePathOther=$commonfunction->getSettingValue("UserImagePathOther");

	if(empty($Company_Logo)){
		$Company_Logo=$UserImagePathOther.$settingValueCompanyImage;
    } 
    else{
		$Company_Logo=$settingValueUserImagePathVerification.$value."/".$Company_Logo;
	}
	
	$img="";
	if ($companyStatus==$verifyStatus) { 
		$img = "<img src='".$UserImagePathOther."".$VerifiedImage."'/>";
	}
	
	$Cmplianceimg="";
	if ($compliance_status==$verifyStatus) { 
		$Cmplianceimg = "<img src='".$UserImagePathOther."".$ComplianceImage."'/>";
	}

	$table.="<div class='row'>
	<div class='col-lg-2 col-md-2 col-sm-12 col-xs-12 col-12'>
		<a href='pgSearchCompany.php?id=".$ID."' class='float-right' target='_blank'><img src='".$Company_Logo."' width='100%' class='img-sm rounded-circle mb-3'></a>
	</div>
	<div class='col-lg-10 col-md-10 col-sm-12 col-xs-12 col-12'>
		<strong>".$CompanyName."".$img."".$Cmplianceimg."</strong><br>
		 <p>".$companyTypeValue."</p>
	</div>
	</div>";		
		

	$i=$i+1;
}
echo $table;

	
?>
