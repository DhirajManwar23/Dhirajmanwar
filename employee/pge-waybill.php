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
$requesttype =""; //$_REQUEST["type"];
$id = 1;//$_REQUEST["id"];
$outward_id = 1;  //$_REQUEST["outwardid"];
$ip_address= $commonfunction->getIPAddress();
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");
$created_by=$_SESSION["employee_id"];
// $company_id=$_SESSION["company_id"];
$settingValueGSTDocuments= $commonfunction->getSettingValue("GSTDocuments");
	
echo $Alladdqry="SELECT company_address,bill_to,ship_to,customer_id,po_id,company_id,final_total_amout,vehicle_id FROM tw_material_outward where id='".$outward_id."' ";
$Alladd = $sign->FunctionJSON($Alladdqry);
$decodedJSON3 = json_decode($Alladd);
$company_addressId=$decodedJSON3->response[0]->company_address;
$bill_toId=$decodedJSON3->response[1]->bill_to;
$ship_toId=$decodedJSON3->response[2]->ship_to;
$cid=$decodedJSON3->response[3]->customer_id;
$po_id=$decodedJSON3->response[4]->po_id;
$company_id=$decodedJSON3->response[5]->company_id;
$totalbeforetax=$decodedJSON3->response[6]->final_total_amout;
$vehicle_id=$decodedJSON3->response[7]->vehicle_id;
	
$detailsQry="SELECT sum(total) as total,tax FROM tw_temp_po_details where po_id='".$po_id."'";
$details = $sign->FunctionJSON($detailsQry);
$decodedJSON5 = json_decode($details);
$detailstotal=$decodedJSON5->response[0]->total;
$detailstax=$decodedJSON5->response[1]->tax;

$DetailsTotalwithTax=$detailstotal * ($detailstax/100);
$Ftotal=$DetailsTotalwithTax+$detailstotal;

$dateqry="SELECT po_date FROM `tw_temp_po_info` where id='".$po_id."'";
$fetchDate=$sign->SelectF($dateqry,"po_date");
$datetime = $fetchDate;
$date = date('Y-m-d', strtotime($datetime));

$BilltoAddqry="SELECT concat(address_line1,' ',address_line2,' ',location,' ',city,' ',state,' ',country)as address ,city,pincode,state FROM tw_company_address where id='".$bill_toId."'";
$BilltoAddDetails =$sign->FunctionJSON($BilltoAddqry);
$decodedJSONBilltoAdd = json_decode($BilltoAddDetails);
$BilltoAdd=$decodedJSONBilltoAdd->response[0]->address;
$BilltoCity=$decodedJSONBilltoAdd->response[1]->city;
$Billtopincode=$decodedJSONBilltoAdd->response[2]->pincode;
$Billtostate=$decodedJSONBilltoAdd->response[3]->state;

$BilltocodeQry="SELECT id FROM `tw_state_master` where state_name='".$Billtostate."'";
$Billtostatecode=$sign->SelectF($BilltocodeQry,"id");


$ShiptoAddqry="SELECT concat(address_line1,' ',address_line2,' ',location,' ',city,' ',state,' ',country)as address,city,pincode,state FROM tw_company_address where id='".$ship_toId."'";
$ShiptoAdd = $sign->SelectF($ShiptoAddqry,"address");

$ShiptoAddDetails =$sign->FunctionJSON($BilltoAddqry);
$decodedJSONShiptoAdd = json_decode($BilltoAddDetails);
$ShiptoAdd=$decodedJSONShiptoAdd->response[0]->address;
$ShiptoCity=$decodedJSONShiptoAdd->response[1]->city;
$ShiptoCmppincode=$decodedJSONShiptoAdd->response[2]->pincode;
$ShiptoCmpstate=$decodedJSONShiptoAdd->response[3]->state;

$ShiptocodeQry="SELECT id FROM `tw_state_master` where state_name='".$ShiptoCmpstate."'";
$Shiptostatecode=$sign->SelectF($ShiptocodeQry,"id");


