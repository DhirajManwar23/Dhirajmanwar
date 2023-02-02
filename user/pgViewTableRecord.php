<?php
session_start();
// Include class definition
require "function.php";
include("commonFunctions.php");
$sign=new Signup();
$commonfunction=new Common();

$plant_wbs_date=date("Y-m-d");
$requesttype = $_REQUEST["type"];
$request_id = $_REQUEST["id"];
$poid = $_REQUEST["poid"];
$qryPendingPO="select  aggeragator_name,gst,grn_number,type_of_submission,grnfile,purchase_invoice_number,purchase_invoice_date,dispatched_state,dispatched_place,invoice_quantity,invoicefile,plant_quantity,aggregator_wbs_number,aggregator_wbs_date,wbsfile,plant_wbs_number,plant_wbs_date,pwbsfile,vehicle_number,vehiclefile,eway_bill_number,ewayfile,lr_number,lr_date,lrfile,reason,category_name,material_name,status from tw_temp where id = '".$request_id."' order by id Asc";

$retValPendingPO = $sign->FunctionJSON($qryPendingPO);
$decodedJSON = json_decode($retValPendingPO);

$aggeragator_name = $decodedJSON->response[0]->aggeragator_name;
$gst = $decodedJSON->response[1]->gst;
$grn_number = $decodedJSON->response[2]->grn_number;
$type_of_submission = $decodedJSON->response[3]->type_of_submission;	
$grnfile = $decodedJSON->response[4]->grnfile;	
$purchase_invoice_number = $decodedJSON->response[5]->purchase_invoice_number;	

$purchase_invoice_date = $decodedJSON->response[6]->purchase_invoice_date;	
$purchase_invoice_date=date("d-m-Y", strtotime($purchase_invoice_date));

$dispatched_state = $decodedJSON->response[7]->dispatched_state;	
$dispatched_place = $decodedJSON->response[8]->dispatched_place;
$invoice_quantity = $decodedJSON->response[9]->invoice_quantity;	
$invoicefile = $decodedJSON->response[10]->invoicefile;
$plant_quantity = $decodedJSON->response[11]->plant_quantity;
$aggregator_wbs_number = $decodedJSON->response[12]->aggregator_wbs_number;	

$aggregator_wbs_date = $decodedJSON->response[13]->aggregator_wbs_date;
$aggregator_wbs_date=date("d-m-Y", strtotime($aggregator_wbs_date)); 

$wbsfile = $decodedJSON->response[14]->wbsfile;
$plant_wbs_number = $decodedJSON->response[15]->plant_wbs_number;

$plant_wbs_date = $decodedJSON->response[16]->plant_wbs_date;	
$plant_wbs_date=date("d-m-Y", strtotime($plant_wbs_date)); 

$pwbsfile = $decodedJSON->response[17]->pwbsfile;
$vehicle_number = $decodedJSON->response[18]->vehicle_number;
$vehiclefile = $decodedJSON->response[19]->vehiclefile;	
$eway_bill_number = $decodedJSON->response[20]->eway_bill_number;
$ewayfile = $decodedJSON->response[21]->ewayfile;
$lr_number = $decodedJSON->response[22]->lr_number;

$lr_date = $decodedJSON->response[23]->lr_date;
$lr_date=date("d-m-Y", strtotime($lr_date)); 

$lrfile = $decodedJSON->response[24]->lrfile;
$reason = $decodedJSON->response[25]->reason;
$category_name = $decodedJSON->response[26]->category_name;
$material_name = $decodedJSON->response[27]->material_name;
$status = $decodedJSON->response[28]->status;

//echo "Success";

$queryrejectedreason="SELECT reason FROM tw_rejected_reason_master where id='".$reason."'";
$rejectedreason=$sign->SelectF($queryrejectedreason,"reason");

$settingValueUserImagePathEPRServicesDocument=$commonfunction->getSettingValue("UserImagePathEPRSDocument");
$settingValueUserImagePathEPRServicesDocument.$lrfile;
$settingValueUserImagePathOther = $commonfunction->getSettingValue("UserImagePathOther");
$settingValueRejectedStatus= $commonfunction->getSettingValue("Rejected status");


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Trace Waste | View Record</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../assets/vendors/feather/feather.css">
  <link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- inject:css -->
  <link rel="stylesheet" href="../assets/css/vertical-layout-light/style.css">
  
  <link rel="stylesheet" href="../assets/css/custom/sweetalert2.min.css">
  <link rel="stylesheet" href="../assets/css/custom/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="../assets/images/favicon.png" />
      
