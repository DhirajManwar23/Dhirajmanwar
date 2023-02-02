<?php
// Include class definition
include_once "function.php";
include_once("commonFunctions.php");
$sign=new Signup();
$commonfunction=new Common();
session_start();

$employee_id=$_SESSION["employee_id"];
$company_id=$_SESSION["company_id"];
$ip_address= $commonfunction->getIPAddress();
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");

if($_POST["requestidid"]==""){
	$qry="delete from tw_po_details_temp where company_id='".$company_id."'";
}
else{
 $qry="DELETE FROM tw_po_details_temp WHERE id='".$_POST["requestidid"]."'";
}

$retVal = $sign->FunctionQuery($qry);
if($retVal=="Success"){
	$qry1="Select id,fulfillment_cycle,description,state,district,supplier_part_number,product_id,quantity,start_date,delivery_date,price_per_unit,amount from tw_po_details_temp where company_id = '".$company_id."' ORDER BY id Asc";
	$retVal1 = $sign->FunctionJSON($qry1);

	$qry1="Select count(*) as cnt from tw_po_details_temp where company_id = '".$company_id."'";
	$retVal2 = $sign->Select($qry1);

	$decodedJSON2 = json_decode($retVal1);
	$count = 0;
	$i = 1;
	$x=$retVal2;
	$table="";
	$it=1;
	$valtotalamt=0;
	$valtotalQty=0;
	$table.="<thead><tr><th>#</th><th>Fulfillment Cycle</th><th>Description</th><th>State</th><th>City</th><th>Supplier Part Number</th><th>Category</th><th>Product</th><th>Quantity</th><th>Start Date</th><th>Delivery Date</th><th>Price Per Unit</th><th>Amount</th><th>Remove</th></tr></thead><tbody>";
		while($x>=$i){
		
		
		$id = $decodedJSON2->response[$count]->id;
		$count=$count+1;		
		$fulfillment_cycle = $decodedJSON2->response[$count]->fulfillment_cycle;
		$count=$count+1;
		$description = $decodedJSON2->response[$count]->description;
		$count=$count+1;
		$state = $decodedJSON2->response[$count]->state;
		$count=$count+1;
		$district = $decodedJSON2->response[$count]->district;
		$count=$count+1;
		$supplier_part_number = $decodedJSON2->response[$count]->supplier_part_number;
		$count=$count+1;
		$product_id = $decodedJSON2->response[$count]->product_id;
		$count=$count+1;
		$quantity = $decodedJSON2->response[$count]->quantity;
		$count=$count+1;
		$start_date = $decodedJSON2->response[$count]->start_date;
		$count=$count+1;
		$delivery_date = $decodedJSON2->response[$count]->delivery_date;
		$count=$count+1;
		$price_per_unit = $decodedJSON2->response[$count]->price_per_unit;
		$count=$count+1;
		$amount = $decodedJSON2->response[$count]->amount;
		$count=$count+1;
		
		$temptotal = ($quantity*$price_per_unit);
		//$temptax = $temptotal * ($tax/100);
		$valtotalamt = $valtotalamt+$temptotal;
		$valtotalQty = round($valtotalQty+$quantity);
		
		$cityqry="Select city_name from tw_city_master where id = '".$district."' ";
		$city = $sign->SelectF($cityqry,"city_name");
		
		$stateQry="Select state_name from tw_state_master where id = '".$state."' ";
		$stateFetch = $sign->SelectF($stateQry,"state_name");
		
		$qry2="SELECT pm.epr_product_name,cm.epr_category_name FROM tw_epr_product_master pm INNER JOIN tw_epr_category_master cm ON pm.epr_category_id=cm.id WHERE pm.id='".$product_id."'";
		$retVal2 = $sign->FunctionJSON($qry2);
		$decodedJSONepr = json_decode($retVal2);
		$epr_product_name = $decodedJSONepr->response[0]->epr_product_name;
		$epr_category_name = $decodedJSONepr->response[1]->epr_category_name;
			
		
		$table.="<tr>";
		$table.="<td>".$it."</td>"; 

		$table.="<td>".$fulfillment_cycle."</td>";
		$table.="<td>".$description."</td>";
		$table.="<td>".$stateFetch."</td>";
		$table.="<td>".$city."</td>";
		$table.="<td>".$supplier_part_number."</td>";
		$table.="<td>".$epr_category_name."</td>";
		$table.="<td>".$epr_product_name."</td>";
		$table.="<td>".number_format(round($quantity,2),2)."</td>";
		$table.="<td>".date("d-m-Y",strtotime($start_date))."</td>";
		$table.="<td>".date("d-m-Y",strtotime($delivery_date))."</td>";
		$table.="<td>&#8377 ".number_format(round($price_per_unit,2),2)."</td>";
		$table.="<td>&#8377 ".number_format(round($amount,2),2)."</td>";
		$table.="<td><a href='javascript:void(0)' onclick='funcremoverow(".$id.")'><i class='ti-close'></i></a></td>";
		$it++;
		$table.="</tr>";       

		$i=$i+1;
	}
	$table.="<td><strong>Total</strong></td>";
	$table.="<td colspan='8' class='right-text'><strong>".number_format($valtotalQty,2)."</strong></td>";
	$table.="<td colspan='4' class='right-text'><strong>&#8377 ".number_format($valtotalamt,2)."</strong></td>";
	$table.="<td></td>";
	
	$table.="</tr>";

	$table.="</tbody>";
	
	$responsearray=array();
	array_push($responsearray, $table,round($valtotalamt,2),round($valtotalQty,2));
	echo json_encode($responsearray);
}
else{
	echo "error";
}




	?>