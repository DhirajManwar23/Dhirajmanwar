<?php
session_start();
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();

//$id = $_REQUEST["id"];
$id = $_POST["requestidid"];
//$requestidid = $_REQUEST["id"];
$employee_id=$_SESSION["employee_id"];
$company_id=$_SESSION["company_id"];
$ip_address= $commonfunction->getIPAddress();
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");
$valtotalamt=0;
$valtotalquantity=0;
$valtotalgst=0;

$valtotalamount=0;
$valfinaltotalamount=0;
if($id==""){
	 $qry="delete from tw_temp_po_details_individual_temp where employee_id='".$employee_id."'";
}
else{
	$qry="DELETE FROM tw_temp_po_details_individual_temp WHERE id='".$id."'";
}
	

$retVal = $sign->FunctionQuery($qry);
if($retVal=="Success"){
	$qry2="select id,material_id,quantity,hsn,tax,rate,total from tw_temp_po_details_individual_temp where employee_id='".$employee_id."' order by id ASC";
	$retVal2 = $sign->FunctionJSON($qry2);
	
	$qry3="Select count(*) as cnt from tw_temp_po_details_individual_temp where employee_id='".$employee_id."'";
	$retVal3 = $sign->Select($qry3);

	$decodedJSON2 = json_decode($retVal2);
	$count = 0;
	$i = 1;
	$x=$retVal3;
	$table="";
	$it=1;
	$valtotalamt=0;
	$valtotalquantity=0;
	$table.="<thead><tr><th>#</th><th>Material Name</th><th>Quantity</th><th>HSN</th><th>Tax(%)</th><th>Rate</th><th>Amount</th><th>Total GST</th><th>Total</th><th>Remove</th></tr></thead><tbody>";
		$valfinaltotalamount=0;
	while($x>=$i){
	
	
    $id = $decodedJSON2->response[$count]->id;
	$count=$count+1;
	$material_id = $decodedJSON2->response[$count]->material_id;
	$count=$count+1;
	$quantity = $decodedJSON2->response[$count]->quantity;
	$count=$count+1;
	$hsn = $decodedJSON2->response[$count]->hsn;
	$count=$count+1;
	$tax = $decodedJSON2->response[$count]->tax;
	$count=$count+1;
	$rate = $decodedJSON2->response[$count]->rate;
	$count=$count+1;
	$total = $decodedJSON2->response[$count]->total;
	$count=$count+1;
	
	$temptotal = ($quantity*$rate);
	$temptax = $temptotal * ($tax/100);
	$valtotalamt = $valtotalamt+$temptotal+$temptax;
	$valtotalquantity=$valtotalquantity+$quantity;

	$finaltotal=$temptax + $total;
			
	$valtotalgst=$valtotalgst + $temptax;
	$valtotalamount=$valtotalamount + $total;
	$valfinaltotalamount=$valfinaltotalamount + $finaltotal;			
	$qry2="Select product_name from tw_product_management where id = '".$material_id."' ORDER BY id ASC";
	$retVal2 = $sign->SelectF($qry2,"product_name");
	
	$table.="<tr>";
	$table.="<td>".$it."</td>"; 
	$table.="<td>".$retVal2."</td>";
	$table.="<td>".number_format($quantity,2)."</td>";
	$table.="<td>".$hsn."</td>";
	$table.="<td>&#8377 ".number_format($tax,2)."</td>";
	$table.="<td>&#8377 ".number_format($rate,2)."</td>";	
	$table.="<td>&#8377 ".number_format($total,2)."</td>";
	$table.="<td>&#8377 ".number_format($temptax,2)."</td>";
	
	$table.="<td>&#8377 ".number_format($finaltotal,2)."</td>";
	$table.="<td><a href='javascript:void(0)' onclick='funcremoverow(".$id.")'><i class='ti-close'></i></a></td>";
	$it++;
	$table.="</tr>";

	$i=$i+1;
}
	$table.="<tr style='font-weight:bold'><td colspan='2'>Total</td>";
	$table.="<td class='center-text'>".number_format($valtotalquantity,2)."</td>";
	$table.="<td></td>";
	$table.="<td></td>";
	$table.="<td></td>";
	$table.="<td>&#8377;".number_format($valtotalamount,2)."</td>";
	$table.="<td class='right-text'>&#8377 ".number_format($valtotalgst,2)."</td>";
	$table.="<td class='right-text'>&#8377 ".number_format($valfinaltotalamount,2)."</td>";
	$table.="<td></td>";
	$table.="</tr>";

$table.="</tbody>";
	//echo $table; 
	if($valtotalamt==0){
		echo $table.",0.00"."-"."0";
	}
	else{
		echo $table.",".round($valtotalamt,2)."-".$valtotalquantity;
	}
}
else{
	echo "error";
}




	?>