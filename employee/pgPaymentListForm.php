<?php 
session_start();
if(!isset($_SESSION["employee_id"])){
	header("Location:pgEmployeeLogIn.php");
}
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();
$requesttype = $_REQUEST["type"];
$_SESSION["requesttype"] = $requesttype;
$requestid = $_REQUEST["id"];
$outward_id = $_REQUEST["outward_id"];
$company_id = $_SESSION["company_id"];
//whether ip is from share internet
$ip_address= $commonfunction->getIPAddress();

date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");
$created_by=$_SESSION["employee_id"];
	
	$invoice_id = "";
	$transaction_id = "";
	$payment_type = "";
	$amount = "";
	$status = "";
	$comments = "";
	$CompanyName = "";
	
	$settingValuePendingStatus= $commonfunction->getSettingValue("Pending Status");
	
	$qryoutwarddata="SELECT company_id,customer_id from tw_material_outward WHERE id='".$outward_id."'";
	$retValoutwarddata = $sign->FunctionJSON($qryoutwarddata);
	$decodedJSON5 = json_decode($retValoutwarddata);
	$ocompany_id = $decodedJSON5->response[0]->company_id;
	$customer_id = $decodedJSON5->response[1]->customer_id;

	$qryInvoice="SELECT COUNT(*) as cnt from tw_material_outward_documents WHERE type='Invoice' and outward_id='".$outward_id."' ORDER BY outward_id ASC";
	$retValInvoice = $sign->SelectF($qryInvoice,"cnt");

	$qry1Invoice="SELECT COUNT(*) as cnt from tw_tax_invoice WHERE outward_id='".$outward_id."' ORDER BY outward_id ASC";
	$retVal1Invoice = $sign->SelectF($qry1Invoice,"cnt");
	
	if($retValInvoice>0){
		$qryInvoice="SELECT id,document_value,amount from tw_material_outward_documents WHERE type='Invoice' and outward_id='".$outward_id."' ORDER BY outward_id ASC";
		$retVal = $sign->FunctionJSON($qryInvoice);
		$decodedJSON = json_decode($retVal);
		$invid = $decodedJSON->response[0]->id;
		$invoice_number = $decodedJSON->response[1]->document_value;
		$invoiceamount = $decodedJSON->response[2]->amount;
		
	}
	else{
		$qry1Invoice="SELECT id,invoice_number,final_total_amount from tw_tax_invoice WHERE outward_id='".$outward_id."' ORDER BY outward_id ASC";
		$retVal = $sign->FunctionJSON($qry1Invoice);
		$decodedJSON = json_decode($retVal);
		$invid = $decodedJSON->response[0]->id;
		$invoice_number = $decodedJSON->response[1]->invoice_number;
		$invoiceamount = $decodedJSON->response[2]->final_total_amount;
		
	}
	
	$QryTotal="SELECT SUM(amount) as TotalAmt FROM tw_invoice_transaction WHERE outward_id='".$outward_id."'";
	$retValQryTotal = $sign->FunctionJSON($QryTotal);
	$decodedJSON = json_decode($retValQryTotal);
	$TotalAmt = $decodedJSON->response[0]->TotalAmt;
	if($TotalAmt==""){
		$TotalAmt=0.00;
	}
	$Balanceamount=($invoiceamount-$TotalAmt);
	
	$qrypaymenttype = "select id,payment_type_value from tw_payment_type_master where visibility='true' Order by priority,payment_type_value";
	$retValpaymenttype = $sign->FunctionOption($qrypaymenttype,'','payment_type_value','id');

	$qrysuppliername="select ID as SupplierID,CompanyName as SupplierName from tw_company_details where ID ='".$ocompany_id."'";
	$retValsupplierdata = $sign->FunctionJSON($qrysuppliername);
	$decodedJSON4 = json_decode($retValsupplierdata);
	$SupplierID = $decodedJSON4->response[0]->SupplierID; 
	$SupplierName = $decodedJSON4->response[1]->SupplierName;
	
	$qrycustomerdata="select ID as CustomerID,CompanyName as CustomerName from tw_company_details where ID ='".$customer_id."'";
	$retValcustomerdata = $sign->FunctionJSON($qrycustomerdata);
	$decodedJSON3 = json_decode($retValcustomerdata);
	$CustomerID = $decodedJSON3->response[0]->CustomerID; 
	$CustomerName = $decodedJSON3->response[1]->CustomerName;

	if($requesttype=="edit")
	{
		$qry="select transaction_id,payment_type,amount,status,comments,payment_date from tw_invoice_transaction where id ='".$requestid."'";
		$retVal = $sign->FunctionJSON($qry);
		$decodedJSON = json_decode($retVal);
		$transaction_id = $decodedJSON->response[0]->transaction_id; 
		$payment_type = $decodedJSON->response[1]->payment_type;
		$amount = $decodedJSON->response[2]->amount;
		$status = $decodedJSON->response[3]->status;
		$comments = $decodedJSON->response[4]->comments;
		$payment_date = $decodedJSON->response[5]->payment_date;
		
		$qryInvoice="SELECT COUNT(*) as cnt from tw_material_outward_documents WHERE type='Invoice' and outward_id='".$outward_id."' ORDER BY outward_id ASC";
		$retValInvoice = $sign->SelectF($qryInvoice,"cnt");

		$qry1Invoice="SELECT COUNT(*) as cnt from tw_tax_invoice WHERE outward_id='".$outward_id."' ORDER BY outward_id ASC";
		$retVal1Invoice = $sign->SelectF($qry1Invoice,"cnt");
	
	if($retValInvoice>0){
		$qryInvoice="SELECT id,document_value from tw_material_outward_documents WHERE type='Invoice' and outward_id='".$outward_id."' ORDER BY outward_id ASC";
		$retVal = $sign->FunctionJSON($qryInvoice);
		$decodedJSON = json_decode($retVal);
		$invid = $decodedJSON->response[0]->id;
		$invoice_number = $decodedJSON->response[1]->document_value;
		
	}
	else{
		$qry1Invoice="SELECT id,invoice_number from tw_tax_invoice WHERE outward_id='".$outward_id."' ORDER BY outward_id ASC";
		$retVal = $sign->FunctionJSON($qry1Invoice);
		$decodedJSON = json_decode($retVal);
		$invid = $decodedJSON->response[0]->id;
		$invoice_number = $decodedJSON->response[1]->invoice_number;
		
	}	
	
	}
	
