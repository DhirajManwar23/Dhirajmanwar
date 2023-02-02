<?php
session_start();
if(!isset($_SESSION["employee_id"])){
	header("Location:pgEmployeeProfile.php");
}
include_once "function.php";
$sign=new Signup();
include_once "commonFunctions.php";
$commonfunction=new Common();
include_once "Qrfunction.php";
$qrfunction=new TwQr();

$po_id = $_REQUEST["po_id"];
$ip_address= $commonfunction->getIPAddress();
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");

$settingValueUserImagePathVerification= $commonfunction->getSettingValue("UserImagePathVerification");
$settingValueGSTDocuments= $commonfunction->getSettingValue("GSTDocuments");
$settingValueUserImagePathOther= $commonfunction->getSettingValue("UserImagePathOther");
$settingValuePo= $commonfunction->getSettingValue("DocPo");
$VerifyLink= $commonfunction->getSettingValue("VerifyLink");
$settingValueCompanyImage = $commonfunction->getSettingValue("Company Image");
$querypo="Select id,supplier_id,buyer_id,po_date,final_total_amount,delivery_terms,disp_instruction,payment_terms,sp_instruction,created_by,supplier_address,bill_to_address from tw_temp_po_info where id='".$po_id."'";
$DocValpo = $sign->FunctionJSON($querypo);
$decodedJSON = json_decode($DocValpo);
$id = $decodedJSON->response[0]->id; 
$supplier_id = $decodedJSON->response[1]->supplier_id; 
$buyer_id = $decodedJSON->response[2]->buyer_id; 
$po_date = $decodedJSON->response[3]->po_date; 
$final_total_amount = $decodedJSON->response[4]->final_total_amount; 
$delivery_terms = $decodedJSON->response[5]->delivery_terms; 
$disp_instruction = $decodedJSON->response[6]->disp_instruction; 
$payment_terms = $decodedJSON->response[7]->payment_terms; 
$sp_instruction = $decodedJSON->response[8]->sp_instruction; 
$created_by = $decodedJSON->response[9]->created_by; 
$supplier_address = $decodedJSON->response[10]->supplier_address; 
$bill_to_address = $decodedJSON->response[11]->bill_to_address; 

$po_date=date("d-m-Y", strtotime($po_date)); 
$final_total_amount=round($final_total_amount,0);
$amountInWords= $commonfunction->amountInWords($final_total_amount);
$encValue=$po_id;
$encValue=$commonfunction->CommonEnc($encValue);
$encValue=urlencode($encValue);
$Link=$VerifyLink."pgPODocument.php?q=".$encValue;
$valqrfunction = $qrfunction->GetQrcode($Link,$settingValueUserImagePathOther);

$queryn="Select employee_name from tw_employee_registration where id='".$created_by."'";
$empName = $sign->SelectF($queryn,"employee_name");

$query="Select ID,Company_Logo,CompanyName from tw_company_details where ID='".$buyer_id."'";
$DocVal = $sign->FunctionJSON($query);
$decodedJSON = json_decode($DocVal);
$ID = $decodedJSON->response[0]->ID; 
$Company_Logo = $decodedJSON->response[1]->Company_Logo; 
$CompanyName = $decodedJSON->response[2]->CompanyName;

$query1="Select ID,CompanyName as SupplierName from tw_company_details where ID='".$supplier_id."'";
$DocVal1 = $sign->FunctionJSON($query1);
$decodedJSON1 = json_decode($DocVal1);
$ID = $decodedJSON1->response[0]->ID; 
$SupplierName = $decodedJSON1->response[1]->SupplierName;

$qry="Select document_number from tw_company_document where document_type='".$settingValueGSTDocuments."' and company_id='".$buyer_id."'";
$GSTIN = $sign->SelectF($qry,"document_number");

$qry1="Select value as email from tw_company_contact where company_id='".$buyer_id."'";
$email = $sign->FunctionJSON($qry1);
$decodedJSON5 = json_decode($email);
$email = $decodedJSON5->response[0]->email;
if($Company_Logo==""){
	$logopath = $settingValueUserImagePathOther.$settingValueCompanyImage;
}
else{
	$logopath = $settingValueUserImagePathVerification.$email."/".$Company_Logo;
}
$qry6="Select document_number from tw_company_document where document_type='".$settingValueGSTDocuments."' and company_id='".$supplier_id."'"; 
$CompanyGST = $sign->SelectF($qry6,"document_number");

