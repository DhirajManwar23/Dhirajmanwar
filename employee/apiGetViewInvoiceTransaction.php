
<?php
	session_start();
	if(!isset($_SESSION["employee_id"])){
		header("Location:pgEmployeeLogIn.php");
	}
	// Include class definition
	include_once "function.php";
	$sign=new Signup();
	include_once "commonFunctions.php";
	$commonfunction=new Common();

	$company_id = $_SESSION["company_id"];
	$settingValueEmployeeUrl= $commonfunction->getSettingValue("EmployeeUrl");

	$qry="select id,invoice_id,transaction_id,amount,payment_date,outward_id from tw_invoice_transaction where supplier_id='".$company_id."'";
	
	$retVal = $sign->FunctionJSON($qry);

	$qry1="Select count(*) as cnt from tw_invoice_transaction where supplier_id='".$company_id."' order by id Desc";
	$retVal1 = $sign->Select($qry1);

	$decodedJSON2 = json_decode($retVal);
	$count = 0;
	$i = 1;
	$x=$retVal1;
	$table="";
	$it=1;
	$table.="<thead><tr><th>#</th><th>Invoice ID</th><th>Transaction ID</th><th>Amount</th><th>Payment Date</th></tr></thead><tbody>";
	
	while($x>=$i){
	$id = $decodedJSON2->response[$count]->id;
	$count=$count+1;
	$invoice_id = $decodedJSON2->response[$count]->invoice_id;
	$count=$count+1;
	$transaction_id  = $decodedJSON2->response[$count]->transaction_id ;
	$count=$count+1;
	$amount = $decodedJSON2->response[$count]->amount;
	$count=$count+1;
	$payment_date = $decodedJSON2->response[$count]->payment_date;
	$count=$count+1;
	$outward_id = $decodedJSON2->response[$count]->outward_id ;
	$count=$count+1;
	
	$qryInvoice="SELECT COUNT(*) as cnt from tw_material_outward_documents WHERE type='Invoice' and outward_id='".$outward_id."' ORDER BY outward_id ASC";
	$retValInvoice = $sign->SelectF($qryInvoice,"cnt");

	$qry1Invoice="SELECT COUNT(*) as cnt from tw_tax_invoice WHERE outward_id='".$outward_id."' ORDER BY outward_id ASC";
	$retVal1Invoice = $sign->SelectF($qry1Invoice,"cnt");
	
	if($retValInvoice>0){
		$qryInvoice="SELECT document,document_value from tw_material_outward_documents WHERE type='Invoice' and outward_id='".$outward_id."' ORDER BY outward_id ASC";
		$retVal = $sign->FunctionJSON($qryInvoice);
		$decodedJSON = json_decode($retVal);
		$document = $decodedJSON->response[0]->document;
		$document_value = $decodedJSON->response[1]->document_value;
		
		$atag = "<a href='../assets/images/Documents/Employee/Outward/".$document."' target='_blank'>".$document_value."</a>";
	}
	else{
		$qry1Invoice="SELECT id,invoice_number from tw_tax_invoice WHERE outward_id='".$outward_id."' ORDER BY outward_id ASC";
		$retVal = $sign->FunctionJSON($qry1Invoice);
		$decodedJSON = json_decode($retVal);
		$id = $decodedJSON->response[0]->id;
		$invoice_number = $decodedJSON->response[1]->invoice_number;
		
		$atag = "<a href='".$settingValueEmployeeUrl."pgTaxInvoiceDocuments.php?id=".$id."&voutward_id=".$outward_id."' target='_blank'>".$invoice_number."</a>";
	}
	
		$table.="<tr>";
		$table.="<td>".$it."</td>"; 
		$table.="<td>".$atag."</td>";
		$table.="<td>".$transaction_id."</td>";
		$table.="<td>&#8377; ".$amount."</td>";
		$table.="<td>".$payment_date."</td>";
		$it++;
		$table.="</tr>";
		

	$i=$i+1;
}
	$table.="</tbody>";
	echo $table;
?>
	



