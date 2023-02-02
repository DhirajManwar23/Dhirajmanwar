<?php
session_start();
	
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();
$company_id = $_SESSION["company_id"];

$UserImagePathOther = $commonfunction->getSettingValue("UserImagePathOther ");
$settingValueUserImagePathVerification= $commonfunction->getSettingValue("UserImagePathVerification");
$settingValueCompanyImage= $commonfunction->getSettingValue("Company Image");
$PendingStatus= $commonfunction->getSettingValue("Pending Status");

$qry="select id,receiver_id,sender_id,connect_date,connect_status from tw_company_network where sender_id='".$company_id."' AND connect_status='".$PendingStatus."' order by id Desc";
$retVal = $sign->FunctionJSON($qry);

$qry1="Select count(*) as cnt from tw_company_network where sender_id='".$company_id."' AND connect_status='".$PendingStatus."'";
$retVal1 = $sign->Select($qry1);

$decodedJSON2=json_decode($retVal);
$count = 0;
$i = 1;
$x=$retVal1;
$table="";
while($x>=$i){
	$id = $decodedJSON2->response[$count]->id;
	$count=$count+1;
	$receiver_id=$decodedJSON2->response[$count]->receiver_id;
	$count=$count+1;
	$sender_id = $decodedJSON2->response[$count]->sender_id;
	$count=$count+1;
	$connect_date  = $decodedJSON2->response[$count]->connect_date ;
	$count=$count+1;
	$connect_status  = $decodedJSON2->response[$count]->connect_status ;
	$count=$count+1;
	
    $query="SELECT cd.ID,cd.CompanyName,cd.Company_Logo,cc.value,cd.CompanyType FROM tw_company_details cd INNER JOIN tw_company_contact cc ON cc.company_id = cd.id WHERE cd.id ='".$receiver_id."' and cc.contact_field='".$PendingStatus."'";
	$retVal2 = $sign->FunctionJSON($query);
	$decodedJSON = json_decode($retVal2);
	
	$ID = $decodedJSON->response[0]->ID; 
	$CompanyName = $decodedJSON->response[1]->CompanyName; 
	$Company_Logo1 = $decodedJSON->response[2]->Company_Logo; 
    $Company_Email = $decodedJSON->response[3]->value; 
    $CompanyTypeID = $decodedJSON->response[4]->CompanyType; 
    $qry3="Select company_type from tw_company_type_master where id='".$CompanyTypeID."' order by id desc";
	$retVal3= $sign->SelectF($qry3,"company_type");		
	$company_type = $retVal3;
	
	$qryStatus="select Status from tw_company_details where ID='".$sender_id."'";
	$companyStatus= $sign->SelectF($qryStatus,'Status');
	$verifyStatus=$commonfunction->getSettingValue("Verified Status");
	
	$CommonDataValueCommonImagePath =$commonfunction->getCommonDataValue("CommonImagePath");
	$VerifiedImage=$commonfunction->getCommonDataValue("Verified Image");
	
	
    if(empty($Company_Logo1)){
		$Company_Logo=$UserImagePathOther.$settingValueCompanyImage;
    } 
    else{
		$Company_Logo=$settingValueUserImagePathVerification.$Company_Email."/".$Company_Logo1;
	}

	$img="";
	if ($companyStatus==$verifyStatus) { 
		$img = "<img src='".$CommonDataValueCommonImagePath.$VerifiedImage."'/>";
	}
	else{
		$img="";
	}
			$table.="<div class='col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12'><div class='card rounded border mb-2'><div class='card-body p-3'><div class='row align-items-center justify-content-center'><div class='col-lg-10 col-md-10 col-sm-10 col-xs-10 col-10'><img src='".$Company_Logo."' width='100%'></div></div><hr><div class='row'><div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12 text-center'><h4>".$CompanyName."".$img."</h4></div><div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12 text-center'><br><div class='btn-group' role='group'>
			
			
			
			<button type='button' class='btn btn-primary' onclick='viewCompanyProfile(".$receiver_id.")'><i class='ti-info'></i></button>
			
			<button type='button' class='btn btn-primary'><i class='ti-time'></i></button>
			";
		$i=$i+1; 
} 
echo $table;
	
?>