$qry9="Select tnc_value from tw_tnc where company_id='".$buyer_id."' and tnc_for='PO'";
$retVal9 = $sign->FunctionJSON($qry9);
$decodedJSON9 = json_decode($retVal9);
$tnc_value = $decodedJSON9->response[0]->tnc_value;

$qry3="select address_line1,address_line2,location,pincode,city,state,country from tw_company_address where id='".$bill_to_address."'";
$retVal3 = $sign->FunctionJSON($qry3);
$decodedJSON3 = json_decode($retVal3);
	if(isset($decodedJSON3->response[0]->address_line1))
	{
		$address_line1 = $decodedJSON3->response[0]->address_line1;
	}
	else
	{
		$address_line1="";
	}
	if(isset($decodedJSON3->response[1]->address_line2))
	{
		$address_line2 = $decodedJSON3->response[1]->address_line2;
	}
	else
	{
		$address_line2="";
	}
	if(isset($decodedJSON3->response[2]->location))
	{
		$location = $decodedJSON3->response[2]->location;
	}
	else
	{
		$location="";
	}
	if(isset($decodedJSON3->response[3]->pincode))
	{
		$pincode = $decodedJSON3->response[3]->pincode;
	}
	else
	{
		$pincode="";
	}
	if(isset($decodedJSON3->response[4]->city))
	{
		$city = $decodedJSON3->response[4]->city;
	}
	else
	{
		$city="";
	}
	if(isset($decodedJSON3->response[5]->state))
	{
		$cstate = $decodedJSON3->response[5]->state;
	}
	else
	{
		$cstate="";
	}
	if(isset($decodedJSON3->response[6]->country))
	{
		$country = $decodedJSON3->response[6]->country;
	}
	else
	{
		$country="";
	}
	
	$shipto=$address_line1.", ".$address_line2.", ".$location.", ".$city." ".$pincode.", ".$cstate.", ".$country;
    $qry10="select state_code from tw_state_master where state_name= '".$cstate."'";
    $cstate_code = $sign->SelectF($qry10, "state_code");
	
	$qry4="select address_line1,address_line2,location,pincode,city,state,country from tw_company_address where id = '".$supplier_address."' ";
	$retVal4 = $sign->FunctionJSON($qry4);
	$decodedJSON4 = json_decode($retVal4);
	
	if(isset($decodedJSON4->response[0]->address_line1))
	{
		$address_line1 = $decodedJSON4->response[0]->address_line1;
	}
	else
	{
		$address_line1="";
	}
	if(isset($decodedJSON4->response[1]->address_line2))
	{
		$address_line2 = $decodedJSON4->response[1]->address_line2;
	}
	else
	{
		$address_line2="";
	}
	if(isset($decodedJSON4->response[2]->location))
	{
		$location = $decodedJSON4->response[2]->location;
	}
	else
	{
		$location="";
	}
	if(isset($decodedJSON4->response[3]->pincode))
	{
		$pincode = $decodedJSON4->response[3]->pincode;
	}
	else
	{
		$pincode="";
	}
	if(isset($decodedJSON4->response[4]->city))
	{
		$city = $decodedJSON4->response[4]->city;
	}
	else
	{
		$city="";
	}
	if(isset($decodedJSON4->response[5]->state))
	{
		$sstate = $decodedJSON4->response[5]->state;
	}
	else
	{
		$sstate="";
	}
	if(isset($decodedJSON4->response[6]->country))
	{
		$country = $decodedJSON4->response[6]->country;
	}
	else
	{
		$country="";
	}
	
	$companyaddress=$address_line1.", ".$address_line2.", ".$location.", ".$city." ".$pincode.", ".$sstate.", ".$country;
	
	$Signatureqry="select approved_by,prepared_by,for_company from tw_digital_signature  where  form_type='".$settingValuePo."' AND company_id='".$buyer_id."' ";
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
<title>Purchase Order</title>
<link rel="stylesheet" href="../assets/css/custom/style.css" />
<link rel="shortcut icon" href="../assets/images/favicon.png" />
</head>
</head>

