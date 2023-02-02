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
$outwardid=$_REQUEST["voutward_id"];
//--Check Valid Data STart
/* $qryCheckDataCount="SELECT count(*) as cnt FROM `tw_material_outward` WHERE (customer_id='".$session_company_id."' OR company_id='".$session_company_id."') and id ='".$outwardid."'";
$retValCheckDataCount = $sign->select($qryCheckDataCount);

$qryCheckDataCountID="SELECT count(*) as cnt FROM `tw_tax_invoice` WHERE id ='".$requestid."'";
$retValCheckDataCountID = $sign->select($qryCheckDataCountID);

if(($retValCheckDataCount > 0) || ($retValCheckDataCountID > 0)){
	
	$qryCheckData="select customer_id,company_id from tw_material_outward where id='".$outwardid."'";
	$retValCheckData = $sign->FunctionJSON($qryCheckData);
	$decodedJSON1 = json_decode($retValCheckData);
	$retValCheckDataCustomer = $decodedJSON1->response[0]->customer_id;
	$retValCheckDatacompany_id = $decodedJSON1->response[1]->company_id;
	
	if(($retValCheckDataCustomer==$session_company_id)&&($retValCheckDatacompany_id==$session_company_id)){
		//echo "In";
	}
	else{
	//	echo "Out";
		header("Location:pgNodata.php");
	}
}
else{
	header("Location:pgNodata.php");
} */
//--Check Valid Data End
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");
$settingValueUserImagePathOther= $commonfunction->getSettingValue("UserImagePathOther");
$settingValueCompanyImage= $commonfunction->getSettingValue("Company Image");
$settingValueUserImagePathVerification= $commonfunction->getSettingValue("UserImagePathVerification");
$settingValueUserPrimaryEmail= $commonfunction->getSettingValue("Primary Email");
$settingValueTaxInvoice= $commonfunction->getSettingValue("DocTaxInvoice");
$settingValueUserCompanyImage= $commonfunction->getSettingValue("Company Image");
$settingValueVerifyLink= $commonfunction->getSettingValue("VerifyLink");

$qry="select id,invoice_number,delivery_challan_no,obp_certificate_no,centre_outward_slip_no,invoice_date,remark,company_id,created_by ,termsofpayment from tw_tax_invoice where outward_id='".$outwardid."' and id='".$requestid."' order by id Desc";
$retVal = $sign->FunctionJSON($qry);
$decodedJSON = json_decode($retVal);
$id = $decodedJSON->response[0]->id;
$invoice_number = $decodedJSON->response[1]->invoice_number;
$delivery_challan_no = $decodedJSON->response[2]->delivery_challan_no;
$obp_certificate_no = $decodedJSON->response[3]->obp_certificate_no;
$centre_outward_slip_no = $decodedJSON->response[4]->centre_outward_slip_no;
$invoice_date = $decodedJSON->response[5]->invoice_date;
$remark = $decodedJSON->response[6]->remark;
$company_id = $decodedJSON->response[7]->company_id;
$created_by = $decodedJSON->response[8]->created_by;
$payment_term = $decodedJSON->response[9]->termsofpayment;


$qry1="select id,company_address,bill_to,ship_to,final_total_amout,vehicle_id,po_id,total_quantity from tw_material_outward where id='".$outwardid."' order by id Desc";
$retVal1 = $sign->FunctionJSON($qry1);
$decodedJSON1 = json_decode($retVal1);
$id = $decodedJSON1->response[0]->id;
$company_address = $decodedJSON1->response[1]->company_address;
$bill_to = $decodedJSON1->response[2]->bill_to;
$ship_to = $decodedJSON1->response[3]->ship_to;
$final_total_amout = $decodedJSON1->response[4]->final_total_amout;
$vehicle_id = $decodedJSON1->response[5]->vehicle_id;
$po_id = $decodedJSON1->response[6]->po_id;
$total_quantity = $decodedJSON1->response[7]->total_quantity;

