<?php
session_start();
if(!isset($_SESSION["companyusername"])){
	header("Location:pgLogIn.php");
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
$requestid_sid=$_REQUEST['supid'];
// $employee_id= $_SESSION["employee_id"];
$company_id=$_SESSION['company_id'];
$po_id=$_REQUEST['id'];
$contact_field_id=$settingValuePemail;
//$currency="INR";
$gst_number_value=$settingValueUserGSTDocuments;

$supplier_noqry="select supplier_no FROM `tw_supplier_info` where supplier_id='".$requestid_sid."'";
$supplier_no= $sign->SelectF($supplier_noqry,"supplier_no");

	$qry = "SELECT PI.supplier_id,CD.Company_Logo,PI.po_number,SU.CompanyName, SA.address_line1,SA.address_line2,CONCAT(SA.location,', ',SA.pincode,' ',SA.city) as SupplierAddress3,SA.state As Supplierstate,SA.country as suppliercountry , EMP.employee_name, EC.value as EmployeeEmail, PI.date_of_po,PI.payment_term,PI.shipping_term,PI.invoicing_method,SA.state as SupplierState,CD.CompanyName as LoginCompany, CONCAT(BILLADD.address_line1,'<br>',BILLADD.address_line2,'<br>',BILLADD.location,' ',BILLADD.pincode,'<br>',BILLADD.city,' ',BILLADD.state,' ',BILLADD.country) as BILLADD, CONCAT(SENDADD.address_line1,'<br>',SENDADD.address_line2,'<br>',SENDADD.location,' ',SENDADD.pincode,'<br>',SENDADD.city,' ',SENDADD.state,' ',SENDADD.country) as SENDADD, PI.company_id from tw_po_info PI INNER JOIN tw_company_details CD on PI.company_id=CD.ID INNER JOIN tw_company_details SU on PI.supplier_id=SU.ID INNER JOIN tw_company_address SA on PI.supplier_address_id=SA.id INNER JOIN tw_employee_registration EMP on PI.employee_id=EMP.id INNER JOIN tw_employee_contact EC on PI.employee_id=EC.employee_id INNER JOIN tw_company_address BILLADD on PI.bill_to_address_id=BILLADD.id INNER JOIN tw_company_address SENDADD on PI.send_invoice_to_address_id=SENDADD.id where PI.ID=".$po_id." and EC.contact_field='".$contact_field_id."'";
	$retVal = $sign->FunctionJSON($qry);
	$decodedJSON = json_decode($retVal);
	$supplier_id = $decodedJSON->response[0]->supplier_id;
    $company_logo = $decodedJSON->response[1]->Company_Logo;
	$po_number = $decodedJSON->response[2]->po_number;
	$supplier_company_name = $decodedJSON->response[3]->CompanyName;
	$supplier_address1 = $decodedJSON->response[4]->address_line1;
	$supplier_address2 = $decodedJSON->response[5]->address_line2;
	$supplier_address3 = $decodedJSON->response[6]->SupplierAddress3;
	$supplier_address4 = $decodedJSON->response[7]->Supplierstate;
	$suppliercountry = $decodedJSON->response[8]->suppliercountry;
	$employee_name = $decodedJSON->response[9]->employee_name;
	$employee_email = $decodedJSON->response[10]->EmployeeEmail;
	$date_of_po = $decodedJSON->response[11]->date_of_po;
	$payment_term = $decodedJSON->response[12]->payment_term;
	$shipping_term = $decodedJSON->response[13]->shipping_term;
	$invoicing_method = $decodedJSON->response[14]->invoicing_method;
	$supplier_state = $decodedJSON->response[15]->SupplierState;
	$login_company_name = $decodedJSON->response[16]->LoginCompany;
	$login_company_bill_address = $decodedJSON->response[17]->BILLADD;
	$login_company_send_address = $decodedJSON->response[18]->SENDADD;
	$company_id = $decodedJSON->response[19]->company_id;
	
	$query="Select ID,Company_Logo,CompanyName from tw_company_details where ID='".$company_id."'";
	$DocVal = $sign->FunctionJSON($query);
	$decodedJSON = json_decode($DocVal);
	$ID = $decodedJSON->response[0]->ID; 
	$Company_Logo = $decodedJSON->response[1]->Company_Logo; 
	$CompanyName = $decodedJSON->response[2]->CompanyName;
		
	$email_query = "select value from tw_company_contact where company_id = '".$company_id."' and contact_field='".$settingValuePemail."'";
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



	$CMPEMAILQRY="select value from tw_company_contact where company_id='".$company_id."'";
	$EMAIL= $sign->SelectF($CMPEMAILQRY,"value");

    $companyAddQry="SELECT concat(address_line1,', ',address_line2,', ',location,', ',pincode,', ',city) as Address,state,country FROM `tw_company_address` where company_id='".$company_id."'";
	$retValAdd = $sign->FunctionJSON($companyAddQry);
	$decodedJSON9 = json_decode($retValAdd);
    $companyAdd = $decodedJSON9->response[0]->Address;
    $companystate = $decodedJSON9->response[1]->state;
    $companycountry = $decodedJSON9->response[2]->country;

	
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




	$DocGstQry="SELECT count(document_number) as cnt,IFNULL(document_number,'NA') as document_number from tw_company_document where company_id='".$company_id."' AND document_type='".$settingValueUserGSTDocuments."'  ORDER by document_type ASC ";

	$supplier_gst = $sign->SelectF($DocGstQry,"document_number");
	
	$SuppGstQry="SELECT count(document_number) as cnt,IFNULL(document_number,'NA') as document_number from tw_company_document where company_id='".$supplier_id."' AND document_type='".$settingValueUserGSTDocuments."'  ORDER by document_type ASC ";

	$supplier_Docgst = $sign->SelectF($SuppGstQry,"document_number");
	
	
	
	
	//GET LOGIN COMPANY'S GST DETAILS
	$qryCompanyDoc = "SELECT document_number from tw_company_document where company_id='".$company_id."' and document_type='".$gst_number_value."'";
	$retValCompanyDoc = $sign->FunctionJSON($qryCompanyDoc);
	$decodedJSONCompanyDoc = json_decode($retValCompanyDoc);
	$login_company_gst_number=$decodedJSONCompanyDoc->response[0]->document_number;
	//$login_company_gst_number="27ARFPS6434E1Z";
	$encValue=$po_id."~".$requestid_sid;
	$encValue=$commonfunction->CommonEnc($encValue);
	$encValue=urlencode($encValue);
	$Link="https://verify.trace-waste.com/documents/pgPrintPO.php?q=".$encValue;
	$valqrfunction = $QrCodefunction->GetQrcode($Link,$settingValueUserImagePathOther);

	
	
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>Purchase Order</title>
<link rel="stylesheet" href="../assets/css/custom/style.css" />
<link rel="shortcut icon" href="../assets/images/favicon.png" />
</head>

<body>

<div id="printableArea">
	<table class="printtbl-noborder">
	<tr>
	<td width="75px" class="left-text"><img src="<?php echo $company_logo;?>" width="70px" /></td>
	<td> <strong><?php echo $login_company_name;?></strong><br><?php echo  $companyAdd; ?><br><?php echo $companystate."(".$StateCode.")".", ".$companycountry; ?><br>PAN Number: <strong><?php echo $supplier_pan; ?> </strong>| GST Number:<strong> <?php echo $supplier_gst; ?></strong><br>E-Mail: <?php echo $EMAIL;?> </td>
	<td width="90px" class="right-text"><img src="<?php echo $valqrfunction; ?>" width="50px" /></td>
	
	</tr>
	</table>

<table class="printtbl">
	
		<?php 
			
		?> 
	
		<tr>
			<td width="75%"><h2>Purchase Order</h2></td>
			<td width="25%" class="right-text">
				Date: <strong><?php echo date("d-m-Y",strtotime($date_of_po));?></strong><br>PO Number: <strong><?php echo $po_number;?> </strong>
			</td>
		</tr>
</table>

<table width="100%" class="printtbl" id="Bill_to">
                                        <tr>
										    <th width="33%">Supplier</th>
                                            <th  width="33%" class="center-text">Bill To</th>
                                            <th  width="33%" class="center-text">Ship To</th>
                                        </tr>
										<tr>
										     <td><strong><?php echo $supplier_company_name; ?></strong><br><?php echo $supplier_address1.", <br>".$supplier_address2.",<br> ".$supplier_address3.", <br>".$supplier_address4.", ".$suppliercountry; ?></strong><br>GSTIN: <strong><?php echo $supplier_Docgst; ?></strong></td>
										
										
                                            <td><strong><?php echo $login_company_name; ?></strong><br><?php echo $login_company_bill_address; ?><br>Kind Attention: <strong><?php echo $employee_name; ?></strong><br>GSTIN: <strong><?php echo $supplier_gst; ?></strong></td>
                                            <td class="top-align"><strong><?php echo $login_company_name; ?></strong><br><?php echo $login_company_send_address; ?></td>
											
                                        </tr>
										
										
										
									</table>

<table  width="100%" class="printtbl-noborder">
                                        <tr>
                                            
											
                                    </table>
									<table class="printtbl" width="100%">
                                        <tr>
										 <td width="50%">Requester Name: <strong><?php echo $employee_name; ?></strong></td>
                                            
                                         <td width="50%">Currency: <strong><?php echo $currency; ?></strong></td> 
                                        </tr>
										<tr>
										
                                           
                                            <td>Requester Email: <strong><?php echo $employee_email; ?></strong></td>
												<td>Shipping Terms: <strong><?php echo $shipping_term; ?></strong></td>
                                        </tr>
										<tr >
										<td>Supplier Number: <strong><?php echo $supplier_no; ?></strong></td>
										
										<td> Payment Terms: <strong><?php echo $payment_term; ?></strong></td>
										</tr>
										<tr>
                                         <td>EPR Services:<strong> Certification </strong></td>
                                            <td>Invoicing Method: <strong><?php echo $invoicing_method; ?></strong></td>
                                        </tr>
										
                                    </table>
									<table width="100%" class="printtbl" >
                                        <tr>
                                            <th width="5%"  class="center-text">#</th>
                                            <th width="25%" class="center-text">Description</th>  
                                            <th width="10%" class="center-text">HSN</th>
											<th width="10%" class="center-text">Supplier part number</th>
                                            <th width="10%" class="center-text">Quantity</th>
                                            <th width="10%" class="center-text">Start Date</th>
                                            <th width="10%" class="center-text">Delivery Date</th>
                                            <th width="10%" class="center-text">Rate</th>
                                            <th width="10%" class="center-text">Total</th>
                                        </tr>
										<?php
											//GET PO DETAILS
											$qryPODetails = "SELECT description,supplier_part_number,product_id,quantity,start_date,delivery_date,price_per_unit,amount,state,district from tw_po_details where po_id='".$po_id."' ORDER BY id ASC";
											$retValPODetails = $sign->FunctionJSON($qryPODetails);
											$decodedJSONPODetails = json_decode($retValPODetails);
											
											$qryPODetailsCnt="Select count(*) as cnt from tw_po_details where po_id='".$po_id."'";
											$retValPODetailsCnt = $sign->Select($qryPODetailsCnt);
											
											$valtotalamt=0;
											$valtotalQty=0;
											$count = 0;
											$i = 1;
											$x=$retValPODetailsCnt;
											$amountInWords="";
											$table="";
											while($x>=$i){
												
												$po_desc=$decodedJSONPODetails->response[$count]->description;
												$count=$count+1;
												$supplier_part_number=$decodedJSONPODetails->response[$count]->supplier_part_number;
												$count=$count+1;
												$product_id=$decodedJSONPODetails->response[$count]->product_id;
												$count=$count+1;
												
												$po_qty=$decodedJSONPODetails->response[$count]->quantity;
												$count=$count+1;
												
												$start_date=$decodedJSONPODetails->response[$count]->start_date;
												$count=$count+1;
												$delivery_date=$decodedJSONPODetails->response[$count]->delivery_date;
												$count=$count+1;
												$po_price_per_unit=$decodedJSONPODetails->response[$count]->price_per_unit;
												$count=$count+1;
												$po_amount=$decodedJSONPODetails->response[$count]->amount;
												$count=$count+1;
												$state=$decodedJSONPODetails->response[$count]->state;
												$count=$count+1;
												$district=$decodedJSONPODetails->response[$count]->district;
												$count=$count+1;
												
												
												$temptotal = ($po_qty*$po_price_per_unit);
												//$temptax = $temptotal * ($tax/100);
												$valtotalamt = $valtotalamt+$temptotal;
												$valtotalQty = $valtotalQty+$po_qty;
												$HSNQry="SELECT hsn FROM `tw_product_management` where id='".$product_id."'";
												$HSN=$sign->SelectF($HSNQry,"hsn");
												
												$ProductNameQry="Select eprcm.epr_category_name,eprpm.epr_product_name from tw_epr_product_master eprpm INNER Join tw_epr_category_master eprcm ON eprpm.epr_category_id=eprcm.id where eprpm.id='".$product_id."'";
												$RetProductNameQry = $sign->FunctionJSON($ProductNameQry);
												$decodedJSON = json_decode($RetProductNameQry);
												$epr_category_name = $decodedJSON->response[0]->epr_category_name; 
												$ProductName = $decodedJSON->response[1]->epr_product_name; 
												
												
												$StateQry="SELECT state_name FROM tw_state_master where id='".$state."'";
												$Fetchstate=$sign->SelectF($StateQry,"state_name");
												$CityQry="SELECT city_name FROM tw_city_master where id='".$district."'";
												$FetchCity=$sign->SelectF($CityQry,"city_name");
												
												if($supplier_part_number==""){
												$supplier_part_number= "-";
												}
												//FILL TABLE
												$table.="<tr>";
												$table.="<td class='center-text'>".$i."</td>
												<td class='left-text'>".$epr_category_name."-".$ProductName." (".$Fetchstate."/".$FetchCity.")<br>".$po_desc." </td>
												<td class='center-text'>".$HSN."</td>
												<td class='center-text'>".$supplier_part_number."</td>
												<td class='right-text' >".number_format(round($po_qty,2),2)."</td>
												<td class='center-text'>".date("d-m-Y",strtotime($start_date))."</td>
												<td class='center-text'>".date("d-m-Y",strtotime($delivery_date))."</td>
												<td class='right-text'>&#8377;  ".number_format(round($po_price_per_unit,2),2)."</td>
												<td class='right-text'>&#8377; ".number_format(round($po_amount,2),2)."</td>";
												$table.="</tr>";
												$i=$i+1;
												
											}
											$amountInWords= $commonfunction->amountInWords($valtotalamt);
											if ($count>0)
											{
												$table.="<tr><td colspan='4'  class='right-text top-align;'>Total Quantity </td><td  class='right-text' ><strong>".number_format($valtotalQty,2)."</strong></td> <td colspan='3'  class='right-text top-align;'>Total Value(excluding tax)</td><td  class='right-text' ><strong>&#8377; ".number_format(round($valtotalamt,2),2)."</strong></td></tr>
												<tr><td colspan='9'  class='right-text;'> Amount In Words: <strong>".$amountInWords."</strong> </td></tr>";
												echo $table;
											}
											else
											{
												echo "<tr><td colspan='8'>No record available</td></tr>";
											}
											
										?>
										
                                    </table>
									
									<?php 
							$TnCQRY=" select tnc_value from tw_tnc where tnc_for='EPR PO' AND company_id='".$company_id."'";
							$TnC= $sign->SelectF($TnCQRY,"tnc_value");
							$TnCINFoQRY=" select invoice_information from tw_invoice_info where  company_id='".$company_id."'";
							
							$TnCINFo= $sign->SelectF($TnCINFoQRY,"invoice_information");
							?>
						
<table class="printtbl" width="100%">
	<tr>
		<td><strong>Terms and Conditions of Purchase:</strong><br><?php echo $TnC; ?></td>
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