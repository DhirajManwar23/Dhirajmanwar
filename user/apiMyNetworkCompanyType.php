<?php
session_start();
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();
$company_id = $_SESSION["company_id"];

$qry="Select id,company_type from tw_company_type_master order by priority asc";
$retVal = $sign->FunctionJSON($qry);
$decodedJSON=json_decode($retVal);
$qry1="Select count(*) as cnt from tw_company_type_master ";
$retVal1 = $sign->Select($qry1);

$settingValueUserImagePathVerification = $commonfunction->getSettingValue("UserImagePathVerification");
$settingValueCompanyImage= $commonfunction->getSettingValue("Company Image");
$VerifiedStatus= $commonfunction->getSettingValue("Verified Status");
$count = 0;
$i = 1;
$x=$retVal1;
$table="";
while($x>=$i){
	$company_type_id=$decodedJSON->response[$count]->id;
	$count=$count+1;
	$company_type=$decodedJSON->response[$count]->company_type;
	$count=$count+1;
	$table.="<div class='row'><div class='col-md-12 grid-margin stretch-card'><div class='card'><div class='card-body'><h4 class='card-title'>".$company_type."</h4><div class='card-body'><div class='row'>";
	//==================
	
	$qry3="SELECT CD.ID,CD.CompanyName,CD.Company_Logo,CC.value FROM `tw_company_network` CN INNER JOIN tw_company_details CD on (((CN.receiver_id=CD.id) and CN.sender_id='".$company_id."') or ((CN.sender_id=CD.id) and CN.receiver_id='".$company_id."'))  INNER JOIN tw_company_contact CC on CD.id=CC.company_id where CN.connect_status='".$VerifiedStatus."' and CD.CompanyType='".$company_type_id."' and cc.contact_field='1'";
	$retVal3 = $sign->FunctionJSON($qry3);
	
	$qry4="SELECT count(*) as cnt FROM `tw_company_network` CN INNER JOIN tw_company_details CD on (((CN.receiver_id=CD.id) and CN.sender_id='".$company_id."') or ((CN.sender_id=CD.id) and CN.receiver_id='".$company_id."'))  INNER JOIN tw_company_contact CC on CD.id=CC.company_id where CN.connect_status='".$VerifiedStatus."' and CD.CompanyType='".$company_type_id."' and cc.contact_field='1'";
	$retVal4 = $sign->Select($qry4);
	$decodedJSONInner=json_decode($retVal3);
	$cntInner=0;
	$iInner=1;
	$xInner=$retVal4;
	$tableInner="";
	while($xInner>=$iInner) {
		
		$cid=$decodedJSONInner->response[$cntInner]->ID;
		$cntInner=$cntInner+1;
		$compNameInner=$decodedJSONInner->response[$cntInner]->CompanyName;
		$cntInner=$cntInner+1;
		$compLogoInner=$decodedJSONInner->response[$cntInner]->Company_Logo;
		$cntInner=$cntInner+1;
		$compEmailInner=$decodedJSONInner->response[$cntInner]->value;
		$cntInner=$cntInner+1;
		
		$qryStatus="select Status from tw_company_details where ID='".$cid."'";
		$companyStatus= $sign->SelectF($qryStatus,'Status');
		$verifyStatus=$commonfunction->getSettingValue("Verified Status");
		$UserImagePathOther=$commonfunction->getSettingValue("UserImagePathOther");
		$CommonDataValueCommonImagePath =$commonfunction->getCommonDataValue("CommonImagePath");
		$VerifiedImage=$commonfunction->getCommonDataValue("Verified Image");
		
		if($compLogoInner==""){
			$compLogoInnerimg=$UserImagePathOther.$settingValueCompanyImage;
		} 
		else{
			$compLogoInnerimg=$settingValueUserImagePathVerification.$compEmailInner."/".$compLogoInner;
		}
		$img="";
		if ($companyStatus==$verifyStatus) { 
		$img = "<img src='".$CommonDataValueCommonImagePath.$VerifiedImage."'/>";
		}
		else{
		$img="";
		}
		$countNO=0;
		
		$QRYcnt="select count(*) as cnt from tw_supplier_info where supplier_id='".$cid."' ";
		$countNO=$sign->Select($QRYcnt);
	 
		
		
		
		$tableInner.="<div class='col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12'><div class='card rounded border mb-2'><div class='card-body p-3'><div class='row align-items-center justify-content-center'><div class='col-lg-10 col-md-10 col-sm-10 col-xs-10 col-10'><img src='".$compLogoInnerimg."' width='100%'></div></div><hr><div class='row'><div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12 text-center'><h4>".$compNameInner."".$img."</h4></div><div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12 text-center'><br><div class='btn-group' role='group'><button type='button' class='btn btn-primary' onclick='viewCompanyProfile(".$cid.")'><i class='ti-info'></i></button>
		<button type='button' class='btn btn-success' onclick='AddSupplierNo(".$cid.",".$countNO.")'><i class='ti-settings' ></i></button>
		<button type='button' class='btn btn-primary'><i class='ti-time'></i></button></div></div></div></div></div></div>";
		

		
		$iInner=$iInner+1;
	}
	$table.=$tableInner;
	//=====================
	$table.="</div></div></div></div></div></div>";
	$i=$i+1; 	 
} 
echo $table;
?>
