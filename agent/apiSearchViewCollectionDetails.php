<?php
session_start();
// Include class definition 
include("function.php");
include("commonFunctions.php");
date_default_timezone_set("Asia/Kolkata");
$sign=new Signup();
$commonfunction=new Common();

$agent_id = $_SESSION["agent_id"];
$StartDate=$_POST["StartDate"];
$EndDate=$_POST["EndDate"];
$EndDate++;
$SearchStatus=$_POST["SearchStatus"];

$settingValuePendingStatus= $commonfunction->getSettingValue("Pending Status");
$settingValueApprovedStatus= $commonfunction->getSettingValue("Approved Status");
$settingValueAwaitingStatus= $commonfunction->getSettingValue("Awaiting Status");
$settingValueCompletedStatus= $commonfunction->getSettingValue("Completed Status");
$settingValueRejectedStatus= $commonfunction->getSettingValue("Rejected status");
$settingValueVerifiedStatus= $commonfunction->getSettingValue("Verified Status");

$CommonDataValueCommonImagePath =$commonfunction->getCommonDataValue("CommonImagePath");
$VerifiedImage=$commonfunction->getCommonDataValue("Verified Image");

$qry1="select count(*) as cnt from tw_mix_waste_collection where agent_id='".$agent_id."' and status='".$settingValuePendingStatus."' and  collection_date_time Between '".$StartDate."' and '".$EndDate."' order by id desc";
$qryCntInprocess = $sign->Select($qry1);

$qry2="select count(*) as cnt from tw_mix_waste_collection where agent_id='".$agent_id."' and status='".$settingValueApprovedStatus."' and  collection_date_time Between '".$StartDate."' and '".$EndDate."' order by id desc";
$qryCntApproved = $sign->Select($qry2);

$qry3="select count(*) as cnt from tw_mix_waste_collection where agent_id='".$agent_id."' and status='".$settingValueAwaitingStatus."' and  collection_date_time Between '".$StartDate."' and '".$EndDate."' order by id desc";
$qryCntAwaiting = $sign->Select($qry3);

$qry4="select count(*) as cnt from tw_mix_waste_collection where agent_id='".$agent_id."' and status='".$settingValueCompletedStatus."' and  collection_date_time Between '".$StartDate."' and '".$EndDate."' order by id desc";
$qryCntCompleted = $sign->Select($qry4);

$qry5="select count(*) as cnt from tw_mix_waste_collection where agent_id='".$agent_id."' and  collection_date_time Between '".$StartDate."' and '".$EndDate."' ";
$qryCntAll = $sign->Select($qry5);

$qry6="select count(*) as cnt from tw_mix_waste_collection where agent_id='".$agent_id."' and status='".$settingValueRejectedStatus."' and collection_date_time Between '".$StartDate."' and '".$EndDate."'  order by id desc";
$qryCntRejected = $sign->Select($qry6);


$Status="";
$qry5 = "select id,verification_status from tw_verification_status_master where visibility='true' Order by verification_status ,priority desc";
$retVal5 = $sign->FunctionOption($qry5,$Status,'verification_status','id');

$settingValueCollectionPointImagePathOther= $commonfunction->getSettingValue("CollectionPointImagePathOther");
$settingValueCollectionPointImagePathVerification= $commonfunction->getSettingValue("CollectionPointImagePathVerification");
$settingValueCollectionPointImage= $commonfunction->getSettingValue("CollectionPoint Image");
$settingValueCollectionPointMapLogo= $commonfunction->getSettingValue("CollectionPointMapLogo");
$settingValueCollectionPointGalleryImage= $commonfunction->getSettingValue("CollectionPointGalleryImage");
$settingValueNodatafoundImage=$commonfunction->getSettingValue("NodatafoundImage");
$settingValueAgentImagePathVerification= $commonfunction->getSettingValue("AgentImagePathVerification");
$settingValueAll= $commonfunction->getSettingValue("All");

$QueryAgentMobileNumber="Select mobilenumber from tw_agent_details where id='".$agent_id."'";
$AgentMobileNumber=$sign->SelectF($QueryAgentMobileNumber,'mobilenumber');

$qry="";
$qry1="";
if($SearchStatus!=$settingValueAll){
	$qry="select id,cp_id,collection_date_time,type_of_material,quantity,photo,status,reason,geo_location,drop_geo_location from tw_mix_waste_collection where agent_id='".$agent_id."' and collection_date_time Between '".$StartDate."' and '".$EndDate."' and status='".$SearchStatus."'";
	
	$qry1="Select count(*) as cnt from tw_mix_waste_collection where agent_id='".$agent_id."' and collection_date_time Between '".$StartDate."' and '".$EndDate."' and status='".$SearchStatus."'";
	
}
else{
	$qry="select id,cp_id,collection_date_time,type_of_material,quantity,photo,status,reason,geo_location,drop_geo_location from tw_mix_waste_collection where agent_id='".$agent_id."' and collection_date_time Between '".$StartDate."' and '".$EndDate."'";

	$qry1="Select count(*) as cnt from tw_mix_waste_collection where agent_id='".$agent_id."' and collection_date_time Between '".$StartDate."' and '".$EndDate."'";
	
}
//Execute actual query
$retVal = $sign->FunctionJSON($qry);
$decodedJSON2 = json_decode($retVal);
//Execute count query
$retVal1 = $sign->Select($qry1);

