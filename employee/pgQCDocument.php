<?php
session_start();
if(!isset($_SESSION["employee_id"])){
	header("Location:pgEmployeeProfile.php");
}
// Include class definition
include_once "function.php";
$sign=new Signup();

include_once "Qrfunction.php";
$qrfunction=new TwQr();

//$requesttype = $_REQUEST["type"];
$requestid = $_REQUEST["id"];
$inward_id = $_REQUEST["inward_id"];

//whether ip is from share internet
include_once "commonFunctions.php";
$commonfunction=new Common();
$ip_address= $commonfunction->getIPAddress();
$settingValueUserImagePathVerification= $commonfunction->getSettingValue("UserImagePathVerification");
$settingValueUserPrimaryEmail= $commonfunction->getSettingValue("Primary Email");
$settingValueUserImagePathOther= $commonfunction->getSettingValue("UserImagePathOther");
$settingValueGSTDocuments= $commonfunction->getSettingValue("GSTDocuments");
$settingValueQC= $commonfunction->getSettingValue("DocQC");
$settingValueVerifyLink= $commonfunction->getSettingValue("VerifyLink");
$settingValueCompanyImage = $commonfunction->getSettingValue("Company Image");

date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d");

$query="Select MI.id,MI.company_id,MI.party_id,MI.party_bill_no,MI.date as mdate,MI.vehicle_no,MI.remark,CD.CompanyName,CD.Company_Logo,cc.value from tw_material_inward_qc MI INNER JOIN tw_company_details CD on MI.company_id=CD.id INNER JOIN tw_company_contact cc on MI.company_id=cc.company_id  where MI.inward_id='".$inward_id."' AND cc.contact_field='".$settingValueUserPrimaryEmail."' ";
$DocVal = $sign->FunctionJSON($query);
$decodedJSON = json_decode($DocVal);

$id = $decodedJSON->response[0]->id; 
$company_id = $decodedJSON->response[1]->company_id; 
$party_id = $decodedJSON->response[2]->party_id;
$party_bill_no = $decodedJSON->response[3]->party_bill_no;
$Fetchdate = $decodedJSON->response[4]->mdate;
$vehicle_no = $decodedJSON->response[5]->vehicle_no;
$remark = $decodedJSON->response[6]->remark;
$CompanyName = $decodedJSON->response[7]->CompanyName;
$Company_Logo = $decodedJSON->response[8]->Company_Logo;
$email = $decodedJSON->response[9]->value;
if($Company_Logo==""){
	$logopath = $settingValueUserImagePathOther.$settingValueCompanyImage;
}
else{
	$logopath = $settingValueUserImagePathVerification.$email."/".$Company_Logo;
}
$qryp="SELECT CompanyName FROM tw_company_details WHERE id='".$party_id."'";
$retValp = $sign->SelectF($qryp,"CompanyName");
	
$qry7="select document_number from tw_company_document where company_id='".$company_id."' and document_type='".$settingValueGSTDocuments."'";
$retVal7 = $sign->FunctionJSON($qry7);
$decodedJSON7 = json_decode($retVal7);
$gstnumbercompanyto = $decodedJSON7->response[0]->document_number;

$qry8="SELECT company_address,ship_to,vehicle_id FROM tw_material_outward where id='".$inward_id."'";
$retVal8 = $sign->FunctionJSON($qry8);
$decodedJSON = json_decode($retVal8);
$company_address = $decodedJSON->response[0]->company_address;
$bill_to = $decodedJSON->response[1]->ship_to;
$vehicle_id = $decodedJSON->response[2]->vehicle_id;

$qryv="select tm.mode_of_transport from tw_vehicle_details_master vdm INNER JOIN tw_transport_master tm ON vdm.transporter_id=tm.id  where vdm.id='".$vehicle_id."'";
$valtransport = $sign->FunctionJSON($qryv);
$decodedJSONSign = json_decode($valtransport);
$mode_of_transport=$decodedJSONSign->response[0]->mode_of_transport;

$encValue=$requestid."~".$inward_id;
$encValue=$commonfunction->CommonEnc($encValue);
$encValue=urlencode($encValue);
$Link=$settingValueVerifyLink."pgQCDocument.php?q=".$encValue;
$valqrfunction = $qrfunction->GetQrcode($Link,$settingValueUserImagePathOther);

$qry3="select address_line1,address_line2,location,pincode,city,state,country from tw_company_address where id='".$bill_to."'";
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
	
	$bill_to=$address_line1.", ".$address_line2.", ".$location.", ".$city." ".$pincode.", ".$cstate.", ".$country;
	
$qry4="select address_line1,address_line2,location,pincode,city,state,country from tw_company_address where id='".$company_address."'";
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
		$cstate = $decodedJSON4->response[5]->state;
	}
	else
	{
		$cstate="";
	}
	if(isset($decodedJSON4->response[6]->country))
	{
		$country = $decodedJSON4->response[6]->country;
	}
	else
	{
		$country="";
	}
	
	$company_address=$address_line1.", ".$address_line2.", ".$location.", ".$city." ".$pincode.", ".$cstate.", ".$country;
	
	$Signatureqry="select approved_by,prepared_by,for_company from tw_digital_signature  where  form_type='".$settingValueQC."' AND company_id='".$company_id."' ";
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

