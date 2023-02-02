<?php
session_start();
	
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";
$commonfunction=new Common();
$sign=new Signup();
$settingValuePendingStatus= $commonfunction->getSettingValue("Pending Status");
$settingValueApprovedStatus= $commonfunction->getSettingValue("Approved Status");
$settingValueRejectedStatus= $commonfunction->getSettingValue("Rejected status");
$settingValueCompletedStatus= $commonfunction->getSettingValue("Completed Status");
$settingValueAwaitingStatus= $commonfunction->getSettingValue("Awaiting Status");
$settingValueUserImagePathVerification= $commonfunction->getSettingValue("UserImagePathVerification");
$settingValueUserImagePathOther = $commonfunction->getSettingValue("UserImagePathOther");
$settingValueAcceptImage = $commonfunction->getSettingValue("Accept Image");
$settingValueRejectImage = $commonfunction->getSettingValue("Reject Image");

$settingValueEmployeeImagePathOther=$commonfunction->getSettingValue("EmployeeImagePathOther");
$settingValueNodatafoundImage=$commonfunction->getSettingValue("NodatafoundImage");
$settingValueCompanyImage= $commonfunction->getSettingValue("Company Image");
$settingValuePemail= $commonfunction->getSettingValue("Primary Email");


$settingValueCollectionPointImagePathOther= $commonfunction->getSettingValue("CollectionPointImagePathOther");
$settingValueCollectionPointImagePathVerification= $commonfunction->getSettingValue("CollectionPointImagePathVerification");
$settingValueCollectionPointImage= $commonfunction->getSettingValue("CollectionPoint Image");
$settingValueAgentImagePathOther = $commonfunction->getSettingValue("AgentImagePathOther");
$settingValueAgentImagePathVerification = $commonfunction->getSettingValue("AgentImagePathVerification");
$settingValueAgentImage = $commonfunction->getSettingValue("Agent Image");
$settingValueDistanceImage = $commonfunction->getSettingValue("DistanceImage");
$settingValuegalleryImage  = $commonfunction->getSettingValue("galleryImage");
$company_id = $_SESSION["company_id"];
$mix_waste_lot_id = $_POST["mix_waste_lot_id"];

$qry="select id,mix_waste_collection_id from tw_mix_waste_lot_details
 where mix_waste_lot_id='".$mix_waste_lot_id."' order by id asc";
	$qry1="select count(*) as cnt from tw_mix_waste_lot_details where mix_waste_lot_id='".$mix_waste_lot_id."' ";

$retVal = $sign->FunctionJSON($qry);
$qryCnt = $sign->Select($qry1);

