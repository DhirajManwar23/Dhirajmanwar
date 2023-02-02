<?php 
session_start();
	if(!isset($_SESSION["employeeusername"])){
		header("Location:pgEmployeeLogIn.php");
	}
	include_once "function.php";
	include_once "commonFunctions.php";
	$sign=new Signup();
	$commonfunction=new Common();
	
	$employee_photo = "";
	$value = "";
	$employee_id=$_SESSION["employee_id"];
	$company_id=$_SESSION["company_id"];
	
	$settingValueRightsID=$commonfunction->getSettingValue("RightsID");
	//CHECK for Dashboard rights
	$qrySelModulename="Select count(*) as cnt from tw_company_rights_management where company_id='".$company_id."' and rights_id='".$settingValueRightsID."'";
	$moduleCnt = $sign->Select($qrySelModulename);
	if($moduleCnt==0){
		header("Location:pgEmployeeProfile.php");
	}
	 
	$settingValueEmployeeImagePathOther  = $commonfunction->getSettingValue("EmployeeImagePathOther");
	$settingValueEmployeeImage= $commonfunction->getSettingValue("Employee Image");
	$settingValueEmployeeImagePathVerification =$commonfunction->getSettingValue("EmployeeImagePathVerification");
	
	$settingValuePendingStatus =$commonfunction->getSettingValue("Pending Status");
	$settingValueApprovedStatus= $commonfunction->getSettingValue("Approved Status");
	$settingValueCompletedStatus= $commonfunction->getSettingValue("Completed Status");
	$settingValueAwaitingStatus= $commonfunction->getSettingValue("Awaiting Status");
	$settingValueRejectedStatus= $commonfunction->getSettingValue("Rejected status");
	$settingValueNewimage= $commonfunction->getSettingValue("Newimage");
	$settingValueRejectedByCompany= $commonfunction->getSettingValue("RejectedByCompany");
	$settingValueRejectedByAuditor= $commonfunction->getSettingValue("RejectedByAuditor");
	
		
	$settingValuePrimaryEmail =$commonfunction->getSettingValue("Primary Email");
	$settingValuePrimaryContact= $commonfunction->getSettingValue("Primary Contact");
		$settingValueVerifiedStatus= $commonfunction->getSettingValue("Verified Status");
	
	$qry="select employee_name from tw_employee_registration where id='".$employee_id."'";
	$Name= $sign->SelectF($qry,'employee_name');
	
	$qryStatus="select Status from  tw_employee_registration where ID='".$employee_id."'";
	$employeeStatus= $sign->SelectF($qryStatus,'Status');
	$verifyStatus=$commonfunction->getSettingValue("Verified Status");
	$VerifiedImage=$commonfunction->getSettingValue("Verified Image");
	$EmployeeImagePathOther =$commonfunction->getSettingValue("EmployeeImagePathOther");
	
	$qry="select er.employee_name,dm.designation_value from tw_employee_registration er INNER JOIN tw_designation_master dm ON er.employee_designation=dm.id where er.id='".$employee_id."'";
	$Empdata = $sign->FunctionJSON($qry);
	$decodedJSON = json_decode($Empdata);
	$employee_name = $decodedJSON->response[0]->employee_name; 
	$designation_value = $decodedJSON->response[1]->designation_value; 
	
	$query="SELECT cd.CompanyName FROM tw_company_details cd INNER JOIN tw_employee_registration er ON er.company_id=cd.ID where cd.ID='".$company_id."'";
	$Companydata = $sign->FunctionJSON($query);
	$decodedJSON1 = json_decode($Companydata);
	$CompanyName = $decodedJSON1->response[0]->CompanyName; 
	
	$query1="SELECT er.employee_photo,er.employee_role,ec.value FROM tw_employee_registration er INNER JOIN tw_employee_contact ec ON ec.employee_id=er.id WHERE er.id = '".$employee_id."'";
	$Employeedata = $sign->FunctionJSON($query1);
	$decodedJSON1 = json_decode($Employeedata);
	$employee_photo = $decodedJSON1->response[0]->employee_photo; 
	$employee_role = $decodedJSON1->response[1]->employee_role; 
	$value = $decodedJSON1->response[2]->value; 	
	
	
	$queryInprocess="SELECT count(*) as cnt FROM tw_temp tt INNER JOIN tw_po_info pi ON tt.po_id=pi.id WHERE pi.company_id='".$company_id."' and tt.status='".$settingValueAwaitingStatus."'";
	$retqueryInprocess = $sign->Select($queryInprocess);
	
	$DropdownDetailsQry="SELECT DISTINCT pi.supplier_id,cd.CompanyName FROM tw_po_info pi INNER JOIN tw_company_details cd ON pi.supplier_id=cd.ID where pi.company_id='".$company_id."' ";
	$DropdownDetails=$sign->FunctionOption($DropdownDetailsQry,"",'CompanyName',"supplier_id");
	//------------------------------ Progress div starts -------------------------------//
	
	$Cont=0;
	$divCont1=0;
	$divCont2=0;
	$divCont3=0;
	$divCont4=0;
	$divCont5=0;
	$divCont6=0;
	$divCont7=0;
	$divCont8=0;
	
	$queryEmpContactCnt="SELECT count(*) as cnt FROM tw_employee_contact WHERE employee_id='".$employee_id."' and contact_field!='".$settingValuePrimaryEmail."' and contact_field!='".$settingValuePrimaryContact."'";
	$ValueEmpContactCnt = $sign->Select($queryEmpContactCnt);
	if($ValueEmpContactCnt==0){
		$divCont1=0;
	}
	else{
		$divCont1=1;
	}
	
	$queryEmpAddressCnt="SELECT count(*) as cnt FROM tw_employee_address WHERE employee_id='".$employee_id."'";
	$ValueEmpAddressCnt = $sign->Select($queryEmpAddressCnt);
	if($ValueEmpAddressCnt==0){
		$divCont2=0;
	}
	else{
		$divCont2=1;
	}
	
	
	$queryEmpBankDetailsCnt="SELECT count(*) as cnt FROM tw_employee_bankdetails WHERE employee_id='".$employee_id."'";
	$ValueEmpBankDetailsCnt = $sign->Select($queryEmpBankDetailsCnt);
	if($ValueEmpBankDetailsCnt==0){
		$divCont3=0;
	}
	else{
		$divCont3=1;
	}
	
	$queryEmpDocCnt="SELECT count(*) as cnt FROM tw_employee_document WHERE employee_id='".$employee_id."'";
	$ValueEmpDocCnt = $sign->Select($queryEmpDocCnt);
	if($ValueEmpDocCnt==0){
		$divCont4=0;
	}
	else{
		$divCont4=1;
	}
	
	
	$queryEmpDescCnt="SELECT count(*) as cnt FROM tw_employee_registration WHERE id='".$employee_id."' and description!=''";
	$ValueEmpDescCnt = $sign->Select($queryEmpDescCnt);
	if($ValueEmpDescCnt==0){
		$divCont5=0;
	}
	else{
		$divCont5=1;
	}
	
	
	$queryEmpPersonalDetailsCnt="SELECT count(*) as cnt FROM tw_employee_registration WHERE id='".$employee_id."' and (employee_gender!='' or employee_marital_status!='' or date_of_birth!='')";
	$ValueEmpPersonalDetailsCnt = $sign->Select($queryEmpPersonalDetailsCnt);
	if($ValueEmpPersonalDetailsCnt==0){
		$divCont6=0;
	}
	else{
		$divCont6=1;
	}
	
	$queryEmpPrimaryContactCnt="SELECT count(*) as cnt FROM tw_employee_contact WHERE id='".$employee_id."' and contact_field='".$settingValuePrimaryEmail."' or contact_field='".$settingValuePrimaryContact."' and status='".$settingValueVerifiedStatus."'";
	$ValueEmpPrimaryContactCnt = $sign->Select($queryEmpPrimaryContactCnt);
	if($ValueEmpPrimaryContactCnt==0){
		$divCont7=0;
	}
	else{
		$divCont7=1;
	}
	
	$queryEmpPhotoCnt="SELECT count(*) as cnt FROM tw_employee_registration WHERE id='".$employee_id."' and employee_photo!=''";
	$ValueEmpPhotoCnt = $sign->Select($queryEmpPhotoCnt);
	if($ValueEmpPhotoCnt==0){
		$divCont8=0;
	}           
	else{       
		$divCont8=1;
	}
	
	
	$Progressive = $divCont1+$divCont2+$divCont3+$divCont4+$divCont5+$divCont6+$divCont7+$divCont8;
	//echo $Progressive."-----------";
	
	$percentage=($Progressive/8)*100;

	
	//------------------------------ Progressive div starts ---------------------------------//

	if($percentage>=0 && $percentage<=24.99){	
		
			$progressstatus = "progress-bar bg-danger";
		}
		else if($percentage>=25 && $percentage<=49.99){
			$progressstatus = "progress-bar bg-warning";
		}
		else if($percentage>=50 && $percentage<=99.99){
			
			$progressstatus = "progress-bar bg-primary";
		}
		else if($percentage>=100){
			$percentage=100.00;
			$progressstatus = "progress-bar bg-success";
		}
		else{
			$percentage=0.00;
			$progressstatus = "progress-bar bg-danger";

		}

	//------------------------------ Progressive div ends ---------------------------------//
	