<body>
<div id="printarea">
	
	<table class="printtbl-noborder">
	<tr>
	<td width="75px" class="left-text"><img src="<?php echo $logopath;?>" width="70px" /></td>
	<td> <strong><?php echo $CompanyName;?></strong><br><?php echo $shipto;?><br>GSTIN/UIN: <?php echo $GSTIN;?><br>E-Mail: <?php echo $email;?> </td>
	<td width="90px" class="right-text"><img src="<?php echo $valqrfunction; ?>" width="50px" /></td>
	</tr>
	</table>

<table class="printtbl">
	
		<?php 
			
		?> 
	
		<tr>
			<td width="75%"><h2>Purchase Order</h2></td>
			<td width="25%" class="right-text">
				Date: <strong><?php echo $po_date;?></strong><br>PO Number: <strong><?php echo $id;?> </strong>
			</td>
		</tr>
		
		
	<table class="printtbl">
		<tr>
			<td> Vendor: <strong><?php echo $SupplierName;?></strong><br><?php echo $companyaddress;?><br>GSTIN/UIN: <strong><?php echo $CompanyGST;?></strong></td>
		</tr>
	</table>
			<?php //}?>
			
	<table class="printtbl" >
		<tr>
		<th width="5%">#</th>
		<th width="40%">Description</th>
		<th width="10%">HSN</th>
		<th width="10%">Quantity</th>
		<th width="5%">UOM</th>
		<th width="10%">Rate</th>
		<th width="5%">GST</th>
		
		<th width="15%">Amount</th>	
		</tr> 
	
	
		<?php 
		
		
		$qry7="Select po.id,pm.product_name as Description,po.quantity as Quantity,po.rate as Rate,po.hsn as hsn,po.tax as tax,um.name as UOM,po.total as Amount from tw_temp_po_details po INNER JOIN tw_product_management pm ON pm.id=po.material_id INNER JOIN tw_unit_of_measurement um ON pm.uom=um.id where po.po_id='".$po_id."' ORDER by po.id ASC";
		$retVal7 = $sign->FunctionJSON($qry7);
		$decodedJSON7 = json_decode($retVal7);
		
		$qry2="Select count(*) as cnt from tw_temp_po_details where po_id='".$po_id."'";
		$retVal2 = $sign->Select($qry2);
		$totalbeforetax = 0;
		$count = 0;
		$totalQuantity = 0.00;
		$i = 1;
		$x=$retVal2;
		$it=1;
			while($x>=$i){
			$id = $decodedJSON7->response[$count]->id;
			$count=$count+1;
			$Description = $decodedJSON7->response[$count]->Description;
			$count=$count+1;
			$Quantity = $decodedJSON7->response[$count]->Quantity;
			$count=$count+1;
			$Rate = $decodedJSON7->response[$count]->Rate;
			$count=$count+1;
			$HSN = $decodedJSON7->response[$count]->hsn;
			$count=$count+1;
			$tax = $decodedJSON7->response[$count]->tax;
			$count=$count+1;
			$UOM = $decodedJSON7->response[$count]->UOM;
			$count=$count+1;
			$Amount = $decodedJSON7->response[$count]->Amount;
			$count=$count+1;
			
			
			$totalbeforetax=$totalbeforetax+$Amount;
			$totalQuantity=$totalQuantity+$Quantity;

		?>
	
		<tr>
			<td class="center-text"><?php echo $it;?></td>
			<td class="left-text"><?php echo $Description;?></td>
			<td class="center-text"><?php echo $HSN;?></td>
			<td class="right-text"><?php echo $Quantity;?></td>
			<td class="center-text"><?php echo $UOM;?></td>
			<td class="right-text">&#8377; <?php echo $Rate;?></td>
			<td class="center-text"><?php echo $tax;?>%</td>
			<td class="right-text">&#8377; <?php echo number_format(round($Amount,2),2); ?></td>
		
		</tr>
		
			<?php 
			$it=$i+1;
			$i=$i+1;
			}
			$CGST="";
			$SGST="";
			$IGST="";
			$final_total_amout=0;

