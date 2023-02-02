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
$settingValueUserImagePathOther= $commonfunction->getSettingValue("UserImagePathOther");
$settingValueCompanyImage= $commonfunction->getSettingValue("Company Image");
$settingValuePrimaryEmail= $commonfunction->getSettingValue("Primary Email");
$settingValueWebsite= $commonfunction->getSettingValue("Website");
$settingValueCIN= $commonfunction->getSettingValue("CIN");
$settingValueDocEPRCertificate= $commonfunction->getSettingValue("DocEPRCertificate");
$settingValueRegistrationno= $commonfunction->getSettingValue("Registration no");
$settingValueVerifyLink= $commonfunction->getSettingValue("VerifyLink");

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

$AllocationCompanyQry="SELECT CompanyName FROM `tw_company_details` where id='".$supplier_id."'";
$AllocationCompany=$sign->SelectF($AllocationCompanyQry,"CompanyName");

$shortCMPName= substr($AllocationCompany, 0, 3);
$shortstate= substr($state, 0, 3);

$CompanyDetailsQry="SELECT cd.Company_Logo ,concat( ca.address_line1,' ',ca.address_line2,' ',ca.location,' ',ca.pincode,' ',ca.city,' ',ca.state,' ',ca.country)as address FROM tw_company_details cd INNER JOIN tw_company_address ca ON cd.ID=ca.company_id where cd.ID='".$supplier_id."' AND ca.default_address='true' ";
$CompanyDetails = $sign->FunctionJSON($CompanyDetailsQry);
$decodedJSON1 = json_decode($CompanyDetails);
$Company_Logo = $decodedJSON1->response[0]->Company_Logo; 
$Cmpaddress = $decodedJSON1->response[1]->address; 

$cmpAddQry="SELECT concat( ca.address_line1,' ',ca.address_line2,' ',ca.location,' ',ca.pincode,', ',ca.city,', ',ca.state,' ',ca.country)as address FROM tw_company_details cd INNER JOIN tw_company_address ca ON cd.ID=ca.company_id where cd.ID='".$company_id."' AND ca.default_address='true' ";
$cmpADD=$sign->SelectF($cmpAddQry,"address");

$CMPEMAILQRY="select value from tw_company_contact where company_id='".$supplier_id."' AND 	contact_field='".$settingValuePrimaryEmail."' ";
$EMAIL= $sign->SelectF($CMPEMAILQRY,"value");

$CINqry="SELECT document_number FROM tw_company_document where company_id='".$company_id."' AND document_type='".$settingValueCIN."' ";
$CIN = $sign->SelectF($CINqry,"document_number");

// echo $Stateqry="SELECT state_name FROM `tw_state_master` where id='".$state."' ";
// $StateName = $sign->SelectF($Stateqry,"state_name");

$Registrationqry="SELECT document_number FROM tw_company_document where company_id='".$company_id."' AND  document_type='".$settingValueRegistrationno."' ";
$Registration = $sign->SelectF($Registrationqry,"document_number");

if($Company_Logo==""){
  $Company_Logo = $settingValueUserImagePathOther.$settingValueCompanyImage;
}
else{
	$Company_Logo = $settingValueUserImagePathVerification.$EMAIL."/".$Company_Logo;
}
$encValue=$po_id."~".$s_id;
$encValue=$commonfunction->CommonEnc($encValue);
$encValue=urlencode($encValue);
$Link=$settingValueVerifyLink."pgEPRCertificate.php?q=".$encValue;
$valqrfunction = $qrfunction->GetQrcode($Link,$settingValueUserImagePathOther);

$email_query = "select value from tw_company_contact where company_id = '".$requestid_sid."' and contact_field='".$settingValuePemail."'";
$email_val = $sign->SelectF($email_query,'value');

	/*$Signatureqry="select approved_by,prepared_by,for_company from tw_digital_signature  where form_type='".$settingValuePo."' AND company_id='".$company_id."' AND employee_id='".$created_by."' ";
	$retvalSignatureQry=$sign->FunctionJSON($Signatureqry);
	$decodedJSONSign = json_decode($retvalSignatureQry);
	$approved_by=$decodedJSONSign->response[0]->approved_by;
	$prepared_by=$decodedJSONSign->response[1]->prepared_by;
	$for_company=$decodedJSONSign->response[2]->for_company;*/

