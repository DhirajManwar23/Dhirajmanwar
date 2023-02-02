<?php
session_start();
if(!isset($_SESSION["employee_id"])){
	header("Location:pgEmployeeProfile.php");
}
include_once "function.php";
$sign=new Signup();
include_once "commonFunctions.php";
include_once "Qrfunction.php";
$commonfunction=new Common();
$qrfunction=new TwQr();

$requestid = $_REQUEST["id"];
$inward_id = $_REQUEST["inward_id"];

$ip_address= $commonfunction->getIPAddress();
$settingValueGSTDocuments = $commonfunction->getSettingValue("GSTDocuments");
$settingValueGRN= $commonfunction->getSettingValue("DocGRN");

date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:m");

$settingValueUserImagePathVerification= $commonfunction->getSettingValue("UserImagePathVerification");
$settingValueUserImagePathOther = $commonfunction->getSettingValue("UserImagePathOther");
$settingValueCompanyImage = $commonfunction->getSettingValue("Company Image");

$settingValueVerifyLink= $commonfunction->getSettingValue("VerifyLink");

$query1="Select company_id,customer_id,company_address,bill_to,ship_to,final_total_amout,po_id,total_quantity from tw_material_outward where id='".$inward_id."'";
$migrndata = $sign->FunctionJSON($query1);
$decodedJSON2 = json_decode($migrndata);
$grncompany_id = $decodedJSON2->response[0]->company_id;//agg id
$grncustomer_id = $decodedJSON2->response[1]->customer_id;//rec id
$grncompany_address = $decodedJSON2->response[2]->company_address;
$bill_to = $decodedJSON2->response[3]->bill_to;
$ship_to = $decodedJSON2->response[4]->ship_to;
$final_total_amout = $decodedJSON2->response[5]->final_total_amout;
$po_id = $decodedJSON2->response[6]->po_id;
$total_quantity = $decodedJSON2->response[7]->total_quantity;
$amountInWords= $commonfunction->amountInWords($final_total_amout);	



$query="Select id ,date,remark,created_by from tw_material_inward_grn where inward_id='".$inward_id."'";
$DocVal = $sign->FunctionJSON($query);
$decodedJSON = json_decode($DocVal);
$id = $decodedJSON->response[0]->id; 
$date = $decodedJSON->response[1]->date;
$Remark = $decodedJSON->response[2]->remark;
$created_by = $decodedJSON->response[3]->created_by;


$queryEmpName="Select employee_name from tw_employee_registration where id='".$created_by."'";
$ValEmpName = $sign->SelectF($queryEmpName,"employee_name");

$qry8="Select CompanyName as SupplierName from tw_company_details where ID='".$grncompany_id."'";
$data1 = $sign->FunctionJSON($qry8);
$decodedJSON8 = json_decode($data1);
$SupplierName = $decodedJSON8->response[0]->SupplierName;

$qry9="Select CompanyName as SupplierName,Company_Logo from tw_company_details where ID='".$grncustomer_id."'";
$data1 = $sign->FunctionJSON($qry9);
$decodedJSON8 = json_decode($data1);
$CompanyName = $decodedJSON8->response[0]->SupplierName;
$Company_Logo = $decodedJSON8->response[1]->Company_Logo;


$qry="Select document_number as GSTIN from tw_company_document where document_type='".$settingValueGSTDocuments."' and company_id='".$grncustomer_id."'";
$RecyclerGSTIN = $sign->SelectF($qry,"GSTIN");

$qry1="Select value as email from tw_company_contact where company_id='".$grncustomer_id."'";
$email = $sign->FunctionJSON($qry1);
$decodedJSON5 = json_decode($email);
$email = $decodedJSON5->response[0]->email;
$logopath="";
if($Company_Logo==""){
	$logopath = $settingValueUserImagePathOther.$settingValueCompanyImage;
}
else{
	$logopath = $settingValueUserImagePathVerification.$email."/".$Company_Logo;
}


$qry6="Select document_number from tw_company_document where document_type='".$settingValueGSTDocuments."' and company_id='".$grncompany_id."'";
$AggGST = $sign->SelectF($qry6,"document_number");