<!DOCTYPE html>
<html>
<head>
<title>QC</title>
<link rel="stylesheet" href="../assets/css/custom/style.css" />
<link rel="shortcut icon" href="../assets/images/favicon.png" />
</head>
<body>
<div id="printarea">
<table class="printtbl-noborder">
	<tr>
		<td width="75px" class="left-text">
			<img src="<?php echo $logopath;?>" width="70px" />
		</td>
		<td>
			<strong><?php echo $CompanyName;?></strong><br><?php echo $bill_to; ?></b>
			<br>GSTIN/UIN: <?php echo $gstnumbercompanyto;?><br>E-Mail: <?php echo $email;?>
		</td>
		<td class="right-text"><img src="<?php echo $valqrfunction; ?>" width="50px" /></td>
	</tr>
</table>
	
	<table class="printtbl">
		<tr>
			<td width="75%"><h2>Quality Certificate</h2></td>
			<td width="25%" class="right-text">
				Date: <strong><?php echo date("d-m-Y",strtotime($Fetchdate));?></strong><br>Vehicle Number: <strong><?php echo $vehicle_no;?> </strong> | Mode of Transport: <strong><?php echo $mode_of_transport; ?></strong>
			</td>
		</tr>
		
	</table>
		
	<table class="printtbl">
		<tr>
			<td> Vendor: <strong><?php echo $retValp;?></strong><br><?php echo $company_address; ?><br>Vendor Bill Number: <strong><?php echo $party_bill_no;;?> </strong></td>
		</tr>
	</table>
	<table class="printtbl"> 
	

	<tr>
		<th width="5%";><b>#</b></th>
		<th width="35%";><b>Description</b></th>
		<th width="10%";><b>Norms</b></th>
		<th width="10%";><b>1st</b></th>
		<th width="10%";><b>2nd</b></th>
		<th width="10%";><b>3nd</b></th>
		<th width="10%";><b>Total</b></th>
		<th width="10%";><b>Average</b></th>
	</tr>
	<?php 
		 $query1="Select id,description,norms,first,second,third,total,average from tw_material_inward_qc_individual where material_inward_qc_id='".$requestid."'  ";
		 $DocVal = $sign->FunctionJSON($query1);
		
		 $qry2="Select count(*) as cnt from tw_material_inward_qc_individual where material_inward_qc_id='".$requestid."' order by id Desc";
		 $retVal2 = $sign->Select($qry2);
		
		$decodedJSON2 = json_decode($DocVal);
		$count = 0;
		$i = 1;
		$x=$retVal2;
		$it=0;
		$table="";
		$retVal4="";
			while($x>=$i){
				
				
			$id = $decodedJSON2->response[$count]->id;
			$count=$count+1;
			$description = $decodedJSON2->response[$count]->description;
			$count=$count+1;
			$norms = $decodedJSON2->response[$count]->norms;
			$count=$count+1;
			$first = $decodedJSON2->response[$count]->first;
			$count=$count+1;
			$second = $decodedJSON2->response[$count]->second;
			$count=$count+1;
			$third = $decodedJSON2->response[$count]->third;
			$count=$count+1;
			$total = $decodedJSON2->response[$count]->total;
			$count=$count+1;
			$average = $decodedJSON2->response[$count]->average;
			$count=$count+1;
			
			//$i=$i+1;
			$qry4 = "select description from tw_test_report_designation_master where id='".$description."'  ";
			$retVal4 = $sign->SelectF($qry4,'description');
			
			$i=$i+1;
			$it=$it+1;
			//echo $table;
	
	?>
		<tr>
			<td><?php echo $it; ?></td>
			<td><?php echo $retVal4; ?></td>
			<td><?php echo $norms; ?></td>
			<td><?php echo $first; ?></td>
			<td><?php echo $second; ?></td>
			<td><?php echo $third; ?></td>
			<td><?php echo $total; ?></td>
			<td><?php echo $average; ?></td>
		</tr>
			<?php }?>
	<tr>
		<td colspan="8">Remarks: <br><?php echo $remark;?></td>
	
	</tr>
	</table>
		<table class="printtbl">
		<tr>
			<td width="35%" class="left-text top-align"><b>Prepared By</b><br><?php echo $prepared_by_img; ?></td>
			<td width="35%" class="left-text top-align"><b>Approved By</b><br><?php echo $approved_by_img; ?>
			</td>
			<td width="30%" class="right-text top-align"> For <b><?php echo $CompanyName;?> </b><br><?php echo $for_company_img; ?></td>
		</tr>
	</table>
	</div>
	<br>
	<div class="center-text">
	<button id="printPageButton" onclick="printDiv('printarea');">Print</button>
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
</html>