$decodedJSON2 = json_decode($retVal);
$count = 0;
$TotalBalQty = 0.00;
$per = 0.00;
//$valper = 0.00;
$i = 1;
$x=$qryCnt;
$progressstatus="";
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
		$mix_waste_collection_id = $decodedJSON2->response[$count]->mix_waste_collection_id;
		$count=$count+1;
		
		
		$qry="select cp_id,agent_id,collection_date_time,type_of_material,quantity,status,reason,photo,geo_location from tw_mix_waste_collection where id='".$mix_waste_collection_id."' order by id desc";
		
		$retVal = $sign->FunctionJSON($qry);
		$decodedJSON = json_decode($retVal);
		
		$cp_id = $decodedJSON->response[0]->cp_id;
		$agent_id = $decodedJSON->response[1]->agent_id;
		$collection_date_time = $decodedJSON->response[2]->collection_date_time;
		$type_of_material = $decodedJSON->response[3]->type_of_material;
		$quantity = $decodedJSON->response[4]->quantity;
		$status = $decodedJSON->response[5]->status;
		$reason = $decodedJSON->response[6]->reason;
		$photo = $decodedJSON->response[7]->photo;
		$geo_location = $decodedJSON->response[8]->geo_location;
		
		$qry2="Select collection_point_name,collection_point_logo,mobile_number, location from tw_collection_point_master where id='".$cp_id."'";
		$retVal = $sign->FunctionJSON($qry2);
		$decodedJSON = json_decode($retVal);
		$collection_point_name = $decodedJSON->response[0]->collection_point_name;	
		$collection_point_logo = $decodedJSON->response[1]->collection_point_logo;	
		$mobile_number = $decodedJSON->response[2]->mobile_number;	
		$colllocation = $decodedJSON->response[3]->location;	
		if($collection_point_logo==""){
			$collectionpointlogo = $settingValueCollectionPointImagePathOther.$settingValueCollectionPointImage;
		}
		else{
			$collectionpointlogo = $settingValueCollectionPointImagePathVerification.$mobile_number."/".$collection_point_logo;
		}
		
		$qry3="Select agent_name,agent_photo,mobilenumber, location from tw_agent_details where id='".$agent_id."'";
		$retVal1 = $sign->FunctionJSON($qry3);
		$decodedJSON = json_decode($retVal1);
		$agent_name = $decodedJSON->response[0]->agent_name;	
		$agent_photo = $decodedJSON->response[1]->agent_photo;	
		$mobilenumber = $decodedJSON->response[2]->mobilenumber;	
		$agtlocation = $decodedJSON->response[3]->location;	
		
		if($agent_photo==""){
			$agentphoto = $settingValueAgentImagePathOther.$settingValueAgentImage;
		}
		else{
			$agentphoto = $settingValueAgentImagePathVerification.$mobilenumber."/".$agent_photo;
		}
		
		$qry5="Select verification_status from tw_verification_status_master where id='".$status."'";
		$verification_status = $sign->SelectF($qry5,"verification_status");
		
		$verifyStatus=$commonfunction->getSettingValue("Verified Status");
		$VerifiedImage=$commonfunction->getSettingValue("Verified Image");
        
		
		$imgC="";
		if($photo!==""){
			$imgpath = $settingValueAgentImagePathVerification.$mobile_number."/".$photo;
			$imgC = "<a href='".$imgpath."' class='float-right target='_blank'><img src='".$settingValueCollectionPointImagePathOther.$settingValuegalleryImage."' width='100%' class='img-sm mb-3 '></a>";
		}
		$distanceimage = $settingValueCollectionPointImagePathOther.$settingValueDistanceImage;
		$link="https://www.google.com/maps/place/".$geo_location;
		/* $table.="<div class='card'>
				  
					<div class='card-body'>
						<div class='row'>
							
							<div class='col-lg-10 col-md-10 col-sm-10 col-xs-10 col-10'>
								<p>Collection Point : ".$collection_point_name."</p><p>Agent Name : ".$agent_name."</p><p>Status: ".$verification_status."</p>
								 <p><i class='ti-calendar'></i> Collection Date: ".date("d-m-Y", strtotime($collection_date_time))." | <i class='ti-package'></i> Total Quantity : ".number_format($quantity,2)."</p>
							</div>
						</div>
						 
					</div>
					
				  </div><br>
						"; */
		$table.="
				<div class='col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12'>
				  <div class='bg-success p-4 rounded'>
					
					<div class='row'>
						<div class='col-lg-10 col-md-10 col-sm-10 col-xs-10 col-10'>
							<h6 class='card-title text-white'><i class='ti-calendar'></i> ".date("d-m-Y", strtotime($collection_date_time))." | <i class='ti-package'></i> ".number_format($quantity,2)." | ".$verification_status."</h6>
						</div>                               
					 </div>
					<div id='profile-list-left' class='py-2'>
					  <div class='card rounded mb-2'>
						<div class='card-body p-3'>
						  <div class='media'>
							<img src='".$collectionpointlogo."' alt='image' class='img-sm me-3 rounded-circle'>
							<div class='media-body'>
							  <h6 class='mb-1'>".$collection_point_name."</h6>
							  <p class='mb-0 text-muted'>
								 ".$colllocation."                               
							  </p>
							</div>                              
						  </div>
						</div>
					  </div>
					  <div class='card rounded mb-2'>
						<div class='card-body p-3'>
						  <div class='media'>
							<img src='".$agentphoto."' alt='image' class='img-sm me-3 rounded-circle'>
							<div class='media-body'>
							  <h6 class='mb-1'>".$agent_name."</h6>
							  <p class='mb-0 text-muted'>
								 ".$agtlocation."                                 
							  </p>
							</div>                              
						  </div>  
						</div>
					  </div>
					</div>
					
					<div class='row'>
						<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12'>
							 <a  href='".$link."' class='float-right ' target='_blank'><img src='".$distanceimage."' width='100%' class='img-sm mb-3 '></a>
								".$imgC."
						</div>                    
					 </div>
				  </div><br>
				</div>";

		$i=$i+1;
	}

}
echo $table;

	
?>
