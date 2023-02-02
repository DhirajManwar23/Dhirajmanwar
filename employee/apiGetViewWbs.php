<?php
// Include class definition
require "function.php";
session_start();
$outwardid=$_POST["outwardid"];
$qry="select id,carrier_no,party_name,material_name,net_weight,created_on from tw_material_outward_wbs where outward_id='".$outwardid."' order by id Desc";
$sign=new Signup();
$retVal = $sign->FunctionJSON($qry);

$qry1="Select count(*) as cnt from tw_material_outward_wbs where outward_id = '".$outwardid."' ORDER BY id DESC";
$retVal1 = $sign->Select($qry1);

$decodedJSON2 = json_decode($retVal);
$count = 0;
$i = 1;
$x=$retVal1;
$table="";
$it=1;
$table.="<thead><tr><th>SR.NO</th><th>Carrier No</th><th>Party Name</th><th>Net Weight</th><th>Date Time</th><th>Edit</th><th>Delete</th><th>WBS</th></tr></thead><tbody>";
	while($x>=$i){
		
	$id = $decodedJSON2->response[$count]->id;
	$count=$count+1;
	$carrier_no = $decodedJSON2->response[$count]->carrier_no;
	$count=$count+1;
	$party_name = $decodedJSON2->response[$count]->party_name;
	$count=$count+1;
	$material_name = $decodedJSON2->response[$count]->material_name;
	$count=$count+1;
	$net_weight = $decodedJSON2->response[$count]->net_weight;
	$count=$count+1;
	$created_on = $decodedJSON2->response[$count]->created_on;
	$count=$count+1;
	
	$table.="<tr>";
 	$table.="<td>".$it."</td>"; 
	$table.="<td>".$carrier_no."</td>";
	$table.="<td>".$party_name."</td>";
	//$table.="<td>".$material_name."</td>";
	$table.="<td>".$net_weight."</td>";
	$table.="<td>".$created_on."</td>";
	$table.="<td><a href='javascript:void(0)' onclick='editRecord(".$id.")'>Edit</a></td>";
	$table.="<td><a href='javascript:void(0)' onclick='deleteRecord(".$id.")'>Delete</a></td>";
	$table.="<td><a href='pgWaybillslip.php?id=".$id."&outward_id=".$outwardid."' target='_blank'>WBS</a></td>";
	$it++;
	$table.="</tr>";

	$i=$i+1;
}

	$table.="</tbody>";
	echo $table;
	?>