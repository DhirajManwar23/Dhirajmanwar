<?php 
session_start();
	if(!isset($_SESSION["username"])){
		header("Location:pgLogin.php");
	}
$Masterslist = array("Address Type Master","Bank Account Proof Type Master","Bank Account Status Master",
"Bank Account Type Master","Category Master","City Master","Collection Point Type Master","Company Type Master","Compliance Document Type Master","Contact Feild Master","Country Master","Department Master",
"Designation Master","Document Purpose Master","Document Type Master","Document Verification Status Master","EPR Category Master","EPR Product Master",
"Employee Type Master","Material Type Master","Module Master","Panel Master","Payment Type Master","Printable Document Master","RagPicker Documents Master","Reason Master","Role Master","Segregation Waste Type Master","State Master","Sub Category Master",
"Sub Module Master","Tax Master","Unit Of Measurement","User Type Master","Verification Status Master","Vehicle Type Master","Ward Master","Waste Type Master");

$Tablename = array("tw_address_type_master","tw_bank_account_proof_type_master","tw_bank_account_status_master",
"tw_bank_account_type_master","tw_category_master","tw_city_master","tw_collection_point_type_master", "tw_company_type_master","tw_compliance_type_master","tw_contact_field_master","tw_country_master",
"tw_department_master", "tw_designation_master","tw_document_purpose_master","tw_document_type_master",
"tw_document_verification_status_master","tw_epr_category_master","tw_epr_product_master","tw_employee_type_master","tw_product_type_master","tw_module_master","tw_panel_master","tw_payment_type_master","tw_printable_document_master","tw_ragpicker_documents",
"tw_rejected_reason_master","tw_role_master","tw_segregation_waste_type_master","tw_state_master"," tw_subcategory_master","tw_submodule_master","tw_tax_master"," tw_unit_of_measurement","tw_user_type_master",
"tw_verification_status_master","tw_vehicle_type_master","tw_ward_master","tw_waste_type_master");

$Mastersref = array("pgAddressTypeMaster.php","pgBankAccountProofTypeMaster.php","pgBankAccountStatusMaster.php",
"pgBankAccountTypeMaster.php","pgCategoryMaster.php","pgCityMaster.php","pgCollectionPointType.php","pgCompanyTypeMaster.php","pgComplianceDocumentTypeMaster.php","pgContactFieldMaster.php","pgCountryMaster.php",
"pgDepartmentMaster.php","pgDesignationMaster.php","pgDocumentPurposeMaster.php","pgDocumentTypeMaster.php",
"pgDocumentVerificationStatusMaster.php","pgEPRCategoryMaster.php","pgEPRProductMaster.php","pgEmployeeTypeMaster.php","pgProductTypeMaster.php","pgModuleMaster.php","pgPanelMaster.php",
"pgPaymentTypeMaster.php",
"pgPrintableDocumentMaster.php","pgRagpickerDocumentsMaster.php","pgReasonMaster.php","pgRoleMaster.php","pgSegregationWasteTypeMaster.php","pgStateMaster.php","pgSubCategory.php","pgSubModuleMaster.php","pgTaxMaster.php",
"pgUnitOfMeasurement.php","pgUserTypeMaster.php","pgVerificationStatusMaster.php","pgVehiclesTypeMaster.php","pgWardMaster.php","pgWasteTypeMaster.php");

date_default_timezone_set("Asia/Kolkata");


require "function.php";
$dbobj=new Signup();

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Trace Waste | Master List</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../assets/vendors/feather/feather.css">
  <link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- inject:css -->
  <link rel="stylesheet" href="../assets/css/vertical-layout-light/style.css">
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
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Master List</h4>
                  
                   
                    <div class="table-responsive pt-3">
						<table class="table table-bordered">
						  <thead>
							<tr>
							  <th>#</th>
							  <th>
								Master
							  </th>
							  <th>
								Number of Entries
							  </th>
							  <th>
								Last Created Date
							  </th>
							  <th>
								Last Modified Date
							  </th>
							</tr>
						  </thead>
						  <tbody>
						  <?php
							$vcnt=0;
							$sn=1;
							foreach($Masterslist as $values) {
								
								$qry = "select count(*) as cnt,max(created_on) as created,max(modified_on) as modified from ".$Tablename[$vcnt];
								$retVal = $dbobj->FunctionJSON($qry);
								$decodedJSON = json_decode($retVal);
								
								$cnt = $decodedJSON->response[0]->cnt;
								$created_date = $decodedJSON->response[1]->created;
								$modified_date = $decodedJSON->response[2]->modified;
								
								$dbdate=date_create($created_date);
								$todaydate=date_create(date ('Y-m-d H:i:s'));
								$diff=date_diff($dbdate,$todaydate);
								$cd_diff = $diff->format("%a");
								 
								$dbdate=date_create($modified_date);
								$todaydate=date_create(date ('Y-m-d H:i:s'));
								$diff=date_diff($dbdate,$todaydate);
								$md_diff = $diff->format("%a"); 
						
								$created_date = date("d-m-Y H:i:s",strtotime($created_date));
								
								$modified_date = date("d-m-Y H:i:s",strtotime($modified_date));
								
								if($created_date=="01-01-1970 05:30:00")
								{
									$created_date = "---";
								}
								if($modified_date=="01-01-1970 05:30:00")
								{
									$modified_date = "---";
								}
								
								$cd_flag = "";
								if ($cd_diff<=3 && $cnt>0)
								{
									$cd_flag = "<i class='ti-control-record text-danger'>";
								}
								else
								{
									$cd_flag = "";
								}
								
								$md_flag = "";
								if ($md_diff<=3 && $cnt>0)
								{
									$md_flag = "<i class='ti-control-record text-success'>";
								}
								else
								{
									$md_flag = "";
								}
				
								echo "<tr><td>".$sn++."</td>
								<td><a href='".$Mastersref[$vcnt]."'>".$values."</a>".$cd_flag." ".$md_flag."</td>
								<td>".$cnt."</td>
								<td>".$created_date."</td>
								<td>".$modified_date."</td>
								</tr>";
									$vcnt=$vcnt+1;
							} 
							?>
						  </tbody>
						</table>
                  </div>
                </div>
              </div>
            </div>
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
  <script src="../assets/css/jquery/jquery.min.js"></script>
   	
</body>

</html>