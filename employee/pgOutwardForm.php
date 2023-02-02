<?php 
session_start();
	if(!isset($_SESSION["employee_id"])){
		header("Location:pgEmployeeLogIn.php");
	}
	
include_once "function.php";
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();
$requesttype = $_REQUEST["type"];
$requestid = $_REQUEST["id"];
$ip_address= $commonfunction->getIPAddress();
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");
$created_by=$_SESSION["employee_id"];
$entry_date = "";
$customer_name = "";
$material_name = "";
$quantity = "";
$disable="";
$ward = "";
if($requesttype=="edit")
{
	$disable="readonly";
	$qry="select id,entry_date,customer_name,material_name,quantity,ward from tw_outward_data_entry where ID='".$requestid."' order by id asc";
	$retVal = $sign->FunctionJSON($qry);
	$decodedJSON = json_decode($retVal);
	$id = $decodedJSON->response[0]->id; 
	$entry_date = $decodedJSON->response[1]->entry_date; 
	$customer_name = $decodedJSON->response[2]->customer_name; 
	$material_name = $decodedJSON->response[3]->material_name;
	$quantity = $decodedJSON->response[4]->quantity;
	$ward = $decodedJSON->response[5]->ward;
}
if($requesttype=="edit"){ 
$cur_date =$entry_date;
 } else {
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");
 }
$CustnameQry="SELECT id,name FROM tw_partner_outward_master ORDER by id ASC";
$Custname = $sign->FunctionOption($CustnameQry,$customer_name,'name',"id");

$materialnameQry="SELECT id,name FROM tw_inward_waste_type_master ORDER by priority ASC";
$materialname = $sign->FunctionOption($materialnameQry,$material_name,'name',"id");

$QueryWardType = "select id,ward_name from tw_ward_master where visibility='true' Order by priority,ward_name";
$ValueWardType = $sign->FunctionOption($QueryWardType,$ward,'ward_name','id');
?>
<!DOCTYPE html>
<html lang="en">

<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Trace Waste | Outward Form</title>
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
                  <h4 class="card-title">Outward Data Entry</h4>
					<div class="forms-sample">
					
					 <div class="row">
					<div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12 ">
					 <label class='form-label'>Date<code>*</code></label>
					 
					  <input type="date" class="form-control" id="txtDate" <?php echo $disable; ?> max='<?php echo $cur_date; ?>'  value="<?php  echo date("Y-m-d",strtotime($cur_date))  ?>" placeholder=" Date" /> 
					</div>
					<div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
					  <label for="txtSupplier">Customer Name<code>*</code></label>
						  <select  class="form-control" id="customername"  placeholder="Select txtSupplier">
						<?php echo $Custname; ?>
					  </select>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 grid-margin">
						<div class="form-group" >
						<label>Ward<code>*</code></label>
						<select name="Ward" id="txtWard" class="form-control" >
						 <?php echo $ValueWardType;?>
						</select>
						</div>
						</div>
					</div> 
					<div class="row">
					<div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12">
					  <label for="txtSupplier">Material Name<code>*</code></label>
						   <select  class="form-control" id="materialname"  placeholder="Select Material Name">
						<?php echo $materialname; ?>
					  </select>
						  
						  
					</div>
					<div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12">
					  <label for="txtSupplier">Quantity<code>*</code></label>
						  <input type="text" class="form-control " id="quantity" value="<?php echo $quantity; ?>" placeholder="Quantity"  />
					</div>
					</div>
						        
						<button type="button" class="btn btn-success" id="btnAddrecord" onclick="adddata()"><?php if($requesttype=="add"){ ?>Add<?php }else{ ?>Update<?php } ?></button>
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

<script src="../assets/js/custom/sweetAlert.js"></script>
<script src="../assets/js/custom/sweetalert2.min.js"></script>
<script src="../assets/css/jquery/jquery.min.js"></script>
<script src="../assets/js/custom/twCommonValidation.js"></script>
<script>
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
	var valplaceholder = $("#"+inputComponent).attr("placeholder");
	var vallblid = $("#" +inputComponent).attr("id");
	var valid = "err" + vallblid;
	var valtext = "Please enter " + valplaceholder;
    var check = $("#" +inputComponent).val().trim();
	var checkElementExists = document.getElementById(valid);
		
		if(!checkElementExists)
		{
			$("#" +inputComponent).parent().addClass('has-danger');
			$("#" +inputComponent).after('<label id="' + valid + '" class="error mt-2 text-danger">'+valtext+'</label>');
			$("#" +inputComponent).focus();
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
function adddata(){
	  var quantity = $("#quantity").val();
	    if(!validateBlank($("#customername").val())){
			setErrorOnBlur("customername");
		}
		else if(!validateBlank($("#materialname").val())){
		setErrorOnBlur("materialname");
		} 
		
		else if(!validateBlank(quantity)|| parseInt(quantity) < 0 || isNaN(parseInt(quantity))){
		setErrorOnBlur("quantity");
		}
		else{ 
		
			disableButton('#btnAddrecord','Processing...');
			var valcreated_by="<?php echo $created_by;?>";
			var valcreated_on="<?php echo $cur_date;?>";
			var valcreated_ip="<?php echo $ip_address;?>";
			var valrequesttype="<?php echo $requesttype;?>";
			if(valrequesttype=="add"){
				var valquery = "insert into   tw_outward_data_entry(entry_date,customer_name,material_name,quantity,ward,created_by,created_on,created_ip) values('"+$("#txtDate").val()+"','"+$("#customername").val()+"','"+$("#materialname").val()+"','"+$("#quantity").val()+"','"+$("#txtWard").val()+"','"+valcreated_by+"','"+valcreated_on+"','"+valcreated_ip+"')";
				var valquerycount = "select count(*) as cnt from tw_outward_data_entry where customer_name='"+$("#customername").val()+"'";
			}
			else{
				var valrequestid = "<?php echo $requestid;?>";
				var valquery = "Update tw_outward_data_entry set entry_date='"+$("#txtDate").val()+"',customer_name='"+$("#customername").val()+"', material_name='"+$("#materialname").val()+"',quantity='"+$("#quantity").val()+"',ward='"+$("#txtWard").val()+"', modified_by='"+valcreated_by+"', modified_on='"+valcreated_on+"', modified_ip='"+valcreated_ip+"' where id='"+valrequestid+"'";
				var valquerycount = "select count(*) as cnt from tw_outward_data_entry where name='"+$("#txtname").val()+"' and ID!='"+valrequestid+"'";
			}
				$.ajax({
				type:"POST",
				url:"apiCommonQuerySingle.php",
				data:{valquery:valquery,valquerycount:valquerycount},
				success:function(response){
				console.log(response);
					if(valrequesttype=="add"){
							enableButton('#btnAddrecord','Add Record');
						}
					else{
						enableButton('#btnAddrecord','Update Record');
					}	
					if($.trim(response)=="Success"){
						if(valrequesttype=="add"){
							$('#btnAddrecord').html('Add Record');
							showAlertRedirect("Success","Record Added Successfully","success","pgOutward.php");
						}
						else{
							$('#btnAddrecord').html('Update Record');
							showAlertRedirect("Success","Record Updated Successfully","success","pgOutward.php");
						}
					}
					else if($.trim(response)=="Exist"){
						showAlert("Warning","Record already exist","warning");
					}else{
						showAlert("Error","Something Went Wrong. Please Try After Sometime.","error");	
					}
				}
			});   
	} 
}
</script> 
</body>
</html>