
<?php
	session_start();
	if(!isset($_SESSION["companyusername"])){
		header("Location:pgLogin.php");
	}
	// Include class definition
	include_once "function.php";
	$sign=new Signup();
	include_once "commonFunctions.php";
	$commonfunction=new Common();
	
	$invoiceamount = $_POST["amount"];
	//$Intinvoiceamount= str_replace(',', '', $invoiceamount);
	$invoiceno = $_POST["invoiceno"];

	$company_id = $_SESSION["company_id"];
	$valpoi_id = $_POST["valpoi_id"];
	$settingValueEmployeeUrl= $commonfunction->getSettingValue("EmployeeUrl");

	echo $qry="select id,invoice_id,transaction_id,amount,payment_date,po_id from tw_invoice_transaction_eprs where supplier_id='".$company_id."' and po_id='".$valpoi_id."'";
	
	$retVal = $sign->FunctionJSON($qry);

	$qry1="Select count(*) as cnt from tw_invoice_transaction_eprs where supplier_id='".$company_id."'";
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
	$po_id = $decodedJSON2->response[$count]->po_id ;
	$count=$count+1;
	
	
	$table.="<tr>";
	$table.="<td>".$it."</td>"; 
	$table.="<td>".$invoice_id."</td>"; 
	$table.="<td>".$transaction_id."</td>";
	$table.="<td>&#8377; ".$amount."</td>";
	$table.="<td>".$payment_date."</td>";
	//$table.="<td><a href='pgEPRSPaymentListForm.php?type=edit&id=".$id."&po_id=".$po_id."&amount=".$invoiceamount."&invoiceno=".$invoiceno."'><i class='ti-pencil'></i></a></td>";
	$it++;
	$table.="</tr>";
	

	$i=$i+1;
}
	$table.="</tbody>";
	echo $table;
?>



