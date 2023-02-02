<?php 
session_start();
if(!isset($_SESSION["employee_id"])){
	header("Location:pgEmployeeLogIn.php");
}
// Include class definition
include_once "function.php";
$sign=new Signup();
include_once "commonFunctions.php";
$commonfunction=new Common();
include_once "Qrfunction.php";
$qrfunction=new TwQr();

$session_company_id = $_SESSION["company_id"];

$Company_Logo="";
$requestid=$_REQUEST["id"];


date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");

$settingValueUserImagePathOther= $commonfunction->getSettingValue("UserImagePathOther");
$settingValueCompanyImage= $commonfunction->getSettingValue("Company Image");
$settingValueUserImagePathVerification= $commonfunction->getSettingValue("UserImagePathVerification");
$settingValueUserPrimaryEmail= $commonfunction->getSettingValue("Primary Email");
$settingValueTaxInvoice= $commonfunction->getSettingValue("DocTaxInvoice");

$qry="select id,invoice_number,delivery_challan,obp_number,centre_outward_number,invoice_date,company_address_id,lr_no,lr_date,supplier_name,bill_to_address,bill_to_gst_number,bill_to_pincode,bill_to_state,vehicle_no,mode_of_transport,ship_to_address,ship_to_gst,ship_to_pincode,ship_to_state,remark,termsofpayment,final_amount from tw_thirdparty_invoice where id='".$requestid."' order by id Desc";
$retVal = $sign->FunctionJSON($qry);
$decodedJSON = json_decode($retVal);
$id = $decodedJSON->response[0]->id;
$invoice_number = $decodedJSON->response[1]->invoice_number;
$delivery_challan = $decodedJSON->response[2]->delivery_challan;
$obp_number = $decodedJSON->response[3]->obp_number;
$centre_outward_number = $decodedJSON->response[4]->centre_outward_number;
$invoice_date = $decodedJSON->response[5]->invoice_date;
$company_address_id = $decodedJSON->response[6]->company_address_id;
$lr_no = $decodedJSON->response[7]->lr_no;
$lr_date = $decodedJSON->response[8]->lr_date;
$supplier_name = $decodedJSON->response[9]->supplier_name;
$bill_to_address = $decodedJSON->response[10]->bill_to_address;
$bill_to_gst_number = $decodedJSON->response[11]->bill_to_gst_number;
$bill_to_pincode = $decodedJSON->response[12]->bill_to_pincode;
$bill_to_state = $decodedJSON->response[13]->bill_to_state;
$vehicle_no = $decodedJSON->response[14]->vehicle_no;
$mode_of_transport = $decodedJSON->response[15]->mode_of_transport;
$ship_to_address = $decodedJSON->response[16]->ship_to_address;
$ship_to_gst = $decodedJSON->response[17]->ship_to_gst;
$ship_to_pincode = $decodedJSON->response[18]->ship_to_pincode;
$ship_to_state = $decodedJSON->response[19]->ship_to_state;
$remark = $decodedJSON->response[20]->remark;
$payment_term = $decodedJSON->response[21]->termsofpayment;
$final_amount = $decodedJSON->response[22]->final_amount;

$queryTnc="Select tnc_value from tw_tnc where tnc_for='invoice' and company_id='".$session_company_id."'";
$Tnc=$sign->SelectF($queryTnc,"tnc_value");

$SelectLoginCompAdd="Select ca.address_line1,ca.address_line2,ca.location,ca.pincode,ca.city,ca.state,ca.country,cd.CompanyName,cd.Company_Logo from tw_company_address ca INNER JOIN tw_company_details cd ON cd.ID=ca.company_id where ca.company_id='".$session_company_id."'";
$retValLoginCompAdd = $sign->FunctionJSON($SelectLoginCompAdd);
$decodedJSON2 = json_decode($retValLoginCompAdd);
$address_line1 = $decodedJSON2->response[0]->address_line1;
$address_line2 = $decodedJSON2->response[1]->address_line2;
$location = $decodedJSON2->response[2]->location;
$pincode = $decodedJSON2->response[3]->pincode;
$city = $decodedJSON2->response[4]->city;
$state = $decodedJSON2->response[5]->state;
$country = $decodedJSON2->response[6]->country;
$CompanyName = $decodedJSON2->response[7]->CompanyName;
$Company_Logo = $decodedJSON2->response[8]->Company_Logo;


