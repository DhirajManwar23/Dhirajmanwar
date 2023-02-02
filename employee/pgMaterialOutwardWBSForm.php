<?php 
session_start();
	if(!isset($_SESSION["employee_id"])){
		header("Location:pgEmployeeLogIn.php");
	}
// Include class definition
require "function.php";
include("commonFunctions.php");
$sign=new Signup();
$commonfunction=new Common();
$requesttype = $_REQUEST["type"];
$id = $_REQUEST["id"];
$outwardid = $_REQUEST["outwardid"];
$ip_address= $commonfunction->getIPAddress();
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d H:i");
$varCD=str_replace(" ","T",$cur_date);



/* $gross_weight_date_time=date("Y-m-d H:i");
$varGD=str_replace(" ","T",$gross_weight_date_time); */
$created_by=$_SESSION["employee_id"];

$qryWBS="SELECT COUNT(*) as cnt from tw_material_outward_documents WHERE type='WBS' and outward_id='".$outwardid."' ORDER BY outward_id ASC";
$retValWBS = $sign->SelectF($qryWBS,"cnt");

$qry1WBS="SELECT COUNT(*) as cnt from tw_material_outward_wbs WHERE outward_id='".$outwardid."' ORDER BY outward_id ASC";
$retVal1WBS = $sign->SelectF($qry1WBS,"cnt");
if($retValWBS>0 || $retVal1WBS>0){
	$disabledWBS="disabled";
}
else{
	$disabledWBS="";
}

	
$carrier_no	 = "";
$party_name = ""; 
$material_name = ""; 
$gross_weight = "";
$gross_weight_date_time = "";
$tare_weight = "";
$tare_weight_date_time = "";
$net_weight = "";
$amount_received = "";
$driver_name = "";
$payment_mode = "";
$vehicle_number = "";

if($requesttype=="edit"){
	$qry = "SELECT carrier_no,party_name,material_name,gross_weight,gross_weight_date_time,tare_weight,tare_weight_date_time,net_weight,amount_received,driver_name,payment_mode from tw_material_outward_wbs WHERE id = '".$id."' ";
	$retVal = $sign->FunctionJSON($qry);
	$decodedJSON = json_decode($retVal);
	
	$carrier_no= $decodedJSON->response[0]->carrier_no;
	$party_name = $decodedJSON->response[1]->party_name; 
	$material_name = $decodedJSON->response[2]->material_name; 
	$gross_weight = $decodedJSON->response[3]->gross_weight;
	$gross_weight_date_time = $decodedJSON->response[4]->gross_weight_date_time;
	$tare_weight = $decodedJSON->response[5]->tare_weight;
	$tare_weight_date_time = $decodedJSON->response[6]->tare_weight_date_time;
	$net_weight = $decodedJSON->response[7]->net_weight;
	$amount_received = $decodedJSON->response[8]->amount_received;
	$driver_name = $decodedJSON->response[9]->driver_name;
	$payment_mode = $decodedJSON->response[10]->payment_mode; 


	
}
//$gross_weight_date_time=date("Y-m-d H:i");
$varGD=str_replace(" ","T",$gross_weight_date_time);
$varTD=str_replace(" ","T",$tare_weight_date_time);

$qry1 = "SELECT vm.vehicle_number,mo.po_id from tw_vehicle_details_master vm INNER JOIN tw_material_outward mo ON mo.vehicle_id=vm.id WHERE mo.id = '".$outwardid."' ";
$vehicle_number = $sign->FunctionJSON($qry1);
$decodedJSON1 = json_decode($vehicle_number);
$vehicle_number = $decodedJSON1->response[0]->vehicle_number; 
$po_id = $decodedJSON1->response[1]->po_id; 
	
$qry4="SELECT cd.CompanyName FROM tw_company_details cd INNER join tw_material_outward mo on mo.customer_id=cd.id where mo.id='".$outwardid."' GROUP by cd.CompanyName";
$retVal4 = $sign->SelectF($qry4,"CompanyName");


$qrypaymenttype = "select id,payment_type_value from tw_payment_type_master where visibility='true' Order by priority,payment_type_value";
$retValpaymentmode = $sign->FunctionOption($qrypaymenttype,$payment_mode,'payment_type_value','id');

$dateqry="SELECT po_date FROM `tw_temp_po_info` where id='".$po_id."'";
$fetchDate=$sign->SelectF($dateqry,"po_date");
 