$company_address_qry="SELECT ca.address_line1 as address_line1,ca.address_line2 as address_line2,ca.location as location,ca.pincode as pincode,ca.city as city,ca.state as state,ca.country as country,cd.CompanyName as CompanyName,cd.ID,cd.Company_Logo,cc.value FROM tw_company_address ca, tw_company_details cd inner join tw_company_contact cc on cd.ID=cc.company_id where ca.company_id=cd.ID and ca.id='".$company_address."' AND cc.contact_field='".$settingValueUserPrimaryEmail."' ";
$ret_company_address = $sign->FunctionJSON($company_address_qry);
$decodedJSON5 = json_decode($ret_company_address);


$caddress_line1 = $decodedJSON5->response[0]->address_line1;
$caddress_line2 = $decodedJSON5->response[1]->address_line2;
$clocation = $decodedJSON5->response[2]->location;
$cpincode = $decodedJSON5->response[3]->pincode;
$ccity = $decodedJSON5->response[4]->city;
$cstate = $decodedJSON5->response[5]->state;
$ccountry = $decodedJSON5->response[6]->country;
$cCompanyName = $decodedJSON5->response[7]->CompanyName;
$cCompanyID = $decodedJSON5->response[8]->ID;
$Company_Logo = $decodedJSON5->response[9]->Company_Logo;
$email = $decodedJSON5->response[10]->value;


if($Company_Logo==""){
  $Company_Logo = $settingValueUserImagePathOther.$settingValueCompanyImage;
}
else{
	$Company_Logo = $settingValueUserImagePathVerification.$email."/".$Company_Logo;
}


$qry10="select state_code from tw_state_master where state_name= '".$cstate."'";
$cstate_code = $sign->SelectF($qry10, "state_code");


$bill_to_address_qry="SELECT ca.address_line1 as address_line1,ca.address_line2 as address_line2,ca.location as location,ca.pincode as pincode,ca.city as city,ca.state as state,ca.country as country,cd.CompanyName as CompanyName,cd.ID as ID FROM tw_company_address ca, tw_company_details cd where ca.company_id=cd.ID and ca.id='".$bill_to."'";
$ret_bill_to_address = $sign->FunctionJSON($bill_to_address_qry);
$decodedJSON1 = json_decode($ret_bill_to_address);

$baddress_line1 = $decodedJSON1->response[0]->address_line1;
$baddress_line2 = $decodedJSON1->response[1]->address_line2;
$blocation = $decodedJSON1->response[2]->location;
$bpincode = $decodedJSON1->response[3]->pincode;
$bcity = $decodedJSON1->response[4]->city;
$bstate = $decodedJSON1->response[5]->state;
$bcountry = $decodedJSON1->response[6]->country;
$bCompanyName = $decodedJSON1->response[7]->CompanyName;
$bCompanyID = $decodedJSON1->response[8]->ID;

$qry10="select state_code from tw_state_master where state_name= '".$bstate."'";
$bstate_code = $sign->SelectF($qry10, "state_code");

$ship_to_address_qry="SELECT ca.address_line1 as address_line1,ca.address_line2 as address_line2,ca.location as location,ca.pincode as pincode,ca.city as city,ca.state as state,ca.country as country,cd.CompanyName as CompanyName,cd.ID as ID FROM tw_company_address ca, tw_company_details cd where ca.company_id=cd.ID and ca.id='".$ship_to."'";
$ret_ship_to_address = $sign->FunctionJSON($ship_to_address_qry);
$decodedJSON1 = json_decode($ret_ship_to_address);
$saddress_line1 = $decodedJSON1->response[0]->address_line1;
$saddress_line2 = $decodedJSON1->response[1]->address_line2;
$slocation = $decodedJSON1->response[2]->location;
$spincode = $decodedJSON1->response[3]->pincode;
$scity = $decodedJSON1->response[4]->city;
$sstate = $decodedJSON1->response[5]->state;
$scountry = $decodedJSON1->response[6]->country;
$sCompanyName = $decodedJSON1->response[7]->CompanyName;
$sCompanyID = $decodedJSON1->response[8]->ID;