$QueryEmail="Select value from tw_company_contact where contact_field='".$settingValueUserPrimaryEmail."' and company_id='".$session_company_id."'";
$Email=$sign->SelectF($QueryEmail,"value");


if($Company_Logo==""){
  $Company_Logo = $settingValueUserImagePathOther.$settingValueCompanyImage;
}
else{
	$Company_Logo = $settingValueUserImagePathVerification.$Email."/".$Company_Logo;
}

//$BilltoCompanyDetails="Select CompanyName from tw_company_details where ";

$Signatureqry="select approved_by,prepared_by,for_company from tw_digital_signature  where  form_type='".$settingValueTaxInvoice."' AND company_id='".$session_company_id."'";
	$retvalSignatureQry=$sign->FunctionJSON($Signatureqry);
	$decodedJSONSign = json_decode($retvalSignatureQry);
	$approved_by=$decodedJSONSign->response[0]->approved_by;
	$prepared_by=$decodedJSONSign->response[1]->prepared_by;
	$for_company=$decodedJSONSign->response[2]->for_company;
	
	if($approved_by!==""){
		    $approved_by_img="<img src=".$settingValueUserImagePathVerification.$email."/".$approved_by." width='70px' />";
	}
	else{
		$approved_by_img="";
	}
	if($prepared_by!==""){
		$prepared_by_img="<img src=".$settingValueUserImagePathVerification.$email."/".$prepared_by." width='70px' />";
	}
	else{
		$prepared_by_img="";
	}
	if($for_company!==""){
		 $for_company_img="<img src=".$settingValueUserImagePathVerification.$email."/".$for_company." width='70px' />";
	}
	else{
		$for_company_img="";
	}


?>
<html>
<head>
<title>Tax Invoice</title>
<link rel="stylesheet" href="../assets/css/custom/style.css" />
<link rel="shortcut icon" href="../assets/images/favicon.png" />
</head>

<body>
<div id="printarea">
<table width="100%" class="printtbl">
<table class="printtbl-noborder">
	<tr>
	<td width="75px" class="left-text"><img src="<?php echo $Company_Logo; ?>" width="70px" /></td>
	<td> <strong><?php echo $CompanyName;?></strong><br><?php echo $address_line1." ".$address_line2." ".$location." ".$pincode." ".$city."  ".$state." ".$country;?><br>State code : <?php echo $ship_to_state; ?><br>GSTIN/UIN: <?php echo $ship_to_gst;?><br>E-Mail: <?php echo $Email;?> </td>
	<td width="90px" class="right-text"><img src="<?php echo $valqrfunction; ?>" width="50px" /></td>
	</tr>
	</table>


</table>


<table class="printtbl">

<tr>
			<td width="75%"><h2> Tax Invoice </h2></td>
			<td width="25%" class="right-text">
				Invoice Date: <strong><?php echo date("d-m-Y ",strtotime($invoice_date));?></strong><br>Invoice Number: <strong><?php echo $invoice_number;?> 
			</td>
		</tr>


<tr>
</table>
<table style="width:100%" class="printtbl">
<tr>
<th width="50%"; > Bill to Party </th>
<th width="50%"; > Ship to Party </th>
</tr>
</table> 




<table style="width:100%" class="printtbl">
<tr>
<td width="50%"> <strong> Name: <?php echo $supplier_name; ?> </strong><br>
<?php echo $bill_to_address." ".$bill_to_pincode." ".$bill_to_state;?><br>State Code: <?php echo $bill_to_state; ?> <br>GSTIN. <?php echo $bill_to_gst_number; ?> </td>

