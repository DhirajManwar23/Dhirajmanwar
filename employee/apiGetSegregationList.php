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
$employee_id = $_SESSION["employee_id"];
$statustype = $_POST["statustype"];

if($statustype==""){
	$qry="select id,cp_id,agent_id,geo_location,drop_geo_location,collection_date_time,type_of_material,quantity,amount,total_amount,photo,status,reason from tw_mix_waste_collection
 where company_id='".$company_id."' and employee_id='".$employee_id."' order by id desc";
	$qry1="select count(*) as cnt from tw_mix_waste_collection where company_id='".$company_id."' and employee_id='".$employee_id."'  order by id desc";
}
else{
  $qry="select id,cp_id,agent_id,geo_location,drop_geo_location,collection_date_time,type_of_material,quantity,amount,total_amount,photo,status,reason from tw_mix_waste_collection
 where company_id='".$company_id."' and status='".$statustype."'  and employee_id='".$employee_id."'  order by id desc";
	$qry1="select count(*) as cnt from tw_mix_waste_collection where company_id='".$company_id."' and status='".$statustype."'  and employee_id='".$employee_id."'  order by id desc";
}
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
	
	<div class='card '>
				  
					<div class='card-body text-center'>
							<img src='".$settingValueEmployeeImagePathOther."".$settingValueNodatafoundImage."' width='250px' />
						</div>
					
					
				  </div><br>";
}
else{
	while($x>=$i){
		$id = $decodedJSON2->response[$count]->id;
		$count=$count+1;
		$cp_id = $decodedJSON2->response[$count]->cp_id;
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
		$amount  = $decodedJSON2->response[$count]->amount ;
		$count=$count+1;
		$total_amount  = $decodedJSON2->response[$count]->total_amount ;
		$count=$count+1;
		$photo  = $decodedJSON2->response[$count]->photo ;
		$count=$count+1;
		$status  = $decodedJSON2->response[$count]->status ;
		$count=$count+1;
		$reason  = $decodedJSON2->response[$count]->reason ;
		$count=$count+1;
		
		$qry2="Select collection_point_name,collection_point_logo,mobile_number,location,ward from tw_collection_point_master where id='".$cp_id."'";
		$retVal = $sign->FunctionJSON($qry2);
		$decodedJSON = json_decode($retVal);
		$collection_point_name = $decodedJSON->response[0]->collection_point_name;	
		$collection_point_logo = $decodedJSON->response[1]->collection_point_logo;	
		$mobile_number = $decodedJSON->response[2]->mobile_number;	
		$colllocation = $decodedJSON->response[3]->location;	
		$ward = $decodedJSON->response[4]->ward;	
		
		$wardNameqry="SELECT ward_name FROM `tw_ward_master` where id='".$ward."'";
		$wardName=$sign->SelectF($wardNameqry,"ward_name");
		if($collection_point_logo==""){
			$collectionpointlogo = $settingValueCollectionPointImagePathOther.$settingValueCollectionPointImage;
		}
		else{
			$collectionpointlogo = $settingValueCollectionPointImagePathVerification.$mobile_number."/".$collection_point_logo;
		}
		
		$qry3="Select agent_name,agent_photo,mobilenumber,location from tw_agent_details where id='".$agent_id."'";
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

		
		if($status==$settingValueAwaitingStatus){
			$div = "";
			$divCheck = "<input class='cbCheck' name='cbCheck' onclick='checkIndividual();' type='checkbox' value='".$id."/".$quantity."' id='check-".$id."' />";
			$dropdown = "";
			$valcolor = "warning";
		}
		else if($status==$settingValueRejectedStatus){
			$div = "<div class='btn-group' role='group'>
						<code>".$reason."</code>
					</div>";
			$dropdown = "";
			$divCheck = "<a class='dropdown-item text-white' href='#' onclick='viewReasonDetails(".$id.")'><i class='ti-info'></i></a>";
			$valcolor = "danger";
		}
		else if($status==$settingValueCompletedStatus){
			$div = "";
			$dropdown = "";
			$divCheck = "";
			$valcolor = "success";
		}
		else{
			$valcolor = "info";
			$buttondisabled = "";
			if($per==100.00 || $per==100){
				$buttondisabled = "disabled";
			}
			else{
				$buttondisabled = "";
			} 
			
			$div="";
			$divCheck = "";
			$dropdown = "<div class='row'>
						
							<div class='col-12 col-xl-12'>
							 <div class='justify-content-end d-flex'>
							  <div class='dropdown flex-md-grow-1 flex-xl-grow-0'>
								<button class='btn btn-sm btn-light bg-white dropdown-toggle' type='button' id='dropdownMenuDate2' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
								 </button>
								<div class='dropdown-menu dropdown-menu-right' aria-labelledby='dropdownMenuDate2' style=''>
								  <a class='dropdown-item' href='#' onclick='addRecord(".$id.")'><i class='ti-plus'></i> Segregate</a>
							 </div>
							</div>
						  </div>
						</div>
					  </div>";
		}
				  
		$imgC="";
		if($photo!==""){
			$imgpath = $settingValueAgentImagePathVerification.$mobile_number."/".$photo;
			$imgC = "<a href='".$imgpath."' class='float-right target='_blank'><img src='".$settingValueCollectionPointImagePathOther.$settingValuegalleryImage."' width='100%' class='img-sm mb-3 '></a>";
		}
		$distanceimage = $settingValueCollectionPointImagePathOther.$settingValueDistanceImage;
		$link="https://www.google.com/maps/place/".$geo_location;
		$table.="
				<div class='col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12'>
				  <div class='bg-".$valcolor." p-4 rounded'>
					
					<div class='row'>
						<div class='col-lg-10 col-md-10 col-sm-10 col-xs-10 col-10'>
							<h6 class='card-title text-white'><i class='ti-calendar'></i> ".date("d-m-Y", strtotime($collection_date_time))." | <i class='ti-package'></i> ".number_format($quantity,2)." | ".$verification_status." | &#8377;".$amount." | &#8377;".$total_amount."</h6>
						</div> 
						<div class='col-lg-2 col-md-2 col-sm-2 col-xs-2 col-2'>
							".$divCheck."
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
								 ".$colllocation." | ".$wardName."                             
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
							 <a href='".$link."' class='float-right' target='_blank'><img src='".$distanceimage."' width='100%' class='img-sm mb-3 '></a>
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