$CmpAddqry="SELECT concat(address_line1,' ',address_line2,' ',location,' ',city,' ',state,' ',country)as address,city,pincode,state FROM tw_company_address where id='".$company_addressId."'";
$CmpAddDetails =$sign->FunctionJSON($CmpAddqry);
$decodedJSONCmpAdd = json_decode($CmpAddDetails);
$CmpAdd=$decodedJSONCmpAdd->response[0]->address;
$CmpCity=$decodedJSONCmpAdd->response[1]->city;
$Cmppincode=$decodedJSONCmpAdd->response[2]->pincode;
$Cmpstate=$decodedJSONCmpAdd->response[3]->state;

$CmpstatecodeQry="SELECT id FROM `tw_state_master` where state_name='".$Cmpstate."'";
$Cmpstatecode=$sign->SelectF($CmpstatecodeQry,"id");


$BillCmpDetailsQry="SELECT cd.CompanyName,cdoc.document_number FROM `tw_company_details` cd INNER JOIN tw_company_document cdoc ON cd.ID=cdoc.company_id WHERE cd.ID='".$company_id."' and cdoc.document_type='".$settingValueGSTDocuments."'";
$BillCmpDetails = $sign->FunctionJSON($BillCmpDetailsQry);
$decodedJSONBillCmp = json_decode($BillCmpDetails);
$BillToCompanyName=$decodedJSONBillCmp->response[0]->CompanyName;
$BillToCompanyGST=$decodedJSONBillCmp->response[1]->document_number;

$ShipCmpDetailsQry="SELECT cd.CompanyName,cdoc.document_number FROM `tw_company_details` cd INNER JOIN tw_company_document cdoc ON cd.ID=cdoc.company_id WHERE cd.ID='".$cid."' and cdoc.document_type='".$settingValueGSTDocuments."'";
$ShipCmpDetails = $sign->FunctionJSON($ShipCmpDetailsQry);
$decodedJSONShipCmp = json_decode($ShipCmpDetails);
$ShipToCompanyName=$decodedJSONShipCmp->response[0]->CompanyName;
$ShipToCompanyGST=$decodedJSONShipCmp->response[1]->document_number;


$qry1="Select id,material_id,quantity,tax,rate,total from  tw_material_outward_individual where material_outward_id='".$outward_id."' ORDER BY id Asc";
	$retVal1 = $sign->FunctionJSON($qry1);


	$qry1="Select count(*) as cnt from tw_material_outward_individual where material_outward_id='".$outward_id."' ORDER BY id Asc";
	$retVal2 = $sign->Select($qry1);

	$decodedJSON2 = json_decode($retVal1);
	$count = 0;
	$i = 1;
	$x=$retVal2;
	$table="";
	$it=1;
	$valtotalamt=0;
	$valtotalquantity=0;
	$valtotalgst=0;
	$valtotalamount=0;
	$valfinaltotalamount=0;
	$totalbeforetax=0;
	
	while($x>=$i){
		
		
		$id = $decodedJSON2->response[$count]->id;
		$count=$count+1;
		$material_id = $decodedJSON2->response[$count]->material_id;
		$count=$count+1;
		$quantity = $decodedJSON2->response[$count]->quantity;
		$count=$count+1;
		$tax = $decodedJSON2->response[$count]->tax;
		$count=$count+1;
		$rate = $decodedJSON2->response[$count]->rate;
		$count=$count+1;
		$total = $decodedJSON2->response[$count]->total;
		$count=$count+1;

		$totalbeforetax=$totalbeforetax+$total;

	$it++;

	$i=$i+1;
	
}

