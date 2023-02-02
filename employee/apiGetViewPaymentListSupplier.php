
<?php
	session_start();
	if(!isset($_SESSION["employee_id"])){
		header("Location:pgEmployeeLogIn.php");
	}
	// Include class definition
	include_once "function.php";
	$sign=new Signup();
	$company_id = $_SESSION["company_id"];
	$outward_id = $_POST["valoutwardid"];
	$qry="select id,transaction_id,amount,payment_date,supplier_id from tw_invoice_transaction where outward_id='".$outward_id."'";
	
	$retVal = $sign->FunctionJSON($qry);

	$qry1="Select count(*) as cnt from tw_invoice_transaction where outward_id='".$outward_id."' order by id Desc";
	$retVal1 = $sign->Select($qry1);

	$decodedJSON2 = json_decode($retVal);
	$count = 0;
	$i = 1;
	$x=$retVal1;
	$table="";
	$it=1;
	$table.="<thead class='text-center'><tr><th>#</th><th>Transaction ID</th><th>Amount</th><th>Payment Date</th></tr></thead><tbody>";
	
	while($x>=$i){
	
	$id = $decodedJSON2->response[$count]->id;
	$count=$count+1;
	$transaction_id = $decodedJSON2->response[$count]->transaction_id;
	$count=$count+1;
	$amount  = $decodedJSON2->response[$count]->amount ;
	$count=$count+1;
	$payment_date = $decodedJSON2->response[$count]->payment_date;
	$count=$count+1;
	$supplier_id = $decodedJSON2->response[$count]->supplier_id;
	$count=$count+1;
	$payment_date = date("d-m-Y h:i:sa", strtotime($payment_date));
	
	
		$table.="<tr>";
		$table.="<td class='text-center'>".$it."</td>"; 
		$table.="<td class='text-center'>".$transaction_id."</td>";
		$table.="<td class='text-right'> &#8377; ".number_format(round($amount,2),2);"</td>";
		$table.="<td class='text-center'>".$payment_date."</td>";
		$it++;
		$table.="</tr>";
		

	$i=$i+1;
}
	$table.="</tbody>";
	echo $table;
?>
	



