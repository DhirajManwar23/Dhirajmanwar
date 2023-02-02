<?php
// Include class definition
require "function.php";
include("commonFunctions.php");
$sign=new Signup();
$commonfunction=new Common();
session_start();
$outward_id=$_POST["outward_id"];
$employee_id=$_SESSION["employee_id"];

$ip_address= $commonfunction->getIPAddress();
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");

 $qry1="Select id,material_id,quantity,tax,rate,total from  tw_material_outward_individual where material_outward_id='".$outward_id."' ORDER BY id Asc";
	$retVal1 = $sign->FunctionJSON($qry1);


	$qry1="Select count(*) as cnt from tw_material_outward_individual where material_outward_id='".$outward_id."' ORDER BY id Asc";
	$retVal2 = $sign->Select($qry1);

	$decodedJSON2 = json_decode($retVal1);
	$count = 0;
	$i = 1;
	$x=$retVal2;
	$table="";
	$it=1;
	$valtotalamt=0;
	$valtotalquantity=0;
	$valtotalgst=0;
	$valtotalamount=0;
	$valfinaltotalamount=0;
	$table.="<thead><tr><th>#</th><th>Material Name</th><th>Quantity</th><th>Tax(%)</th><th>Rate</th><th>Amount</th><th>Total GST</th><th>Total</th></tr></thead><tbody>";
		while($x>=$i){
		
		
		$id = $decodedJSON2->response[$count]->id;
		$count=$count+1;
		$material_id = $decodedJSON2->response[$count]->material_id;
		$count=$count+1;
		$quantity = $decodedJSON2->response[$count]->quantity;
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
		
		$finaltotal=$temptax + $total;
		$valtotalquantity=$valtotalquantity + $quantity;
		$valtotalgst=$valtotalgst + $temptax;
		$valtotalamount=$valtotalamount + $total;
		$valfinaltotalamount=$valfinaltotalamount + $finaltotal;
		
		
		$qry2="Select product_name from tw_product_management where id = '".$material_id."' ORDER BY id DESC";
		$retVal2 = $sign->SelectF($qry2,"product_name");
		
		$table.="<tr>";
		$table.="<td>".$it."</td>"; 
		$table.="<td class='left-text'>".$retVal2."</td>";
		$table.="<td class='right-text'>".number_format($quantity,2)."</td>";
		
		$table.="<td class='center-text'>".$tax."</td>";
		$table.="<td class='right-text'>&#8377 ".$rate."</td>";
		$table.="<td class='right-text'>&#8377 ".number_format($total,2)."</td>";
		$table.="<td class='center-text'>&#8377 ".number_format($temptax,2)."</td>";
		$table.="<td class='right-text'>&#8377 ".number_format($finaltotal,2)."</td>";
		$it++;
		$table.="</tr>";

		$i=$i+1;
	}
	$table.="<tr style='font-weight:bold'><td colspan='2'>Total</td>";
	$table.="<td class='right-text'>".number_format($valtotalquantity,2)."</td>";
	$table.="<td></td>";
	$table.="<td></td>";
	$table.="<td class='right-text'>&#8377 ".number_format($valtotalamount,2)."</td>";
	$table.="<td class='right-text'>&#8377 ".number_format($valtotalgst,2)."</td>";
	$table.="<td class='right-text'>&#8377 ".number_format($valfinaltotalamount,2)."</td>";
	$table.="</tr>";

	$table.="</tbody>";
	//echo $table; 
	$responsearray=array();
	array_push($responsearray, $table,$valtotalamt);
	echo json_encode($responsearray);
	?>