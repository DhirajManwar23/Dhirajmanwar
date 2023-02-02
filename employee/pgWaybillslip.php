<?php 
session_start();
if(!isset($_SESSION["employee_id"])){
	header("Location:pgEmployeeLogIn.php");
}
// Include class definition
include_once "function.php";
$sign=new Signup();
include_once "commonFunctions.php";
include_once "Qrfunction.php";
$commonfunction=new Common();
$qrfunction=new TwQr();

$requestid=$_REQUEST["id"];
$outwardid=$_REQUEST["outward_id"];
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("d-m-Y h:m a");

$settingValueUserImagePathVerification = $commonfunction->getSettingValue("UserImagePathVerification");
$settingValueUserImagePathOther = $commonfunction->getSettingValue("UserImagePathOther");
$settingValueCompanyImage = $commonfunction->getSettingValue("Company Image");
$settingValueGSTDocuments = $commonfunction->getSettingValue("GSTDocuments");
$settingValueWBS= $commonfunction->getSettingValue("DocWBS");

$settingValueVerifyLink= $commonfunction->getSettingValue("VerifyLink");


$qry2="Select company_id,customer_id,company_address,vehicle_id,ship_to from tw_material_outward where id='".$outwardid."'";
$data = $sign->FunctionJSON($qry2);
$decodedJSON8 = json_decode($data);
$wbscompany_id = $decodedJSON8->response[0]->company_id;
$customer_id = $decodedJSON8->response[1]->customer_id;
$company_address = $decodedJSON8->response[2]->company_address;
$vehicle_id = $decodedJSON8->response[3]->vehicle_id;
$ship_to = $decodedJSON8->response[4]->ship_to;

$qry4="select tm.mode_of_transport from tw_vehicle_details_master vdm INNER JOIN tw_transport_master tm ON vdm.transporter_id=tm.id  where vdm.id='".$vehicle_id."'";
$valtransport = $sign->FunctionJSON($qry4);
$decodedJSONSign = json_decode($valtransport);
$mode_of_transport=$decodedJSONSign->response[0]->mode_of_transport;

$qry7="Select vtm.name from tw_vehicle_type_master vtm INNER JOIN tw_vehicle_details_master vdm ON vdm.vehicle_type = vtm.id WHERE vdm.id='".$vehicle_id."'";
$VehicleType = $sign->SelectF($qry7,"name");


$qry="select id,carrier_no,party_name,material_name,gross_weight,gross_weight_date_time,tare_weight,tare_weight_date_time,net_weight,amount_received,driver_name,payment_mode,created_on from tw_material_outward_wbs where id='".$requestid."' order by id Desc";
$sign=new Signup();
$retVal = $sign->FunctionJSON($qry);
$decodedJSON = json_decode($retVal);
$id = $decodedJSON->response[0]->id;
$carrier_no = $decodedJSON->response[1]->carrier_no;
$party_name = $decodedJSON->response[2]->party_name;
$material_name = $decodedJSON->response[3]->material_name;
$gross_weight = $decodedJSON->response[4]->gross_weight;
$gross_weight_date_time = $decodedJSON->response[5]->gross_weight_date_time;
$tare_weight = $decodedJSON->response[6]->tare_weight;
$tare_weight_date_time = $decodedJSON->response[7]->tare_weight_date_time;
$net_weight = $decodedJSON->response[8]->net_weight;
$amount_received = $decodedJSON->response[9]->amount_received;
$driver_name = $decodedJSON->response[10]->driver_name;
$payment_mode = $decodedJSON->response[11]->payment_mode;
$created_on = $decodedJSON->response[12]->created_on;


$query="select CompanyName,Company_Logo from tw_company_details where ID='".$wbscompany_id."' order by id Desc";
$sign=new Signup();
$Data = $sign->FunctionJSON($query);
$decodedJSON1 = json_decode($Data);
$CompanyName = $decodedJSON1->response[0]->CompanyName;
$Company_Logo = $decodedJSON1->response[1]->Company_Logo;

$queryRecyclerName="select CompanyName as RecyclerName from tw_company_details where ID='".$customer_id."' order by id Desc";
$RecyclerName = $sign->SelectF($queryRecyclerName,"RecyclerName");

$qry="Select document_number as GSTIN from tw_company_document where document_type='".$settingValueGSTDocuments."' and company_id='".$customer_id."'";
$RecyclerGSTIN = $sign->SelectF($qry,"GSTIN");

