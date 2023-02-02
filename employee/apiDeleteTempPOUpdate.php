<?php
// Include class definition
require "function.php";
include("commonFunctions.php");
$sign=new Signup();
$commonfunction=new Common();
session_start();

$employee_id=$_SESSION["employee_id"];
$_POST["requestidid"];
$ip_address= $commonfunction->getIPAddress();
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");

if($_POST["requestidid"]==""){
	echo $qry="delete from tw_material_outward_temp where employee_id='".$employee_id."'";
}
else{
	echo  $qry="DELETE FROM tw_material_outward_temp WHERE id='".$_POST["requestidid"]."'";
}
	

$retVal = $sign->FunctionQuery($qry);
if($retVal=="Success"){
	echo $qry1="Select id,material_id,quantity,hsn,tax,rate,total from tw_material_outward_temp where employee_id = '".$employee_id."' ORDER BY id DESC";
	$retVal1 = $sign->FunctionJSON($qry1);


	echo $qry1="Select count(*) as cnt from tw_material_outward_temp where employee_id = '".$employee_id."' ORDER BY id DESC";
	$retVal2 = $sign->Select($qry1);

	$decodedJSON2 = json_decode($retVal1);
	$count = 0;
	$i = 1;
	$x=$retVal2;
	$table="";
	$it=1;
	$valtotalamt=0;

	$table.="<thead><tr><th>#SR.NO</th><th>Material Name</th><th>Quantity</th><th>HSN</th><th>Tax</th><th>Rate</th><th>Total GST</th><th>Total</th><th>Remove</th></tr></thead><tbody>";
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
		
		$qry2="Select product_name from tw_product_management where id = '".$material_id."' ORDER BY id DESC";
		$retVal2 = $sign->SelectF($qry2,"product_name");
		
		
		$table.="<tr>";
		$table.="<td>".$it."</td>"; 
		$table.="<td>".$retVal2."</td>";
		$table.="<td>".$quantity."</td>";
		$table.="<td>".$hsn."</td>";
		$table.="<td>".$tax."</td>";
		$table.="<td>".$rate."</td>";
		$table.="<td>".round($temptax,2)."</td>";
		$table.="<td>".round($total,2)."</td>";
		$table.="<td><a href='javascript:void(0)' onclick='funcremoverow(".$id.")'><i class='ti-close'></i></a></td>";
		$it++;
		$table.="</tr>";

		$i=$i+1;
	}

	$table.="</tbody>";
	if($valtotalamt==0){
		echo $table.",0.00";
	}
	else{
		echo $table.",".round($valtotalamt,2);
	}
}
else{
	echo "error";
}




	?>