$sign_query="select approved_by,prepared_by,for_company from tw_digital_signature where form_type='".$settingValueDocEPRCertificate."' and company_id='".$requestid_sid."'";
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
$shortmonth= substr($monthWord, 0, 3);
$year1=date_format($date,"Y");
if($month>04){
	$year=$year1+1;
}
else{
	$year=$year1-1;
}
$shortyear= substr($year, 2, 4);
$shortyear1= substr($year1, 2, 4);
$ProductQry="SELECT cm.category_name FROM `tw_po_details` pd INNER JOIN tw_product_management pm ON pd.product_id=pm.id INNER JOIN tw_category_master cm ON pm.category=cm.id where pd.po_id='".$po_id."' limit 1";
$Productcategory_name = $sign->SelectF($ProductQry,"category_name");
?>
<!DOCTYPE html>
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
    <td width="75%" class="center-text"><h2>EPR CERTIFICATE</h2></td>

   
  </tr>
</table>
	
	<table class="printtbl">
  <tr>
    <td width="25%" class="center-text">Client</td>
    <td width="75%" class="center-text"><?php echo $CompanyName; ?></td>
   
  </tr>
  <tr>
    <td width="25%" class="center-text">Category</td>
    <td width="75%" class="center-text"><?php echo $company_type; ?></td>
   
  </tr>
  <tr>
    <td width="25%" class="center-text">Address</td>
    <td width="75%" class="center-text"><?php echo $cmpADD; ?></td>
   
  </tr>
  <tr>
    <td width="25%" class="center-text">Finacial Year</td>
    <td width="75%" class="center-text">April <?php echo $year1; ?> - March <?php echo $year; ?></td>
   
  </tr>
  <tr>
    <td width="25%" class="center-text">Period</td>
    <td width="75%" class="center-text"><?php if($month >= "04" && $month <= "06" ) { echo "Quater 1" ;} else if($month >= "07" && $month <= "09"){ echo "Quater 2"; } else if($month >= "10" && $month <= "12"){ echo "Quater 3"; }else{ echo "Quater 4 " ;} ?></td>
   
  </tr>
  <tr>
    <td width="25%" class="center-text">Month</td>
    <td width="75%" class="center-text"><?php echo $monthWord; ?> &nbsp;<?php echo $year1; ?> </td>
   
  </tr>
  <tr>
    <td width="25%" class="center-text">State</td>
    <td width="75%" class="center-text"><?php echo $state; ?></td>
   
  </tr>
  <tr>
    <td width="25%" class="center-text">Waste Stream</td>
    <td width="75%" class="center-text"><?php echo $Productcategory_name; ?> </td>
   
  </tr>
  <tr>
    <td width="25%" class="center-text">Total Quantity</td>
    <td width="75%" class="center-text"><b><?php echo $total_quantity; ?>KG</b></td>
   
  </tr>
   <tr>
    <td width="25%" class="center-text">Quantity Allocation</td>
    <td width="75%" class="center-text"><b><?php echo $AllocationCompany; ?></b></td>
   
  </tr>
  <tr>
  <td colspan="2" class="center-text"><h5>Registration under rule 13(3) and (4) of Plastic Waste Management Rules, 2016<br>
  Registration no. <?php echo $Registration; ?> </h5></td>
   
  </tr>

   <tr>
   <td width="25%" class="center-text">Certificate Number</td>
   <td width="75%" class="center-text"><b>EPR/<?php echo $shortCMPName; ?>/<?php echo $shortstate ?>/<?php echo $shortmonth; ?>/<?php echo $po_id; ?>/<?php echo $shortyear1; ?>-<?php echo $shortyear; ?></b></td>
    
   
  </tr>
  <tr>
  <td width="25%" class="center-text">Issued Date</td>
  <td width="75%" class="center-text"><b><?php echo  date("d-m-Y",strtotime($date_of_po));  ?></b></td>
	</tr>
</table>
<table class="printtbl">
		<tr>
			<td width="35%" class="left-text top-align"><b>Prepared By</b><br>
			
			<?php echo $prepared_by_img; ?> </td>
			<td width="35%" class="left-text top-align"><b>Approved By</b><br><?php echo $approved_by_img; ?><br>
			</td>
			<td width="30%" class="right-text top-align"> For <b><?php echo $CompanyName;?> </b><br><?php echo $for_company_img; ?><br><br><br><br><br></td>
		</tr>
</table>
<br>
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