?>
<!DOCTYPE html>
<html lang="en">

<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Trace-Waste | Employee Dashboard </title>
<!-- plugins:css -->
<link rel="stylesheet" href="../assets/vendors/feather/feather.css">
<link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
<link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
<!-- endinject -->
<!-- Plugin css for this page -->
<link rel="stylesheet" href="../assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css">
<link rel="stylesheet" type="text/css" href="../assets/js/select.dataTables.min.css">
<!-- End plugin css for this page -->
<!-- inject:css -->
<link rel="stylesheet" href="../assets/css/vertical-layout-light/style.css">
<link rel="stylesheet" href="../assets/css/custom/style.css">
<!-- endinject -->
<link rel="shortcut icon" href="../assets/images/favicon.png" />


</head>
<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html navTopHeader-->
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
		
		 <!-- First Row starts--> 
		 <div class="row">
            <div class="col-lg-9 col-md-9 col-sm-9 com-xs-9 col-9  grid-margin">
			<div class="card">
					<div class="card-body">
              <div class="row">
                <div class="col-lg-9 col-md-9 col-sm-9 com-xs-9 col-9 dashboard-username">
					<img style="border-radius: 15px;" src="<?php if($employee_photo==""){echo $settingValueEmployeeImagePathOther.$settingValueEmployeeImage; }else{ echo $settingValueEmployeeImagePathVerification.$value."/".$employee_photo;}?>" class="img-lg  mb-3" />
					<h3 class="font-weight-bold">Welcome <?php echo $Name;  if ($employeeStatus==$verifyStatus) { echo " <img src='".$EmployeeImagePathOther ."".$VerifiedImage."'/>";} ?></h3><br>
					<h6 class="font-weight-normal mb-0"><?php echo $designation_value; ?><br> <?php echo $CompanyName; ?> <?php if($retqueryInprocess>0){ ?>You have<span class="text-primary"> <a href="pgEprPo.php?type=Approved" class="text-success"><br><?php echo $retqueryInprocess; ?>  documents for approval</a><img class="New-img" src="<?php echo $settingValueEmployeeImagePathOther.$settingValueNewimage ?>"  /></span><?php } ?></h6>
                </div>
				<div class="col-lg-3 col-md-3 col-sm-3 com-xs-3 col-3">
				 <div class="dropdown">
					<select id="txtSupplierName" class="form-control"  onchange="VendorSel()" >
						<option value="0">All Vendor</option>
						<?php echo $DropdownDetails; ?>
					</select>
                  </div>
				</div><br>
		
              </div>
			</div>
			</div>
			</div>
		<!------------------------Progressive Div Starts------------------------------------>
			 <div class="col-lg-3 col-md-3 col-sm-3 com-xs-3 col-3 grid-margin">
				<div class="card">
					<div class="card-body">
					  <?php
						echo $progressdiv = "<div class='template-demo'>
											<div> 
												<h2 ><center>".round($percentage)."%</center></h2>
											</div>
										 <div class='progress progress-lg mt-2 '>
											  <div class='".$progressstatus."' role='progressbar' style='width:".$percentage."%' aria-valuenow='per' aria-valuemin= '".$percentage."' aria-valuemax='100'></div>
										  </div><br>
									</div>"
						?>	
					</div>			
				</div>			
			</div>			
		<!------------------------Progressive Div Ends-------------------------------------->	

			
		
          
		 
			
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        <?php
			include_once("footer.php");
		?>
        <!-- partial -->
     
      <!-- main-panel ends -->
    </div>   
    <!-- page-body-wrapper ends -->
