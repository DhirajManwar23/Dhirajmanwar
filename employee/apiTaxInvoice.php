
<?php
	session_start();
	if(!isset($_SESSION["employee_id"])){
		header("Location:pgEmployeeLogIn.php");
	}
	// Include class definition
	include_once "function.php";
	$sign=new Signup();
	
	$company_id = $_SESSION["company_id"];
	$valoutward_id = $_POST["valoutward_id"];
	
	$qry="select id,company_address,bill_to_company_id,ship_to_company_address,invoice_number,invoice_date,date_of_supply,remark,bill_to_company_address from tw_tax_invoice where outward_id = '".$valoutward_id."' order by id Desc";
	$retVal = $sign->FunctionJSON($qry);
	$decodedJSON2 = json_decode($retVal);

	$qry1="Select count(*) as cnt from tw_tax_invoice where outward_id = '".$valoutward_id."' order by id Desc";
	$retVal1 = $sign->Select($qry1);
	
	$count = 0;
	$i = 1;
	$x=$retVal1;
	$table="";
	$it=1;
	$table.="<thead><tr><th>SR.NO</th><th>Bill To Company Name</th><th>Invoice No</th><th>Invoice Date</th><th>Date Of Supply</th><th>Tax Invoice</th><th>Edit</th><th>Delete</th></tr></thead><tbody>";
	
	while($x>=$i){
	$id = $decodedJSON2->response[$count]->id;
	$count=$count+1;
	$company_address = $decodedJSON2->response[$count]->company_address;
	$count=$count+1;
	$bill_to_company_id  = $decodedJSON2->response[$count]->bill_to_company_id ;
	$count=$count+1;
	$ship_to_company_address = $decodedJSON2->response[$count]->ship_to_company_address;
	$count=$count+1;
	$invoice_number = $decodedJSON2->response[$count]->invoice_number;
	$count=$count+1;
	$invoice_date = $decodedJSON2->response[$count]->invoice_date;
	$count=$count+1;
	$date_of_supply = $decodedJSON2->response[$count]->date_of_supply;
	$count=$count+1;
	$remark = $decodedJSON2->response[$count]->remark;
	$count=$count+1;
	$bill_to_company_address = $decodedJSON2->response[$count]->bill_to_company_address;
	$count=$count+1;
	
	
	$qry3="Select CompanyName from tw_company_details where ID = '".$bill_to_company_id."'";
	$bill_to_company_name = $sign->SelectF($qry3, 'CompanyName');
	
	/*$qry4="Select transporter_name from tw_transport_master where ID = '".$transporter_id."'";
	$transporter_name = $sign->SelectF($qry4, 'transporter_name');*/
	
	if(!empty($invoice_date)){
		$invoice_date = date("d-m-Y",strtotime($invoice_date));
	}
	if(!empty($date_of_supply)){
		$date_of_supply = date("d-m-Y",strtotime($date_of_supply));
	}
	
		$table.="<tr>";
		$table.="<td>".$it."</td>"; 
		//$table.="<td>".$company_address."</td>";
		$table.="<td>".$bill_to_company_name."</td>";
		//$table.="<td>".$bill_to_company_address."</td>";
		//$table.="<td>".$ship_to_company_address."</td>";
		$table.="<td>".$invoice_number."</td>";
		$table.="<td>".$invoice_date."</td>";
		//$table.="<td>".$transporter_name."</td>";
		//$table.="<td>".$transport_mode."</td>";
		$table.="<td>".$date_of_supply."</td>";
		//$table.="<td>".$remark."</td>";
		$table.="<td><a href='javascript:void(0)' onclick='taxinvoice(".$id.")'><i class='ti-printer'></i></a></td>";
		$table.="<td><a href='javascript:void(0)' onclick='editRecord(".$id.")'>Edit</a></td>";
		$table.="<td><a href='javascript:void(0)' onclick='deleteRecord(".$id.")'>Delete</a></td>";
		$it++;
		$table.="</tr>";
		

	$i=$i+1;
}
	$table.="</tbody>";
	echo $table;
?>
	