?>
<!DOCTYPE html>
<html lang="en">
<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Trace Waste | Accounts </title>
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
                  <h4 class="card-title">Accounts</h4>
                  <div class="forms-sample">
					 <div class="form-group">
                      <label for="txtinvoiceid">Invoice Number<code>*</code></label>
                      <input class="form-control" disabled id="txtinvoiceid" maxlength="30" placeholder="Invoice Number" value="<?php echo $invoice_number; ?>">
                    </div>
					<div class="form-group">
						<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col-6">
								   <label for="txtcustomername"> Customer Name</label>
									<input type="text" class="form-control" disabled id="txtcustomername" maxlength="100" placeholder="Customer Name" value="<?php echo $CustomerName; ?>"><br>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col-6">
								   <label for="txtsuppliername"> Supplier Name<code>*</code></label>
									<input type="text" class="form-control" disabled id="txtsuppliername" maxlength="100" placeholder="Supplier Name" value="<?php echo $SupplierName; ?>"><br>
								</div>
							</div>
                    </div>
					<div class="form-group">
						<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col-6">
								  <label for="txttransactionid">Transaction ID <code>*</code></label>
									<input type="number" class="form-control" id="txttransactionid" maxlength="30" value="<?php echo $transaction_id; ?>" placeholder="Transaction ID" />
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col-6">
								   <label for="txtpaymentdate"> Payment Date <code>*</code></label>
									<input type="Date" class="form-control" id="txtpaymentdate" value='<?php if(!empty($payment_date)){echo date("Y-m-d",strtotime($payment_date));}else{echo date("Y-m-d",strtotime($cur_date));}?>'  placeholder="Payment Date" />
								</div>
								
						</div>
					</div>
					
					<div class="form-group">
						<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col-6">
								   <label for="txtamount"> Amount<code>*</code></label>
									<input type="number" class="form-control" id="txtamount" maxlength="100" placeholder="Amount" value="<?php echo $amount; ?>"><br>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col-6">
								   <label for="txtpaymenttype"> Payment Type <code>*</code></label>
									<select id="txtpaymenttype" class="form-control" >
										<?php echo $retValpaymenttype;?>
									</select>
								</div>
						</div>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
								<label for="txtcomments">Comments<code>*</code></label>
								<textarea type="text" class="form-control" id="txtcomments" maxlength="30" placeholder="Comments" ><?php echo $comments; ?></textarea>
							</div>									
						</div>
                    </div>	
					<button type="button" class="btn btn-success" id="btnAddrecord" onclick="adddata();"><?php if($requesttype=="add"){ ?>Add<?php }else{ ?>Update<?php } ?></button>
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
<script src="../assets/css/jquery/jquery.min.js"></script>
<script>
var valBalInvoiceAmount = "<?php echo $Balanceamount; ?>";
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
			$("#" +inputComponent).focus();
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
$("#txtamount").blur(function()
{
	removeError(txtamount);
	if ($("#txtamount").val()!="")
	{
		if(!isNumber($("#txtamount").val())){
			setError(txtamount);
		}
		else
		{
			removeError(txtamount);
		}
	}
});