$datetime = $fetchDate;
$date = date('Y-m-d H:i', strtotime($datetime));
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Trace Waste | Weigh Bridge Slip</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../assets/vendors/feather/feather.css">
  <link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- inject:css -->
  <link rel="stylesheet" href="../assets/css/vertical-layout-light/style.css">
   <link rel="stylesheet" href="../assets/css/custom/sweetalert2.min.css">
   <!-- endinject -->
  <!-- tw-css:start -->
  <link rel="stylesheet" href="../assets/css/custom/tw-switch.css">
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
                  <h4 class="card-title">Weigh Bridge Slip</h4>
                 
                  <div class="forms-sample">
                    <div class="form-group">
						<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
							<label for="txtPartyName">Vendor Name<code>*</code></label>
							<input type="text" class="form-control" id="txtPartyName" maxlength="100" disabled value="<?php echo $retVal4; ?>" placeholder="Party Name" />
						</div>
						</div>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 col-4">
							<label for="txtGrossWeight">Gross Weight<code>*</code></label>
							<input type="number" class="form-control" id="txtGrossWeight" maxlength="100" value="<?php echo $gross_weight; ?>" placeholder="Gross Weight" />
						</div>
						<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 col-4">
								<label for="txtTare_Weight">Tare Weight<code>*</code></label>
								<input type="number" class="form-control" id="txtTare_Weight" maxlength="100" value="<?php echo $tare_weight; ?>" placeholder="Tare Weight" />
							</div>
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 col-4">
								<label for="txtNet_Weight">Net Weight<code>*</code></label>
								<input type="number" class="form-control" readonly id="txtNet_Weight" maxlength="100" value="<?php echo $net_weight; ?>" placeholder="Net Weight" />
							</div>
						</div>
					</div>	
					<div class="form-group">
						<div class="row">
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 col-4">
							<label for="txtGross_Weight_Date_Time">Gross Weight Date Time<code>*</code></label>
							<input type="datetime-local" class="form-control"  min="<?php echo $date; ?>" max="<?php echo $cur_date; ?>" id="txtGross_Weight_Date_Time" maxlength="100" value='<?php if(!empty($gross_weight_date_time)){echo $varGD;}else{echo $varCD;}?>' placeholder="Gross Weight Date Time" />
						</div>
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 col-4">
								<label for="txtTare_Weight_Date_Time">Tare Weight Date Time<code>*</code></label>
								<input type="datetime-local" class="form-control"  min="<?php echo $date; ?>" max="<?php echo $cur_date; ?>"  id="txtTare_Weight_Date_Time" maxlength="100" value='<?php if(!empty($tare_weight_date_time)){echo $varTD;}else{echo $varCD;}?>' placeholder="Tare Weight Date Time" />
							</div>
						</div>
					</div>
                    <div class="form-group">
						<div class="row">
							 <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col-6">
							<label for="txtCarrierNo">Carrier No <code>*</code></label>
							<input type="text" class="form-control" id="txtCarrierNo" maxlength="100" disabled value="<?php echo $vehicle_number; ?>" placeholder="Carrier No" />
						</div> 
							
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col-6">
								<label for="txtDriver_Name">Driver Name<code>*</code></label>
								<input type="text" class="form-control" id="txtDriver_Name" maxlength="100" value="<?php echo $driver_name; ?>" placeholder="Driver Name" />
							</div>
						</div>
					</div>
                    <div class="form-group">
						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col-6">
								<label for="txtAmount_Received">Amount Received<code>*</code></label>
								<input type="number" class="form-control" id="txtAmount_Received" maxlength="100" value="<?php echo $amount_received; ?>" placeholder="Amount Received" />
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col-6">
								<label for="txtPaymentMode">Payment Mode<code>*</code></label>
								<select id="txtPaymentMode" class="form-control" >
								 <?php echo $retValpaymentmode;?>
								</select>
							</div>
						</div>
					</div>
				
				<?php if($requesttype=="add"){ ?>
					<button type="button" class="btn btn-success" <?php echo $disabledWBS;?> id="btnAddrecord" onclick="addrecord();">Add Record</button>
				<?php }else{ ?>
					<button type="button" class="btn btn-success" id="btnAddrecord" onclick="addrecord();">Update Record</button>
				<?php } ?>
					
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
  <script src="../assets/js/custom/sweetalert2.min.js"></script>
	<script src="../assets/js/custom/sweetAlert.js"></script>
  <script src="../assets/vendors/typeahead.js/typeahead.bundle.min.js"></script>
  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="../assets/js/hoverable-collapse.js"></script>
  <script src="../assets/js/template.js"></script>
  <script src="../assets/js/settings.js"></script>
  <!-- endinject -->
  <script src="../assets/css/jquery/jquery.min.js"></script>
    <script src="../assets/js/custom/twCommonValidation.js"></script>
     <script type='text/javascript'>
