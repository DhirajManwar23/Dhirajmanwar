<?php
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();
session_start();

$employee_id=$_SESSION["employee_id"];

$ip_address= $commonfunction->getIPAddress();
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");

if($_POST["requestidid"]==""){
	$qry="delete from tw_material_outward_temp where employee_id='".$employee_id."'";
}
else{
	$qry="DELETE FROM tw_material_outward_temp WHERE id='".$_POST["requestidid"]."'";
}

$retVal = $sign->FunctionQuery($qry);
if($retVal=="Success"){
	$qry1="Select id,description,quantity,rate,uom,tax,total from tw_material_outward_temp where employee_id = '".$employee_id."' ORDER BY id Asc";
	$retVal1 = $sign->FunctionJSON($qry1);

	$qry2="Select count(*) as cnt from tw_material_outward_temp where employee_id = '".$employee_id."' ORDER BY id Asc";
	$retVal2 = $sign->Select($qry2);

	$decodedJSON2 = json_decode($retVal1);
	$count = 0;
	$i = 1;
	$x=$retVal2;
	$table="";
	$it=1;
	$valtotalamt=0;
	$table.="<thead><tr><th>#SR.NO</th><th>Description</th><th>Quantity</th><th>Rate</th><th>UOM</th><th>Tax</th><th>Total</th><th>Remove</th></tr></thead><tbody>";
		while($x>=$i){
		
		$id = $decodedJSON2->response[$count]->id;
		$count=$count+1;
		$description = $decodedJSON2->response[$count]->description;
		$count=$count+1;
		$quantity = $decodedJSON2->response[$count]->quantity;
		$count=$count+1;
		$rate = $decodedJSON2->response[$count]->rate;
		$count=$count+1;
		$uom = $decodedJSON2->response[$count]->uom;
		$count=$count+1;
		$tax = $decodedJSON2->response[$count]->tax;
		$count=$count+1;
		$total = $decodedJSON2->response[$count]->total;
		$count=$count+1;
		
		$temptotal = ($quantity*$rate);
		$temptax = $temptotal * ($tax/100);
		$valtotalamt = $valtotalamt+$temptotal+$temptax;
		
		
		$qry3="Select product_name from tw_product_management where id = '".$description."' ORDER BY id DESC";
		$retVal3 = $sign->SelectF($qry3,"product_name");
		
		$table.="<tr>";
		$table.="<td>".$it."</td>"; 
		$table.="<td>".$retVal3."</td>";
		$table.="<td>".$quantity."</td>";
		$table.="<td>".$rate."</td>";
		$table.="<td>".$uom."</td>";
		$table.="<td>".$tax."</td>";
		$table.="<td>".$total."</td>";
		$table.="<td><a href='javascript:void(0)' onclick='funcremoverow(".$id.")'><i class='ti-close'></i></a></td>";
		$it++;
		$table.="</tr>";

		$i=$i+1;
	}

	$table.="</tbody>";
	//echo $table; 
	if($valtotalamt==0){
		echo $table.",0";
	}
	else{
		echo $table.",".$valtotalamt;
	}
}
else{
	echo "error";
}




	?>