function adddata(){
	     if(!validateBlank($("#txttransactionid").val())){
			setErrorOnBlur("txttransactionid");
		  } 
		  else if(!validateBlank($("#txtpaymentdate").val())){
			setErrorOnBlur("txtpaymentdate");
		  } 
		  else if(!validateBlank($("#txtamount").val())){
			setErrorOnBlur("txtamount");
		  }  
		  
		  else if(!validateBlank($("#txtcomments").val())){
			setErrorOnBlur("txtcomments");
		  } 
		  else{  
				var valcreated_by="<?php echo $created_by;?>";
				var valcreated_on="<?php echo $cur_date;?>";
				var valcreated_ip="<?php echo $ip_address;?>";
				var valrequesttype="<?php echo $requesttype;?>";
				var valoutwardid="<?php echo $outward_id;?>";	
				var valsupplierid="<?php echo $SupplierID;?>";
				var valcustomerid="<?php echo $CustomerID;?>";
				var valinvoiceid="<?php echo $invid;?>";
				var status="<?php echo $settingValuePendingStatus; ?>";
				var id="<?php echo $requestid; ?>";
				
				$.ajax({
						type:"POST",
						url:"apiPayment.php",
						data:{id:id,transaction_id:$("#txttransactionid").val(),payment_type:$("#txtpaymenttype").val(),payment_date:$("#txtpaymentdate").val(),amount:$("#txtamount").val(),comments:$("#txtcomments").val(),valinvoiceid:valinvoiceid,valcustomerid:valcustomerid,valsupplierid:valsupplierid,valoutwardid:valoutwardid,valrequesttype:valrequesttype,valcreated_by:valcreated_by,status:status,valBalInvoiceAmount:valBalInvoiceAmount},
						success:function(response){
							console.log(response);
							if(valrequesttype=="add"){
								enableButton('#btnAddrecord','Add Record');
							}
							else
							{
								enableButton('#btnAddrecord','Update Record');
							}
							if($.trim(response)=="Success"){
								if(valrequesttype=="add"){
									showAlertRedirect("Success","Record Added Successfully","success","pgPaymentListBuyer.php?outward_id="+valoutwardid);
								}
								else{
									showAlertRedirect("Success","Record Updated Successfully","success","pgPaymentListBuyer.php?outward_id="+valoutwardid);
								}
							}
							else if($.trim(response)=="Exist"){
								showAlert("Warning","Transaction ID already exist","warning");
								$("#txttransactionid").focus();
								$("#txttransactionid").val("");
							}
							else if($.trim(response)=="PaymentCleared"){
								showAlertRedirect("Warning","Payment Cleared No Amount to be Paid","warning","pgPaymentListBuyer.php?outward_id="+valoutwardid);
							}
							else if($.trim(response)=="AmountGreater"){
								showAlert("Warning","Amount is Greater than the payable amount","warning");
								$("#txtamount").focus();
								$("#txtamount").val("");
							}
							else{
								showAlert("Error","Something Went Wrong. Please Try After Sometime.","error");	
								
							}
						}
					}); 
		  } 
	  } 

</script>
</body>
</html>