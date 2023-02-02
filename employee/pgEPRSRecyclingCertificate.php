<?php 
include_once "function.php";
$sign=new Signup();
include_once "commonFunctions.php";
$commonfunction=new Common();
include_once "Qrfunction.php";
$qrfunction=new TwQr();

$po_id = $_REQUEST["po_id"];
$rdatepo_id = $_REQUEST["date"];
$state_id = $_REQUEST["state"];

$ip_address= $commonfunction->getIPAddress();
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");
$monthname=explode("-",$rdatepo_id);
$month_name = date("F", mktime(0, 0, 0, $monthname[1], 10));
$settingValueUserImagePathVerification= $commonfunction->getSettingValue("UserImagePathVerification");
$settingValueCompanyImage= $commonfunction->getSettingValue("Company Image");
$settingValueUserImagePathOther= $commonfunction->getSettingValue("UserImagePathOther");
$settingValuePrimaryEmail= $commonfunction->getSettingValue("Primary Email");
$settingValueWebsite= $commonfunction->getSettingValue("Website");
$settingValueCIN= $commonfunction->getSettingValue("CIN");
$settingValueEPRRecycling= $commonfunction->getSettingValue("EPRRecycling");
$settingValueApprovedStatus= $commonfunction->getSettingValue("Approved Status");
$settingValueCompletedStatus= $commonfunction->getSettingValue("Completed Status");

$settingValueVerifyLink = $commonfunction->getSettingValue("VerifyLink");

$querypo="Select poi.id,poi.supplier_id,poi.company_id,poi.supplier_address_id,poi.date_of_po,poi.total_quantity,cd.CompanyName, ca.address_line1,ca.address_line2,ca.location,ca.pincode,ca.city,ca.state,ca.country,ctm.company_type,poi.created_by from tw_po_info poi INNER join tw_company_details cd on poi.company_id=cd.ID INNER JOIN tw_company_address ca ON poi.supplier_address_id=ca.id INNER JOIN tw_company_type_master ctm ON cd.CompanyType=ctm.id where poi.id='".$po_id."'";
$DocValpo = $sign->FunctionJSON($querypo);
$decodedJSON = json_decode($DocValpo);
$id = $decodedJSON->response[0]->id; 
$supplier_id = $decodedJSON->response[1]->supplier_id; 
$company_id = $decodedJSON->response[2]->company_id; 
$supplier_address_id = $decodedJSON->response[3]->supplier_address_id; 
$date_of_po = $decodedJSON->response[4]->date_of_po; 
$total_quantity = $decodedJSON->response[5]->total_quantity; 
$CompanyName = $decodedJSON->response[6]->CompanyName; 
$address_line1 = $decodedJSON->response[7]->address_line1; 
$address_line2 = $decodedJSON->response[8]->address_line2; 
$location = $decodedJSON->response[9]->location; 
$pincode = $decodedJSON->response[10]->pincode; 
$city = $decodedJSON->response[11]->city; 
$state = $decodedJSON->response[12]->state; 
$country = $decodedJSON->response[13]->country; 
$company_type = $decodedJSON->response[14]->company_type; 
$created_by = $decodedJSON->response[15]->created_by; 

$Address=$address_line1.", ".$address_line2.", ".$location.", ".$city.", ".$pincode.", ".$state.", ".$country;

$AllocationCompanyQry="SELECT CompanyName FROM tw_company_details where id='".$supplier_id."'";
$AllocationCompany=$sign->SelectF($AllocationCompanyQry,"CompanyName");

$CompanyDetailsQry="SELECT cd.Company_Logo ,concat( ca.address_line1,' ',ca.address_line2,' ',ca.location,' ',ca.pincode,' ',ca.city,' ',ca.state,' ',ca.country)as address FROM tw_company_details cd INNER JOIN tw_company_address ca ON cd.ID=ca.company_id where cd.ID='".$supplier_id."' AND ca.default_address='true' ";
$CompanyDetails = $sign->FunctionJSON($CompanyDetailsQry);
$decodedJSON1 = json_decode($CompanyDetails);
$Company_Logo = $decodedJSON1->response[0]->Company_Logo; 
$Cmpaddress = $decodedJSON1->response[1]->address; 


$CMPEMAILQRY="select value from tw_company_contact where company_id='".$supplier_id."' AND 	contact_field='".$settingValuePrimaryEmail."' ";
$EMAIL= $sign->SelectF($CMPEMAILQRY,"value");

$CINqry="SELECT document_number FROM tw_company_document where company_id='".$company_id."' AND document_type='".$settingValueCIN."'";
$CIN= $sign->SelectF($CINqry,"document_number");

