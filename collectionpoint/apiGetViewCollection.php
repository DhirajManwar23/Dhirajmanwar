<?php
session_start();
	
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";
$commonfunction=new Common();
$sign=new Signup();
$cp_id=$_SESSION["cp_id"];
$settingValuePendingStatus= $commonfunction->getSettingValue("Pending Status");
$settingValueApprovedStatus= $commonfunction->getSettingValue("Approved Status");
$settingValueRejectedStatus= $commonfunction->getSettingValue("Rejected status");
$settingValueCompletedStatus= $commonfunction->getSettingValue("Completed Status");
$settingValueVerifiedStatus= $commonfunction->getSettingValue("Verified Status");

$CommonDataValueCommonImagePath =$commonfunction->getCommonDataValue("CommonImagePath");
$VerifiedImage=$commonfunction->getCommonDataValue("Verified Image");

$settingValueAgentImagePathVerification = $commonfunction->getSettingValue("AgentImagePathVerification ");
$settingValueAgentImagePathOther = $commonfunction->getSettingValue("AgentImagePathOther");
$settingValueDistanceImage = $commonfunction->getSettingValue("DistanceImage");
$settingValuegalleryImage  = $commonfunction->getSettingValue("galleryImage");
$settingValueAgentImage  = $commonfunction->getSettingValue("Agent Image");

$settingValueEmployeeImagePathOther=$commonfunction->getSettingValue("EmployeeImagePathOther");
$settingValueNodatafoundImage=$commonfunction->getSettingValue("NodatafoundImage");
$settingValueCompanyImage= $commonfunction->getSettingValue("Company Image");
$settingValuePemail= $commonfunction->getSettingValue("Primary Email");


$qry="select id,agent_id,geo_location,drop_geo_location,collection_date_time,type_of_material,quantity,status,reason,photo from tw_mix_waste_collection where cp_id='".$cp_id."' order by id desc";
$qry1="select count(*) as cnt from tw_mix_waste_collection where cp_id='".$cp_id."' order by id desc";

$retVal = $sign->FunctionJSON($qry);
$qryCnt = $sign->Select($qry1);

$decodedJSON2 = json_decode($retVal);
$count = 0;
$i = 1;
$x=$qryCnt;
$table="";

if($qryCnt==0 || $qryCnt==0.00){
	$table.="
				<div class='card'>
				  
					<div class='card-body text-center'>
							<img src='".$settingValueEmployeeImagePathOther."".$settingValueNodatafoundImage."' width='250px' />
						</div>
					</div>
					
				  </div><br>";	
	
}
else{
	while($x>=$i){
		$id = $decodedJSON2->response[$count]->id;
		$count=$count+1;
		$agent_id = $decodedJSON2->response[$count]->agent_id;
		$count=$count+1;
		$geo_location = $decodedJSON2->response[$count]->geo_location;
		$count=$count+1;
		$drop_geo_location = $decodedJSON2->response[$count]->drop_geo_location;
		$count=$count+1;
		$collection_date_time = $decodedJSON2->response[$count]->collection_date_time;
		$count=$count+1;
		$type_of_material  = $decodedJSON2->response[$count]->type_of_material ;
		$count=$count+1;
		$quantity  = $decodedJSON2->response[$count]->quantity ;
		$count=$count+1;
		$status  = $decodedJSON2->response[$count]->status ;
		$count=$count+1;
		$reason  = $decodedJSON2->response[$count]->reason ;
		$count=$count+1;
		$photo  = $decodedJSON2->response[$count]->photo ;
		$count=$count+1;
		
		$qry2="Select agent_name,agent_photo,mobilenumber,status from tw_agent_details where id='".$agent_id."'";
		$retVal1 = $sign->FunctionJSON($qry2);
		$decodedJSON = json_decode($retVal1);
		$agent_name = $decodedJSON->response[0]->agent_name;
		$agent_photo = $decodedJSON->response[1]->agent_photo;
		$mobilenumber = $decodedJSON->response[2]->mobilenumber;
		$agentstatus = $decodedJSON->response[3]->status;
		
		$QueryStatus="Select verification_status from tw_verification_status_master where id='".$status."' order by priority";
		$ColStatus=$sign->SelectF($QueryStatus,'verification_status');
		if($ColStatus=="Rejected"){
			$Viewreason=$reason;
		}
		else{
			$Viewreason="";
		}
		$qry3="Select name from tw_waste_type_master where id='".$type_of_material."'";
		$retVal3 = $sign->SelectF($qry3,"name");
		if ($agentstatus==$settingValueVerifiedStatus) { 
			$agentstatusimg = "<img src='".$CommonDataValueCommonImagePath."".$VerifiedImage."'/>";
		}
		else{
			$agentstatusimg ="";
		}
	
		if($agent_photo==""){
			$img = $settingValueAgentImagePathOther.$settingValueAgentImage;
		}
		else{
			$img = $settingValueAgentImagePathVerification.$mobilenumber."/".$agent_photo;
		}
		
		
		$imgC="";
		if($photo!==""){
			$imgpath = $settingValueAgentImagePathVerification.$mobilenumber."/".$photo;
			$imgC = "<a href='".$imgpath."' target='_blank'><img src='".$settingValueAgentImagePathOther.$settingValuegalleryImage."' width='100%' class='img-sm mb-3 '></a>";
		}
		$distanceimage = $settingValueAgentImagePathOther.$settingValueDistanceImage;
		$link="https://www.google.com/maps/dir/".$geo_location."/".$drop_geo_location;
		//$link = "https://www.google.com/maps/dir/19.0166912,72.8286951/18.9842089,72.8200753/@19.0004798,72.8112561,14z/data=!3m1!4b1!4m9!4m8!1m3!2m2!1d72.8286951!2d19.0166912!1m3!2m2!1d72.8200753!2d18.9842089";
		$table.="<div class='card'>
				  
					<div class='card-body'>
						<div class='row'>
							<div class='col-lg-2 col-md-2 col-sm-12 col-xs-12 col-12' style='text-align:center;'>
								<img src='".$img."' width='100%' style='width:100px;height:100px;' class='img-sm mb-3 '>
							</div>
							<div class='col-lg-10 col-md-10 col-sm-12 col-xs-12 col-12'>
								<strong>".$agent_name." ".$agentstatusimg."</strong><p>Type of Material : ".$retVal3." | Quantity: ".$quantity."</p>
								 <p><i class='ti-calendar'></i> Collection Date: ".date("d-m-Y", strtotime($collection_date_time))." | Status: ".$ColStatus." </p><br>
								 <code>".$Viewreason."</code><br>
								 <a href='".$link."' target='_blank'><img src='".$distanceimage."' width='100%' class='img-sm mb-3 '></a>
								".$imgC."
							</div>
						</div>
						  
						 
					</div>
					
					
				  </div><br>";		
			

		$i=$i+1;
	}

}
echo $table;

	
?>