</head>

<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
		<?php
			include_once("navTopHeader.php");
		?>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_settings-panel.html -->
        <?php
			include_once("navRightSideSetting.php");
		?>
      <!-- partial -->
      <!-- partial:partials/_sidebar.html -->
		<?php
			include_once("navSideBar.php");
		?>
      <!-- partial -->
      <div class="main-panel">        
        <div class="content-wrapper">
<!-----------------------------------------------First Row Starts -----------------------------------------> 	
          <div class="row">
            <div class="col-lg-12 col-md-12 grid-margin">
              <div class="card">
                <div class="card-body">
					<div style="text-align:center;">
						<h3><?php  if($aggeragator_name==""){echo "---" ;} else { echo $aggeragator_name;}?>
						<?php if($requesttype=="edit"){ ?>
							<a href="pgEPRDEditDocumentData.php?id=<?php echo $request_id; ?>&poid=<?php echo $poid; ?>"><i class="ti-pencil fs-20"></i></a>
						<?php } ?>
						</h3>
					</div>
					<br>
					<h5>Aggregator GST: <?php if($gst==""){echo "---" ;} else { echo $gst;}?></h5>				
					<h5>Type Of Submission: <?php if($type_of_submission==""){echo "---" ;} else {echo $type_of_submission;}?></h5>
						
				</div>
               </div>
             </div>
           </div> 
			
		 <!-----------------------------------------------First Row Ends -----------------------------------------> 	   
		 <!-----------------------------------------------Second Row Starts -----------------------------------------> 	
		<div class="row">
			<div class="col-lg-4 col-md-4 grid-margin">
				<div class="card">
					<div class="card-body">
						<h5>Aggregator GRN: <?php if($grn_number==""){echo "---" ;} else { echo $grn_number;}?></h5>	
						<h5>GRN File: <?php if($grnfile!=""){?>
						<a href="<?php echo $settingValueUserImagePathEPRServicesDocument.$grnfile;?>" target="_blank">View</a>
						<?php }else {echo "---";}?></h5>
					</div>
				</div>
			</div>  


			<div class="col-lg-4 col-md-4 grid-margin">
				<div class="card">
					<div class="card-body">
						<h5>Purchase Invoice Number: <?php if($purchase_invoice_number==""){echo "---" ;} else{ echo $purchase_invoice_number;}?></h5>	
						<h5>Purchase Invoice Date: <?php if($purchase_invoice_date==""){echo "---" ;} else{ echo $purchase_invoice_date;}?></h5>	
					</div>
				</div>
			</div> 

			<div class="col-lg-4 col-md-4 grid-margin">
				<div class="card">
					<div class="card-body">
					<h5>Dispatched State: <?php if($dispatched_state==""){echo "---" ;} else{ echo $dispatched_state;}?></h5>				
					<h5>Dispatched Place:<?php if($dispatched_place==""){echo "---" ;} else{ echo $dispatched_place;}?></h5>	
					</div>
				</div>
			</div>
		</div>
             
<!-----------------------------------------------Second Row Ends -----------------------------------------> 
<!-----------------------------------------------Third Row Starts -----------------------------------------> 
		
		<div class="row">
			<div class="col-lg-4 col-md-4 grid-margin">
				<div class="card">
					<div class="card-body">
						<h5>Invoice Quantity: <?php if($invoice_quantity==""){echo "---" ;} else{ echo $invoice_quantity;}?></h5>
						<h5>Invoice File:<?php if($invoicefile!=""){?>
						<a href="<?php echo $settingValueUserImagePathEPRServicesDocument.$invoicefile;?>" target="_blank">View</a>
						<?php }else {echo "---";}?></h5>
					</div>
				</div>
			</div> 

			<div class="col-lg-4 col-md-4 grid-margin">
				<div class="card">
					<div class="card-body">
						<h5>Aggregator WBS Number: <?php if($aggregator_wbs_number==""){echo "---" ;} else{ echo $aggregator_wbs_number;}?></h5>		
						<h5>Aggregator WBS Date: <?php if($aggregator_wbs_date==""){echo "---" ;} else{ echo $aggregator_wbs_date;}?></h5>		
						<h5> WBS File: <?php if($wbsfile!=""){?>
						<a href="<?php echo $settingValueUserImagePathEPRServicesDocument.$wbsfile;?>" target="_blank">View</a>
						<?php }else{ echo"---";}?></h5>		
					</div>
				</div>
			</div>

			<div class="col-lg-4 col-md-4 grid-margin">
				<div class="card">
					<div class="card-body">
						<h5>Plant Quantity: <?php if($plant_quantity==""){echo "---" ;} else{  echo $plant_quantity;}?></h5>
						<h5>Plant WBS Number: <?php if($plant_wbs_number==""){echo "---" ;} else{  echo $plant_wbs_number;}?></h5>		
						<h5>Plant WBS Date: <?php if($plant_wbs_date==""){echo "---" ;} else{  echo $plant_wbs_date;}?></h5>		
						<h5> Plant WBS File: <?php if($pwbsfile!=""){?>

						<a href="<?php echo $settingValueUserImagePathEPRServicesDocument.$pwbsfile;?>" target="_blank">View</a>

						<?php } else{ echo"---";}?></h5>		
					</div>
				</div>
			</div>
		</div>
