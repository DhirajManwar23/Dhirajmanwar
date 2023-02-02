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
$requestid = $_REQUEST["id"];
$outward_id = $_REQUEST["outward_id"];

$company_id = $_SESSION["company_id"];
$created_by=$_SESSION["employee_id"];

//whether ip is from share internet
$ip_address= $commonfunction->getIPAddress();

date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d");

$invoice_number = "";
$delivery_challan_no = "";
$obp_certificate_no = "";
$centre_outward_slip_no = "";
$invoice_date = "";
$date_of_supply = "";
$remark = "";
$termsofpayment = "";
$settingValueStatuspending= $commonfunction->getSettingValue("Registration Status");	

$qryInvoice="SELECT COUNT(*) as cnt from tw_material_outward_documents WHERE type='Invoice' and outward_id='".$outward_id."' ORDER BY outward_id ASC";
$retValInvoice = $sign->SelectF($qryInvoice,"cnt");

$qry1Invoice="SELECT COUNT(*) as cnt from tw_tax_invoice WHERE outward_id='".$outward_id."' ORDER BY outward_id ASC";
$retVal1Invoice = $sign->SelectF($qry1Invoice,"cnt");
if($retValInvoice>0 || $retVal1Invoice>0){
	$disabledInvoice="disabled";
}
else{
	$disabledInvoice="";
}

$Alladdqry="SELECT company_address,bill_to,ship_to,customer_id,po_id FROM tw_material_outward where id='".$outward_id."' ";
$Alladd = $sign->FunctionJSON($Alladdqry);
$decodedJSON3 = json_decode($Alladd);
$company_addressId=$decodedJSON3->response[0]->company_address;
$bill_toId=$decodedJSON3->response[1]->bill_to;
$ship_toId=$decodedJSON3->response[2]->ship_to;
$cid=$decodedJSON3->response[3]->customer_id;
$po_id=$decodedJSON3->response[4]->po_id;
	
$detailsQry="SELECT sum(total) as total,tax FROM tw_temp_po_details where po_id='".$po_id."'";
$details = $sign->FunctionJSON($detailsQry);
$decodedJSON5 = json_decode($details);
$detailstotal=$decodedJSON5->response[0]->total;
$detailstax=$decodedJSON5->response[1]->tax;

$DetailsTotalwithTax=$detailstotal *($detailstax/100);
$Ftotal=$DetailsTotalwithTax+$detailstotal;

