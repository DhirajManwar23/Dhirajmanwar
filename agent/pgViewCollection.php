<?php 
session_start();
if(!isset($_SESSION["agent_id"])){
	header("Location:pgAgentLogin.php");
}
// Include class definition 
include("function.php");
include("commonFunctions.php");
$sign=new Signup();
$commonfunction=new Common();
$SearchDate="";
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d");
$agent_id = $_SESSION["agent_id"];

$settingValuePendingStatus= $commonfunction->getSettingValue("Pending Status");
$settingValueApprovedStatus= $commonfunction->getSettingValue("Approved Status");
$settingValueAwaitingStatus= $commonfunction->getSettingValue("Awaiting Status");
$settingValueCompletedStatus= $commonfunction->getSettingValue("Completed Status");
$settingValueRejectedStatus= $commonfunction->getSettingValue("Rejected status");

$qry1="select count(*) as cnt from tw_mix_waste_collection where agent_id='".$agent_id."' and status='".$settingValuePendingStatus."' and collection_date_time LIKE '%".$cur_date."%' order by id desc";
$qryCntInprocess = $sign->Select($qry1);

$qry2="select count(*) as cnt from tw_mix_waste_collection where agent_id='".$agent_id."' and status='".$settingValueApprovedStatus."' and collection_date_time LIKE '%".$cur_date."%' order by id desc";
$qryCntApproved = $sign->Select($qry2);

$qry3="select count(*) as cnt from tw_mix_waste_collection where agent_id='".$agent_id."' and status='".$settingValueAwaitingStatus."' and collection_date_time LIKE '%".$cur_date."%' order by id desc";
$qryCntAwaiting = $sign->Select($qry3);

$qry4="select count(*) as cnt from tw_mix_waste_collection where agent_id='".$agent_id."' and status='".$settingValueCompletedStatus."' and collection_date_time LIKE '%".$cur_date."%' order by id desc";
$qryCntCompleted = $sign->Select($qry4);

$qry5="select count(*) as cnt from tw_mix_waste_collection where agent_id='".$agent_id."' and collection_date_time LIKE '%".$cur_date."%' ";
$qryCntAll = $sign->Select($qry5);

$qry6="select count(*) as cnt from tw_mix_waste_collection where agent_id='".$agent_id."' and status='".$settingValueRejectedStatus."' and collection_date_time LIKE '%".$cur_date."%' order by id desc";
$qryCntRejected = $sign->Select($qry6);

$Status="";
$qry5 = "select id,verification_status from tw_verification_status_master where visibility='true' Order by verification_status ,priority desc";
$retVal5 = $sign->FunctionOption($qry5,$Status,'verification_status','id');


?>
<!DOCTYPE html>
<html lang="en">
<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Trace Waste | View Collection</title>
<!-- plugins:css -->
<link rel="stylesheet" href="../assets/vendors/feather/feather.css">
<link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
<link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
<!-- endinject -->
<!-- inject:css -->
<link rel="stylesheet" href="../assets/css/vertical-layout-light/style.css">
<link rel="stylesheet" href="../assets/css/custom/sweetalert2.min.css">
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
         <div class="form-group">
			<div class="row">
				<div class="col-lg-12">
					<div class="mt-4 py-2 border-top border-bottom">
                        <ul class="nav profile-navbar">
                          <li class="nav-item">
                            <p class="nav-link" id="totalcollection" > <?php echo $qryCntAll; ?></p>
                          </li>
                          <li class="nav-item">
                            <p class="nav-link" id="PendingCollection" > <?php echo $qryCntInprocess; ?></p>
                          </li>
                          <li class="nav-item">
                            <p class="nav-link" id="AwaitingCollection" > <?php echo $qryCntAwaiting; ?></p>
                          </li>
                          <li class="nav-item">
                            <p class="nav-link" id="AcceptedCollection" > <?php echo $qryCntApproved; ?></p>
                          </li>
                          <li class="nav-item">
                             <p  class="nav-link" id="RejectedCollection" > <?php echo $qryCntRejected; ?></p>                    
                          </li>
                        </ul>
                    </div>
				</div>
			</div>					  
			<br>
			<div class="row">
				<div class="col-md-3">
					<label class="col-sm-12">Start Date </label>
					<input type="date" class="form-control" id="txtStartDate" placeholder="Select Start Date" value='<?php echo date("Y-m-d",strtotime($cur_date));?>'/>
				</div>
				<div class="col-md-3">
					<label class="col-sm-12">End Date </label>
					<input type="date" class="form-control" id="txtEndDate" placeholder="Select End Date" value='<?php echo date("Y-m-d",strtotime($cur_date));?>' max="<?php echo($cur_date);?>"/>
				</div>
				<div class="col-md-3">
				<label class="col-sm-12">Status </label>
					<select name="Status" id="txtStatus" class="form-control" >
						<?php echo $retVal5;?>
					</select>
				</div>
				<div class="col-md-3">
				<label class="col-sm-12">Search</label>							
					<button type="Submit" class="btn btn-success" id="btnSearchrecord" onclick="SearchResult();">Search</button>
				</div>
			</div>
						
		</div>
		<div id="bottomDiv">
		</div>
				
      </div>
	<!-- content-wrapper ends -->
	<!-- partial:partials/_footer.html -->
	<?php
		include_once("footer.php");
	?>
	<!-- partial -->
  </div>
      <!-- main-panel ends -->
 </div>
    <!-- page-body-wrapper ends -->
</div>
<!-- container-scroller -->
<!-- plugins:js -->
<script src="../assets/vendors/js/vendor.bundle.base.js"></script>
<!-- endinject -->
<!-- Plugin js for this page -->
<script src="../assets/vendors/typeahead.js/typeahead.bundle.min.js"></script>
<!-- End plugin js for this page -->
<!-- inject:js -->
<script src="../assets/js/hoverable-collapse.js"></script>
<script src="../assets/js/template.js"></script>
<script src="../assets/js/settings.js"></script>
<!-- endinject -->
<script src="../assets/js/custom/sweetAlert.js"></script>
<script src="../assets/js/custom/sweetalert2.min.js"></script>
<script src="../assets/css/jquery/jquery.min.js"></script>
<script src="../assets/js/custom/twCommonValidation.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type='text/javascript'>

$(document).ready(function(){
	SearchResult();
});
function SearchResult(){	
		  $.ajax({
          type:"POST",
          url:"apiSearchViewCollectionDetails.php",
		  dataType: 'JSON',
          data:{StartDate:$("#txtStartDate").val(),EndDate:$("#txtEndDate").val(),SearchStatus:$("#txtStatus").val()},
          success:function(response){
			  console.log(response[0]);
			 $("#bottomDiv").html(response[0]);
			 $("#totalcollection").html("<i class='ti-pencil-alt'></i>" + " Total: " + response[1]);
			 $("#PendingCollection").html("<i class='ti-timer'></i>" + " Pending: " + response[2]);
			 $("#AwaitingCollection").html("<i class='ti-alert'></i>" + " Awaiting: " + response[3]);
			 $("#AcceptedCollection").html("<i class='ti-check-box'></i>" + " Approved: " + response[4]);
			 $("#RejectedCollection").html("<i class='ti-na'></i>" + " Rejected: " + response[5]);
			
          }
      });
}		 
</script>
</body>
</html>