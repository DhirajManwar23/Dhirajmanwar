<?php
session_start();
include_once "function.php";
include_once "commonFunctions.php";	
$sign=new Signup();
$commonfunction=new Common();
$employee_id=$_SESSION["employee_id"];
$company_id=$_SESSION["company_id"];
$material_outward_id=$_POST["id"];


$qry=" SELECT material_id,quantity,tax,rate,total FROM tw_material_outward_individual where material_outward_id='".$material_outward_id."' order by id Asc";
$qrycnt="SELECT COUNT(*) as cnt FROM tw_material_outward_individual where material_outward_id='".$material_outward_id."'";
$Cnt = $sign->Select($qrycnt);

$retVal11 = $sign->FunctionJSON($qry);
$decodedJSON3 = json_decode($retVal11);
$i = 1;
$it1=1;
$count = 0;
$x=$Cnt;
$valtotalamt=0;
$valtotalquantity=0;
$valtotalgst=0;
$valtotalamount=0;
$valfinaltotalamount=0;
	
$table="<table class='printtbl'>
	  <th class='center-text'>#</th>
	  <th class='center-text'>MAterial Name</th>
	  <th class='center-text'>Quantity</th>
	  <th class='center-text'>HSN</th>
	  <th class='center-text'>Tax(%)</th>
	  <th class='center-text'>Rate</th>
	  <th class='center-text'>Amount</th>
	  <th class='center-text'>Total GST</th>
	  <th class='center-text'>Total</th>";

while($x>=$i){
	 $material_id= $decodedJSON3->response[$count]->material_id;
	 $count=$count+1;
	 $quantity= $decodedJSON3->response[$count]->quantity;
	 $count=$count+1;
	 $tax= $decodedJSON3->response[$count]->tax;
	 $count=$count+1;
	 $rate= $decodedJSON3->response[$count]->rate;
	 $count=$count+1;
	 $total	= $decodedJSON3->response[$count]->total;
	 $count=$count+1;
	 
	$temptotal = ($quantity*$rate);
	$temptax = $temptotal * ($tax/100);
	$valtotalamt = $valtotalamt+$temptotal+$temptax;
	$valtotalquantity=$valtotalquantity+$quantity;

	$finaltotal=$temptax + $total;
	
	$valtotalgst=$valtotalgst + $temptax;
	$valtotalamount=$valtotalamount + $total;
	$valfinaltotalamount=$valfinaltotalamount + $finaltotal;
		 $hsn="";
	 $qryproductName=" SELECT product_name,hsn FROM tw_product_management where id='".$material_id."'";
	 $data = $sign->FunctionJSON($qryproductName);
	 $decodedJSON = json_decode($data);
	 $productName = $decodedJSON->response[0]->product_name; 
	 $hsn = $decodedJSON->response[1]->hsn; 
	 $table.="
	
	  
	  <tr>
				<td class='center-text'>".$it1."</td>
				<td class='left-text'>".$productName."</td>
				<td class='right-text'>".number_format($quantity,2)."</td>
				<td class='center-text'>".$hsn."</td>
				<td class='center-text'>".number_format($tax,2)."</td>
				<td class='right-text'>&#8377 ".number_format($rate,2)."</td>
				<td class='right-text'>&#8377 ".number_format($total,2)."</td>
				<td class='right-text'>&#8377 ".number_format($temptax,2)."</td>
				<td class='right-text'>&#8377 ".number_format($finaltotal,2)."</td>
	  </tr> 
	 "; 


				  $i=$i+1;
				  $it1=$it1+1;
}
	$table.="<tr style='font-weight:bold'><td colspan='2'>Total</td>";
	$table.="<td class='right-text'>".number_format($valtotalquantity,2)."</td>";
	$table.="<td></td>";
	$table.="<td></td>";
	$table.="<td></td>";
	$table.="<td class='right-text'>&#8377;".number_format($valtotalamount,2)."</td>";
	$table.="<td class='right-text'>&#8377 ".number_format($valtotalgst,2)."</td>";
	$table.="<td class='right-text'>&#8377 ".number_format($valfinaltotalamount,2)."</td>";
	$table.="</tr>";
$table.="</table>";
echo $table;
?>