var valpo_id='<?php echo $po_id; ?>';
var TotalWt=0.00;
var valrequestid="";
$("#txtGrossWeight").on('change keyup paste', function () {
	TotalWt = $("#txtGrossWeight").val()-$("#txtTare_Weight").val();
	$("#txtNet_Weight").val(TotalWt);
});
$("#txtTare_Weight").on('change keyup paste', function () {
	TotalWt = $("#txtGrossWeight").val()-$("#txtTare_Weight").val();
	$("#txtNet_Weight").val(TotalWt);
});
/* $("#txtGrossWeight").blur(function()
{
	removeError(txtGrossWeight);
	if ($("#txtGrossWeight").val()!="")
	{
		if(!isNumber($("#txtGrossWeight").val())){
			setError(txtGrossWeight);
		}
		else
		{
			removeError(txtGrossWeight);
		}
	}
});
$("#txtTare_Weight").blur(function()
{
	removeError(txtTare_Weight);
	if ($("#txtTare_Weight").val()!="")
	{
		if(!isNumber($("#txtTare_Weight").val())){
			setError(txtTare_Weight);
		}
		else
		{
			removeError(txtTare_Weight);
		}
	}
});
$("#txtNet_Weight").blur(function()
{
	removeError(txtNet_Weight);
	if ($("#txtNet_Weight").val()!="")
	{
		if(!isNumber($("#txtNet_Weight").val())){
			setError(txtNet_Weight);
		}
		else
		{
			removeError(txtNet_Weight);
		}
	}
});
$("#txtAmount_Received").blur(function()
{
	removeError(txtAmount_Received);
	if ($("#txtAmount_Received").val()!="")
	{
		if(!isNumber($("#txtAmount_Received").val())){
			setError(txtAmount_Received);
		}
		else
		{
			removeError(txtAmount_Received);
		}
	}
}); */
$('input').blur(function()
{
	var valplaceholder = $(this).attr("placeholder");
	var vallblid = $(this).attr("id");
	var valid = "err" + vallblid;
	var valtext = "Please enter " + valplaceholder;
    var check = $(this).val().trim();
	var checkElementExists = document.getElementById(valid);
	if(check=='')
	{
		
		if(!checkElementExists)
		{
			$(this).parent().addClass('has-danger');
			$(this).after('<label id="' + valid + '" class="error mt-2 text-danger">'+valtext+'</label>');
		}

	}
	else
	{
		$(this).parent().removeClass('has-danger');  
		if (checkElementExists)
		{
			checkElementExists.remove();
		}
	}
});
function setErrorOnBlur(inputComponent)
{
	var valplaceholder = $("#" +inputComponent).attr("placeholder");
	var vallblid = $("#" +inputComponent).attr("id");
	var valid = "err" + vallblid;
	var valtext = "Please enter " + valplaceholder;
    var check = $("#" +inputComponent).val().trim();
	var checkElementExists = document.getElementById(valid);
	if(check=='')
	{
		
		if(!checkElementExists)
		{
			$("#" +inputComponent).parent().addClass('has-danger');
			$("#" +inputComponent).after('<label id="' + valid + '" class="error mt-2 text-danger">'+valtext+'</label>');
		}

	}
	else
	{
		$("#" +inputComponent).parent().removeClass('has-danger');  
		if (checkElementExists)
		{
			checkElementExists.remove();
		}
	}
}

function setError(inputComponent)
{
	
	var valplaceholder = $(inputComponent).attr("placeholder");
	var vallblid = $(inputComponent).attr("id");
	var valid = "errSet" + vallblid;
	var valtext = "Please enter valid " + valplaceholder;
	var checkElementExists = document.getElementById(valid);
	if(!checkElementExists)
	{
		$("#" + vallblid).parent().addClass('has-danger');
		$("#" + vallblid).after('<label id="' + valid + '" class="error mt-2 text-danger">'+valtext+'</label>');
	}
	
}

function removeError(inputComponent)
{
	var vallblid = $(inputComponent).attr("id");
	$("#" + vallblid).parent().removeClass('has-danger');
	const element = document.getElementById("errSet"+vallblid);
	if (element)
	{
		element.remove();
	}
}
var valrequesttype = "<?php echo $requesttype;?>";