<!-----------------------------------------------Third Row Ends ----------------------------------------->       
<!-----------------------------------------------Fourth Row Starts ----------------------------------------->       

		<div class="row">
			<div class="col-lg-4 col-md-4 grid-margin">
				<div class="card">
					<div class="card-body">
						<div style="text-align:left;">
							<h5>Vehicle Number: <?php if($vehicle_number==""){echo "---" ;} else{ echo $vehicle_number;}?></h5>
							<h5>Vehicle File:<?php if($vehiclefile!=""){?>
							<a href="<?php echo $settingValueUserImagePathEPRServicesDocument.$vehiclefile;?>" target="_blank">View</a>
							<?php } else{ echo"---";}?></h5>
						</div>	
					</div>
				</div>
			</div>

			<div class="col-lg-4 col-md-4 grid-margin">
				<div class="card">
					<div class="card-body">
						<div style="text-align:left;">
						<h5>Eway Bill Number: <?php if($eway_bill_number==""){echo "---" ;} else{ echo $eway_bill_number;}?></h5>
						<h5>Eway File: <?php if($ewayfile!=""){?>

						<a href="<?php echo $settingValueUserImagePathEPRServicesDocument.$ewayfile;?>" target="_blank">View</a>
						<?php } else{ echo"---";}?>	</h5>
						</div>	
					</div>
				</div>
			</div> 

			<div class="col-lg-4 col-md-4 grid-margin">
				<div class="card">
					<div class="card-body">
						<div style="text-align:left;">
							<h5>LR Number: <?php if($lr_date==""){echo "---" ;} else { echo $lr_number;}?></h5>
							<h5>LR Date: <?php if($lr_date==""){echo "---" ;} else { echo $lr_date;}?></h5>
							<h5>LR File: 
							<?php if($lrfile!=""){?>
							<a href="<?php echo $settingValueUserImagePathEPRServicesDocument.$lrfile;?>" target="_blank">View</a>
							<?php } else{ echo"---";}?>			
							</h5>
						</div>	
					</div>
				</div>
			</div> 
		</div> 

<!-----------------------------------------------Fourth Row Ends ----------------------------------------->       
<!-----------------------------------------------Fifth Row Starts -----------------------------------------> 			 
		<div class="row">
			<?php if($status == $settingValueRejectedStatus){?>
				<div class="col-lg-4 col-md-4 grid-margin">
					<div class="card">
						<div class="card-body">
							<div style="text-align:left;">
								<h5>Reason: <?php echo $rejectedreason;?></h5>
							</div>	
						</div>
					</div>
				</div> 
			<?php }  ?>

			<div class="col-lg-4 col-md-4 grid-margin">
				<div class="card">
					<div class="card-body">
						<div style="text-align:left;">
							<h5>Category Name: <?php if($category_name==""){echo "---" ;} else { echo $category_name;}?></h5>
							<h5>Material Name: <?php if($material_name==""){echo "---" ;} else { echo $material_name;}?></h5>
						</div>	
					</div>
				</div>
			</div>
		</div>
			 
 <!-----------------------------------------------Fifth Row Ends ----------------------------------------->			 
			 
         </div> 
		     
	   <?php
			include_once("footer.php");
		?>
	</div>
			              
			 
   </div>
    
</div>  
      <!-- main-panel ends -->
    
</body>
</html>