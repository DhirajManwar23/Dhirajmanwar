<?php
include_once "function.php";
$sign=new Signup();
include_once "commonFunctions.php";
$commonfunction=new Common();
include_once "Qrfunction.php";
$qrfunction=new TwQr();

$po_id = $_REQUEST["po_id"];
$s_id = $_REQUEST["supid"];
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
$Link="https://verify.trace-waste.com/documents/pgViewSummarySheet.php?q=".$encValue;
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
  
$month=date_format($date,"m");
$monthWord=date_format($date,"M");
$year1=date_format($date,"Y");

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
    <td width="75%" class="center-text"><h2>EPR CERTIFICATE - <br><?php echo $monthWord; ?> <?php echo $year1; ?></h2></td>  
</tr>
</table>
<table class="printtbl">
  <?php 
	$detailsQry="SELECT md.m_id,md.alloted_company_id,md.quantity,md.state,md.city,md.outward_id FROM tw_epr_material_assign_details md INNER JOIN tw_epr_material_assign_info mi ON md.m_id=mi.id where mi.po_id='".$po_id."' AND  mi.status='".$settingValueApprovedStatus."' order by md.alloted_company_id";
	$retVal7 = $sign->FunctionJSON($detailsQry);
	$decodedJSON7 = json_decode($retVal7);

	$qry2="SELECT count(*) as cnt FROM tw_epr_material_assign_details md INNER JOIN tw_epr_material_assign_info mi ON 		md.m_id=mi.id where mi.po_id='".$po_id."' AND mi.status='".$settingValueApprovedStatus."' order by md.alloted_company_id  ";
	$retVal2 = $sign->Select($qry2);
	$totalquantity = 0;
	$count1 = 0;
	$i1 = 1;
	$x1=$retVal2;
	$it1=1;
	$varCurrenID = "";
	while($x1>=$i1){
            $m_id = $decodedJSON7->response[$count1]->m_id;
			$count1=$count1+1;
			$alloted_company_id = $decodedJSON7->response[$count1]->alloted_company_id;
			$count1=$count1+1;
			$quantity = $decodedJSON7->response[$count1]->quantity;
			$count1=$count1+1;
			$state = $decodedJSON7->response[$count1]->state;
			$count1=$count1+1;
			$city = $decodedJSON7->response[$count1]->city;
			$count1=$count1+1;
		    $outward_details_id = $decodedJSON7->response[$count1]->outward_id;
			$count1=$count1+1;
			$totalquantity=$totalquantity+$quantity;

			
			$Qrymaterial_outward_id="SELECT material_outward_id FROM tw_material_outward_individual where id='".$outward_details_id."' ";
			$outward_id=$sign->SelectF($Qrymaterial_outward_id,"material_outward_id");
			
			$companyCntQry="SELECT count(*) as cnt FROM `tw_company_details` where id='".$alloted_company_id."' ";
			$companyCnt=$sign->Select($companyCntQry);
			
			
			$companyNameQry="SELECT CompanyName FROM tw_company_details  where ID='".$alloted_company_id."'";
			$CompanyName1 = $sign->SelectF($companyNameQry,"CompanyName"); 
			
			?>
				
			<?php 
				
		
				if($varCurrenID==$alloted_company_id){
					//$varCurrenID=$alloted_company_id;
					//echo "Nikul";
					
				}
				else{
					$varCurrenID=$alloted_company_id;
					//echo "Karuna".$varCurrenID;
					?>
					 <tr>
					   <td colspan="12" class="center-text"><h2><?php echo $CompanyName1;  ?></h2></td>
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
			?>
			
				
		
		<tr>	
	
		<td width="5%" class="center-text" ><?php echo $it1; ?></td>
		
				<?php
						//Grn from document
						$query1 = "select id,document,document_value from tw_material_inward_documents where inward_id = '".$outward_id."' and type='GRN' Order by id Desc";
						
						$retValue1 = $sign->FunctionJSON($query1);
						
						$qry1="Select count(*) as cnt from tw_material_inward_documents where inward_id = '".$outward_id."' and type='GRN' Order by id Desc";
						$retVal1 = $sign->Select($qry1);
				        $Grn1=$retVal1 ;
						//End
						
						
						//Grn from Grn Table
						$query2 = "select id from tw_material_inward_grn where inward_id = '".$outward_id."' Order by id Desc";
						$retValue2 = $sign->FunctionJSON($query2);
						
						$qry3="Select count(*) as cnt from tw_material_inward_grn where inward_id = '".$outward_id."' Order by id Desc";
						$retVal3 = $sign->Select($qry3);
						$grn2=$retVal3;
						$decodedJSON2 = json_decode($retValue2);
						$count3 = 0;
						$i3 = 1;
						$x3=$retVal3;
						//End
						
				        if($Grn1>0) {
						$decodedJSON2 = json_decode($retValue1);
						$count2 = 0;
						$i2 = 1;
						$x2=$retVal1;
						$it2=1;
						while($x2>=$i2){
							$id1 = $decodedJSON2->response[$count2]->id;
							$count2=$count2+1;
							$document = $decodedJSON2->response[$count2]->document;
							$count2=$count2+1;
							$document_value1 = $decodedJSON2->response[$count2]->document_value;
							$count2=$count2+1;
							$i2=$i2+1;
											
						?>
						
						
						<td width="10%" class="card-description center-text"><a href="../assets/images/Documents/Employee/Outward/<?php echo $document; ?>" target="_blank"><?php echo $document_value1; ?></a></td>
						<?php
                            }						
						 ?>
						
						<?php
						
							
							}
						else if($grn2==0 && $Grn1==0) {  ?>
						<td width="10%" class="center-text">-</td>	
					<?php 	} else{
							$it3=1;
							while($x3>=$i3){
								$id2 = $decodedJSON2->response[$count3]->id;
								$count3=$count3+1;
								$i3=$i3+1;
													
						?>
						<td width="10%" class="card-description center-text" ><a href="pgGRN.php?id=<?php echo$id; ?>&inward_id=<?php echo$outward_id; ?>" target="_blank"><?php echo $id2; ?></a></td>
					<?php } 
					} ?>
						
			
				<?php
					$query3 = "select id,document,document_value,created_on from tw_material_outward_documents where outward_id = '".$outward_id."' and type='Invoice' Order by id Desc";
					$retValue3 = $sign->FunctionJSON($query3);
							
					$qry4="Select count(*) as cnt from tw_material_outward_documents where outward_id = '".$outward_id."' and type='Invoice' Order by id Desc";
					$retVal4 = $sign->Select($qry4);
					
					
					$query4 = "select id,invoice_number,invoice_date from tw_tax_invoice where outward_id = '".$outward_id."' Order by id Desc";
					$retValue4 = $sign->FunctionJSON($query4);
					
					$qry5="Select count(*) as cnt from tw_tax_invoice where outward_id = '".$outward_id."' Order by id Desc";
					$retVal5 = $sign->Select($qry5);
					$decodedJSON2 = json_decode($retValue4);
					$count5 = 0;
					$i5 = 1;
					$x5=$retVal5;
					$it5=1;
					
					if($retVal4>0){
					$decodedJSON2 = json_decode($retValue3);
					$count4 = 0;
					$i4 = 1;
					$x4=$retVal4;
					$it4=1;
					while($x4>=$i4){

						$id3 = $decodedJSON2->response[$count4]->id;
						$count4=$count4+1;
						$document1 = $decodedJSON2->response[$count4]->document;
						$count4=$count4+1;
					    $document_value2 = $decodedJSON2->response[$count4]->document_value;
						$count4=$count4+1;
						$created_on1 = $decodedJSON2->response[$count4]->created_on;
						$count4=$count4+1;
						$i4=$i4+1;
				?>
				
					<td width="10%" class="center-text"  ><a href="../assets/images/Documents/Employee/Outward/<?php echo $document1; ?>" target="_blank"><?php echo $document_value2; ?></a></td>
					<td width="10%" class="center-text" ><?php echo date("d-m-Y",strtotime($created_on1)) ; ?></td>
				<?php } ?>
					<?php 
				
				    
					}
					else if($retVal5==0 && $retVal4==0){ ?>
					<td width="10%" class="center-text">-</td>	
					<td width="10%" class="center-text">-</td>	
				<?php	}
				else{
					while($x5>=$i5){

						$id4 = $decodedJSON2->response[$count5]->id;
						$count5=$count5+1;
						$invoice_number = $decodedJSON2->response[$count5]->invoice_number;
						$count5=$count5+1;
						$invoice_date = $decodedJSON2->response[$count5]->invoice_date;
						$count5=$count5+1;	
						$i5=$i5+1;
				?>
				
					<td width="10%" class="center-text"  ><a href="pgTaxInvoiceDocuments.php?id=<?php echo$id4; ?>&voutward_id=<?php echo $outward_id; ?>" target="_blank"><?php echo $invoice_number; ?></a></td>
					<td width="10%" class="center-text" ><?php echo  date("d-m-Y",strtotime($invoice_date)); ?></td>
				<?php }  
				}
				 ?>
				
				
			<td width="10%" class="center-text">-</td>
			<td width="10%" class="right-text"><?php echo $quantity; ?></td>
				
				
				<?php
						
							$query5 = "select id,document,document_value,created_on from tw_material_outward_documents where outward_id = '".$outward_id."' and type='WBS' Order by id Desc";
							$retValue5 = $sign->FunctionJSON($query5);
							
							$qry6="Select count(*) as cnt from tw_material_outward_documents where outward_id = '".$outward_id."' and type='WBS' Order by id Desc";
							$retVal6 = $sign->Select($qry6);
							if($retVal6>0){
							$decodedJSON2 = json_decode($retValue5);
							$count6 = 0;
							$i6 = 1;
							$x6=$retVal6;
							$it6=1;
							while($x6>=$i6){
			
								$id5 = $decodedJSON2->response[$count6]->id;
								$count6=$count6+1;
								$document2 = $decodedJSON2->response[$count6]->document;
								$count6=$count6+1;
								$document_value3 = $decodedJSON2->response[$count6]->document_value;
								$count6=$count6+1;
								$created_on2 = $decodedJSON2->response[$count6]->created_on;
								$count6=$count6+1;
								$i6=$i6+1;
													
						?>
						
					<td width="10%" class="center-text" ><a href="../assets/images/Documents/Employee/Outward/<?php echo$document2; ?>" target="_blank"> <?php echo $document_value3; ?></a></td>
					<td width="10%" class="center-text" ><?php echo date("d-m-Y",strtotime($created_on2)); ?></td>
				<?php } 
					} else{ ?>
						<td width="10%" class="center-text " >-</td>
						<td width="10%" class="center-text " >-</td>
				<?php	} 
				?>
				
				<td width="5%" class="center-text " >-</td>
				<td width="5%" class="center-text"  >-</td>
				
						<?php
						
							$query9 = "SELECT vdm.vehicle_number FROM tw_material_outward mo INNER JOIN tw_vehicle_details_master vdm ON mo.vehicle_id=vdm.id where mo.id='".$outward_id."'";
							$vehicleN0 = $sign->selectF($query9,"vehicle_number");	
							if($vehicleN0==""){
							?>
							<td width="5%" class="center-text"  >-</td>
							<?php }   
						     else {  ?>
							<td width="5%" class="center-text" ><?php echo $vehicleN0;  ?></td>
							<?php  } ?>
				
				
				
				
		
				
				
			<?php
			   //
				$query6 = "select id,document,document_value from tw_material_outward_documents where outward_id = '".$outward_id."' and type='Eway' Order by id Desc";
				$retValue6 = $sign->FunctionJSON($query6);
				
				$qry7="Select count(*) as cnt from tw_material_outward_documents where outward_id = '".$outward_id."' and type='Eway' Order by id Desc";
				$retVal7 = $sign->Select($qry7);
				//
				
				//
				$query7 = "select id,transporter_id from tw_material_outward_eway where outward_id = '".$outward_id."' Order by id Desc";
				$retValue7 = $sign->FunctionJSON($query7);
				
				$qry8="Select count(*) as cnt from tw_material_outward_eway where outward_id = '".$outward_id."' Order by id Desc";
				$retVal8 = $sign->Select($qry8);
				$decodedJSON2 = json_decode($retValue7);
				$count8 = 0;
				$i8 = 1;
				$x8=$retVal8;
				$it8=1;
				
				if($retVal7>0){
				$decodedJSON2 = json_decode($retValue6);
				$count7 = 0;
				$i7 = 1;
				$x7=$retVal7;
				$it7=1;
				while($x7>=$i7){

					$id6 = $decodedJSON2->response[$count7]->id;
					$count7=$count7+1;
					$document3 = $decodedJSON2->response[$count7]->document;
					$count7=$count7+1;
					$document_value_eway = $decodedJSON2->response[$count7]->document_value;
					$count7=$count7+1;
					$i7=$i7+1;
			?>
				<td class="center-text" width="10%"><a href="../assets/images/Documents/Employee/Outward/<?php echo $document3; ?>" target="_blank"><?php echo $document_value_eway; ?></a></td>
			
			<?php }  
					?>
			
			<?php 
			
				}
				else if($retVal7==0 && $retVal8==0  ){ ?>
					<td width="10%" class="center-text"  >-</td>
			<?php	}
			else{
			while($x8>=$i8){

				$id7 = $decodedJSON2->response[$count8]->id;
				$count8=$count8+1;
				$transporter_id = $decodedJSON2->response[$count8]->transporter_id;
				$count8=$count8+1;
				$i8=$i8+1;
				
				
				$vehicleNoQry="SELECT vehicle_number FROM `tw_vehicle_details_master` where transporter_id='".$transporter_id."'";
				$vehicleNo= $sign->SelectF($vehicleNoQry,"vehicle_number");
			?>
			 
				<td class="center-text" width="10%"><a href="pgeWayBill.php?id=<?php echo $id; ?>&outward_id=<?php echo$outward_id; ?>" target="_blank"><?php echo $id7; ?></a></td>
			
			<?php }
            }			?>
			

			
			   	<?php 

			    $it1=$it1+1;
				$i1=$i1+1;
				
}?>
</tr>	
			<tr>
				<td colspan='5' class="center-text" ></td>
				<td class="right-text" ><strong><?php echo number_format($totalquantity,2)?></strong> </td>
				<td colspan='6' class="center-text" ></td>
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