$CGST="";
$SGST="";
$IGST="";
$final_total_amout="";
if($Cmpstate==$ShiptoCmpstate){
    $CGST = $totalbeforetax *(9/100);
	//$CGST = number_format(round($CGST,2),2);
	$SGST = $totalbeforetax *(9/100);
	//$SGST = number_format(round($SGST,2),2);
	$IGST="0.00";
    $final_total_amout=$totalbeforetax+$CGST+$SGST;
}
else{
	$IGST = $totalbeforetax *(18/100);
	//$IGST = number_format(round($IGST,2),2);
	$CGST="0.00";
	$SGST="0.00";
	$final_total_amout=$totalbeforetax+$IGST;
}
$vehicleDetails="SELECT vehicle_number FROM `tw_vehicle_details_master` where id='".$vehicle_id."'";
$vehicleNo=$sign->SelectF($vehicleDetails,"vehicle_number");


?>
<html>

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Trace Waste |E way bill</title>
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
	
	
		<div class="main-panel">        
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">1. E-way bill Details</h4>
                  <div class="forms-sample">
				  	<div class="form-group">
						<div class="row">
						   <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12">
								<label for="docNo">docNo <code>*</code></label>
								<input type="text" class="form-control" id="docNo" maxlength="15" value="" placeholder="Invoice Number" />
						   </div>
						   <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12">
								<label for="docDate">docDate<code>*</code></label>
								<input type="date" class="form-control" id="docDate" maxlength="50" value="" placeholder="fromTrdName" />
						   </div>
                       </div>
					</div>
				   </div>
                </div>
              </div>	
            </div>	
            </div>	
            
			 <div class="card">
                <div class="card-body">
                  <h4 class="card-title">2. Address Details</h4>
                  <div class="forms-sample">	
				<div class="form-group">
			    <div class="row">
							<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
								<label for="fromTrdName">fromTrdName<code>*</code></label>
								
									<input type="text" class="form-control" id="fromTrdName" maxlength="30" value="<?php echo $BillToCompanyName; ?>" placeholder="fromTrdName" />
									
						   </div>
							  <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
							  <label for="toTrdName">toTrdName<code>*</code></label>
								<input type="text" class="form-control" id="toTrdName" maxlength="30" value="<?php echo $ShipToCompanyName; ?>" placeholder="toTrdName" />
						   </div>
							<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
							<label for="fromTrdName">shipToTradeName<code>*</code></label>
								<input type="text" class="form-control" id="shipToTradeName" maxlength="30" value="<?php echo $ShipToCompanyName; ?>" placeholder="shipToTradeName" />
						   </div>
					</div>
				</div>	
					
					
				<div class="form-group">
			    <div class="row">
							<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
								<div class="pAddress">
									<h3 class="pAddress-header">Supplier Address<a href="#" class="primary" onclick="showModal();"></a></h3>
									<div class="pAddress-body">
										<p id="txtSupplierAddress"><?php echo $CmpAdd; ?></p>
									</div>
								</div>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
								<div class="pAddress">
									<h3 class="pAddress-header">Bill to Address<a href="#" class="primary" onclick="showBillModal();"></a></h3>
									<div class="pAddress-body">
										<p id="txtBilltoAddress"><?php echo $BilltoAdd; ?></p>
									</div>
								</div>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12">
								<div class="pAddress">
									<h3 class="pAddress-header">Send invoice to address<a href="#" class="primary" onclick="showshipModal();"></a></h3>
									<div class="pAddress-body">
										<p id="txtSendinvoiceAddress"><?php echo $ShiptoAdd; ?></p>
									</div>
								</div>
							</div>
					</div>
				</div>
					
					<div class="form-group">
						<div class="row">
						   <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12">
								<label for="fromGstin">fromGstin <code>*</code></label>
								<input type="text" class="form-control" id="fromGstin" maxlength="30" value="<?php echo $BillToCompanyGST; ?>" placeholder="Invoice Number" />
						   </div>
						   <div class="col-lg-6 col-md-6 col-sm-2 col-xs-2 col-2">
								<label for="fromPlace">fromPlace<code>*</code></label>
								<input type="text" class="form-control" id="fromPlace" maxlength="50" value="<?php echo $CmpCity; ?>" placeholder="fromPlace" />
						   </div>
						  
                       </div>
					</div>
					<div class="form-group">
						<div class="row">
					
						   
						   <div class="col-lg-4 col-md-4 col-sm-4 col-xs-2 col-2">
								<label for="actFromStateCode">actFromStateCode <code>*</code></label>
								<input type="text" class="form-control" id="actFromStateCode" maxlength="50" value="<?php echo $Cmpstatecode; ?>" placeholder="actFromStateCode" />
						   </div>
						   <div class="col-lg-4 col-md-4 col-sm-4 col-xs-2 col-2">
								<label for="fromPincode">fromPincode <code>*</code></label>
								<input type="text" class="form-control" id="fromPincode" maxlength="50" value="<?php echo $Cmppincode; ?>" placeholder="fromPincode" />
						   </div>
						   <div class="col-lg-4 col-md-4 col-sm-4 col-xs-2 col-2">
								<label for="fromStateCode">fromStateCode <code>*</code></label>
								<input type="number" class="form-control" id="fromStateCode" maxlength="50" value="<?php echo $Cmpstatecode; ?>" placeholder="fromStateCode" />
						   </div>
                       </div>
					</div>
					
					<div class="form-group">
						<div class="row">
						   <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12">
								<label for="toGstin">toGstin <code>*</code></label>
								<input type="text" class="form-control" id="toGstin" maxlength="30" value="<?php echo $ShipToCompanyGST  ?>" placeholder="toGstin" />
						   </div>
						    <div class="col-lg-6 col-md-6 col-sm-3 col-xs-3 col-3">
								<label for="toPlace">toPlace<code>*</code></label>
								<input type="text" class="form-control" id="toPlace" maxlength="50" value="<?php echo $BilltoCity; ?>" placeholder="toPlace" />
						   </div>
						  
                       </div>
					</div>
					<div class="form-group">
						<div class="row">
						  
						   <div class="col-lg-4 col-md-4 col-sm-4 col-xs-2 col-2">
								<label for="actToStateCode">actToStateCode <code>*</code></label>
								<input type="text" class="form-control" id="actToStateCode" maxlength="50" value="<?php echo $Billtostatecode; ?>" placeholder="actToStateCode" />
						   </div>
						   <div class="col-lg-4 col-md-4 col-sm-2 col-xs-2 col-2">
								<label for="toPincode">toPincode <code>*</code></label>
								<input type="text" class="form-control" id="toPincode" maxlength="50" value="<?php echo $Billtopincode; ?>" placeholder="toPincode" />
						   </div>
						   <div class="col-lg-4 col-md-4 col-sm-4 col-xs-3 col-3">
								<label for="toStateCode">toStateCode <code>*</code></label>
								<input type="text" class="form-control" id="toStateCode" maxlength="50" value="<?php echo $Billtostatecode; ?>" placeholder="toStateCode" />
						   </div>
                       </div>
					</div>
					<div class="form-group">
						<div class="row">
						   <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12">
								<label for="dispatchFromGSTIN">dispatchFromGSTIN <code>*</code></label>
								<input type="text" class="form-control" id="dispatchFromGSTIN" maxlength="30" value="<?php echo $BillToCompanyGST; ?>" placeholder="dispatchFromGSTIN" />
						   </div>
						  
						   <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12">
								<label for="shipToGSTIN">shipToGSTIN <code>*</code></label>
								<input type="text" class="form-control" id="shipToGSTIN" maxlength="50" value="<?php echo $ShipToCompanyGST;  ?>" placeholder="shipToGSTIN" />
						   </div>
                       </div>
					</div>
				</div>
              </div>	
            </div>		
           
					<!--<div class="form-group">
						<div class="row">
						   
						   <div class="col-lg-3 col-md-3 col-sm-3 col-xs-2 col-2">
								<label for="totalValue">totalValue<code>*</code></label>
								<input type="text" class="form-control" id="totalValue" maxlength="50" value="<?php echo $totalbeforetax ;?>" placeholder="totalValue" />
						   </div>
						   <div class="col-lg-3 col-md-3 col-sm-3 col-xs-2 col-2">
								<label for="cgstValue">cgstValue <code>*</code></label>
								<input type="text" class="form-control" id="cgstValue" maxlength="50" value="<?php echo $CGST; ?>" placeholder="cgstValue" />
						   </div>
						   <div class="col-lg-3 col-md-3 col-sm-3 col-xs-2 col-2">
								<label for="sgstValue">sgstValue <code>*</code></label>
								<input type="text" class="form-control" id="sgstValue" maxlength="50" value="<?php echo $SGST; ?>" placeholder="sgstValue" />
						   </div>
						   <div class="col-lg-3 col-md-3 col-sm-3 col-xs-2 col-2">
								<label for="igstValue">igstValue <code>*</code></label>
								<input type="number" class="form-control" id="igstValue" maxlength="50" value="<?php echo $IGST; ?>" placeholder="igstValue" />
						   </div>
                       </div>
						</div>	-->
						<br>
				<div class="card">
                 <div class="card-body">
                  <h4 class="card-title">3. Transportation Details</h4>
                  <div class="forms-sample">	
				   <div class="form-group">	
					<div class="form-group">
						<div class="row">
						 <!--  <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 col-3">
								<label for="totInvValue">totInvValue <code>*</code></label>
								<input type="text" class="form-control" id="totInvValue" maxlength="30" value="<?php echo $final_total_amout; ?>" placeholder="totInvValue" /> 
						   </div> -->
						   <div class="col-lg-4 col-md-4 col-sm-4 col-xs-2 col-2">
								<label for="transDistance">transDistance<code>*</code></label>
								<input type="text" class="form-control" id="transDistance" maxlength="50" value="650" placeholder="transDistance" />
						   </div>
						   <div class="col-lg-4 col-md-4 col-sm-4 col-xs-3 col-3">
								<label for="transporterId">transporterId <code>*</code></label>
								<input type="text" class="form-control" id="transporterId" maxlength="50" value="05AAACG0904A1ZL" placeholder="transporterId" />
						   </div>
						   <div class="col-lg-4 col-md-4 col-sm-4 col-xs-3 col-3">
								<label for="vehicleNo">vehicleNo <code>*</code></label>
								<input type="text" class="form-control" id="vehicleNo" maxlength="50" value="<?php echo $vehicleNo; ?>" placeholder="vehicleNo" />
						   </div>
						   
                       </div>
					</div>
					
				</div>
              </div>	
            </div>		
           </div>			
				<!-- 	<div class="form-group">
						<div class="row">
						   <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 col-12">
								<label for="productName">productName <code>*</code></label>
								<input type="text" class="form-control" id="productName" maxlength="30" value="Apple" placeholder="productName" />
						   </div>
						   <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 col-2">
								<label for="productDesc">productDesc<code>*</code></label>
								<input type="text" class="form-control" id="productDesc" maxlength="50" value="Apple" placeholder="productDesc" />
						   </div>
						   <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 col-2">
								<label for="hsnCode">hsnCode<code>*</code></label>
								<input type="text" class="form-control" id="hsnCode" maxlength="50" value="1001" placeholder="hsnCode" />
						   </div>
						   <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 col-2">
								<label for="quantity">quantity <code>*</code></label>
								<input type="text" class="form-control" id="quantity" maxlength="50" value="400" placeholder="quantity" />
						   </div>
						   <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 col-2">
								<label for="qtyUnit">qtyUnit <code>*</code></label>
								<input type="text" class="form-control" id="qtyUnit" maxlength="50" value="BOX" placeholder="qtyUnit" />
						   </div>
						   <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 col-2">
								<label for="taxableAmount">taxableAmount <code>*</code></label>
								<input type="text" class="form-control" id="taxableAmount" maxlength="50" value="5000" placeholder="taxableAmount" />
						   </div>  
                       </div>
					</div>  -->
					
					<br>
			    <div class="card">
                 <div class="card-body">
                  <h4 class="card-title">4. Goods Details</h4>
                  <div class="forms-sample">	
				   <div class="form-group">	
					<div class="form-group">
						<div class="row">
					<div class="table-responsive">
						<table id="tableData" class="table" >
						 
						</table>
					</div>
					
					<div>
					<br>
					<p>&ensp; &ensp; Tot.Tax'ble Amt-:<b><span id="TaxbleAMt" value=""></span></b>&ensp; &ensp;  CGST Amt-:<b><span id="CGST"><?php echo $CGST; ?></span></b> &ensp; &ensp;SGST Amt-:<b><span id="SGST"><?php echo $SGST ?></span></b>&ensp; &ensp; IGST Amt-:<b><span id="IGST"><?php echo $IGST; ?></span></b>&ensp; &ensp; CESS Amt-:<b><span>0.00</span></b> &ensp; &ensp;CESS Non.Advol Amt-:<b>0.00</b>  </p>
					<p>&ensp; &ensp; Other Amt-:<b><span id="Other">0.00</span></b>&ensp; &ensp;Total Inv.Amt-:<b><span id="InvAmt">0.00</span></b></p>
					</div>
					
				  </div>
				</div>
					
				</div>
              </div>	
            </div>		
           </div>
					
					
					<button type="button" class="btn btn-success"  id="btnAddrecord" onclick="EWAYBILL();">Add</button> 
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

 $(document).ready(function(){
	showDATA();
	
}); 
function formatDate(date) {
     var d = new Date(date),
         month = '' + (d.getMonth() + 1),
         day = '' + d.getDate(),
         year = d.getFullYear();

     if (month.length < 2) month = '0' + month;
     if (day.length < 2) day = '0' + day;

     return [day, month, year].join('/');
 }
 function showModal(){
		jQuery.noConflict();
		$("#ModalAddress").modal("show");
	}
