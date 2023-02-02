<?php
session_start();
if(!isset($_SESSION["employeeusername"])){
	header("Location:pgEmployeeLogIn.php");
}
include_once "commonFunctions.php";
include_once "Qrfunction.php";
$commonfunction=new Common();
$QrCodefunction=new TwQr();
include_once("function.php");
$sign=new Signup();
$settingValuePemail= $commonfunction->getSettingValue("Primary Email");
$settingValueCompanyImage= $commonfunction->getSettingValue("Company Image");
$settingValueUserImagePathVerification= $commonfunction->getSettingValue("UserImagePathVerification");
$settingValueUserImagePathOther= $commonfunction->getSettingValue("UserImagePathOther");
$settingValueUserPANCard= $commonfunction->getSettingValue("PANCard");
$settingValueUserGSTDocuments= $commonfunction->getSettingValue("GSTDocuments");
$settingValueDocEPR= $commonfunction->getSettingValue("DocEPR");
$settingValueAwaitingStatus= $commonfunction->getSettingValue("Awaiting Status");
$settingValueCompletedStatus= $commonfunction->getSettingValue("Completed Status");
$settingValueFactoryAddress= $commonfunction->getSettingValue("FactoryAddress");
$settingValueVerifyLink= $commonfunction->getSettingValue("VerifyLink");

$po_id=$_REQUEST['po_id'];
$SelectDate=$_REQUEST['date'];
$state=$_REQUEST['state'];

$StateNameQry="SELECT state_name FROM `tw_state_master` where id='".$state."'";
$StateName= $sign->SelectF($StateNameQry,"state_name");

