<?php 
session_start();
if(!isset($_SESSION["employee_id"])){
	header("Location:pgEmployeeLogin.php");
}
include_once "function.php";
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();

$settingValueEmployeeImage= $commonfunction->getSettingValue("Employee Image");
$settingValueEmployeeImagePathOther  = $commonfunction->getSettingValue("EmployeeImagePathOther");
$settingValueAcceptImage  = $commonfunction->getSettingValue("Accept Image");
$settingValueRejectImage  = $commonfunction->getSettingValue("Reject Image");

$case_id = "";
$createdOn = "";
$scheme_doc = "";
$status	 = "";
$citizen_guid = "";
$citizen_name = "";
$dob = "";
$gender = "";
$mobile = "";
$age = "";
$family_guid = "";
$family_name = "";
$hd_id = "";
$Location = "";

/*$qryRagpicker="select case_id,createdOn,scheme_doc,status,citizen_guid,citizen_name,dob,gender,mobile,age,family_guid,family_name,hd_id,Location, from tw_ragpicker_information where id='".$requestid."'";
$valQryRagpicker = $sign->FunctionJSON($qryRagpicker);
$decodedJSON = json_decode($valQryRagpicker);
$case_id = $decodedJSON->response[0]->case_id; 
$createdOn = $decodedJSON->response[1]->createdOn; 
$scheme_doc = $decodedJSON->response[2]->scheme_doc;
$status	 = $decodedJSON->response[3]->status	;
$citizen_guid = $decodedJSON->response[4]->citizen_guid;
$citizen_name = $decodedJSON->response[5]->citizen_name;
$dob = $decodedJSON->response[6]->dob;
$gender = $decodedJSON->response[7]->gender;
$mobile = $decodedJSON->response[8]->mobile;
$age = $decodedJSON->response[9]->age;
$family_guid = $decodedJSON->response[10]->family_guid;
$family_name = $decodedJSON->response[11]->family_name;
$hd_id = $decodedJSON->response[12]->hd_id;
$Location = $decodedJSON->response[13]->Location;*/

?>

<!DOCTYPE html>
<html lang="en">