function showBillModal(){
		jQuery.noConflict();
		$("#ModalBillAddress").modal("show");
	}
function showshipModal(){
	jQuery.noConflict();
	$("#ModalShipAddress").modal("show");
}	
 
function SubmitADD(){
	
	$("#txtSupplierAddress").attr('readonly', true);
}
var BILLTOADDRESS_ID='' ;
function saveBillToAddress(id,address){
	$("#txtBilltoAddress").html(address);
	 BILLTOADDRESS_ID =id;
	$("#txtBilltoAddress").attr('readonly', true);
   $("#txtBilltoAddressId").attr('readonly', true);
	
}
var SHIPTOADDRESS_ID='' ;
function saveShipToAddress(id,address){
$("#txtSendinvoiceAddress").html(address);
    SHIPTOADDRESS_ID=id;
$("#txtSendinvoiceAddressID").attr('readonly', true);
}

	var supplier_address_id=0;
	
 function saveAdd(id,address){
		$("#txtSupplierAddress").html(address);
	  supplier_address_id= id;
	 var selectedValue = document.getElementById('radAddress').value;       
}
 var totalValue1="";
 
 var totInvValue1="";
 var hsnCode1="";
 var productName1="";
 var quantity1="";
 function showDATA(){
	 var varoutward_id = "<?php echo $outward_id; ?>";
	$.ajax({
          type:"POST",
          url:"apigetProductDetailsForEway.php",
		  dataType: 'JSON',
          data:{outward_id:varoutward_id},
          success:function(response){
			  console.log(response);
			// $("#tableData").html(response);
			 $("#tableData").html(response[0]);
			 txtFinalTotalAmount=response[1];
			$("#TaxbleAMt").html(response[2]);
			$("#InvAmt").html(response[4]);
			
			
			
			totalValue1=response[2];
			console.log(totalValue1);
			totInvValue1=response[4];
			console.log(totInvValue1);
			hsnCode1=response[5];
			productName1=response[6];
			quantity1=response[7];
          }
      });
 } 
 
 


 
 function authentication(){
	 alert("hello");
	 var username = "05AAACH6188F1ZM";
	 var password = "abc123@@";
	 $.ajax({
			type: 'GET',
			url:"https://api.mastergst.com/ewaybillapi/v1.03/authenticate?email=dhiraj.manwar23@gmail.com&username=05AAACH6188F1ZM&password=abc123@@&action=ACCESSTOKEN",
			beforeSend: function (xhr){ 
				xhr.setRequestHeader('Authorization',"Basic " + btoa(username + ":" + password)); 
			},
			error: function(e) {
				console.log(e);
				console.log("HelloE1");
			  }, 
			  success: function(e) {
				console.log(e);
				console.log("HelloE");
			  },
			 dataType: 'json',
			 contentType: 'application/json',
			 username: '05AAACH6188F1ZM',
			 password: 'abc123@@',
			 xhrFields: {
				withCredentials: true,
			  },
           
			 async:true,
             crossDomain:true,
			  headers: {
				        //'Accepts': 'application/json',
						'ip_address': '192.168.100.167',
				        'client_id': '2ae9fc68-a67c-4673-a144-92f9ea8d9f3e',
						'client_secret': '94858598-9201-4676-bd95-304c5469c798',
						'gstin': '05AAACH6188F1ZM',
                        "crossDomain":true,
                        "Access-Control-Allow-Origin": "*",
                        'proxy':'http://119.18.54.146:63343',
						'port': '8080'
                    
					
				},
				 success: function( data, textStatus, jQxhr ){
				 var myJSON=jQxhr['responseText'];
				 console.log(myJSON);
				}	 
		});		
	 
 }
 
 
 function EWAYBILL(){

var docNo= ""+$("#docNo").val();
var docDate= ""+formatDate($("#docDate").val());
var fromGstin= ""+$("#fromGstin").val();
var fromTrdName=""+$("#fromTrdName").val();
var fromAddr1="<?php echo $CmpAdd; ?>";
var fromAddr2="";
var fromPlace=""+$("#fromPlace").val();
var actFromStateCode=$("#actFromStateCode").val();
var fromStateCode=$("#fromStateCode").val();
var fromPincode=$("#fromPincode").val();
var toGstin=""+$("#toGstin").val();
var toTrdName=""+$("#toTrdName").val();
var toAddr1="<?php echo $BilltoAdd; ?>";
var toAddr2="";
var toPlace=""+$("#toPlace").val();
var actToStateCode=$("#actToStateCode").val();
var toPincode=$("#toPincode").val();
var toStateCode=$("#toStateCode").val();
var dispatchFromGSTIN=""+$("#dispatchFromGSTIN").val();
var dispatchFromTradeName=""+$("#dispatchFromTradeName").val();
var shipToGSTIN=""+$("#shipToGSTIN").val();
var shipToTradeName=""+$("#shipToTradeName").val();
var totalValue=totalValue1;
var cgstValue="<?php echo $CGST ?>";
var sgstValue="<?php echo $SGST ?>";
var igstValue="<?php echo $IGST ?>";
var totInvValue=totInvValue1;
var transDistance=$("#transDistance").val();
var transporterId=$("#transporterId").val();
var vehicleNo=$("#vehicleNo").val();
var productName=""+productName1;
var productDesc=""+productName1;
var hsnCode=hsnCode1;
var quantity=quantity1;
var qtyUnit="BOX";
var taxableAmount="<?php echo $totalbeforetax; ?>";

			var SendInfo = { 
              
		                        
			  "subSupplyType": "5",
			  "supplyType": "O",
			  "subSupplyDesc": "",
			  "docType": "CHL",
			  "docNo": docNo,
			  "docDate": docDate,
			  "fromGstin": fromGstin,
			  "fromTrdName": fromTrdName,
			  "fromAddr1": fromAddr1,
			  "fromAddr2": fromAddr2,
			  "fromPlace": fromPlace,
			  "actFromStateCode": parseInt(actFromStateCode),
			  "fromPincode": parseInt(fromPincode),
			  "fromStateCode": parseInt(fromStateCode),
			  "toGstin": toGstin,
			  "toTrdName": toTrdName,
			  "toAddr1": toAddr1,
			  "toAddr2": toAddr2,
			  "toPlace": toPlace,
			  "toPincode": parseInt(toPincode),
			  "actToStateCode": parseInt(actToStateCode),
			  "toStateCode": parseInt(toStateCode),
			  "transactionType": 4,
			  "dispatchFromGSTIN": dispatchFromGSTIN,
			  "dispatchFromTradeName": dispatchFromTradeName,
			  "shipToGSTIN": shipToGSTIN,
			  "shipToTradeName": shipToTradeName,
			  "totalValue": parseInt(totalValue),
			  "cgstValue": parseInt(cgstValue),
			  "sgstValue": parseInt(sgstValue),
			  "igstValue": parseInt(igstValue),
			  "cessValue": 400.56,
			  "cessNonAdvolValue": 400,
			  "totInvValue": parseInt(totInvValue),
			  "transMode": "1",
			  "transDistance": transDistance,
			  "transporterName": "",
			  "transporterId": transporterId,
			  "transDocNo": "12",
			  "transDocDate": "",
			  "vehicleNo": vehicleNo,
			  "vehicleType": "R",
			  "itemList": [
				{
				  "productName": productName,
				  "productDesc": productDesc,
				  "hsnCode": parseInt(hsnCode),
				  "quantity": parseInt(quantity),
				  "qtyUnit": qtyUnit,
				  "taxableAmount": parseInt(taxableAmount),
				  "sgstRate": 0,
				  "cgstRate": 0,
				  "igstRate": 3,
				  "cessRate": 0
				}
			  ]
			

		
}
        alert("hello2");
		$.ajax({
			type: 'POST',
			url:"https://api.mastergst.com/ewaybillapi/v1.03/ewayapi/genewaybill?email=dhiraj.manwar23%40gmail.com",
			data:JSON.stringify(SendInfo),
			 dataType: 'json',
			 contentType: 'application/json',
			 username: '05AAACH6188F1ZM',
			 password: 'abc123@@',
			 xhrFields: {
				withCredentials: true,
			  },

			 async:true,
             crossDomain:true,
			  headers: {
				        //'Accepts': 'application/json',
						'authtoken': '34579835-8668-4560-a49c-c48eb1cc4952',
				        'client_id': '2ae9fc68-a67c-4673-a144-92f9ea8d9f3e',
						'client_secret': '94858598-9201-4676-bd95-304c5469c798',
						'gstin': '05AAACH6188F1ZM',
						'ip_address': '192.168.100.167',
                        "crossDomain":true,
                        "Access-Control-Allow-Origin": "*",
                        'proxy':'http://119.18.54.146:63343',
						'port': '8080'
                    
					
				},
				
				
				
				 success: function( data, textStatus, jQxhr ){
				   
					var myJSON=jQxhr['responseText'];
					const myObj = JSON.parse(myJSON);
					var x = myObj["status_cd"];
					console.log(myJSON);
					if(x==1){  //sucess;
					console.log(myJSON);
					var data = myObj["data"];

					var ewayBillNo=data['ewayBillNo'];
					var ewayBillDate=data['ewayBillDate'];
					var validUpto=data['validUpto'];
					var alert=data['alert'];
					console.log(ewayBillNo);
					console.log(ewayBillDate);
					console.log(validUpto);
					console.log(alert);
					
					}
					else{
						//error;
					
						var msg = myObj["error"];
						
						var myJSON1=msg['message'];
						const myObj1 = JSON.parse(myJSON1);
						var code = myObj1["errorCodes"];
						if(code==238){
							authentication();
						}
						else{
							//EWAYBILL();
						console.log(code);
						 console.log(myJSON);
						}
					
					}
				 },
				// const response = await fetch(url);
				// if (response.status == 238 ) {
				  // const jsonResponse = await response.json();
				  // console.log(jsonResponse);
				  // console.log("Response");
				// } else {
				  // // Handle errors
				  // console.log(response.status, response.statusText);
				// }			
					
					
		});
}					
</script>
</body>
</html>