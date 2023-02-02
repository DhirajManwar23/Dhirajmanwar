<?php
// Include class definition
session_start();
include_once "function.php";
include_once "commonFunctions.php";	
$sign=new Signup();
$commonfunction=new Common();
$company_id=$_SESSION["company_id"];
$statustype = $_POST["statustype"];
$settingValueApprovedStatus= $commonfunction->getSettingValue("Approved Status");
$settingValueEmployeeUrl= $commonfunction->getSettingValue("EmployeeUrl");
$settingValueEmployeeImagePathOther=$commonfunction->getSettingValue("EmployeeImagePathOther");
$settingValueNodatafoundImage=$commonfunction->getSettingValue("NodatafoundImage");

$settingValuePendingStatus= $commonfunction->getSettingValue("Pending Status");
$settingValueCompletedStatus= $commonfunction->getSettingValue("Completed Status");

$qry="SELECT mo.id,cd.CompanyName,mo.total_quantity,mo.final_total_amout FROM tw_material_outward mo INNER JOIN tw_company_details cd ON mo.company_id = cd.ID WHERE mo.customer_id = '".$company_id."'  and mo.status='".$settingValueApprovedStatus."' ORDER BY mo.id DESC";
$retVal = $sign->FunctionJSON($qry);

$qry1="Select count(*) as cnt from tw_material_outward where customer_id='".$company_id."' and status='".$settingValueApprovedStatus."'";
$retVal1 = $sign->Select($qry1);

