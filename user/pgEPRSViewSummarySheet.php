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

$monthname=explode("-",$rdatepo_id);
$month_name = date("F", mktime(0, 0, 0, $monthname[1], 10));
$ip_address= $commonfunction->getIPAddress();
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");

$settingValueUserImagePathVerification= $commonfunction->getSettingValue("UserImagePathVerification");
$settingValueCompanyImage= $commonfunction->getSettingValue("Company Image");
$settingValueUserImagePathOther= $commonfunction->getSettingValue("UserImagePathOther");
$settingValuePrimaryEmail= $commonfunction->getSettingValue("Primary Email");
$settingValueWebsite = $commonfunction->getSettingValue("Website");
$settingValueCIN= $commonfunction->getSettingValue("CIN");
$settingValuePo= $commonfunction->getSettingValue("DocPo");
$settingValueGSTDocuments= $commonfunction->getSettingValue("GSTDocuments");
$settingValueApprovedStatus= $commonfunction->getSettingValue("Approved Status");
$settingValueCompletedStatus= $commonfunction->getSettingValue("Completed Status");
$settingValueVerifyLink = $commonfunction->getSettingValue("VerifyLink");
$settingUserImagePathEPRSDocument = $commonfunction->getSettingValue("UserImagePathEPRSDocument");

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
$encValue=$id;
$encValue=$commonfunction->CommonEnc($encValue);
$encValue=urlencode($encValue);
$Link=$settingValueVerifyLink."pgViewSummarySheet.php?q=".$encValue;
$valqrfunction = $qrfunction->GetQrcode($Link,$settingValueUserImagePathOther);

	$Signatureqry="select approved_by,prepared_by,for_company from tw_digital_signature  where  form_type='".$settingValuePo."' AND company_id='".$company_id."' AND employee_id='".$created_by."' ";
	$retvalSignatureQry=$sign->FunctionJSON($Signatureqry);
	$decodedJSONSign = json_decode($retvalSignatureQry);
	$approved_by=$decodedJSONSign->response[0]->approved_by;
	$prepared_by=$decodedJSONSign->response[1]->prepared_by;
	$for_company=$decodedJSONSign->response[2]->for_company;
	
	if($approved_by!==""){
		    $approved_by_img="<img src=".$settingValueUserImagePathVerification.$email."/".$prepared_by." width='70px' />";
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
 
  
$date=date_create($date_of_po);
  
$month=$monthname[0];
$year1=date_format($date,"Y");

$qrystateid="select state_name from tw_state_master where id ='".$state_id."'";
$statename=$sign->SelectF($qrystateid,"state_name");
?>
<html>
<head>
<title>Trace Waste | Summary Sheet</title>
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
    <td width="75%" class="center-text"><h2>EPR CERTIFICATE - <br><?php echo $month_name; ?> <?php echo $year1; ?></h2></td>  
</tr>
</table>

  <?php 
	$firstrun=true;
	$detailsQry="select aggeragator_name,grn_number,grnfile,purchase_invoice_number,purchase_invoice_date,dispatched_place,invoice_quantity,invoicefile,IFNULL (replace(plant_quantity, ',', ''), 0) as plant_quantity,aggregator_wbs_number,aggregator_wbs_date,wbsfile,plant_wbs_number,plant_wbs_date,pwbsfile,pwbsfile,vehicle_number,vehiclefile,eway_bill_number,ewayfile FROM tw_temp where po_id='".$po_id."' and dispatched_state='".$statename."' and status='".$settingValueCompletedStatus."' and plant_wbs_date like '".$rdatepo_id."%'";
	$retVal7 = $sign->FunctionJSON($detailsQry);
	$decodedJSON7 = json_decode($retVal7);

	$qry2="SELECT count(*) as cnt FROM tw_temp where po_id='".$po_id."' and dispatched_state='".$statename."' and status='".$settingValueCompletedStatus."' and plant_wbs_date like '".$rdatepo_id."%'";
	$retVal2 = $sign->Select($qry2);
	$totalquantity = 0.00;
	$count1 = 0;
	$i1 = 1;
	$x1=$retVal2;
	$it1=1;
	$varCurrenID = "";
	while($x1>=$i1){
            $aggeragator_name = $decodedJSON7->response[$count1]->aggeragator_name;
			$count1=$count1+1;
			$grn_number = $decodedJSON7->response[$count1]->grn_number;
			$count1=$count1+1;
			$grnfile = $decodedJSON7->response[$count1]->grnfile;
			$count1=$count1+1;
			$purchase_invoice_number = $decodedJSON7->response[$count1]->purchase_invoice_number;
			$count1=$count1+1;
			$purchase_invoice_date = $decodedJSON7->response[$count1]->purchase_invoice_date;
			$count1=$count1+1;
			$dispatched_place = $decodedJSON7->response[$count1]->dispatched_place;
			$count1=$count1+1;
		    $invoice_quantity = $decodedJSON7->response[$count1]->invoice_quantity;
			$count1=$count1+1;
		    $invoicefile = $decodedJSON7->response[$count1]->invoicefile;
			$count1=$count1+1;
		    $plant_quantity = $decodedJSON7->response[$count1]->plant_quantity;
			$count1=$count1+1;
		    $aggregator_wbs_number = $decodedJSON7->response[$count1]->aggregator_wbs_number;
			$count1=$count1+1;
		    $aggregator_wbs_date = $decodedJSON7->response[$count1]->aggregator_wbs_date;
			$count1=$count1+1;
		    $wbsfile = $decodedJSON7->response[$count1]->wbsfile;
			$count1=$count1+1;
		    $plant_wbs_number = $decodedJSON7->response[$count1]->plant_wbs_number;
			$count1=$count1+1;
		    $plant_wbs_date = $decodedJSON7->response[$count1]->plant_wbs_date;
			$count1=$count1+1;
		    $pwbsfile = $decodedJSON7->response[$count1]->pwbsfile;
			$count1=$count1+1;
		    $vehicle_number = $decodedJSON7->response[$count1]->vehicle_number;
			$count1=$count1+1;
		    $vehiclefile = $decodedJSON7->response[$count1]->vehiclefile;
			$count1=$count1+1;
		    $eway_bill_number = $decodedJSON7->response[$count1]->eway_bill_number;
			$count1=$count1+1;
		    $ewayfile = $decodedJSON7->response[$count1]->ewayfile;
			$count1=$count1+1;
			
			?>
				
			<?php 
				if($varCurrenID!=$aggeragator_name){
					
					$varCurrenID=$aggeragator_name;
					if ($firstrun==false)
					{
						echo "<tr>";
						echo "<td colspan='5' class='center-text'></td>";
						echo "<td class='right-text' ><strong>".number_format($totalquantity,2)."</strong> </td>";
						echo "<td colspan='6' class='center-text'></td>";
						echo "</tr>	";
						echo "</table><br>";
						$totalquantity=0.00;	
					}
					
					$firstrun=false;
					?>
					<table class="printtbl">
					 <tr>
					   <td colspan="12" class="center-text"><h2><?php echo $aggeragator_name;  ?></h2></td>
					 </tr>	
					  <th rowspan="2" width="5%">#</th>
					  <th rowspan="2" width="10%">GRN.No</th>
					  <th colspan="2" width="20%">Purchase Invoice</th>
					  <th rowspan="2" width="10%">Dispatched Place</th>
					  <th rowspan="2" width="10%">Net Inward (KG)</th>
					  <th colspan="2" width="20%">Aggregator Weigh Bridge</th>
					  <th colspan="2" width="10%">Plant Weigh Bridge</th>
					  <th rowspan="2" width="5%">Vehicle No</th>
					  <th rowspan="2" width="10%">EWAY Bill No</th>
					  <tr>
						<th width="10%" class="center-text">No</th>
						<th width="10%" class="center-text">Date</th>
						<th width="10%" class="center-text" >Number</th>
						<th width="10%" class="center-text" >Date</th>
						<th width="5%"  class="center-text">Number</th>
						<th width="5%"  class="center-text">Date</th>
					  </tr>
				<?php 
				}
				$totalquantity=$totalquantity+(int)$plant_quantity;
				//echo $totalquantity=number_format($totalquantity,2);
			?>
	<tr>	
		<td width="5%" class="center-text" ><?php echo $it1; ?></td>
		
		<?php if($grnfile!=""){ ?>
			<td width="10%" class="card-description center-text"><a href="<?php echo $settingUserImagePathEPRSDocument.$grnfile; ?>" target="_blank"><?php echo $grn_number; ?></a></td>
		<?php }else{?>
			<td width="10%" class="card-description center-text"><?php echo $grn_number; ?></td>
		<?php } ?>
		
		<?php if($invoicefile!=""){ ?>
			<td width="10%" class="card-description center-text"><a href="<?php echo $settingUserImagePathEPRSDocument.$invoicefile; ?>" target="_blank"><?php echo $purchase_invoice_number; ?></a></td>
		<?php }else{?>
			<td width="10%" class="card-description center-text"><?php echo $purchase_invoice_number; ?></td>
		<?php } ?>
		
		<td width="10%" class="center-text" ><?php echo date("d-m-Y",strtotime($purchase_invoice_date)) ; ?></td>
		<td width="10%" class="center-text"><?php echo $dispatched_place; ?></td>
		<td width="10%" class="right-text"><?php echo number_format((int)$plant_quantity,2); ?></td>
		
		<?php if($wbsfile!=""){ ?>
			<td width="10%" class="card-description center-text"><a href="<?php echo $settingUserImagePathEPRSDocument.$wbsfile; ?>" target="_blank"><?php echo $aggregator_wbs_number; ?></a></td>
		<?php }else{?>
			<td width="10%" class="card-description center-text"><?php echo $aggregator_wbs_number; ?></td>
		<?php } ?>
		
		
		<td width="10%" class="center-text" ><?php echo  date("d-m-Y",strtotime($aggregator_wbs_date)); ?></td>
		
		<?php if($pwbsfile!=""){ ?>
			<td width="10%" class="card-description center-text"><a href="<?php echo $settingUserImagePathEPRSDocument.$pwbsfile; ?>" target="_blank"><?php echo $plant_wbs_number; ?></a></td>
		<?php }else{?>
			<td width="10%" class="card-description center-text"><?php echo $plant_wbs_number; ?></td>
		<?php } ?>
		
		<td width="10%" class="center-text" ><?php echo  date("d-m-Y",strtotime($plant_wbs_date)); ?></td>
		
		<?php if($vehiclefile!=""){ ?>
			<td width="10%" class="card-description center-text"><a href="<?php echo $settingUserImagePathEPRSDocument.$vehiclefile; ?>" target="_blank"><?php echo $vehicle_number; ?></a></td>
		<?php }else{?>
			<td width="10%" class="card-description center-text"><?php echo $vehicle_number; ?></td>
		<?php } ?>
		
		<?php if($ewayfile!=""){ ?>
			<td width="10%" class="card-description center-text"><a href="<?php echo $settingUserImagePathEPRSDocument.$ewayfile; ?>" target="_blank"><?php echo $eway_bill_number; ?></a></td>
		<?php }else{?>
			<td width="10%" class="card-description center-text"><?php echo $eway_bill_number; ?></td>
		<?php } ?>
		
		<?php 
			
			$it1=$it1+1;
			$i1=$i1+1;
				
		}?>
		<tr>
		<td colspan='5' class='center-text'></td>
		<td class='right-text' ><strong><?php echo number_format($totalquantity,2);?></strong> </td>
		<td colspan='6' class='center-text'></td>
		</tr>
		</table><br>
	</tr>	
	
</table>
</div>
<br>
<div class="center-text center-text">
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