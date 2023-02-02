<?php
include_once "function.php";
$sign=new Signup();
include_once "commonFunctions.php";
$commonfunction=new Common();
include_once "Qrfunction.php";
$qrfunction=new TwQr();

$po_id = $_REQUEST["po_id"];
$s_id = $_REQUEST["supid"];
$requestid_sid=$_REQUEST['supid'];
$ip_address= $commonfunction->getIPAddress();
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");
$settingValuePemail= $commonfunction->getSettingValue("Primary Email");
$settingValueUserImagePathVerification= $commonfunction->getSettingValue("UserImagePathVerification");
$settingValueCompanyImage= $commonfunction->getSettingValue("Company Image");
$settingValueUserImagePathOther= $commonfunction->getSettingValue("UserImagePathOther");
$settingValuePrimaryEmail= $commonfunction->getSettingValue("Primary Email");
$settingValueWebsite= $commonfunction->getSettingValue("Website");
$settingValueCIN= $commonfunction->getSettingValue("CIN");
$settingValueDocEPRTAbulated= $commonfunction->getSettingValue("DocEPRTAbulated");
$settingValueGSTDocuments= $commonfunction->getSettingValue("GSTDocuments");
$settingValueApprovedStatus= $commonfunction->getSettingValue("Approved Status");
$settingValueCompletedStatus= $commonfunction->getSettingValue("Completed Status");


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
$encValue=$po_id."~".$s_id;
$encValue=$commonfunction->CommonEnc($encValue);
$encValue=urlencode($encValue);
$Link="https://verify.trace-waste.com/documents/pgViewTabulatedDetails.php?q=".$encValue;
$valqrfunction = $qrfunction->GetQrcode($Link,$settingValueUserImagePathOther);

$email_query = "select value from tw_company_contact where company_id = '".$supplier_id."' and contact_field='".$settingValuePemail."'";
$email_val = $sign->SelectF($email_query,'value');

$sign_query="select approved_by,prepared_by,for_company from tw_digital_signature where form_type='".$settingValueDocEPRTAbulated."'  and company_id='".$supplier_id."'";
$sign_val = $sign->FunctionJSON($sign_query);
$sign_val_json = json_decode($sign_val);

$approved_by=$sign_val_json->response[0]->approved_by;
$prepared_by=$sign_val_json->response[1]->prepared_by;
$for_company=$sign_val_json->response[2]->for_company;

$approved_by_img="";
$prepared_by_img="";
$for_company_img="";

	
	if($approved_by!==""){
		    $approved_by_img="<img src=".$settingValueUserImagePathVerification.$email_val."/".$approved_by." width='70px' />";
	}
	else{
		$approved_by_img="";
	}
	if($prepared_by!==""){
		$prepared_by_img="<img src=".$settingValueUserImagePathVerification.$email_val."/".$prepared_by." width='70px' />";
	}
	else{
		$prepared_by_img="";
	}
	if($for_company!==""){
		$for_company_img="<img src=".$settingValueUserImagePathVerification.$email_val."/".$for_company." width='70px' />";
	}
	else{
		$for_company_img="";
	}
  
 
$date=date_create($date_of_po);
$month=date_format($date,"m");
$monthWord=date_format($date,"M");
$year1=date_format($date,"Y");

?>
<html>
<head>
<title>EPR Certificate</title>
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
    <td width="75%" class="center-text"><h2>TABULATED DETAILS FOR THE MONTH OF <br><?php echo $monthWord; ?> <?php echo $year1; ?></h2></td>
</tr>
</table> 
<table class="printtbl">
  <th>Annexures </th>
  <th>Aggregators </th>
  <th>City </th>
  <th>GSTIN </th>
  <th>Quantity (KG) </th>
  <?php 
	$detailsQry="SELECT md.m_id,md.alloted_company_id,md.quantity,md.state,md.city FROM tw_epr_material_assign_details md INNER JOIN tw_epr_material_assign_info mi ON md.m_id=mi.id where mi.po_id='".$po_id."' AND  mi.status='".$settingValueApprovedStatus."' ";
	$retVal7 = $sign->FunctionJSON($detailsQry);
	$decodedJSON7 = json_decode($retVal7);

	$qry2="SELECT count(*) as cnt FROM tw_epr_material_assign_details md INNER JOIN tw_epr_material_assign_info mi ON md.m_id=mi.id where mi.po_id='".$po_id."' AND  mi.status='".$settingValueApprovedStatus."'";
	$retVal2 = $sign->Select($qry2);
	$totalquantity = 0;
	$count = 0;
	$i = 1;
	$x=$retVal2;
	$it=1;
		while($x>=$i){
            $m_id = $decodedJSON7->response[$count]->m_id;
			$count=$count+1;  
			$alloted_company_id = $decodedJSON7->response[$count]->alloted_company_id;
			$count=$count+1;
			$quantity = $decodedJSON7->response[$count]->quantity;
			$count=$count+1;
			$state = $decodedJSON7->response[$count]->state;
			$count=$count+1;
			$city = $decodedJSON7->response[$count]->city;
			$count=$count+1;
			$totalquantity=$totalquantity+$quantity;
			
$companyNameQry="SELECT cd.CompanyName,doc.document_number FROM `tw_company_details` cd INNER JOIN tw_company_document doc on cd.ID=doc.company_id where doc.document_type='".$settingValueGSTDocuments."' AND cd.ID='".$alloted_company_id."'";
$DocValpo = $sign->FunctionJSON($companyNameQry);
$decodedJSON1 = json_decode($DocValpo);
$CompanyName = $decodedJSON1->response[0]->CompanyName; 
$document_number = $decodedJSON1->response[1]->document_number; 
   
$stateNameQry="SELECT state_name FROM `tw_state_master` where id='".$state."'";
$stateName=$sign->SelectF($stateNameQry,"state_name"); 

$cityNameQry="SELECT city_name FROM `tw_city_master` where id='".$city."'";
$cityName=$sign->SelectF($cityNameQry,"city_name");
	  ?>
    
<tr>
	<td class="center-text"><?php echo $it;?></td>
	<td class="left-text"><?php echo $CompanyName;?></td>
	<td class="center-text"><?php echo $cityName;?></td>
	<td class="center-text"><?php echo $document_number;?></td>
	<td class="center-text"><?php echo $quantity;?></td>
</tr> 
<?php 
	$it=$i+1;
	$i=$i+1;
	}  ?>
<tr>
	<td colspan="4" class="center-text"><strong>Total Quantity </strong></td>
	<td class="center-text"><strong><?php echo number_format($totalquantity,2); ?><strong></td>
</tr>	
<table class="printtbl">
	<tr>
		<td width="35%" class="left-text top-align"><b>Prepared By</b><br>
			<?php echo $prepared_by_img; ?> </td>
		<td width="35%" class="left-text top-align"><b>Approved By</b><br><?php echo $approved_by_img; ?><br>
		</td>
		<td width="30%" class="right-text top-align"> For <b><?php echo $CompanyName;?> </b><br><?php echo $for_company_img; ?><br><br><br><br><br></td>
	</tr>
</table>
</table>
<br>

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