<?php 
session_start();
if(!isset($_SESSION["companyusername"])){
	header("Location:pgLogin.php");
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
$requestid=$_REQUEST["po_id"];
$date=$_REQUEST["date"];
$state=$_REQUEST["state"];

$StateNameQry="SELECT state_name FROM `tw_state_master` where id='".$state."'";
$StateName= $sign->SelectF($StateNameQry,"state_name");

//--Check Valid Data End
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");
$settingValueCompletedStatus= $commonfunction->getSettingValue("Completed Status");
$settingValueUserImagePathOther= $commonfunction->getSettingValue("UserImagePathOther");
$settingValueCompanyImage= $commonfunction->getSettingValue("Company Image");
$settingValueUserImagePathVerification= $commonfunction->getSettingValue("UserImagePathVerification");
$settingValueUserPrimaryEmail= $commonfunction->getSettingValue("Primary Email");
$settingValueTaxInvoice= $commonfunction->getSettingValue("DocTaxInvoice");
$settingValueUserCompanyImage= $commonfunction->getSettingValue("Company Image");
$settingValueVerifyLink= $commonfunction->getSettingValue("VerifyLink");



$qry1="select id,supplier_address_id,bill_to_address_id,send_invoice_to_address_id,final_total_amount,total_quantity,po_number,created_on,supplier_id from tw_po_info where id='".$requestid."' order by id Desc";
$retVal1 = $sign->FunctionJSON($qry1);
$decodedJSON1 = json_decode($retVal1);
$id = $decodedJSON1->response[0]->id;
$company_address = $decodedJSON1->response[1]->supplier_address_id;
$bill_to = $decodedJSON1->response[2]->bill_to_address_id;
$ship_to = $decodedJSON1->response[3]->send_invoice_to_address_id;
$final_total_amout = $decodedJSON1->response[4]->final_total_amount;
$total_quantity = $decodedJSON1->response[5]->total_quantity;
$po_number = $decodedJSON1->response[6]->po_number;
$created_on_Date= $decodedJSON1->response[7]->created_on;
$supplier_id= $decodedJSON1->response[8]->supplier_id;

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



$qry5="select tnc_value from tw_tnc where company_id='".$supplier_id."'";
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
	
$date1=date_create($created_on_Date);
$month=date_format($date1,"m");	
$shortCMPName= substr($sCompanyName, 0, 3);
$monthWord=date_format($date1,"M");
$shortmonth= substr($monthWord, 0, 3);
$year1=date_format($date1,"Y");
if($month>04){
	$year=$year1+1;
}
else{
	$year=$year1-1;
}
$shortyear= substr($year, 2, 4);
$shortyear1= substr($year1, 2, 4);
$encValue=$requestid;
$encValue=$commonfunction->CommonEnc($encValue);
$encValue=urlencode($encValue);
$Link=$settingValueVerifyLink.$encValue;
$valqrfunction = $qrfunction->GetQrcode($Link,$settingValueUserImagePathOther);
$varInvoiceNo = "EPR/".$shortCMPName."/".$shortmonth."/".$requestid."/".$shortyear1."-".$shortyear;
$_SESSION["varInvoiceNo"]=$varInvoiceNo;
?>
<html>
<head>
<title>EPR Tax Invoice</title>
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
	<td width="90px" class="right-text"><img src="<?php echo $valqrfunction; ?>" width="50px" /></td>
	</tr>
	</table>


</table>


<table class="printtbl">

<tr>
			<td width="75%"><h2> Tax Invoice </h2></td>
			<td width="25%" class="right-text">
				Invoice Date: <strong><?php echo date("d-m-Y ",strtotime($cur_date));?></strong><br>Invoice Number: <b><?php echo $varInvoiceNo; ?></b>
			
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
			<td width="50%"> PO Number: <strong><?php echo " ".$po_number; ?></strong></td>
		<td width="50%">PO Date: <strong><?php echo " ".date("d-m-Y",strtotime( $created_on_Date)); ?></strong> </td>		
		
		
		</tr>
	</table>
<!--<td> Transport Mode :<!--<?php echo $transport_mode; ?> </td>-->



<table style="width:100%" class="printtbl">
<tr>

</tr>
</table> 

 
<table style="width:100%" class="printtbl">
<tr>
<td width="5%" style="text-align:Center;"> # </td>
<td width="20%" style="text-align:Center;"> Product Description </td>
<td width="10%" style="text-align:Center;"> State </td>
<td width="5%" style="text-align:Center;"> Category </td>
<td width="10%" style="text-align:Center;"> HSN code </td>
<td width="10%" style="text-align:Center;"> Quantity </td>
<td width="15%" style="text-align:Center;"> Rate </td> 
<td width="5%" style="text-align:Center;"> UOM </td>
<td width="20%" style="text-align:Center;"> Amount </td>
<tr>

<?php
/* $stateQry="SELECT GROUP_CONCAT(DISTINCT(dispatched_state)) as state  from tw_temp where po_id='".$requestid."' AND status='".$settingValueCompletedStatus."'  ";
$statename = $sign->SelectF($stateQry,'state');
$arrStr = array();
$arrStr = explode(",",$statename); */
$valtotalQty=0.00;
$TotalAmount=00.00;
$TotalAmountperunit=00.00;
/*  for($i1=0; $i1<count($arrStr); $i1++)
{ 
	   count($arrStr);
		$arrStr[$i1]; */

	 
	 
    $qry="select IFNULL(sum(replace(plant_quantity, ',', '')),0) as plant_quantity,category_name,dispatched_state,material_name from tw_temp WHERE po_id='".$requestid."' AND plant_wbs_date like '".$date."%' AND status='".$settingValueCompletedStatus."' AND dispatched_state='".$StateName."'   ORDER BY id Asc";
	$retVal = $sign->FunctionJSON($qry);  


    $qry1="Select count(DISTINCT dispatched_state) as cnt from tw_temp where po_id='".$requestid."' AND plant_wbs_date like '%".$date."%' AND status='".$settingValueCompletedStatus."'  AND dispatched_state='".$StateName."' ";
    $retVal1 = $sign->Select($qry1);

	$decodedJSON2 = json_decode($retVal);
	$count = 0;
	$totalbeforetax = 00.00;
	
	
	$total=0;
	$i = 1;
	$x=$retVal1;
	$it=1;
	while($x>=$i){
			
		$quantity = $decodedJSON2->response[$count]->plant_quantity;
		$count=$count+1;
		$category_name = $decodedJSON2->response[$count]->category_name;
		$count=$count+1;
		$dispatched_state = $decodedJSON2->response[$count]->dispatched_state;
		$count=$count+1;
		$material_name= $decodedJSON2->response[$count]->material_name;
		$count=$count+1;
		
		$categoryQry="SELECT id FROM tw_epr_category_master where epr_category_name='".trim($category_name)."'";
		$category_id = $sign->SelectF($categoryQry, 'id');
		
		$ProductidQry="SELECT id FROM `tw_epr_product_master` where epr_product_name='".$material_name."' AND epr_category_id='".$category_id."'";
		$product_id=$sign->SelectF($ProductidQry, 'id'); 
		
	
	   $ProductrateQry="SELECT price_per_unit FROM tw_po_details where product_id='".$product_id."' AND po_id='".$requestid."' and state='".$state."'";
       $amount_per_unit=$sign->SelectF($ProductrateQry, "price_per_unit"); 
		if($amount_per_unit==""){
			$amount_per_unit=0.00;
		}

		
		 
		//$TotalAmountperunit = ($amount_per_unit * $quantity) ;
		//$TotalAmount = $TotalAmount+$TotalAmountperunit;
		$TotalAmountperunit = ($amount_per_unit * (int)$quantity);
		$TotalAmount = $TotalAmount+$TotalAmountperunit;
		?>
		

		<tr>
		<td class="center-text"><?php echo $it; ?></td>
		<td>EPR Services </td>
		<td class="center-text"><?php echo $dispatched_state; ?> </td>
		
		<td class="center-text"><?php echo $category_name; ?> </td>
		<td class="center-text">999422</td>
		
		<td class="right-text"><?php echo number_format((int)$quantity,2); ?> </td>
		<td class="center-text"><?php echo number_format(round($amount_per_unit,2),2); ?></td>
		<td class="center-text">Kg</td>
		<?php 
				
		?>
		<td style="text-align:right;">&#8377; <?php echo number_format(round($TotalAmountperunit,2),2);?> </td>
		</tr>
		<?php 
		$valtotalQty = $valtotalQty+(int)$quantity;
		
		$totalbeforetax=$totalbeforetax+$TotalAmount;
	    $totalbeforetax;
		
		$it++;

		$i=$i+1;
		
	}
	
//}

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
	<td width="10%" class="Right-text top-align"><strong><?php echo number_format($valtotalQty,2);?></strong></td> 
	<td width="20%" class="Right-text top-align"> Sub Total </td> 
	<td width="20%" style= "text-align:right;" >&#8377; <?php echo number_format(round($totalbeforetax,2),2);?> </td>
</tr>

<tr>
	<td width="50%" class="left-text top-align" rowspan="5"> Remarks: <br><?php //echo $remark;?></td>	
	
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
		<button id="printPageButton" class="noPrint" onclick="location.href='pgEPRSInvoiceTransaction.php?po_id=<?php echo $requestid;?>&amount=<?php echo $final_total_amout; ?>'">Payment</button>
	</div>

<script type="text/javascript">
var valpo_id="<?php echo $requestid; ?>";
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