<td width="50%"><strong> Name: <?php echo $supplier_name; ?> </strong><br>
<?php echo $ship_to_address.", ".$ship_to_state.", ".$ship_to_pincode;?> <br>State Code: <?php echo $ship_to_state; ?><br>GSTIN: <?php echo $ship_to_gst; ?></td>
</tr>
</table> 


<table class="printtbl" style="width:100%";>
		<tr>
			<td width="50%"> OBP Certificate Number: <strong><?php echo " ".$obp_number; ?></strong><br>Centre Outward Slip Number: <strong><?php echo " ".$centre_outward_number; ?></strong><br>Terms of Payment: <strong><?php echo $payment_term ?></strong></td>
		<td width="50%">Delivery Challan: <strong><?php echo " ".$delivery_challan; ?></strong><br>Challan Date: <strong><?php echo date("d-m-Y ",strtotime($invoice_date));?></strong><br> Vehicle Number: <strong><?php echo $vehicle_no; ?></strong> | Mode of Transport: <strong><?php echo $mode_of_transport; ?></strong>
        </td>		
		
		
		</tr>
	</table>
<!--<td> Transport Mode :<!--<?php echo $mode_of_transport; ?> </td>-->



<table style="width:100%" class="printtbl">
<tr>

</tr>
</table> 

<table style="width:100%" class="printtbl right-text">
<tr>
<!--<th width="100%"> Eway Bill Number: <?php //echo $document_value; ?> | Dated <?php //echo date("d-m-Y", strtotime($created_on )); ?> </th>-->
</table>
  

<table style="width:100%" class="printtbl">
<tr>
<td width="5%" style="text-align:Center;"> # </td>
<td width="35%" style="text-align:Center;"> Product Description </td>
<td width="10%" style="text-align:Center;"> HSN code </td>
<td width="10%" style="text-align:Center;"> Quantity </td>
<td width="5%" style="text-align:Center;"> UOM </td>
<td width="15%" style="text-align:Center;"> Rate </td> 
<td width="20%" style="text-align:Center;"> Amount </td>
<tr>

<?php 

$QueryMaterialsDetails="Select material_id,quantity,hsn,rate,total from tw_tax_invoice_details where invoice_id='".$id."'";
$retValMaterialsDetails = $sign->FunctionJSON($QueryMaterialsDetails);

$qry1="Select count(*) as cnt from tw_tax_invoice_details where invoice_id='".$id."'";
$retVal1 = $sign->Select($qry1);

$decodedJSON1 = json_decode($retValMaterialsDetails);
$count = 0;
$totalbeforetax = 0;
$i = 1;
$x=$retVal1;
$it=1;
while($x>=$i){
		
	$material_id = $decodedJSON1->response[$count]->material_id;
	$count=$count+1;
	$quantity = $decodedJSON1->response[$count]->quantity;
	$count=$count+1;
	$hsn = $decodedJSON1->response[$count]->hsn;
	$count=$count+1;
	$rate = $decodedJSON1->response[$count]->rate;
	$count=$count+1;
	$total = $decodedJSON1->response[$count]->total;
	$count=$count+1;
	
	$totalbeforetax=$totalbeforetax+$total;

	$qry2="select id,product_name,hsn,uom from tw_product_management where id='".$material_id."' order by id Asc";
	$retVal2 = $sign->FunctionJSON($qry2);
	$decodedJSON = json_decode($retVal2);
	$id = $decodedJSON->response[0]->id;
	$product_name = $decodedJSON->response[1]->product_name;
	$Phsn = $decodedJSON->response[2]->hsn;
	$uom = $decodedJSON->response[3]->uom;

	$qry3="select name from tw_unit_of_measurement where id='".$uom."' order by id Desc";
	$retVal3 = $sign->SelectF($qry3,"name");


	?>
	

	<tr>
	<td class="center-text"><?php echo $it; ?></td>
	<td><?php echo $product_name; ?> </td>
	<td class="center-text"><?php echo $Phsn; ?> </td>
	
	<td class="right-text"><?php echo number_format($quantity,2); ?> </td>
	<td class="center-text"><?php echo $retVal3; ?></td>
	<td style= "text-align:right;">&#8377; <?php echo number_format(round($rate,2),2);?></td> 
	<td style="text-align:right;">&#8377; <?php echo number_format(round($total,2),2);?> </td>
	</tr>
	<?php 
	
	$it++;

	$i=$i+1;
}