if($Company_Logo==""){
  $Company_Logo = $settingValueUserImagePathOther.$settingValueCompanyImage;
}
else{
	$Company_Logo = $settingValueUserImagePathVerification.$EMAIL."/".$Company_Logo;
}

	$Signatureqry="select approved_by,prepared_by,for_company from tw_digital_signature  where  form_type='".$settingValueEPRRecycling."' AND company_id='".$supplier_id."' ";
	$retvalSignatureQry=$sign->FunctionJSON($Signatureqry);
	$decodedJSONSign = json_decode($retvalSignatureQry);
	$approved_by=$decodedJSONSign->response[0]->approved_by;
	$prepared_by=$decodedJSONSign->response[1]->prepared_by;
	$for_company=$decodedJSONSign->response[2]->for_company;
	
	if($approved_by!==""){
		    $approved_by_img="<img src=".$settingValueUserImagePathVerification.$EMAIL."/".$approved_by." width='70px' />";
	}
	else{
		$approved_by_img="";
	}
	if($prepared_by!==""){
		$prepared_by_img="<img src=".$settingValueUserImagePathVerification.$EMAIL."/".$prepared_by." width='70px' />";
	}
	else{
		$prepared_by_img="";
	}
	if($for_company!==""){
		$for_company_img="<img src=".$settingValueUserImagePathVerification.$EMAIL."/".$for_company." width='70px' />";
	}
	else{
		$for_company_img="";
	}
	
	
	$productQry="SELECT pm.category FROM tw_po_details pd INNER JOIN tw_product_management pm where pd.po_id='".$po_id."' LIMIT 1 ";
	$product= $sign->SelectF($productQry,"category");
	
	$categoryNameQry="SELECT epr_category_name FROM `tw_epr_category_master` where id ='".$product."'";
	$categoryName=$sign->SelectF($categoryNameQry,"epr_category_name");
	
	$date=date_create($date_of_po);
   
$encValue=$po_id;
$encValue=$commonfunction->CommonEnc($encValue);
$encValue=urlencode($encValue);
$Link=$settingValueVerifyLink."pgEPRRecyclingCertificate.php?q=".$encValue;
$valqrfunction = $qrfunction->GetQrcode($Link,$settingValueUserImagePathOther);

$qrystateid="select state_name from tw_state_master where id ='".$state_id."'";
$statename=$sign->SelectF($qrystateid,"state_name");

$qryplant_quantity="select IFNULL (sum(replace(plant_quantity, ',', '')), 0) as plant_quantity FROM tw_temp where po_id='".$po_id."' and dispatched_state='".$statename."' and status='".$settingValueCompletedStatus."' and plant_wbs_date like '".$rdatepo_id."%'";
$plant_quantity=$sign->SelectF($qryplant_quantity,"plant_quantity");

?>

<html>
<head>
<title>Trace Waste | Recycling Certificates</title>
<link rel="stylesheet" href="../assets/css/custom/style.css" />
<link rel="shortcut icon" href="../assets/images/favicon.png" />

</head>
<body>
<div id="printarea">
<table class="printtbl-noborder">
	<tr>
	<td width="75px" class="left-text"><img src="<?php echo $Company_Logo;?>" width="70px" /></td>
	<td> <strong><?php echo $AllocationCompany;?></strong><br><?php echo $Address;?><br>E-Mail: <?php echo $EMAIL;?><br>CIN: <?php echo $CIN;?> </td>
	<td width="90px" class="right-text"><img src="<?php echo $valqrfunction; ?>" width="50px" /></td>
	</tr>
	</table>
<table class="printtbl-noborder">
<tr>
    <td width="75%" class="center-text"><h2><u>COLLECTION / RECYCLING CERTIFICATE</u></h2></td>
  </tr>
</table>

<p class="display-4">The company here by certifies that the post-consumer plastic Category <?php echo $categoryName; ?> has been channelised from our aggregator network for EPR compliance of the client M/s. <?php echo $CompanyName; ?> from the state of <b><?php echo $statename; ?></b> of quantity <b><?php echo number_format($plant_quantity,2); ?></b> Kg for the month of <b> <?php  echo $month_name." ".$monthname[0]; ?></b></p>

<p class="display-4">We certify that we have recycled the above-mentioned quantity of post -consumer plastic at our plant Viz:780/3, 40 Shed Area, GIDC, Vapi – 396 195 .</p>
<p class="display-4">We further certify that the following mentioned quantity has not been accounted for / billed to any other entity for their EPR obligations.</p>
<br>
<br>

<table class="printtbl">
		<tr>
			<td width="35%" class="left-text top-align"><b>Prepared By</b><br>
			
			<?php echo $prepared_by_img; ?> </td>
			<td width="35%" class="center-text top-align"><b>Approved By</b><br><?php echo $approved_by_img; ?><br>
			</td>
			<td width="35%" class="right-text top-align"><b> For </b><?php echo $AllocationCompany;?> </b><br><?php echo $for_company_img; ?><br><br><br><br><br></td>
		</tr>
	</table>

</div>
	
<div class="center-text">
<button id="printPageButton"  onclick="printDiv('printarea');">Print</button>
</div>
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