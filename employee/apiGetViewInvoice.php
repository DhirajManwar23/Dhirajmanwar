<?php
// Include class definition
require "function.php";
session_start();
$outwardid=$_POST["outwardid"];
$qry="select id,sender_company_id,receiver_company_id,created_on from tw_material_outward_eway where outward_id='".$outwardid."' order by id Desc";
$sign=new Signup();
$retVal = $sign->FunctionJSON($qry);

$qry1="Select count(*) as cnt from tw_material_outward_eway where outward_id = '".$outwardid."' ORDER BY id DESC";
$retVal1 = $sign->Select($qry1);

$decodedJSON2 = json_decode($retVal);
$count = 0;
$i = 1;
$x=$retVal1;
$table="";
$it=1;
$table.="<thead><tr><th>SR.NO</th><th>Sender</th><th>Receiver</th><th>Date Time</th><th>Edit</th><th>Delete</th><th>Eway</th></tr></thead><tbody>";
	while($x>=$i){
		
	$id = $decodedJSON2->response[$count]->id;
	$count=$count+1;
	$sender_company_id = $decodedJSON2->response[$count]->sender_company_id;
	$count=$count+1;
	$receiver_company_id = $decodedJSON2->response[$count]->receiver_company_id;
	$count=$count+1;
	$created_on = $decodedJSON2->response[$count]->created_on;
	$count=$count+1;
	
	$table.="<tr>";
 	$table.="<td>".$it."</td>"; 
	$table.="<td>".$sender_company_id."</td>";
	$table.="<td>".$receiver_company_id."</td>";
	$table.="<td>".$created_on."</td>";
	$table.="<td><a href='javascript:void(0)' onclick='editRecord(".$id.")'>Edit</a></td>";
	$table.="<td><a href='javascript:void(0)' onclick='deleteRecord(".$id.")'>Delete</a></td>";
	$table.="<td><a href='pgEwayBill.php?id=".$id."&outwardid=".$outwardid."' target='_blank'>Eway</a></td>";
	$it++;
	$table.="</tr>";

	$i=$i+1;
}

	$table.="</tbody>";
	echo $table;
	?>