$CGST="";
$SGST="";
$IGST="";
$final_total_amout="";
if($ship_to_state==$bill_to_state){
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
$amountInWords= $commonfunction->amountInWords(round($final_total_amout,0));	
?>

</table>

<table style="width:100%" class="printtbl">

<tr>
	<td  class="Right-text top-align">Total Quantity</td>
	<td width="10%" class="Right-text top-align"><strong><?php echo $quantity;?></strong></td> 
	<td width="20%" class="Right-text top-align"> Sub Total </td> 
	<td width="20%" style= "text-align:right;" >&#8377; <?php echo number_format(round($totalbeforetax,2),2);?> </td>
</tr>

<tr>
	<td width="50%" class="left-text top-align" rowspan="5"> Remarks: <br><?php echo $remark;?></td>	
	
	<td width="15%" colspan="2" class="Right-text top-align">Add: CGST 9 %</td> 
	<td width="15%" class="Right-text top-align">  &#8377; <?php echo number_format(round($CGST,2),2);?></td>
	
</tr>
<tr>

	<td width="15%" colspan="2" class="Right-text top-align">Add: SGST 9%</td>
	<td style= "text-align:right;"> &#8377; <?php echo number_format(round($SGST,2),2);?></td>
</tr>
<tr>

	<td width="15%" colspan="2"class="Right-text top-align">Add: IGST 18%</td>
	<td style= "text-align:right;"> &#8377; <?php echo number_format(round($IGST,2),2); ?></td>
</tr>

<!--  <tr>
	<td>Total Amount after Tax</td>
	<td style= "text-align:right;"> &#8377; <?php echo number_format(round($final_total_amout,2),2);?></td>
</tr>
-->
<?php  $Temproundoff=0; 
       $finalRondoff=round($final_total_amout,0) ?>
<?php  $Temproundoff=$finalRondoff-$final_total_amout; 
 ?>
<tr>

	<td width="15%" colspan="2" class="Right-text top-align">Round Off</td>
	<td style= "text-align:right;"> &#8377; <?php   echo number_format($Temproundoff,2); ?></td>
</tr>
<tr>

	<td width="15%" colspan="2" class="Right-text top-align"><strong>Total</strong></td>
	<th style= "text-align:right;"> &#8377; <?php echo number_format(round($final_total_amout,0),2);?></th>
</tr>

</table>

<table width="100%" class="printtbl">



<tr>
<td>Amount In Words: <b><?php echo $amountInWords; ?></b></td>
</tr> 

</table> 

 <table style="width:100%" class="printtbl">
<tr>
<td width="50%"; class="left-text top-align"> Terms & Conditions:<?php echo $Tnc;?> </td>

<td width="20%" class="right-text top-align"> For <?php echo $supplier_name;?><br><?php echo $for_company_img; ?><br><br><br><strong>Authorised signatory</strong></td>
</tr>
</table> 
 </div>
<br>
	<div class="center-text">
	<button id="printPageButton" class="noPrint" onclick="printDiv('printarea');">Print</button>
	</div>

<script type="text/javascript">
function printDiv(printarea) {
    var printContents = document.getElementById(printarea).innerHTML;
    var originalContents = document.body.innerHTML;
	document.body.innerHTML = printContents;
	window.print();
	document.body.innerHTML = originalContents;
}
</script>
</body>
</html>