$qry11="select state_code from tw_state_master where state_name= '".$sstate."'";
$sstate_code = $sign->SelectF($qry11, "state_code");

$CompanyStateqry="select state_code from tw_state_master where state_name= '".$cstate."'";
$Companystate_code = $sign->SelectF($CompanyStateqry, "state_code");



$qry5="select tnc_value from tw_tnc where company_id='".$company_id."'";
$tnc_value = $sign->SelectF($qry5, 'tnc_value');

$settingValueGSTDocuments= $commonfunction->getSettingValue("GSTDocuments");

$qry7="select document_number from tw_company_document where company_id='".$cCompanyID."' and document_type='".$settingValueGSTDocuments."'";
$retVal7 = $sign->FunctionJSON($qry7);
$decodedJSON7 = json_decode($retVal7);
$gstnumbercompanyto = $decodedJSON7->response[0]->document_number;


$qry2="select document_number from tw_company_document where company_id='".$bCompanyID."' and document_type='".$settingValueGSTDocuments."'";
$retVal2 = $sign->FunctionJSON($qry2);
$decodedJSON1 = json_decode($retVal2);
$gstnumberbillto = $decodedJSON1->response[0]->document_number;
$qry3="select document_number from tw_company_document where company_id='".$sCompanyID."' and document_type='".$settingValueGSTDocuments."'";
$retVal3 = $sign->FunctionJSON($qry3);
$decodedJSON1 = json_decode($retVal3);
$gstnumbershipto = $decodedJSON1->response[0]->document_number;


$qry4="select vdm.vehicle_number,tm.mode_of_transport from tw_vehicle_details_master vdm INNER JOIN tw_transport_master tm ON vdm.transporter_id=tm.id  where vdm.id='".$vehicle_id."'";
$valtransport = $sign->FunctionJSON($qry4);
$decodedJSONSign = json_decode($valtransport);
$vehicle_number=$decodedJSONSign->response[0]->vehicle_number;
$mode_of_transport=$decodedJSONSign->response[1]->mode_of_transport;
	
//$valqrfunction = $qrfunction->GetQrcode($invoice_number,$settingValueUserImagePathOther);

$encValue=$requestid."~".$outwardid;
$encValue=$commonfunction->CommonEnc($encValue);
$encValue=urlencode($encValue);
$Link=$settingValueVerifyLink."pgTaxInvoiceDocuments.php?q=".$encValue;
//$valqrfunction = $qrfunction->GetQrcode($Link,$settingValueUserImagePathOther);

$EwayQry="SELECT document_value,created_on FROM tw_material_outward_documents where outward_id='".$outwardid."' ";
$EwayFetch = $sign->FunctionJSON($EwayQry);
$decodedJSON9 = json_decode($EwayFetch);
$document_value = $decodedJSON9->response[0]->document_value;
$created_on = $decodedJSON9->response[1]->created_on;

$Signatureqry="select approved_by,prepared_by,for_company from tw_digital_signature  where  form_type='".$settingValueTaxInvoice."' AND company_id='".$company_id."'";
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
	<td width="75px" class="left-text"><img src="<?php echo $Company_Logo ?>" width="70px" /></td>
	<td> <strong><?php echo $cCompanyName;?></strong><br><?php echo $caddress_line1." ".$caddress_line2." ".$clocation.",".$ccity." ".$cpincode." ".$cstate.", ".$ccountry;?><br>State code : <?php echo $Companystate_code; ?><br>GSTIN/UIN: <?php echo $gstnumbercompanyto;?><br>E-Mail: <?php echo $email;?> </td>
	<td width="90px" class="right-text"><img src="" width="50px" /></td>
	</tr>
	</table>


</table>


<table class="printtbl">