<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Trace Waste | Data Entry </title>
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
<link rel="stylesheet" href="../assets/css/custom/style.css" />
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
	
		
		<div class="row" id="reportData">
					<div class="col-lg-6 col-md-6 col-sm-12 col-12 grid-margin stretch-card">
						<div class="card">
							<div class="card-body">
								<div class="d-flex justify-content-between">
									<p class="card-title">Scheme Benefit Utilisation</p>
								</div>
								<canvas id="Bar_chart"></canvas>
							</div>
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12 col-12 grid-margin stretch-card">
						<div class="card">
							<div class="card-body">
								<div class="d-flex justify-content-between">
									<p class="card-title">Gender Wise Beneficiaries of Safai Sathi</p>
								</div>
								<canvas id="donut_chart"></canvas>
							</div>
						</div>
					</div>
		</div>
		<?php
		$docnameQry="SELECT DISTINCT scheme_doc FROM tw_ragpicker_information";
		$retVal2 = $sign->FunctionJSON($docnameQry);
		$decodedJSON3 = json_decode($retVal2);
		$qryCnt1="SELECT COUNT(DISTINCT scheme_doc)as cnt FROM tw_ragpicker_information";
		$retVal4 = $sign->Select($qryCnt1);
		
		$QryDetails="SELECT DISTINCT Citizen_Name,Status,DOB,Gender,Mobile,Age,Location FROM tw_ragpicker_information GROUP by Citizen_Name;";
		$retVal1 = $sign->FunctionJSON($QryDetails);
		$decodedJSON2 = json_decode($retVal1);
		
		$qryCnt="SELECT COUNT(DISTINCT Citizen_Name)as cnt FROM tw_ragpicker_information";
		$retVal3 = $sign->Select($qryCnt);
		
		$count = 0;
		$i = 1;
		$x=$retVal3;
		while($x>=$i){
		$Citizen_Name = $decodedJSON2->response[$count]->Citizen_Name;
		$count=$count+1;
	    // $scheme_doc = $decodedJSON2->response[$count]->scheme_doc;
		// $count=$count+1;
		$Status = $decodedJSON2->response[$count]->Status;
		$count=$count+1;
		$DOB = $decodedJSON2->response[$count]->DOB;
		$count=$count+1;
		$Gender = $decodedJSON2->response[$count]->Gender;
		$count=$count+1;
		$Mobile = $decodedJSON2->response[$count]->Mobile;
		$count=$count+1;
		$Age = $decodedJSON2->response[$count]->Age;
		$count=$count+1;
		$Location = $decodedJSON2->response[$count]->Location;
		$count=$count+1;
		
		?> <div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12 grid-margin stretch-card">
				  <div class="card">
						<div class="card-body">
						   <div class="row bgcard">
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12">
							<div class="row rag-details-row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
									<img src="<?php echo $settingValueEmployeeImagePathOther."/".$settingValueEmployeeImage ?>" class="rag-profile-pic">
								</div>
								
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
							<br>
									<h4 class="rag-name"><?php echo $Citizen_Name; ?></h4>
								</div>
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
								<p class="text-center">
								<strong>Gender:<span> <?php if($Gender=="M") { echo "Male"; } else{ echo "Female"; } ?></span></strong>
								&nbsp;&nbsp;| <img src="<?php echo $settingValueEmployeeImagePathOther?>/location.png">
									<span>Location: <?php echo $Location; ?></span>
								&nbsp;&nbsp;|
									<span>DOB: <?php echo $DOB; ?></span>
								</p>
								</div>
							</div>
						</div>
						
	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12">
		<div class="row">			
			<?php
			$count1 = 0;
			$i1 = 1;
			$x1=$retVal4;
			while($x1>=$i1){
			$scheme_doc = $decodedJSON3->response[$count1]->scheme_doc;
			$count1=$count1+1;
										
			$StatusQry="SELECT Status FROM tw_ragpicker_information where scheme_doc='".$scheme_doc."' and Citizen_Name='".$Citizen_Name."'";
			$Status=$sign->SelectF($StatusQry,"Status");
										
			$docNameQry="SELECT document_type FROM tw_ragpicker_documents WHERE id='".$scheme_doc."'";
			$docName=$sign->SelectF($docNameQry,"document_type");
			if($Status=="No"){
			?>		
										
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12 ">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12col-12 text">
			<img src="<?php echo $settingValueEmployeeImagePathOther?>/cross.svg" class="checksize"> <?php echo  $docName; ?>
		</div>
			<div class="row line-gap ">
				<div class="col-lg-11 col-md-11 col-sm-12 col-xs-12 col-12 divlien">
				</div>
			</div>
	</div>
	<?php
		}
	else{
	?>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12 text">
			<img src="<?php echo $settingValueEmployeeImagePathOther?>/checked.png" class="checksize"><span> <?php echo $docName; ?></span>
		</div>
		<div class="row line-gap">
			<div class="col-lg-11 col-md-11 col-sm-12 col-xs-12 col-12 divlien"></div>
		</div>
	</div>
	<?php  }
			$i1=$i1+1;
			} ?> 
										
	</div>            
	</div>
	</div>
		</div>
	</div>
</div>
</div>
		<?php
		 $i=$i+1;
		}
		 ?> 
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
<!-- End plugin js for this page -->
<script src="../assets/vendors/chart.js/Chart.min.js"></script>
<script src="../assets/vendors/datatables.net/jquery.dataTables.js"></script>
<script src="../assets/vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
<script src="../assets/js/dataTables.select.min.js"></script>
<!-- inject:js -->
<!-- Custom js for this page-->
<script src="../assets/js/dashboard.js"></script>
<script src="../assets/js/Chart.roundedBarCharts.js"></script>
<script src="../assets/js/chart.js"></script>
<!-- End custom js for this page-->
<script src="../assets/css/jquery/jquery.min.js"></script>
<script src="../assets/js/custom/sweetAlert.js"></script>
<script src="../assets/js/custom/sweetalert2.min.js"></script>
<script type='text/javascript'>
$(document).ready(function(){
	 $("#Chart").hide();
	 $("#Report").hide();
	 buildReports()
});
function buildReports() {
	$.post("api-report-type-RagInfo.php",function(response) {
		console.log(response);
		buildLineChart(response.Bar_chart_data);
		buildDonutChart(response.donut_chart_data);
	});
}
function buildDonutChart(donut_chart_data) {
	var wasteQuantityArray = [donut_chart_data[0],donut_chart_data[1]];
	var colorsArray = [];
    console.log(wasteQuantityArray[0]);
	var donutCtx = document.getElementById("donut_chart").getContext("2d");
	new Chart(donutCtx, {
		type: 'doughnut',
		data: {
			labels: [
				'Male',
				'Female',
				
			  ],
			datasets: [{
				label: 'Data',
				data: wasteQuantityArray,
				 backgroundColor: [
				  '#2196f3',
				  '#f73378'				 
				],
			}]
		},
		options: {
			responsive: true,
			 legend: {
            
        },
			plugins: {
				title: {
					display: true,
					text: 'Doughnut Chart'
				},
				
			}
		},
	});
  
}
function buildLineChart(Bar_chart_data) {
	var ctx = document.getElementById("Bar_chart").getContext("2d");
	var quantityArray = []
	Bar_chart_data.forEach((item) => {
		quantityArray.push(item.EShram,item.Jandhan,item.PMSBY,item.PMJJBY);
	});
 
	new Chart(ctx, {
		type: 'bar',
		
		data: {
			 
			labels: ['EShram','Jandhan','PMSBY','PMJJBY'],
			datasets: [{
				
				data: quantityArray,
				borderWidth: 1,
			    backgroundColor: [
				  'rgba(255, 99, 132, 0.2)',
				  'rgba(255, 159, 64, 0.2)',
				  'rgba(255, 205, 86, 0.2)',
				  'rgba(75, 192, 192, 0.2)'
				 
				],
				borderColor: [
				  'rgb(255, 99, 132)',
				  'rgb(255, 159, 64)',
				  'rgb(255, 205, 86)',
				  'rgb(75, 192, 192)'
				],
			}]
			
		},
		options: {
			legend: {
        display: false
		},
		scales: {
        yAxes: [{
            ticks: {
                beginAtZero: true
            }
        }]
    }
			
		}
	});
	
}
function getColor() {
	var r = Math.floor(Math.random() * 200);
	var g = Math.floor(Math.random() * 200);
	var b = Math.floor(Math.random() * 200);
	return 'rgb(' + r + ', ' + g + ', ' + b + ')';
}
</script>
</body>
</html>