</div>

			
</div>
</div>
<!-- container-scroller -->

<!-- plugins:js -->
<script src="../assets/vendors/js/vendor.bundle.base.js"></script>
<!-- endinject -->
<!-- Plugin js for this page -->
<script src="../assets/vendors/chart.js/Chart.min.js"></script>
<script src="../assets/vendors/datatables.net/jquery.dataTables.js"></script>
<script src="../assets/vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
<script src="../assets/js/dataTables.select.min.js"></script>

<!-- End plugin js for this page -->
<!-- inject:js -->
<script src="../assets/js/off-canvas.js"></script>
<script src="../assets/js/hoverable-collapse.js"></script>
<script src="../assets/js/template.js"></script>
<script src="../assets/js/settings.js"></script>
<script src="../assets/js/todolist.js"></script>
<!-- endinject -->
<!-- Custom js for this page-->
<script src="../assets/js/dashboard.js"></script>
<script src="../assets/js/Chart.roundedBarCharts.js"></script>
<script src="../assets/js/chart.js"></script>

<!-- End custom js for this page-->
<script>
$(document).ready(function(){
	
	getMonthlyCollectionData();
	
});

function getMonthlyCollectionData(){
	var valemp = "<?php echo $employee_id;?>";
	$.ajax({
			type:"POST",
			url:"apigetEmpProfileReport.php",
			data:{val:valemp},
			success:function(response){
				console.log(response);
				var json = JSON.parse(response);
                var arrdata = [];
				json.forEach((item) => {
					arrdata.push(item.sum);
				});  
				var ctx = document.getElementById("epr-chart").getContext("2d");
				const datasetValue = []
				for (i = 0; i < arrdata.length; i++)
				{
					datasetValue[i] = {
						label: "Material Data",
						backgroundColor: "#57c7d4",
						data: arrdata[i]
					}
				}
				if(myBarChartYearly!=""){
					myBarChartYearly.destroy();
				}
				var xValues = ["April "+year,"May "+year,"June "+year,"July "+year,"Aug "+year,"Sep "+year,"Oct "+year,"Nov "+year,"Dec "+year,"Jan "+yearadd,"Feb "+yearadd,"Mar "+yearadd];
				myBarChartYearly = new Chart(ctx, {
				  type: 'bar',
				  data: {labels: xValues, datasets: datasetValue},
				  options: {
					barValueSpacing: 20,
					scales: {
					  yAxes: [{
						ticks: {
						  min: 0,
						}
					  }]
					}
				  }
				});
			}
		});
} 
</script>
</body>
</html>