//Recycler qry
$qry3="select address_line1,address_line2,location,pincode,city,state,country from tw_company_address where id='".$ship_to."'";
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
		$sstate = $decodedJSON3->response[5]->state;
	}
	else
	{
		$sstate="";
	}
	if(isset($decodedJSON3->response[6]->country))
	{
		$country = $decodedJSON3->response[6]->country;
	}
	else
	{
		$country="";
	}
	
	$shipto=$address_line1.", ".$address_line2.", ".$location.", ".$city." ".$pincode.", ".$sstate.", ".$country;

	
	$qry4="select address_line1,address_line2,location,pincode,city,state,country from tw_company_address where id='".$grncompany_address."'";
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
		$state = $decodedJSON4->response[5]->state;
	}
	else
	{
		$state="";
	}
	if(isset($decodedJSON4->response[6]->country))
	{
		$country = $decodedJSON4->response[6]->country;
	}
	else
	{
		$country="";
	}
	
	$companyaddress=$address_line1.", ".$address_line2.", ".$location.", ".$city." ".$pincode.", ".$state.", ".$country;
	$encValue=$requestid."~".$inward_id;
	$encValue=$commonfunction->CommonEnc($encValue);
	$encValue=urlencode($encValue);
    $Link=$settingValueVerifyLink."pgGRN.php?q=".$encValue;
    $valqrfunction = $qrfunction->GetQrcode($Link,$settingValueUserImagePathOther);
	
	$Signatureqry="select approved_by,prepared_by,for_company from tw_digital_signature  where  form_type='".$settingValueGRN."' AND company_id='".$grncustomer_id."'AND (employee_id='".$created_by."' || employee_id='0')";
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
<title>GRN</title>
<link rel="stylesheet" href="../assets/css/custom/style.css" />
<link rel="shortcut icon" href="../assets/images/favicon.png" />
</head>

<body>
<div id="printarea">
<table class="printtbl-noborder" style="width:100%">
<tr>
<td width="75px" class="left-text"><img src="<?php echo $logopath;?>" width="70px" /></td>

<td> <strong><?php echo $CompanyName;?></strong><br><?php echo $shipto;?><br>GSTIN: <?php echo $RecyclerGSTIN;?><br>E-Mail: <?php echo $email;?> </td>


<td class="date" style="text-align:right"><img src="<?php echo $valqrfunction; ?>" width="50px" /><br>  </td>
</tr>
</table>
<!--<table class="Tilli" style="width:100%">
<tr>
<td> Ref. No. <b><?php //echo $requestid;?></b> </td>  </tr>
</table>-->

<table width="100%" class="printtbl" border="1">

	<tr>
	<div id="texts" style="white-space:nowrap;">
	<td width="75%"><h2>Goods Receive Note</h2></td>
	<td width="50%" class="right-text">Ref. No: <b><?php echo $requestid;?></b><br>Dated:  <b><?php echo date("d-m-Y h:m a",strtotime($date)); ?></b>
	</div>
	</tr>
	
</table>
<table width="100%"  class="printtbl">
	<tr style="text-align:left;">
		<td>
		Vendor: <strong><?php echo $SupplierName;?></strong><br><?php echo $companyaddress;?><br>GSTIN/UIN:<?php echo $AggGST;?></td> 
	</tr>
</table>
<table  class="printtbl">
	<tr>
	<th width="5%"> #</th>
	<th width="45%"> Description of Goods   </th>
	
	<th width="10%"> Quantity </th>
	<th width="10%"> UOM </th>
	<th width="10%"> Rate </th>
	<th width="20%"> Amount </th>	
	</tr> 
	
		<?php 
		$qry7="Select mo.id,pm.product_name as Description,mo.quantity as Quantity,mo.rate as Rate,um.name as UOM,mo.total as Amount from tw_material_outward_individual mo INNER JOIN tw_product_management pm ON pm.id=mo.material_id INNER JOIN  tw_unit_of_measurement um ON pm.uom=um.id where material_outward_id='".$inward_id."' order by mo.id asc";
		$retVal7 = $sign->FunctionJSON($qry7);
		$decodedJSON7 = json_decode($retVal7);
		
		$qry2="Select count(*) as cnt from tw_material_outward_individual where material_outward_id='".$inward_id."'";
		$retVal2 = $sign->Select($qry2);
		$count = 0;
		$i = 1;
		$x=$retVal2;
		$it=1;
		
		$totalbeforetax=0;
		
			while($x>=$i){
			$id = $decodedJSON7->response[$count]->id;
			$count=$count+1;
			$Description = $decodedJSON7->response[$count]->Description;
			$count=$count+1;
			$Quantity = $decodedJSON7->response[$count]->Quantity;
			$count=$count+1;
			$Rate = $decodedJSON7->response[$count]->Rate;
			$count=$count+1;
			$UOM = $decodedJSON7->response[$count]->UOM;
			$count=$count+1;
			$Amount = $decodedJSON7->response[$count]->Amount;
			$count=$count+1;
			$i=$i+1;
			
			$totalbeforetax=$totalbeforetax+$Amount;
				
		?>
	
		<tr>
			<td style="text-align:center"><?php echo $it;?></td>
			<td style="text-align:left"><?php echo $Description;?></td>
			<td style="text-align:right"><?php echo number_format($Quantity,2);?></td>
			<td style="text-align:center"><?php echo $UOM;?></td>
			<td style="text-align:right">&#8377; <?php echo number_format(round($Rate,2),2);?></td>
			
			<td style="text-align:right;">&#8377; <?php echo number_format(round($Amount,2),2);?></td>
			<!--<td style="text-align:right"><?php// echo $total;?></td>-->
		</tr>
			<?php
				$it=$it+1;
			}
			
				
	
	//$it++;

	$i=$i+1;