<tr>
			<td width="75%"><h2> Tax Invoice </h2></td>
			<td width="25%" class="right-text">
				Invoice Date: <strong><?php echo date("d-m-Y ",strtotime($invoice_date));?></strong><br>Invoice Number: <strong><?php echo $invoice_number;?> </strong>
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
<td width="50%"> <strong> Name: <?php echo $bCompanyName; ?> </strong><br>
<?php echo $baddress_line1." ".$baddress_line2." ".$blocation.", ".$bcity." ".$bpincode.", ".$bstate.", ".$bcountry;?><br>State Code: <?php echo $bstate_code; ?> <br>GSTIN. <?php echo $gstnumberbillto; ?> </td>

<td width="50%"><strong> Name: <?php echo $sCompanyName; ?> </strong><br>
<?php echo $saddress_line1." ".$saddress_line2." ".$slocation.", ".$scity." ".$spincode.", ".$sstate.", ".$scountry;?> <br>State Code: <?php echo $sstate_code; ?><br>GSTIN: <?php echo $gstnumbershipto; ?></td>
</tr>
</table> 


<table class="printtbl" style="width:100%";>
		<tr>
			<td width="50%"> OBP Certificate Number: <strong><?php echo " ".$obp_certificate_no; ?></strong><br>Centre Outward Slip Number: <strong><?php echo " ".$centre_outward_slip_no; ?></strong><br>Terms of Payment: <strong><?php echo $payment_term ?></strong></td>
		<td width="50%">Delivery Challan: <strong><?php echo " ".$delivery_challan_no; ?></strong><br>Challan Date: <strong><?php echo date("d-m-Y ",strtotime($invoice_date));?></strong><br> Vehicle Number: <strong><?php echo $vehicle_number; ?></strong> | Mode of Transport: <strong><?php echo $mode_of_transport; ?></strong>
        </td>		
		
		
		</tr>
	</table>
<!--<td> Transport Mode :<!--<?php echo $transport_mode; ?> </td>-->



<table style="width:100%" class="printtbl">
<tr>

</tr>
</table> 

<table style="width:100%" class="printtbl right-text">
<tr>
<th width="100%"> Eway Bill Number: <?php echo $document_value; ?> | Dated <?php echo date("d-m-Y", strtotime($created_on )); ?> </th>
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
$qry="select material_id,quantity,tax,rate,total from tw_material_outward_individual WHERE material_outward_id='".$outwardid."' ORDER BY id Asc";
$retVal = $sign->FunctionJSON($qry);

$qry1="Select count(*) as cnt from tw_material_outward_individual where material_outward_id='".$outwardid."'";
$retVal1 = $sign->Select($qry1);

$decodedJSON2 = json_decode($retVal);
$count = 0;
$totalbeforetax = 0;
$i = 1;
$x=$retVal1;
$it=1;
while($x>=$i){
		
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

	$qry2="select id,product_name,hsn,uom from tw_product_management where id='".$material_id."' order by id Asc";
	$retVal2 = $sign->FunctionJSON($qry2);
	$decodedJSON = json_decode($retVal2);
	$id = $decodedJSON->response[0]->id;
	$product_name = $decodedJSON->response[1]->product_name;
	$hsn = $decodedJSON->response[2]->hsn;
	$uom = $decodedJSON->response[3]->uom;

	$qry3="select name from tw_unit_of_measurement where id='".$uom."' order by id Desc";
	$retVal3 = $sign->SelectF($qry3,"name");


	?>
	

	<tr>
	<td class="center-text"><?php echo $it; ?></td>
	<td><?php echo $product_name; ?> </td>
	<td class="center-text"><?php echo $hsn; ?> </td>
	
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
if($cstate==$sstate){
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
	<td width="10%" class="Right-text top-align"><strong><?php echo $total_quantity;?></strong></td> 
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
<td width="50%"; class="left-text top-align"> Terms & Conditions:<?php echo $tnc_value;?> </td>

<td width="20%" class="right-text top-align"> For <?php echo $cCompanyName;?><br><?php echo $for_company_img; ?><br><br><br><strong>Authorised signatory</strong></td>
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