$decodedJSON2 = json_decode($retVal);
$count = 0;
$i = 1;
$x=$retVal1;
$table="";
$it=1;
if($retVal1==0 || $retVal1==0.00){
	$table.="
				<div class='card'>
				  
					<div class='card-body text-center'>
							<img src='".$settingValueEmployeeImagePathOther."".$settingValueNodatafoundImage."' width='250px' />
						</div>
					</div>
					
				  </div><br>";	
	
}
else{

	$table.="<thead class='text-center'><tr><th>#</th><th>Customer Name</th><th>Invoice</th><th>Invoice Date</th><th>Total Amount</th><th>Balance Amount</th><th>Payment</th></tr></thead><tbody>";
		while($x>=$i){
			
		$id = $decodedJSON2->response[$count]->id;
		$count=$count+1;
		$CompanyName = $decodedJSON2->response[$count]->CompanyName;
		$count=$count+1;
		$total_quantity = $decodedJSON2->response[$count]->total_quantity;
		$count=$count+1;
		$final_total_amout = $decodedJSON2->response[$count]->final_total_amout;
		$count=$count+1;
		
		//--Karuna Invoice Amount Start
		$qryInvoice="SELECT COUNT(*) as cnt from tw_material_outward_documents WHERE type='Invoice' and outward_id='".$id."'";
		$retValInvoice = $sign->SelectF($qryInvoice,"cnt");

		$qry1Invoice="SELECT COUNT(*) as cnt from tw_tax_invoice WHERE outward_id='".$id."'";
		$retVal1Invoice = $sign->SelectF($qry1Invoice,"cnt");
		
		if($retValInvoice==0 && $retVal1Invoice==0){
			$invoiceamount=0.00;
			$atag = "-";
			$editatag = "-";
			$invoice_date = "-";
		}
		else if($retValInvoice>0){
			$qryInvoice="SELECT id,document,document_value,amount,created_on from tw_material_outward_documents WHERE type='Invoice' and outward_id='".$id."' ORDER BY outward_id ASC";
			$retVal = $sign->FunctionJSON($qryInvoice);
			$decodedJSON = json_decode($retVal);
			$invid = $decodedJSON->response[0]->id;
			$document = $decodedJSON->response[1]->document;
			$document_value = $decodedJSON->response[2]->document_value;
			$invoiceamount = $decodedJSON->response[3]->amount;
			$invoice_date = $decodedJSON->response[4]->created_on;
			$invoice_date = date("d-m-Y", strtotime($invoice_date));
			$atag = "<a href='../assets/images/Documents/Employee/Outward/".$document."' target='_blank'>".$document_value."</a>";
			
			$editatag = "<a href='javascript:void(0)' onclick='editRecord(".$id.")'><i class='ti-pencil'></i></a>";
		}
		else{
			$qry1Invoice="SELECT id,invoice_number,final_total_amount,invoice_date from tw_tax_invoice WHERE outward_id='".$id."' ORDER BY outward_id ASC";
			$retVal = $sign->FunctionJSON($qry1Invoice);
			$decodedJSON = json_decode($retVal);
			$invid = $decodedJSON->response[0]->id;
			$invoice_number = $decodedJSON->response[1]->invoice_number;
			$invoiceamount = $decodedJSON->response[2]->final_total_amount;
			$invoice_date = $decodedJSON->response[3]->invoice_date;
			$invoice_date = date("d-m-Y", strtotime($invoice_date));
			$atag = "<a href='".$settingValueEmployeeUrl."pgTaxInvoiceDocuments.php?id=".$invid."&voutward_id=".$id."' target='_blank'>".$invoice_number."</a>";
			
			$editatag = "<a href='javascript:void(0)' onclick='editRecord(".$id.")'><i class='ti-pencil'></i></a>";
		}
	
		$QryTotal="SELECT SUM(amount) as TotalAmt FROM tw_invoice_transaction WHERE outward_id='".$id."'";
		$retValQryTotal = $sign->FunctionJSON($QryTotal);
		$decodedJSON = json_decode($retValQryTotal);
		$TotalAmt = $decodedJSON->response[0]->TotalAmt;
		if($TotalAmt==""){
			$TotalAmt=0.00;
		}
		if($invoiceamount==""){
			$invoiceamount=$final_total_amout;
		}
		$Balanceamount=$invoiceamount-$TotalAmt;
		
		//---Karuna Invoice Amount End
		if($statustype==$settingValueCompletedStatus){
			if($Balanceamount==0 || $Balanceamount==0.00){
				
					$table.="<tr>";
					$table.="<td class='text-center'>".$it."</td>"; 
					$table.="<td>".$CompanyName."</td>";
					
					if($retValInvoice==0 && $retVal1Invoice==0){
						$table.="<td class='text-center' colspan='4'>No Invoice Attached Yet</td>";
					}
					else{
						$table.="<td class='text-center'>".$atag."</td>";
						$table.="<td class='text-center'>".$invoice_date."</td>";
						$table.="<td class='text-right'><span>&#8377;</span> ".number_format($invoiceamount,2)."</td>";
						$table.="<td class='text-right'><span>&#8377;</span> ".number_format($Balanceamount,2)."</td>";
						$table.="<td class='text-center'>".$editatag."</td>";
					}
					
					$it++;
					$table.="</tr>";
			}
		}
		else if($statustype==$settingValuePendingStatus){
			if($Balanceamount>0 || $Balanceamount>0.00){
				
					$table.="<tr>";
					$table.="<td class='text-center'>".$it."</td>"; 
					$table.="<td>".$CompanyName."</td>";
					if($retValInvoice==0 && $retVal1Invoice==0){
						$table.="<td class='text-center' colspan='4'>No Invoice Attached Yet</td>";
					}
					else{
						$table.="<td class='text-center'>".$atag."</td>";
						$table.="<td class='text-center'>".$invoice_date."</td>";
						$table.="<td class='text-right'><span>&#8377;</span> ".number_format($invoiceamount,2)."</td>";
						$table.="<td class='text-right'><span>&#8377;</span> ".number_format($Balanceamount,2)."</td>";
						$table.="<td class='text-center'>".$editatag."</td>";
					}
					$it++;
					$table.="</tr>";
			}
		}
		else{
			
			$table.="<tr>";
			$table.="<td class='text-center'>".$it."</td>"; 
			$table.="<td>".$CompanyName."</td>";
			if($retValInvoice==0 && $retVal1Invoice==0){
				$table.="<td class='text-center' colspan='4'>No Invoice Attached Yet</td>";
			}
			else{
				$table.="<td class='text-center'>".$atag."</td>";
				$table.="<td class='text-center'>".$invoice_date."</td>";
				$table.="<td class='text-right'><span>&#8377;</span> ".number_format($invoiceamount,2)."</td>";
				$table.="<td class='text-right'><span>&#8377;</span> ".number_format($Balanceamount,2)."</td>";
				$table.="<td class='text-center'>".$editatag."</td>";
			}
			$it++;
			$table.="</tr>";
		}
		
	

		$i=$i+1;
	}

		$table.="</tbody>";
}
	echo $table;
	?>