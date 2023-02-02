<?php
session_start();
if (!isset($_SESSION["employee_id"])) {
	header("Location:pgEmployeeLogin.php");
}
include_once "function.php";
include_once "commonFunctions.php";
$sign = new Signup();
$commonfunction = new Common();

date_default_timezone_set("Asia/Kolkata");
$cur_date = date("Y-m-d h:i:sa");
$ip_address = $commonfunction->getIPAddress();
$requesttype = $_REQUEST["type"];
$requestid = $_REQUEST["id"];
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
if ($requesttype == "edit") {
	$qry = "select case_id,createdOn,scheme_doc,status,citizen_guid,citizen_name,dob,gender,mobile,age,family_guid,family_name,hd_id,Location from tw_ragpicker_information where id='" . $requestid . "' order by id Desc";
	$retVal = $sign->FunctionJSON($qry);
	$decodedJSON = json_decode($retVal);
	$case_id = $decodedJSON->response[0]->case_id;
	$createdOn = $decodedJSON->response[1]->createdOn;
	$scheme_doc = $decodedJSON->response[2]->scheme_doc;
	$status	 = $decodedJSON->response[3]->status;
	$citizen_guid = $decodedJSON->response[4]->citizen_guid;
	$citizen_name = $decodedJSON->response[5]->citizen_name;
	$dob = $decodedJSON->response[6]->dob;
	$gender = $decodedJSON->response[7]->gender;
	$mobile = $decodedJSON->response[8]->mobile;
	$age = $decodedJSON->response[9]->age;
	$family_guid = $decodedJSON->response[10]->family_guid;
	$family_name = $decodedJSON->response[11]->family_name;
	$hd_id = $decodedJSON->response[12]->hd_id;
	$Location = $decodedJSON->response[13]->Location;
}
$docQry = "SELECT id,document_type FROM tw_ragpicker_documents where visibility='true' ";
$doc = $sign->FunctionOption($docQry, $scheme_doc, 'document_type', "id");