$contact_field_id=$settingValuePemail;
//$currency="INR";
$gst_number_value=$settingValueUserGSTDocuments;

    $AuditorIDQry="SELECT auditor_id FROM tw_auditor_po_details where po_id='".$po_id."'";
	$AuditorID=$sign->SelectF($AuditorIDQry,"auditor_id");
	
	$query="SELECT cd.ID,cd.Company_Logo,cd.CompanyName FROM tw_company_details cd INNER JOIN tw_employee_registration er ON cd.ID=er.company_id where er.id='".$AuditorID."'";
	$DocVal = $sign->FunctionJSON($query);
	$decodedJSON = json_decode($DocVal);
	$ID = $decodedJSON->response[0]->ID; 
	$company_logo = $decodedJSON->response[1]->Company_Logo; 
	$CompanyName = $decodedJSON->response[2]->CompanyName;

	$poinfoQry="SELECT company_id,supplier_id,supplier_address_id,bill_to_address_id,po_number,date_of_po FROM `tw_po_info` where id='".$po_id."'";
	$poinfo= $sign->FunctionJSON($poinfoQry);
    $poifodecodedJSON = json_decode($poinfo);
    $company_id = $poifodecodedJSON->response[0]->company_id;
    $supplier_id = $poifodecodedJSON->response[1]->supplier_id;
    $supplier_address_id = $poifodecodedJSON->response[2]->supplier_address_id;
    $bill_to_address_id = $poifodecodedJSON->response[3]->bill_to_address_id;
    $po_number = $poifodecodedJSON->response[4]->po_number;
    $date_of_po = $poifodecodedJSON->response[5]->date_of_po;

	$email_query = "select value from tw_company_contact where company_id = '".$ID."' and contact_field='".$settingValuePemail."'";
	$email_val = $sign->SelectF($email_query,'value');

	$sign_query="select approved_by,prepared_by,for_company from tw_digital_signature where form_type='".$settingValueDocEPR."'  and company_id='".$company_id."'";
	$sign_val = $sign->FunctionJSON($sign_query);
	$sign_val_json = json_decode($sign_val);

	$approved_by=$sign_val_json->response[0]->approved_by;
	$prepared_by=$sign_val_json->response[1]->prepared_by;
	$for_company=$sign_val_json->response[2]->for_company;

	$approved_by_img="";
	$prepared_by_img="";
	$for_company_img="";


	if(!empty($approved_by)){
		$approved_by_img="<img src=".$settingValueUserImagePathVerification.$email_val."/".$approved_by." width='70px' />";
	}

	if(!empty($prepared_by)){
		$prepared_by_img="<img src=".$settingValueUserImagePathVerification.$email_val."/".$prepared_by." width='70px' />";
	}
		
	if(!empty($for_company)){
		$for_company_img="<img src=".$settingValueUserImagePathVerification.$email_val."/".$for_company." width='70px' />";
	}



	$CMPEMAILQRY="select value from tw_company_contact where company_id='".$ID."'";
	$EMAIL= $sign->SelectF($CMPEMAILQRY,"value");
	
	

    $companyAddQry="SELECT concat(address_line1,', ',address_line2,', ',location,', ',pincode,', ',city) as Address,state,country FROM `tw_company_address` where company_id='".$ID."'";
	$retValAdd = $sign->FunctionJSON($companyAddQry);
	$decodedJSON9 = json_decode($retValAdd);
    $companyAdd = $decodedJSON9->response[0]->Address;
    $companystate = $decodedJSON9->response[1]->state;
    $companycountry = $decodedJSON9->response[2]->country;

    
	
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
	
	$brandnameQry="SELECT CompanyName FROM `tw_company_details` where ID='".$company_id."'";
	$brandname=$sign->SelectF($brandnameQry,"CompanyName");
	
	$supplierQry="SELECT concat(address_line1,', ',address_line2,', ',location,', ',pincode,', ',city) as Address FROM tw_company_address where id='".$supplier_address_id."' and company_id='".$supplier_id ."' ";
	$supplierretValAdd = $sign->FunctionJSON($supplierQry);
	$supplierdecodedJSON9 = json_decode($supplierretValAdd);
    $SAddress = $supplierdecodedJSON9->response[0]->Address;	
	
	$FactAddQry="SELECT concat(address_line1,', ',address_line2,', ',location,', ',pincode,', ',city) as Address FROM tw_company_address where address_type='".$settingValueFactoryAddress."' and company_id='".$supplier_id ."' ";
	$FactAddretValAdd = $sign->FunctionJSON($FactAddQry);
	$FactAdddecodedJSON9 = json_decode($FactAddretValAdd);
    $FactAddress = $FactAdddecodedJSON9->response[0]->Address;

	$BrandDetailsQry="SELECT concat(address_line1,', ',address_line2,', ',location,', ',pincode,', ',city) as Address FROM tw_company_address where id='".$bill_to_address_id."' and company_id='".$company_id ."' ";
	$BrandDetails = $sign->FunctionJSON($BrandDetailsQry);
	$BrandDetailsdecodedJSON9 = json_decode($BrandDetails);
    $BrandAddress = $BrandDetailsdecodedJSON9->response[0]->Address;
    
	
	$CountryQry="SELECT currency FROM tw_country_master where country_name='".$companycountry."'";
	$currency= $sign->SelectF($CountryQry,"currency");
	
	$StateCodeQry="SELECT state_code FROM `tw_state_master` where state_name='".$companystate."'";
	$StateCode= $sign->SelectF($StateCodeQry,"state_code");
	
	$DocPanQry="SELECT count(document_number) as cnt,IFNULL(document_number,'NA') as document_number from tw_company_document where company_id='".$company_id."' AND document_type='".$settingValueUserPANCard."'  ORDER by document_type ASC ";

	$supplier_pan = $sign->SelectF($DocPanQry,"document_number");
	
	
	if($company_logo==""){
	$company_logo = $settingValueUserImagePathOther.$settingValueCompanyImage;
	}
	else{
		$company_logo = $settingValueUserImagePathVerification.$EMAIL."/".$company_logo;
	}




	$DocGstQry="SELECT count(document_number) as cnt,IFNULL(document_number,'NA') as document_number from tw_company_document where company_id='".$ID."' AND document_type='".$settingValueUserGSTDocuments."'  ORDER by document_type ASC ";

	$supplier_gst = $sign->SelectF($DocGstQry,"document_number");
	
	date_default_timezone_set("Asia/Kolkata");
	$cur_date=date("Y-m-d h:i:sa");	
	
	//GET LOGIN COMPANY'S GST DETAILS
	$qryCompanyDoc = "SELECT document_number from tw_company_document where company_id='".$ID."' and document_type='".$gst_number_value."'";
	$retValCompanyDoc = $sign->FunctionJSON($qryCompanyDoc);
	$decodedJSONCompanyDoc = json_decode($retValCompanyDoc);
	$login_company_gst_number=$decodedJSONCompanyDoc->response[0]->document_number;
	
	$encValue=$po_id;
	$encValue=$commonfunction->CommonEnc($encValue);
	$encValue=urlencode($encValue);
	$Link=$settingValueVerifyLink."pgEPRCertificate.php?q=".$encValue;
	$valqrfunction = $QrCodefunction->GetQrcode($Link,$settingValueUserImagePathOther);

	$AllocationCompanyQry="SELECT CompanyName FROM `tw_company_details` where id='".$supplier_id."'";
	$AllocationCompany=$sign->SelectF($AllocationCompanyQry,"CompanyName");
	
	$stateNameqry="SELECT cd.state FROM `tw_company_address` cd INNER JOIN tw_po_info poi ON poi.supplier_id=cd.company_id where poi.id='".$po_id."' and cd.id='".$supplier_address_id."' ";
	$state= $sign->SelectF($stateNameqry,"state");
	
	$shortCMPName= substr($AllocationCompany, 0, 3);
	$shortstate= substr($state, 0, 3);
	 
	$shortyear= substr($year, 2, 4);
	$shortyear1= substr($year1, 2, 4); 
	
	
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>Auditor Certificate</title>
<link rel="stylesheet" href="../assets/css/custom/style.css" />
<link rel="shortcut icon" href="../assets/images/favicon.png" />
</head>