function addrecord(){
		  if(!validateBlank($("#txtCarrierNo").val())){
			setErrorOnBlur("txtCarrierNo");
		  }
		  else if(!validateBlank($("#txtPartyName").val())){
			setErrorOnBlur("txtPartyName");
		  } 
		  else if(!validateBlank($("#txtGrossWeight").val())){
			setErrorOnBlur("txtGrossWeight");
		  } 
		  else if(!validateBlank($("#txtGross_Weight_Date_Time").val())){
			setErrorOnBlur("txtGross_Weight_Date_Time");
		  }
		  else if(!validateBlank($("#txtTare_Weight").val())){
			setErrorOnBlur("txtTare_Weight");
		  }
		  else if(!validateBlank($("#txtTare_Weight_Date_Time").val())){
			setErrorOnBlur("txtTare_Weight_Date_Time");
		  }
		  else if(!validateBlank($("#txtNet_Weight").val())){
			setErrorOnBlur("txtNet_Weight");
		  }
		  else if(!validateBlank($("#txtAmount_Received").val())){
			setErrorOnBlur("txtAmount_Received");
		  }
		  else if(!validateBlank($("#txtDriver_Name").val())){
			setErrorOnBlur("txtDriver_Name");
		  }
		  else if(!validateBlank($("#txtPaymentMode").val())){
			setErrorOnBlur("txtPaymentMode");
		  }
		  else{
				var valcreated_by = "<?php echo $created_by;?>";
				var valcreated_on = "<?php echo $cur_date;?>";
				var valcreated_ip = "<?php echo $ip_address;?>";
				var valoutwardid = "<?php echo $outwardid;?>";
				
				if(valrequesttype=="add"){
					var valquery = "insert into tw_material_outward_wbs(outward_id,carrier_no,party_name,gross_weight,gross_weight_date_time,tare_weight,tare_weight_date_time,net_weight,amount_received,driver_name,payment_mode,created_by,created_on,created_ip)values('"+valoutwardid+"','"+$("#txtCarrierNo").val()+"','"+$("#txtPartyName").val()+"','"+$("#txtGrossWeight").val()+"','"+$("#txtGross_Weight_Date_Time").val()+"','"+$("#txtTare_Weight").val()+"','"+$("#txtTare_Weight_Date_Time").val()+"','"+TotalWt+"','"+$("#txtAmount_Received").val()+"','"+$("#txtDriver_Name").val()+"','"+$("#txtPaymentMode").val()+"','"+valcreated_by+"','"+valcreated_on+"','"+valcreated_ip+"')";
				}
				else{
					valrequestid = "<?php echo $id;?>";
					var valquery = "Update tw_material_outward_wbs set carrier_no = '"+$("#txtCarrierNo").val()+"' , party_name = '"+$("#txtPartyName").val()+"', gross_weight = '"+$("#txtGrossWeight").val()+"', gross_weight_date_time = '"+$("#txtGross_Weight_Date_Time").val()+"', tare_weight = '"+$("#txtTare_Weight").val()+"', tare_weight_date_time = '"+$("#txtTare_Weight_Date_Time").val()+"', net_weight = '"+TotalWt+"', amount_received = '"+$("#txtAmount_Received").val()+"', driver_name = '"+$("#txtDriver_Name").val()+"', payment_mode = '"+$("#txtPaymentMode").val()+"',modified_by='"+valcreated_by+"',modified_on='"+valcreated_on+"',modified_ip='"+valcreated_ip+"' where id = '"+valrequestid+"' ";
				
				}
				var buttonHtml = $('#btnAddrecord').html();
				
				$('#btnAddrecord').attr("disabled","true");
				$('#btnAddrecord').removeClass('btn-success');
				$('#btnAddrecord').addClass('btn-secondary');//secondary;
				$('#btnAddrecord').html('<i class="ti-timer"></i> Processing...');
				 
				
				$.ajax({
					type:"POST",
					url:"apiAddMaterialOutwardWbs.php",
					data:{valquery:valquery,valoutwardid:valoutwardid,valrequestid:valrequestid},
					success:function(response){
						console.log(response);
						$('#btnAddrecord').removeAttr('disabled');
						$('#btnAddrecord').removeClass('btn-secondary');
						$('#btnAddrecord').addClass('btn-success');
						if($.trim(response)=="Success"){
							if(valrequesttype=="add"){
								showAlertRedirect("Success","Data Added Successfully","success","pgMaterialOutward.php?type=In Process&po_id="+valpo_id);
							}
							else{
								showAlertRedirect("Success","Data Updated Successfully","success","pgMaterialOutward.php?type=In Process&po_id="+valpo_id);
							}
						}
						else if($.trim(response)=="Exist"){
							showAlert("warning","Value already exist","warning");
							$("#txtValue").focus();
						}
						else if($.trim(response)=="Status"){
							showAlert("Warning","Outward has been completed you can not upload this document","warning");
						}
						else{
							showAlert("error","Something Went Wrong. Please Try After Sometime","error");
						}
						
						$('#btnAddrecord').html(buttonHtml);
					}
				}); 
		  }
}
</script>	
</body>

</html>