/*$docQry1="SELECT id,document_type FROM tw_ragpicker_documents where visibility='true' ";
$retVal = $sign->FunctionJSON($docQry1);
$qry1="Select count(*) as cnt from tw_ragpicker_documents where visibility='true'";
$retVal1 = $sign->Select($qry1);
$decodedJSON2 = json_decode($retVal);
$count = 0;
$i = 1;
$x=$retVal1;
$table="";{
while($x>=$i)
	$id = $decodedJSON2->response[$count]->id;
	$count=$count+1;
	$document_type = $decodedJSON2->response[$count]->document_type;
	$count=$count+1;
}*/

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Trace Waste | Add Ragpicker</title>
	<!-- plugins:css -->
	<link rel="stylesheet" href="../assets/vendors/feather/feather.css">
	<link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
	<link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
	<!-- endinject -->
	<!-- inject:css -->
	<link rel="stylesheet" href="../assets/css/vertical-layout-light/style.css">
	<link rel="stylesheet" href="../assets/css/custom/tw-switch.css">
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
					<div class="row">
						<div class="col-md-12 grid-margin stretch-card">
							<div class="card">
								<div class="card-body">
									<h4 class="card-title">Add RagPicker</h4>
									<div class="forms-sample">
										<div class="row">
											<div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12 ">
												<label for="txtCitizenGUID">Citizen GUID <code>*</code></label>
												<input type="text" class="form-control" id="txtCitizenGUID" maxlength="30" value="<?php echo $citizen_guid; ?>" placeholder="Citizen GUID">
											</div>
											<div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12 ">
												<label for="txtCitizenName">Citizen Name <code>*</code></label>
												<input type="text" class="form-control" id="txtCitizenName" maxlength="30" value="<?php echo $citizen_name; ?>" placeholder="Citizen Name">
											</div>
										</div>
										<div class="row">
											<div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12 ">
												<label for="txtGender">Gender <code>*</code></label>
												<select name="duplicate" id="txtGender" class="form-control">
													<option value="Male" <?php if ($gender == "Male") {
																				echo "selected";
																			} ?>>Male</option>
													<option value="Female" <?php if ($gender == "Female") {
																				echo "selected";
																			} ?>>Female</option>
												</select>
											</div>
											<div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12">
												<label for="txtMobile">Mobile <code>*</code></label>
												<input type="text" class="form-control" value="<?php echo $mobile; ?>" id="txtMobile" maxlength="10" placeholder="Mobile Number" />
											</div>
										</div>
										<div class="row">
											<div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12 ">
												<label for="txtAge">Age<code>*</code></label>
												<input type="text" class="form-control" id="txtAge" maxlength="30" placeholder="Age" value="<?php echo $age; ?>">
											</div>
											<div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12">
												<label for="txtDOB">Date Of Birth</label>
												<input type="Date" class="form-control" value="<?php echo $dob; ?>" id="txtDOB" placeholder="Date Of Birth" />
											</div>
										</div>
										<div class="row">
											<div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12 ">
												<label for="txtFamilyName">Family Name <code>*</code></label>
												<input type="text" class="form-control" id="txtFamilyName" maxlength="30" placeholder="Family Name" value="<?php echo $family_name; ?>">
											</div>
											<div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12 ">
												<label for="txtHDID">HD ID <code>*</code></label>
												<input type="text" class="form-control" id="txtHDID" maxlength="30" placeholder="HD ID" value="<?php echo $hd_id; ?>">
											</div>
											<div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12 ">
												<label for="txtlocation">Location <code>*</code></label>
												<input type="text" class="form-control" id="txtlocation" maxlength="30" placeholder="Location" value="<?php echo $Location; ?>">
											</div>
											<div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12 ">
												<label for="FamilyGUID">Family GUID <code>*</code></label>
												<input type="text" class="form-control" id="FamilyGUID" maxlength="30" placeholder="Family GUID" value="<?php echo $family_guid; ?>">
											</div>
										</div>
									</div><br>

									<table id="tbl" class="table">
										<thead>
											<tr>
												<th>Select Scheme Documents :</th>
												<th>Created ON:</th>
												<th>CaseID:</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$docQry1 = "SELECT id,document_type FROM tw_ragpicker_documents where visibility='true' ";
											$retVal = $sign->FunctionJSON($docQry1);
											$qry1 = "Select count(*) as cnt from tw_ragpicker_documents where visibility='true'";
											$retVal1 = $sign->Select($qry1);
											$decodedJSON2 = json_decode($retVal);
											$count = 0;
											$i = 1;
											$x = $retVal1;
											$table = "";
											while ($x >= $i) {
												$id = $decodedJSON2->response[$count]->id;
												$count = $count + 1;
												$document_type = $decodedJSON2->response[$count]->document_type;
												$count = $count + 1;
											?>
												<tr>
													<td><?php echo $document_type ?> <input type="checkbox" id="txtdoc<?PHP echo $id; ?>" onclick="myFunction('<?PHP echo $id; ?>')"></label></td>
													<td><label><input type="date" id="txtCreated<?PHP echo $id; ?>" style="display:none" maxlength="30" placeholder="CreatedOn" value="<?php echo date("Y-m-d", strtotime($cur_date)) ?>"></label></td>
													<td><label><input type="text" id="txtcaseid<?PHP echo $id; ?>" style="display:none" value="<?php echo $case_id; ?>" placeholder="Case ID"></label></td>
												<?php
												$i = $i + 1;
											}  ?><tr>

										</tbody>
									</table>



									<button type="button" class="btn btn-success" id="btnAddrecord" onclick="adddata()"><?php if ($requesttype == "add") { ?>Add<?php } else { ?>Update<?php } ?></button>
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

	<script src="../assets/js/custom/sweetAlert.js"></script>
	<script src="../assets/js/custom/sweetalert2.min.js"></script>
	<script src="../assets/css/jquery/jquery.min.js"></script>
	<script src="../assets/js/custom/twCommonValidation.js"></script>
	<script>
		var schemeDocuments = [];

		function myFunction(ID) {
			var checkBox = document.getElementById("txtdoc" + ID);
			var Created = document.getElementById("txtCreated" + ID);
			var caseid = document.getElementById("txtcaseid" + ID);
			if (checkBox.checked == true) {
				schemeDocuments.push(ID);
				Created.style.display = "block";
				caseid.style.display = "block";
			} else {
				schemeDocuments = schemeDocuments.filter(function(e) {
					return e !== ID
				})
				Created.style.display = "none";
				caseid.style.display = "none";
			}
		}
		$('input').blur(function() {
			var valplaceholder = $(this).attr("placeholder");
			var vallblid = $(this).attr("id");
			var valid = "err" + vallblid;
			var valtext = "Please enter " + valplaceholder;
			var check = $(this).val().trim();
			var checkElementExists = document.getElementById(valid);
			if (check == '') {

				if (!checkElementExists) {
					$(this).parent().addClass('has-danger');
					$(this).after('<label id="' + valid + '" class="error mt-2 text-danger">' + valtext + '</label>');
				}

			} else {
				$(this).parent().removeClass('has-danger');
				if (checkElementExists) {
					checkElementExists.remove();
				}
			}
		});

		function setErrorOnBlur(inputComponent) {
			var valplaceholder = $("#" + inputComponent).attr("placeholder");
			var vallblid = $("#" + inputComponent).attr("id");
			var valid = "err" + vallblid;
			var valtext = "Please enter " + valplaceholder;
			var check = $("#" + inputComponent).val().trim();
			var checkElementExists = document.getElementById(valid);
			if (check == '') {

				if (!checkElementExists) {
					$("#" + inputComponent).parent().addClass('has-danger');
					$("#" + inputComponent).after('<label id="' + valid + '" class="error mt-2 text-danger">' + valtext + '</label>');
					$("#" + inputComponent).focus();
				}

			} else {
				$("#" + inputComponent).parent().removeClass('has-danger');
				if (checkElementExists) {
					checkElementExists.remove();
				}
			}
		}

		function setError(inputComponent) {
			var valplaceholder = $(inputComponent).attr("placeholder");
			var vallblid = $(inputComponent).attr("id");
			var valid = "errSet" + vallblid;
			var valtext = "Please enter valid " + valplaceholder;
			var checkElementExists = document.getElementById(valid);
			if (!checkElementExists) {
				$("#" + vallblid).parent().addClass('has-danger');
				$("#" + vallblid).after('<label id="' + valid + '" class="error mt-2 text-danger">' + valtext + '</label>');
			}

		}

		function removeError(inputComponent) {
			var vallblid = $(inputComponent).attr("id");
			$("#" + vallblid).parent().removeClass('has-danger');
			const element = document.getElementById("errSet" + vallblid);
			if (element) {
				element.remove();
			}
		}
		$("#txtMobile").blur(function() {
			removeError(txtMobile);
			if ($("#txtMobile").val() != "") {
				if (!isMobile($("#txtMobile").val())) {
					setError(txtMobile);
				} else {
					removeError(txtMobile);
				}
			}
		});

		function adddata() {

			/*if(!validateBlank($("#txtCaseId").val())){
			setErrorOnBlur("txtCaseId");
		}
		else if(!validateBlank($("#txtCreatedOn").val())){
		setErrorOnBlur("txtCreatedOn");
		} 
		else if(!validateBlank($("#txtSchemeDoc").val())){
		setErrorOnBlur("txtSchemeDoc");
		}
		 elseif(!validateBlank($("#txtStatus").val())){
		setErrorOnBlur("txtStatus");
		}*/
			if (!validateBlank($("#txtCitizenGUID").val())) {
				setErrorOnBlur("txtCitizenGUID");
			} else if (!validateBlank($("#txtCitizenName").val())) {
				setErrorOnBlur("txtCitizenName");
			} else if (!validateBlank($("#txtGender").val())) {
				setErrorOnBlur("txtGender");
			} else if (!validateBlank($("#txtAge").val())) {
				setErrorOnBlur("txtAge");
			} else if (!validateBlank($("#txtDOB").val())) {
				setErrorOnBlur("txtDOB");
			} else if (!validateBlank($("#FamilyGUID").val())) {
				setErrorOnBlur("FamilyGUID");
			} else if (!validateBlank($("#txtFamilyName").val())) {
				setErrorOnBlur("txtFamilyName");
			} else if (!validateBlank($("#txtHDID").val())) {
				setErrorOnBlur("txtHDID");
			} else if (!validateBlank($("#txtlocation").val())) {
				setErrorOnBlur("txtlocation");
			} else {
				disableButton('#btnAddrecord', 'Processing...');
				var valcreated_by = "<?php echo $employee_id; ?>";
				var valcreated_on = "<?php echo $cur_date; ?>";
				var valcreated_ip = "<?php echo $ip_address; ?>";
				var valrequesttype = "<?php echo $requesttype; ?>";
				var queries = [];
				if (valrequesttype == "add") {

					for (const schemeDocument of schemeDocuments) {
						var valquery = "insert into tw_ragpicker_information(case_id,createdOn,scheme_doc,status,citizen_guid,citizen_name,dob,gender,mobile,age,family_guid,family_name,hd_id,Location,created_by,created_on,created_ip)values('" + $("#txtcaseid" + schemeDocument).val() + "','" + $("#txtCreated" + schemeDocument).val() + "','" + schemeDocument + "','" + $("#txtStatus").val() + "','" + $("#txtCitizenGUID").val() + "','" + $("#txtCitizenName").val() + "','" + $("#txtDOB").val() + "','" + $("#txtGender").val() + "','" + $("#txtMobile").val() + "','" + $("#txtAge").val() + "','" + $("#FamilyGUID").val() + "','" + $("#txtFamilyName").val() + "','" + $("#txtHDID").val() + "','" + $("#txtlocation").val() + "','" + valcreated_by + "','" + valcreated_on + "','" + valcreated_ip + "')";
						var valquerycount = "select count(*) as cnt from tw_ragpicker_information where case_id='" + $("#txtcaseid" + schemeDocument).val() + "'";
						queries.push({
							valquery: valquery,
							valquerycount: valquerycount
						});
						// console.log(queries); 
					}

					if (queries.length === 0) {
						queries.push({
							valquery: "insert into tw_ragpicker_information(status,citizen_guid,citizen_name,dob,gender,mobile,age,family_guid,family_name,hd_id,Location,created_by,created_on,created_ip)values('" + $("#txtStatus").val() + "','" + $("#txtCitizenGUID").val() + "','" + $("#txtCitizenName").val() + "','" + $("#txtDOB").val() + "','" + $("#txtGender").val() + "','" + $("#txtMobile").val() + "','" + $("#txtAge").val() + "','" + $("#FamilyGUID").val() + "','" + $("#txtFamilyName").val() + "','" + $("#txtHDID").val() + "','" + $("#txtlocation").val() + "','" + valcreated_by + "','" + valcreated_on + "','" + valcreated_ip + "')",
							valquerycount: "select count(*) as cnt from tw_ragpicker_information where mobile='" + $("#txtMobile").val() + "'"
						})
					}

					// var valquery = "insert into tw_ragpicker_information(case_id,createdOn,scheme_doc,status,citizen_guid,citizen_name,dob,gender,mobile,age,family_guid,family_name,hd_id,Location,created_by,created_on,created_ip)values('" + $("#txtcaseid").val() + "','" + $("#txtCreated").val() + "','" + $("#txtSchemeDoc").val() + "','" + $("#txtStatus").val() + "','" + $("#txtCitizenGUID").val() + "','" + $("#txtCitizenName").val() + "','" + $("#txtDOB").val() + "','" + $("#txtGender").val() + "','" + $("#txtMobile").val() + "','" + $("#txtAge").val() + "','" + $("#FamilyGUID").val() + "','" + $("#txtFamilyName").val() + "','" + $("#txtHDID").val() + "','" + $("#txtlocation").val() + "','" + valcreated_by + "','" + valcreated_on + "','" + valcreated_ip + "')";
					// var valquerycount = "select count(*) as cnt from tw_ragpicker_information where case_id='" + $("#txtcaseid").val() + "'";
				} else {
					var valrequestid = "<?php echo $requestid; ?>";

					for (const schemeDocument of schemeDocuments) {
						var valquery = "update tw_ragpicker_information set case_id = '" + $("#txtcaseid" + schemeDocument).val() + "', createdOn = '" + $("#txtCreated" + schemeDocument).val() + "', scheme_doc = '" + schemeDocument + "', status = '" + $("#txtStatus").val() + "',citizen_guid = '" + $("#txtCitizenGUID").val() + "', citizen_name = '" + $("#txtCitizenName").val() + "',dob = '" + $("#txtDOB").val() + "',gender = '" + $("#txtGender").val() + "',mobile = '" + $("#txtMobile").val() + "',age = '" + $("#txtAge").val() + "',family_guid = '" + $("#FamilyGUID").val() + "',family_name = '" + $("#txtFamilyName").val() + "',hd_id = '" + $("#txtHDID").val() + "',Location = '" + $("#txtlocation").val() + "',modified_by='" + valcreated_by + "',modified_on='" + valcreated_on + "',modified_ip='" + valcreated_ip + "' where id = '" + valrequestid + "' ";
						var valquerycount = "select count(*) as cnt from tw_ragpicker_information where case_id='" + $("#txtcaseid").val() + "' and ID!='" + valrequestid + "'";
						queries.push({
							valquery: valquery,
							valquerycount: valquerycount
						});
						// console.log(queries); 
					}

					if (queries.length === 0) {
						queries.push({
							valquery: "update tw_ragpicker_information set status = '" + $("#txtStatus").val() + "',citizen_guid = '" + $("#txtCitizenGUID").val() + "', citizen_name = '" + $("#txtCitizenName").val() + "',dob = '" + $("#txtDOB").val() + "',gender = '" + $("#txtGender").val() + "',mobile = '" + $("#txtMobile").val() + "',age = '" + $("#txtAge").val() + "',family_guid = '" + $("#FamilyGUID").val() + "',family_name = '" + $("#txtFamilyName").val() + "',hd_id = '" + $("#txtHDID").val() + "',Location = '" + $("#txtlocation").val() + "',modified_by='" + valcreated_by + "',modified_on='" + valcreated_on + "',modified_ip='" + valcreated_ip + "' where id = '" + valrequestid + "' ",
							valquerycount: "select count(*) as cnt from tw_ragpicker_information where case_id='" + $("#txtcaseid").val() + "' and ID!='" + valrequestid + "'"
						})
					}

					// var valquery = "update tw_ragpicker_information set case_id = '" + $("#txtcaseid" + schemeDocument).val() + "', createdOn = '" + $("#txtCreated" + schemeDocument).val() + "', scheme_doc = '" + schemeDocument + "', status = '" + $("#txtStatus").val() + "',citizen_guid = '" + $("#txtCitizenGUID").val() + "', citizen_name = '" + $("#txtCitizenName").val() + "',dob = '" + $("#txtDOB").val() + "',gender = '" + $("#txtGender").val() + "',mobile = '" + $("#txtMobile").val() + "',age = '" + $("#txtAge").val() + "',family_guid = '" + $("#FamilyGUID").val() + "',family_name = '" + $("#txtFamilyName").val() + "',hd_id = '" + $("#txtHDID").val() + "',Location = '" + $("#txtlocation").val() + "',modified_by='" + valcreated_by + "',modified_on='" + valcreated_on + "',modified_ip='" + valcreated_ip + "' where id = '" + valrequestid + "' ";
					// var valquerycount = "select count(*) as cnt from tw_ragpicker_information where case_id='" + $("#txtcaseid").val() + "' and ID!='" + valrequestid + "'";
				}
				var req = {
					queries: queries
				}
				// console.log(JSON.stringify(req))
				$.ajax({
					type: "POST",
					contentType: "application/json",
					url: "apiCommonQueryMultiple.php",
					data: JSON.stringify(req),
					success: function(response) {
						console.log(response);
						if (valrequesttype == "add") {
							enableButton('#btnAddrecord', 'Add Record');
						} else {
							enableButton('#btnAddrecord', 'Update Record');
						}
						if ($.trim(response) == "Success") {
							if (valrequesttype == "add") {
								$('#btnAddrecord').html('Add Record');
								showAlertRedirect("Success", "Record Added Successfully", "success", "pgRagPickerInformation.php");
							} else {
								$('#btnAddrecord').html('Update Record');
								showAlertRedirect("Success", "Record Updated Successfully", "success", "pgRagPickerInformation.php");
							}
						} else if ($.trim(response) == "Exist") {
							showAlert("Warning", "Record already exist", "warning");
						} else {
							showAlert("Error", "Something Went Wrong. Please Try After Sometime.", "error");
						}
					}
				});
			}
		}
	</script>
</body>

</html>