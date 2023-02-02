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
  
$settingValuePemail= $commonfunction->getSettingValue("Primary Email");
$settingValueUserImagePathVerification= $commonfunction->getSettingValue("UserImagePathVerification");
$settingValueCompanyImage= $commonfunction->getSettingValue("Company Image");
$settingValueUserImagePathOther= $commonfunction->getSettingValue("UserImagePathOther");
$settingValuePrimaryEmail= $commonfunction->getSettingValue("Primary Email");
$settingValueCIN= $commonfunction->getSettingValue("CIN");
$settingValueDocEPRTAbulated= $commonfunction->getSettingValue("DocEPRTAbulated");
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
$encValue=$po_id;
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

$qrystateid="select state_name from tw_state_master where id ='".$state_id."'";
$statename=$sign->SelectF($qrystateid,"state_name");
?>
<html>
<head>
<title>Trace Waste | Tabulated Details</title>
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
    <td width="75%" class="center-text"><h2>TABULATED DETAILS FOR THE MONTH OF <br><?php echo $month_name; ?> <?php echo $year1; ?></h2></td>
</tr>
</table> 
<table class="printtbl">
  <th>Annexures </th>
  <th>Aggregators </th>
  <th>State </th>
  <th>GSTIN </th>
  <th>Quantity (KG) </th>
  <th>Recycler Name</th>
  <th>Address</th>
  <?php 
	$detailsQry="select aggeragator_name,dispatched_state,IFNULL (sum(replace(plant_quantity, ',', '')), 0) as plant_quantity,trader_name,trader_add as traderadd FROM tw_temp where po_id='".$po_id."' and dispatched_state='".$statename."' and status='".$settingValueCompletedStatus."' and plant_wbs_date like '".$rdatepo_id."%' GROUP by aggeragator_name";
	$retVal7 = $sign->FunctionJSON($detailsQry);
	$decodedJSON7 = json_decode($retVal7);

	$qry2="SELECT count(distinct(aggeragator_name)) as cnt FROM tw_temp where po_id='".$po_id."' and dispatched_state='".$statename."' and status='".$settingValueCompletedStatus."' and plant_wbs_date like '".$rdatepo_id."%'";
	$retVal2 = $sign->Select($qry2);
	$totalquantity = 0;
	$count = 0;
	$i = 1;
	$x=$retVal2;
	$it=1;
		while($x>=$i){
            $aggeragator_name = $decodedJSON7->response[$count]->aggeragator_name;
			$count=$count+1;  
			$dispatched_state = $decodedJSON7->response[$count]->dispatched_state;
			$count=$count+1;
			$plant_quantity = $decodedJSON7->response[$count]->plant_quantity;
			$count=$count+1;
			$trader_name = $decodedJSON7->response[$count]->trader_name;
			$count=$count+1;
			$traderadd = $decodedJSON7->response[$count]->traderadd;
			$count=$count+1;
			$gst="DFGHJK";
			$totalquantity=$totalquantity+(int)$plant_quantity;
		
	  ?>
    
<tr>
	<td class="center-text"><?php echo $it;?></td>
	<td class="left-text"><?php echo $aggeragator_name;?></td>
	<td class="center-text"><?php echo $dispatched_state;?></td>
	<td class="center-text"><?php echo $gst;?></td>
	<td class="right-text"><?php echo number_format($plant_quantity,2);?></td>
	<td class="left-text"><?php echo $trader_name;?></td>
	<td class="left-text"><?php echo $traderadd;?></td>
</tr> 
<?php 
	$it=$i+1;
	$i=$i+1;
	}  ?>
<tr>
	<td colspan="4" class="right-text"><strong>Total Quantity </strong></td>
	<td class="right-text"><strong><?php echo number_format($totalquantity,2); ?><strong></td>
	<td colspan="2" class="right-text"></td>
</tr>	
<table class="printtbl">
	<tr>
		<td width="35%" class="left-text top-align"><b>Prepared By</b><br>
			<?php echo $prepared_by_img; ?> </td>
		<td width="35%" class="left-text top-align"><b>Approved By</b><br><?php echo $approved_by_img; ?><br>
		</td>
		<td width="30%" class="right-text top-align"> For <b><?php echo $AllocationCompany;?> </b><br><?php echo $for_company_img; ?><br><br><br><br><br></td>
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