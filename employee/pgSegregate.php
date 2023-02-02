<?php 
session_start();
if(!isset($_SESSION["employee_id"])){
	header("Location:pgLogin.php");
}
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";
$commonfunction=new Common();
$sign=new Signup();

$company_id = $_SESSION["company_id"];
$mix_waste_lot_id = $_REQUEST["id"];
$settingValuePendingStatus= $commonfunction->getSettingValue("Pending Status");
$settingValueApprovedStatus= $commonfunction->getSettingValue("Approved Status");
$settingValueRejectedStatus= $commonfunction->getSettingValue("Rejected status");
$settingValueVerifiedStatus= $commonfunction->getSettingValue("Verified Status");
$settingValueCompletedStatus= $commonfunction->getSettingValue("Completed Status");

$qry5="select actual_quantity from tw_mix_waste_lot_info where id='".$mix_waste_lot_id."' order by id desc";
$qryquantity = $sign->SelectF($qry5,"actual_quantity");
?>
<!DOCTYPE html>
<html lang="en">
        
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Trace Waste | Segregation</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../assets/vendors/feather/feather.css">
  <link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- inject:css -->
   <link rel="stylesheet" href="../assets/css/custom/sweetalert2.min.css">
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
						 <h4 class="card-title">Segregation</h4>
							<p>Total Segregation Quantity : <b><?php echo $qryquantity; ?></b></p>
							<div id="divData">
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
<script src="../assets/js/custom/sweetalert2.min.js"></script>
<script src="../assets/js/custom/sweetAlert.js"></script>
<script src="../assets/css/jquery/jquery.min.js"></script>
<script src="../assets/js/custom/twCommonValidation.js"></script>

<script type='text/javascript'>

var valsettingValuePendingStatus='<?php echo $settingValuePendingStatus; ?>';
var valsettingValueApprovedStatus='<?php echo $settingValueApprovedStatus; ?>';
var valsettingValueRejectedStatus='<?php echo $settingValueRejectedStatus;?>';
var valsettingValueCompletedStatus='<?php echo $settingValueCompletedStatus;?>';
var valmix_waste_lot_id='<?php echo $mix_waste_lot_id;?>';
var valqryquantity='<?php echo $qryquantity;?>';

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

$(document).ready(function(){
	showData();
});
var materialtype = [];
var quantity = [];
function showData(id){
		$.ajax({
		type:"POST",
		url:"apiSegregate.php",
		data:{id:id},
		dataType: 'JSON',
		success:function(response){
			console.log(response);
			console.log(response[1]);
			$("#divData").html(response[0]);
			
			//var json = JSON.parse(response[1]);
			var json = response[1];
			
			json.forEach((item) => {
				materialtype.push(item.dataMaterialType);
				quantity.push(item.dataquantity);
			});
			console.log(materialtype);
			console.log(quantity);

		}
	});
}

function addrecord(){
	var quantityvalue = [];
	
		var valTotalquantity = 0;
		for (let i = 0; i < quantity.length; i++) {
		  quantityvalue.push($("#"+quantity[i]+"").val());
		  
		  qty = parseInt($("#"+quantity[i]+"").val());
		 valTotalquantity = (valTotalquantity + qty);
			  
		}
	if(valqryquantity<valTotalquantity){
		showAlert("Warning","Segregated Quantity cannot be greater than Total Quantity","warning");
	}
	else if(valqryquantity>valTotalquantity){
		showAlert("Warning","Segregated Quantity cannot be less than Total Quantity","warning");
	}
	else{
		//alert($("#txtComment").val());
		 $.ajax({
			type:"POST",
			url:"apiSaveSegregate.php",
			data:{materialtype:materialtype,quantityvalue:quantityvalue,valmix_waste_lot_id:valmix_waste_lot_id,txtComment:$("#txtComment").val()},
			success:function(response){
				console.log(response);
				if($.trim(response)=="Success"){
					showAlertRedirect("Success","Waste seggregate Successfully","success","pgSegregationList.php");
				}
				else{
					showAlert("Warning","Something Went Wrong","warning");
				}
				
			}
		});   
	}
}

</script>
</body>
</html>