$dateqry="SELECT po_date FROM `tw_temp_po_info` where id='".$po_id."'";
$fetchDate=$sign->SelectF($dateqry,"po_date");
$datetime = $fetchDate;
$date = date('Y-m-d', strtotime($datetime));
	
	if($requesttype=="edit")
	{
		$qry="select invoice_number,delivery_challan_no,obp_certificate_no,centre_outward_slip_no,invoice_date,date_of_supply,remark,termsofpayment from tw_tax_invoice order by id Desc";
		$retVal = $sign->FunctionJSON($qry);
		$decodedJSON = json_decode($retVal);
		
		$invoice_number = $decodedJSON->response[0]->invoice_number;
		$delivery_challan_no = $decodedJSON->response[1]->delivery_challan_no;
		$obp_certificate_no = $decodedJSON->response[2]->obp_certificate_no;
		$centre_outward_slip_no = $decodedJSON->response[3]->centre_outward_slip_no;
		$invoice_date = $decodedJSON->response[4]->invoice_date;
		$date_of_supply = $decodedJSON->response[5]->date_of_supply;
		$remark = $decodedJSON->response[6]->remark;
		$termsofpayment = $decodedJSON->response[7]->termsofpayment;
	
	} 
	
	$BilltoAddqry="SELECT concat(address_line1,' ',address_line2,' ',location,' ',city,' ',state,' ',country)as address FROM tw_company_address where id='".$bill_toId."'";
	$BilltoAdd = $sign->SelectF($BilltoAddqry,"address"); 
	
	$ShiptoAddqry="SELECT concat(address_line1,' ',address_line2,' ',location,' ',city,' ',state,' ',country)as address FROM tw_company_address where id='".$ship_toId."'";
    $ShiptoAdd = $sign->SelectF($ShiptoAddqry,"address");
	
	$CmpAddqry="SELECT concat(address_line1,' ',address_line2,' ',location,' ',city,' ',state,' ',country)as address FROM tw_company_address where id='".$company_addressId."'";
    $CmpAdd = $sign->SelectF($CmpAddqry,"address");
	
	
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Trace Waste |Tax Invoice</title>
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
  <link rel="stylesheet" href="../assets/css/custom/style.css">

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
	  <!--=========================== MODAL1 START ==============================-->
  <div class="modal" id="ModalSenderAddress" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Pick up address</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="row" >
          <?php echo $returnADD; ?>
          </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="saveAdd"data-dismiss="modal" onclick="">Save changes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
  <!--=========================== MODAL END ==============================--> 
   <!--=========================== MODAL2 START ==============================-->
  <div class="modal" id="ModalBillAddress" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Bill to address</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="row" id="Main">
           
          </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="saveAdd"data-dismiss="modal" onclick="">Save changes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
  <!--=========================== MODAL END ==============================-->  <!--=========================== MODAL2 START ==============================-->
  <div class="modal" id="ModalShipAddress" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Bill to address</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="row" id="MainShip">
           
          </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="saveAdd"data-dismiss="modal" onclick="">Save changes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
  <!--=========================== MODAL END ==============================--> 

	  
      <div class="main-panel">        
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Tax Invoice</h4>
                  <div class="forms-sample">
					
					
                      <!--<div class="form-group">
                      <label for="txtcompanyaddress">Company Address <code>*</code></label>
                      <input type="text" disabled class="form-control" id="txtcompanyaddress"  maxlength="30" value=" <?php //echo $CmpAdd; ?>" />
                    </div>
					<div class="form-group">
                      <label for="txtbilltocompanyaddress">Bill To Company Address <code>*</code></label>
                      <input type="text" disabled class="form-control" id="txtbilltocompanyaddress" maxlength="30" value="<?php// echo  $BilltoAdd;?>" placeholder="Bill To Company Address" />
                    </div>-->
					<div class="form-group">
						<div class="row">
						   <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
								<label for="txtinvoicenumber">Invoice Number <code>*</code></label>
								<input type="text" class="form-control" id="txtinvoicenumber" maxlength="30" value="<?php echo $invoice_number; ?>" placeholder="Invoice Number" />
						   </div>
						   <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
								<label for="txtdeliverychallannumber">Delivery Challan Number<code>*</code></label>
								<input type="text" class="form-control" id="txtdeliverychallannumber" maxlength="50" value="<?php echo $delivery_challan_no; ?>" placeholder="Delivery Challan Number" />
						   </div>
						   <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
								<label for="txtobpcertificatenumber">OBP Certificate Number <code>*</code></label>
								<input type="text" class="form-control" id="txtobpcertificatenumber" maxlength="50" value="<?php echo $obp_certificate_no; ?>" placeholder="OBP Certificate Number" />
						   </div>
                       </div>
					</div>
					<div class="form-group">
						<div class="row">
						   <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
								<label for="txtcentreoutwardslipnumber">Centre Outward Slip Number <code>*</code></label>
								<input type="text" class="form-control" id="txtcentreoutwardslipnumber" maxlength="50" value="<?php echo $centre_outward_slip_no; ?>" placeholder="Centre Outward Slip Number" />
						   </div>
						  <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
								<label for="txtinvoicedate">Invoice Date <code>*</code></label>
								<input type="Date" class="form-control" min="<?php echo $date; ?>" max="<?php echo $cur_date; ?>" value='<?php if(!empty($invoice_date)){echo date("Y-m-d",strtotime($invoice_date));}else{echo date("Y-m-d",strtotime($cur_date));}?>' id="txtinvoicedate"  placeholder="Invoice Date" />
						   </div>
						   <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
								<label for="txtdateofsupply">Date Of Supply<code>*</code></label>
								<input type="date" class="form-control"  min="<?php echo $date; ?>" max="<?php echo $cur_date; ?>" value='<?php if(!empty($date_of_supply)){echo date("Y-m-d",strtotime($date_of_supply));}else{echo date("Y-m-d",strtotime($cur_date));}?>' id="txtdateofsupply"  placeholder="Date Of Supply" />
						   </div>
                       </div>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
								<div class="pAddress">
									<h3 class="pAddress-header">Company Address</h3>
									<div class="pAddress-body">
										<p id="txtcompanyaddress"><?php echo $CmpAdd; ?></p>
									</div>
								</div>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
								<div class="pAddress">
									<h3 class="pAddress-header">Bill To Company Address</h3>
									<div class="pAddress-body">
										<p id="txtbilltocompanyaddress"><?php echo $BilltoAdd; ?></p>
									</div>
								</div>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
								<div class="pAddress">
									<h3 class="pAddress-header">Ship To Company Address</h3>
									<div class="pAddress-body">
										<p id="txtshiptocompanyaddress"><?php echo $ShiptoAdd; ?></p>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group">
                      <label for="txtTermsofPayment">Terms of Payment <code>*</code></label>
                      <input type="text" class="form-control" value="<?php echo $termsofpayment; ?>" id="txtTermsofPayment"  placeholder="Terms of Payment" />
                    </div>
					<div class="form-group">
                      <label for="txtremark">Remark <code>*</code></label>
                      <textarea class="form-control" id="txtremark" maxlength="5000" placeholder="Remark"><?php echo $remark; ?></textarea>
                    </div>
					
					<hr>
					<div class="table-responsive">
						<table id="tableData" class="table" >
						 
						</table>
					</div>
					<hr>	
					<?php if($requesttype=="add"){ ?>
						<button type="button" class="btn btn-success" <?php echo $disabledInvoice;?> id="btnAddrecord" onclick="adddata();">Add</button>
					<?php }else{ ?>
						<button type="button" class="btn btn-success" id="btnAddrecord" onclick="adddata();">Update</button>
					<?php } ?>
					
					
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
 var txtFinalTotalAmount=0.00;
 var valpo_id='<?php echo $po_id; ?>';
 var FinalToal='<?php echo $Ftotal; ?>';
 $(document).ready(function(){
	showDATA();
});
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
 function adddata(){
	  
		if(!validateBlank($("#txtinvoicenumber").val())){
			setErrorOnBlur("txtinvoicenumber");
		} 
		else if(!validateBlank($("#txtinvoicedate").val())){
			setErrorOnBlur("txtinvoicedate");
		} 
		else if(!validateBlank($("#txtdateofsupply").val())){
			setErrorOnBlur("txtdateofsupply");
		} 
		else if(!validateBlank($("#txtTermsofPayment").val())){
			setErrorOnBlur("txtTermsofPayment");
		} 
		else if(!validateBlank($("#txtremark").val())){
			setErrorOnBlur("txtremark");
		} 
		else {
			/* alert(parseInt(txtFinalTotalAmount, 10));
			alert(parseInt(FinalToal, 10)); */
			
			if(txtFinalTotalAmount>FinalToal){
				alert("Yes");
			 showConfirmAlert('Confirm action!', 'Invoice amount, is greater than PO amount do you want procced ?','question', function (confirmed){
					if(confirmed==true){
						addTaxInvoiceData();
					}
			
			
		}); 
	}
	else{
		addTaxInvoiceData();
	}
  } 
}
 function addTaxInvoiceData(){
	 //---
		var buttonHtml = $('#btnAddrecord').html();
		var varoutward_id = "<?php echo $outward_id; ?>";
		var valrequesttype="<?php echo $requesttype;?>";
		var valrequestid ="<?php echo $requestid;?>";
		var valcompany_addressId ="<?php echo $company_addressId; ?>";
		var valbill_to_company_id ="<?php echo $cid; ?>";
		var valbill_to_company_address ="<?php echo $bill_toId; ?>";
		var valship_to_company_address ="<?php echo $ship_toId; ?>";
		
		

		disableButton('#btnAddrecord','<i class="ti-timer"></i> Processing...');
		
		$.ajax({
			type:"POST",
			url:"apiAddMaterialOutwardTaxInvoice.php",
			data:{requestid:valrequestid,company_address:valcompany_addressId,bill_to_company_id:valbill_to_company_id,bill_to_company_address:valbill_to_company_address,ship_to_company_address:valship_to_company_address,
			invoice_number:$("#txtinvoicenumber").val(),deliverychallannumber:$("#txtdeliverychallannumber").val(),obpcertificatenumber:$("#txtobpcertificatenumber").val(),centreoutwardslipnumber:$("#txtcentreoutwardslipnumber").val(),invoice_date:$("#txtinvoicedate").val(),date_of_supply:$("#txtdateofsupply").val(),txtTermsofPayment:$("#txtTermsofPayment").val(),remark:$("#txtremark").val(),final_total_amount:txtFinalTotalAmount,outward_id:varoutward_id,valrequesttype:valrequesttype},
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
						showAlertRedirect("Success","Data Added Successfully","success","pgMaterialOutward.php?type=In Process&po_id="+valpo_id);
					}
					else{
						showAlertRedirect("Success","Data Updated Successfully","success","pgMaterialOutward.php?type=In Process&po_id="+valpo_id);
					}
				}
				else if($.trim(response)=="Exist"){
					showAlert("Warning","Entry already exist","warning");
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
	//---
 }
 function showDATA(){
	 var varoutward_id = "<?php echo $outward_id; ?>";
	$.ajax({
          type:"POST",
          url:"apiAddTempMaterialOutwardTaxInvoice.php",
		  dataType: 'JSON',
          data:{outward_id:varoutward_id},
          success:function(response){
			  console.log(response);
			 $("#tableData").html(response);
			 $("#tableData").html(response[0]);
			 txtFinalTotalAmount=response[1];
			
			
			$("#txtQuantity").val("");
			$("#txtHSN").val("");
			$("#txtRate").val("");
			$("#txtTax").val("");
			$("#txtTotalAmount").val("");  
          }
      });
 }
 </script>
 
 </body>
</html>