$CGST="";
$SGST="";
$IGST="";
$final_total_amout="";
if($state==$sstate){
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
?>
	
	
	
<table style="width:100%" class="printtbl">
<tr>
	<td width="50%" style="text-align:right"> Total Quantity </td> 
	<th width="10%" style= "text-align:right;"><?php echo number_format(round($total_quantity,2),2);?> </th> 
	<td width="20%" style= "text-align:right;">Total Amount before Tax</td> 
	<th width="20%" style= "text-align:right;" >&#8377; <?php echo number_format(round($totalbeforetax,2),2);?> </th>
</tr>
<tr>
	<th width="50%" style="text-align:left" rowspan="7" ></th>
	<td></td>
	<td style= "text-align:right;">Add: CGST 9 %</td>
	<td style= "text-align:right;"> &#8377; <?php echo number_format(round($CGST,2),2);?></td>
</tr>
<tr>
	<td></td>
	<td style= "text-align:right;">Add: SGST 9%</td>
	<td style= "text-align:right;">&#8377; <?php echo number_format(round($SGST,2),2);?></td>
</tr>
<tr>
	<td></td>
	<td style= "text-align:right;">Add: IGST 18%</td>
	<td style= "text-align:right;">&#8377; <?php echo number_format(round($IGST,2),2); ?></td>
</tr>
<?php  $Temproundoff=0; 
       $finalRondoff=round($final_total_amout,0) ?>
<?php $Temproundoff=$finalRondoff-$final_total_amout; 
 ?>
<tr>
	<td></td>
	<td class="Right-text top-align">Round Off</td>
	<td style= "text-align:right;"> &#8377; <?php   echo number_format($Temproundoff,2); ?></td>
</tr>

<tr>
	<td></td>
	<td style= "text-align:right;">Total Amount</td>
	<th style= "text-align:right;">&#8377; <?php echo number_format(round($final_total_amout,0),2);?></th>
</tr>
</table>
	
	
	
	
	
	<table  class="printtbl" >
	<tr>
	<td>Amount In Words: <b><?php echo $amountInWords; ?> </b></td>
	</tr>
	</table>
	<table class="printtbl">
	<tr>
	<td width="50%" class="left-text top-align" > Remarks:<br><?php echo $Remark;?></td>
	<td width="15%" class="left-text top-align" > <strong>Prepared by:</strong> <br><?php echo $ValEmpName; ?><br><?php echo $prepared_by_img; ?></td>
	<td width="15%" class="left-text top-align" > <strong>Verified by:</strong><br><?php echo $approved_by_img; ?></td>
	<td width="20%" class="right-text top-align"> For <?php echo $CompanyName;?><br><br><?php echo $for_company_img; ?><br><br><br><strong>Authorised signatory</strong></td>
	
	</tr>

	
	</table>
	</div>
	<!--<td>
		<input type="button" value="Print Preview" class="btn" onclick="PrintPreview()"/>
	</td>-->
	<br>
	<div class="center-text">
	<button id="printPageButton"  onclick="printDiv('printarea');">Print</button>
	</div>

<script type="text/javascript">
function printDiv(printableArea) {
    var printContents = document.getElementById(printableArea).innerHTML;
    var originalContents = document.body.innerHTML;
	document.body.innerHTML = printContents;
	window.print();
	document.body.innerHTML = originalContents;
}
</script>	 


</body>
</body>

<html>