$qry6="Select document_number from tw_company_document where document_type='".$settingValueGSTDocuments."' and company_id='".$wbscompany_id."'";
$AggGST = $sign->SelectF($qry6,"document_number");

$qry1="Select value as email from tw_company_contact where company_id='".$wbscompany_id."'";
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

$query1="SELECT tnc_value as tnc FROM tw_tnc where company_id='".$wbscompany_id."' and tnc_for='wbs'";
$tnc = $sign->FunctionJSON($query1);
$decodedJSON6 = json_decode($tnc);
$tnc = $decodedJSON6->response[0]->tnc;



$qry4="select address_line1,address_line2,location,pincode,city,state,country from tw_company_address where id='".$company_address."';";
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
    $encValue=$requestid."~".$outwardid;
	$encValue=$commonfunction->CommonEnc($encValue);
	$encValue=urlencode($encValue);
    $Link=$settingValueVerifyLink."pgWaybillslip.php?q=".$encValue;
    $valqrfunction = $qrfunction->GetQrcode($Link,$settingValueUserImagePathOther);


//Recycler Query
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
	
	$Signatureqry="select approved_by,prepared_by,for_company from tw_digital_signature  where  form_type='".$settingValueWBS."' AND company_id='".$wbscompany_id."' ";
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
<title>Weigh Bridge Slip</title>
<link rel="stylesheet" href="../assets/css/custom/style.css" />
<link rel="shortcut icon" href="../assets/images/favicon.png" />
</head>



<body>
<div id="printarea">
<table class="printtbl-noborder">
<tr>
<td width="75px" class="left-text"><img src="<?php echo $logopath;?>" width="70px" /></td>

<td> <strong><?php echo $CompanyName;?></strong><br><?php echo $companyaddress;?><br>GSTIN: <?php echo $AggGST;?><br>E-Mail: <?php echo $email;?> </td>


<td class="right-text"><img src="<?php echo $valqrfunction; ?>" width="50px" />  </td>
</tr>
</table>

<table class="printtbl">

	<tr>
	<td width="75%"><h2>Weigh Bridge Slip</h2></td>
	<td width="25%" class="right-text"> Ref.Number: <b><?php echo $requestid;?></b><br>Dated: <b><?php echo date("d-m-Y h:m a",strtotime($cur_date)); ?></b>
	</tr>
	
</table>
<table class="printtbl">
	<tr style="text-align:left;">
		<td>
		Vendor: <strong><?php echo $RecyclerName;?></strong><br><?php echo $shipto;?><br>GSTIN/UIN:<?php echo $RecyclerGSTIN;?></td> 
	</tr>
</table>

<table class="printtbl">
	
	<tr>
		<td>Vehicle Number: <strong><?php echo $carrier_no; ?></strong></td>
		<td>Vehicle Type: <strong><?php echo $VehicleType; ?></strong></td>
		<td>Driver Name: <strong><?php echo $driver_name; ?></strong></td>
		<td>Mode of Transport: <strong><?php echo $mode_of_transport; ?></strong></td>
	</tr>
</table>
<table class="printtbl">
<tr>
<td></td>
<td class="center-text">Weight in Kgs</td>
<td class="center-text">Date and Time</td>
</tr>
<tr>
<td width="33%"> Gross Weight</td>
<td width="34%"><strong><?php echo $gross_weight;?></strong></td>
<td width="33%"><strong><?php echo date("d-m-Y h:m a",strtotime($gross_weight_date_time));?></strong></td>

</tr>
<tr>
<td width="33%"> Tare Weight</td> 
<td width="34%"><strong><?php echo $tare_weight;?></strong></td>
<td width="33%"><strong><?php echo date("d-m-Y h:m a",strtotime($tare_weight_date_time));?></strong></td>
</tr>
<tr>
<td width="33%"> Net. Weight</td> 
<td width="34%"><strong><?php echo $net_weight;?></strong></td>
<td width="33%"></td>
</tr>
  
</table>
<table class="printtbl">
	<tr>
		<td width="60%" rowspan="2">Terms And Conditions: <?php echo $tnc; ?></td>
		<td width="40%">Amount: <strong>&#8377; <?php echo number_format($amount_received,2); ?></strong></td>
	</tr> 
	<tr>
		<td width="40%">Operator:<?php echo $prepared_by_img; ?> </td>
	</tr>
 </table>
 </div><br>	
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
</html>