if($cstate==$sstate){
	$CGST = $totalbeforetax *(9/100);
	//$CGST = number_format($CGST,2);
	$SGST = $totalbeforetax *(9/100);
	//$SGST = number_format($SGST,2);
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
			
			?>
			
	<tr>
		<td colspan="3" class="Right-text" > Total Quantity </td>
		<td colspan="1" class="right-text"><strong><?php echo number_format($totalQuantity,2);  ?></strong> </td> 
		<td colspan="3" class="right-text"> Sub Total </td> 
		<td class="right-text">&#8377; <?php echo number_format(round($totalbeforetax,2),2);?> </td>
	</tr>
	
	<tr>
		<td rowspan="5" colspan="3"></td>
			<td colspan="1" ></td>
				<td colspan="3" class="right-text"> Add: CGST 9% </td> 
		<td  class="right-text">&#8377; <?php echo number_format($CGST,2);?> </td>
	</tr> 
	<tr>
	<td colspan="1"></td>
		<td colspan="3" class="right-text"> Add: SGST 9% </td> 
		<td class="right-text">&#8377; <?php echo number_format($SGST,2);?> </td>
	</tr> 
	<tr >
		
		<td colspan="1"></td>
		<td colspan="3" class="right-text"> Add: IGST 18%</td> 
		<td class="right-text">&#8377; <?php echo number_format($IGST,2);?> </td>
	</tr> 
	</tr>
<?php  
		$Temproundoff=0; 
        $finalRondoff=round($final_total_amout,0) ?>
<?php   $Temproundoff=$finalRondoff-$final_total_amout; 
 ?>
<tr>

<td colspan="1"></td>
	<td colspan="3" class="Right-text top-align">Round Off</td>
	<td style= "text-align:right;">&#8377; <?php echo number_format($Temproundoff,2); ?></td>
</tr>
	
	
	<tr >
	
	<td colspan="1"></td>
		<td colspan="3" class="right-text"><b>Total</b></td>
		<td class="right-text"> <b>&#8377; <?php echo number_format(round($final_total_amount,0),2);?> </b></td>	
	</tr> 
	<tr>
		<td colspan="8">Amount In Words: <strong><?php echo $amountInWords; ?></strong></td>
	</tr>
	</table>
	<table class="printtbl">
		<tr>
			<td width="25%"><strong>Delivery Terms: </strong></td> 
			<td width="75%"><?php echo $delivery_terms; ?></td> 
		</tr> 
		<tr>
			<td width="25%"><strong>Dispatch Instruction: </strong></td> 
			<td width="75%"><?php echo $disp_instruction; ?></td> 
		</tr> 
		<tr>
			<td width="25%"><strong>Payment Terms(Days): </strong></td> 
			<td width="75%"><?php echo $payment_terms; ?></td> 
		</tr> 
		<tr>
			<td width="25%"><strong>Special Instruction: </strong></td> 
			<td width="75%"><?php echo $sp_instruction; ?></td> 
		</tr> 
	</table>
	
	<table class="printtbl">
		<tr>
			<td> <b>Terms and Conditions:</b><?php echo $tnc_value;?></td>
		</tr>
	</table>
	<table class="printtbl">
		<tr>
			<td width="35%" class="left-text top-align"><b>Prepared By</b><br>
			<?php echo $empName;?><br> <?php echo $prepared_by_img; ?>  </td>
			<td width="35%" class="left-text top-align"><b>Approved By</b><br><?php echo $approved_by_img ?><br>
			</td>
			<td width="30%" class="right-text top-align"> For <b><?php echo $CompanyName;?> </b><br><?php echo $for_company_img; ?><br><br><br><br><br></td>
		</tr>
	</table>
	<br>
</div>
	<button id="printPageButton" onclick="printDiv('printarea');">Print</button>

<script>
function printDiv(printableArea) {
    var printContents = document.getElementById(printableArea).innerHTML;
    var originalContents = document.body.innerHTML;
	document.body.innerHTML = printContents;
	window.print();
	document.body.innerHTML = originalContents;
}
</script>
</body>
<html>