$count = 0;
$i = 1;
$x=$retVal1;
$table="";
if($retVal1==0){
	$table.="<div class='card'>	  
				<div class='card-body text-center'>
						<img src='".$settingValueCollectionPointImagePathOther."".$settingValueNodatafoundImage."' width='250px' />
					</div>
				</div>
			<br>";		
}
else{

	while($x>=$i){
			
		$id = $decodedJSON2->response[$count]->id;
		$count=$count+1;
		$cp_id = $decodedJSON2->response[$count]->cp_id;
		$count=$count+1;
		$collection_date_time = $decodedJSON2->response[$count]->collection_date_time;
		$count=$count+1;
		$type_of_material = $decodedJSON2->response[$count]->type_of_material;
		$count=$count+1;
		$quantity = $decodedJSON2->response[$count]->quantity;
		$count=$count+1;
		$photo = $decodedJSON2->response[$count]->photo;
		$count=$count+1;
		$status = $decodedJSON2->response[$count]->status;
		$count=$count+1;
		$reason = $decodedJSON2->response[$count]->reason;
		$count=$count+1;
		$geo_location = $decodedJSON2->response[$count]->geo_location;
		$count=$count+1;
		$drop_geo_location = $decodedJSON2->response[$count]->drop_geo_location;
		$count=$count+1;
		
		
		
		
		if($status==$settingValueRejectedStatus){
			$Viewreason=$reason;
			
		}
		else{
			$Viewreason="";
		} 
		
		$collection_date_time=date_create($collection_date_time);
		$collection_date_time=date_format($collection_date_time,"d-m-Y H:i:s");
		
		$QueryStatus="Select verification_status from tw_verification_status_master where id='".$status."' order by priority";
		$ColStatus=$sign->SelectF($QueryStatus,'verification_status');
		
		
		
		$QueryMaterialType="Select name from tw_waste_type_master where id='".$type_of_material."' order by priority";
		$MaterialType=$sign->SelectF($QueryMaterialType,'name');
		
		$QuerySocietyData="Select collection_point_name,collection_point_logo,mobile_number,status as CPStatus from tw_collection_point_master where id='".$cp_id."'";
		$retValSocietyData = $sign->FunctionJSON($QuerySocietyData);
		$decodedJSON = json_decode($retValSocietyData);
		$collection_point_name = $decodedJSON->response[0]->collection_point_name;
		$CPLogo = $decodedJSON->response[1]->collection_point_logo;
		$mobile_number = $decodedJSON->response[2]->mobile_number;
		$CPStatus = $decodedJSON->response[3]->CPStatus;
		
		$statusimg="";
		if ($CPStatus==$settingValueVerifiedStatus) {
			
		$statusimg = "<img src='".$CommonDataValueCommonImagePath."".$VerifiedImage."'/>";
		
		}
		else{
		$statusimg="";
		}
		
		$collection_point_logo_empty=$settingValueCollectionPointImagePathOther.$settingValueCollectionPointImage;
		$collection_point_logo=$settingValueCollectionPointImagePathVerification.$mobile_number."/".$CPLogo;
		
		$collection_point_maplogo = $settingValueCollectionPointImagePathOther.$settingValueCollectionPointMapLogo;
		$collection_point_galleryphoto = $settingValueCollectionPointImagePathOther.$settingValueCollectionPointGalleryImage;
		$collection_point_emptygalleryphoto;
	
		if(empty($collection_point_logo)){
			$collection_point_logo_empty;
		} 
		else{
			$collection_point_logo;
		}
		$img="";
		$map="https://www.google.com/maps/dir/".$geo_location."/".$drop_geo_location;
		$table.='<div class="row">
					<div class="col-md-12 grid-margin stretch-card">
						<div class="card">
							<div class="card-body">
								<div class="row">
									<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 col-2">
										<img src="'.$collection_point_logo.'" width="100%" class="img-lg  mb-3">
									</div>
									<div class="col-lg-8 col-md-8 col-sm-10 col-xs-10 col-10">
										
										<p class="mb-1">Collection Point Name: <b>'.$collection_point_name.' '.$statusimg.'</b></p>
										<p class="mb-1"><i class="ti-bag"></i> Collected Material Type: <b>'.$MaterialType.'</b> | <i class="ti-calendar"></i>  Date:  <b>'.$collection_date_time.'</b></p> 	
										<p class="mb-1"><i class="ti-bag"></i> Quantity : <b>'.$quantity.'</b> | Status: <b>'.$ColStatus.'</b></p>
										<code>'.$Viewreason.'</code>
																			
									</div>
									
									<div class="col-lg-1 col-md-1 col-sm-3 col-xs-3 col-3 center-text">
										 <a href="'.$map.'" target="_blank"><img src="'.$collection_point_maplogo.'"  /></a>
									</div>
									';
									
								if(!empty($photo)){
									$table.='<div class="col-lg-1 col-md-1 col-sm-3 col-xs-3 col-3 center-text">
										<img src="'.$collection_point_galleryphoto.'" onclick=window.open("'.$settingValueAgentImagePathVerification.$AgentMobileNumber."/".$photo. '") /> 
									</div>
									';
									}
								$table.='</div>
							</div>
						</div>
					</div>
				</div>';
			$i=$i+1; 
	}	
}
$responsearray=array();
array_push($responsearray,$table,$qryCntAll,$qryCntInprocess,$qryCntAwaiting,$qryCntCompleted,$qryCntRejected);
echo json_encode($responsearray);

?>