<body>

<div id="printableArea">
	<table class="printtbl-noborder">
	<tr>
	<td width="75px" class="left-text"><img src="<?php echo $company_logo;?>" width="70px" /></td>
	<td> <strong><?php echo $CompanyName;?></strong><br><?php echo  $companyAdd; ?><br><?php echo $companystate."(".$StateCode.")".", ".$companycountry; ?><br>PAN Number: <strong><?php echo $supplier_pan; ?> </strong>| GST Number:<strong> <?php echo $supplier_gst; ?></strong><br>E-Mail: <?php echo $EMAIL;?> </td>
	<td width="90px" class="right-text"><img src="<?php echo $valqrfunction; ?>" width="50px" /></td>
	
	</tr>
	</table>

<table class="printtbl">
	
		<?php 
			
		?> 
	
		<tr>
			<td width="75%"><h2>Auditor Certificate</h2></td>
			<td width="25%" class="right-text">
				Date: <strong><?php echo date("d-m-Y",strtotime($cur_date));?></strong><br>PO Number: <strong><?php echo $po_number;?> </strong>
			</td>
		</tr>
</table>

<table width="100%" class="printtbl-noborder"" id="Bill_to">
<tr>
                                       <td><h1 class='center-text'>TO WHOM SO EVER IT MAY CONCERN </h1>
									   <h3>We hereby certify that the EPR Certificates has been issued by M/s. <?php echo $CompanyName;?> vide address <?php echo $SAddress; ?>. to M/s. <?php echo $brandname; ?> <?php  echo $BrandAddress; ?>.
									   </h3>
									   
									  <h3>
									  The Certificate has been issued for <?php if($month >= "04" && $month <= "06" ) { echo "Quater 1" ;} else if($month >= "07" && $month <= "09"){ echo "Quater 2"; } else if($month >= "10" && $month <= "12"){ echo "Quater 3"; }else{ echo "Quater 4 " ;} ?> of FY <?php echo $year1; ?>-<?php echo $year; ?> as per details mentioned below:
									  </h3>
									  
									   </td>
										
	</tr>									
									</table>
									
									<table width="100%" class="printtbl" >
                                        <tr>
                                            <th width="10%"  class="center-text">#</th>
                                            <th width="30%" class="center-text">Certificate Number</th>  
                                            <th width="20%" class="center-text">Date</th>
											<th width="20%" class="center-text">Month</th>
                                            <th width="20%" class="center-text">Quantity</th>
                                            
                                        </tr>
										<?php
											//GET PO DETAILS
											$qryPODetails = "SELECT IFNULL (sum(replace(plant_quantity, ',', '')), 0) as quantity,category_name FROM tw_temp where status='".$settingValueCompletedStatus."' and po_id='".$po_id."'  and dispatched_state='".$StateName."' and plant_wbs_date like '".$SelectDate."%' GROUP by aggeragator_name";
											$retValPODetails = $sign->FunctionJSON($qryPODetails);
											$decodedJSONPODetails = json_decode($retValPODetails);
											
											$qryPODetailsCnt="Select count(DISTINCT po_id) as cnt from tw_temp where po_id='".$po_id."' and status='".$settingValueCompletedStatus."' and dispatched_state='".$StateName."' and plant_wbs_date like '".$SelectDate."%' ";
											$retValPODetailsCnt = $sign->Select($qryPODetailsCnt);
											
											$valtotalamt=0;
											$valtotalQty=0;
											$count = 0;
											$i = 1;
											$x=1;
											$amountInWords="";
											$table="";
											while($x>=$i){
												
											
												$po_qty=$decodedJSONPODetails->response[$count]->quantity;
												$count=$count+1;
												$category_name=$decodedJSONPODetails->response[$count]->category_name;
												$count=$count+1;
										
												$table.="<tr>";
												$table.="<td class='center-text'>".$i."</td>
												<td class='left-text'><b>EPR/".$shortCMPName."/".$shortstate."/".$shortmonth."/". $po_id."/".$shortyear1."-". $shortyear."</b> </td>
												<td class='center-text'>".date("d-m-Y",strtotime($date_of_po))."</td>
												<td class='center-text'>".$monthWord."</td>
												<td class='right-text' >".number_format(round($po_qty,0),2)."</td>";
												$table.="</tr>";
												$i=$i+1;
												
											}
											
											if ($count>0)
											{
												echo $table;
											}
											else
											{
												echo "<tr><td colspan='8'>No record available</td></tr>";
											}
											
										?>
										
                                    </table>
	<table width="100%" class="printtbl-noborder" id="Bill_to">	
    <tr>
	<td>
	<p><h3>We further certify that the post-consumer plastic (<?php echo $category_name; ?>) purchased by M/s.  <?php echo $CompanyName; ?> has been recycled at the Plant <?php echo $FactAddress; ?></h3>
	
	<h3>We have verified the documents viz the Purchase Invoice, E-way Bill & Weigh Bridge Slips attached with the EPR Certificate.</h3>
	
	<h3>
	We also confirm that the quantity utilized for the above EPR Certificate for M/s. <?php echo $brandname; ?> has not been issued or utilized for issuance of any other EPR Certificate.
	</h3>
	</p>
	</td>
    </tr>
    </table>	
	


	<?php 
							$TnCQRY=" select tnc_value from tw_tnc where tnc_for='EPR PO' AND company_id='".$company_id."'";
							$TnC= $sign->SelectF($TnCQRY,"tnc_value");
							$TnCINFoQRY=" select invoice_information from tw_invoice_info where  company_id='".$company_id."'";
							
							$TnCINFo= $sign->SelectF($TnCINFoQRY,"invoice_information");
							?>
						

									
<table class="printtbl">
		<tr>
			<td width="35%" class="left-text top-align"><b>Prepared By</b><br>
			
			<?php echo $prepared_by_img; ?> </td>
			<td width="35%" class="left-text top-align"><b>Approved By</b><br><?php echo $approved_by_img; ?><br>
			</td>
			<td width="30%" class="right-text top-align"> For <b><?php echo $CompanyName;?> </b><br><?php echo $for_company_img; ?><br><br><br><br><br></td>
		</tr>
</table>
									
</div>
<br>
<div class="center-text"><button type="submit" onclick="printDiv('printableArea')" style="background: #149ddd;border: 0; padding: 10px 24px; color: #fff; transition: 0.4s; border-radius: 4px;">Print</button></div>
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