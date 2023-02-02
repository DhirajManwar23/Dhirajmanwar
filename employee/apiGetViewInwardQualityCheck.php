<?php
session_start();
// Include class definition
include_once "function.php";
$company_id=$_SESSION["company_id"];
$inward_id = $_REQUEST["inward_id"];
$qry="SELECT mi.id,cd.CompanyName,mi.party_id,mi.party_bill_no,mi.date FROM tw_material_inward_qc mi INNER JOIN tw_company_details cd ON mi.company_id=cd.ID WHERE mi.inward_id='".$inward_id."'";
$sign=new Signup();
$retVal = $sign->FunctionJSON($qry);
$qry1="SELECT count(*) as cnt FROM tw_material_inward_qc mi INNER JOIN tw_company_details cd ON mi.company_id=cd.ID WHERE mi.inward_id='".$inward_id."'";
$retVal1 = $sign->Select($qry1);

$decodedJSON2 = json_decode($retVal);
$count = 0;
$i = 1;
$x=$retVal1;
$table="";
$it=1;
$table.="<thead><tr><th>SR.NO</th><th>Company Name</th><th>Party Name</th><th>Party Bill Number</th><th>Date</th><th>Edit</th><th>Delete</th><th>Document</th></tr></thead><tbody>";
	while($x>=$i){
		
	$id = $decodedJSON2->response[$count]->id;
	$count=$count+1;
	$CompanyName = $decodedJSON2->response[$count]->CompanyName;
	$count=$count+1;
	$party_id = $decodedJSON2->response[$count]->party_id;
	$count=$count+1;
	$party_bill_no = $decodedJSON2->response[$count]->party_bill_no;
	$count=$count+1;
	$date = $decodedJSON2->response[$count]->date;
	$count=$count+1;
	
	$qry2="SELECT CompanyName FROM tw_company_details WHERE id='".$party_id."'";
	$retVal2 = $sign->SelectF($qry2,"CompanyName");

	$table.="<tr>";
 	$table.="<td>".$it."</td>"; 
	$table.="<td>".$CompanyName."</td>";
	$table.="<td>".$retVal2."</td>";
	$table.="<td>".$party_bill_no."</td>";
	$table.="<td>".$date."</td>";
	$table.="<td><a href='javascript:void(0)' onclick='editRecord(".$id.")'>Edit</a></td>";
	$table.="<td><a href='javascript:void(0)' onclick='deleteRecord(".$id.")'>Delete</a></td>";
	$table.="<td><a href='javascript:void(0)' onclick='DocumentRecord(".$id.")'><i class='ti-printer'></i></a></td>";
	$it++;
	$table.="</tr>";

	$i=$i+1;
}

	$table.="</tbody>";
	echo $table;
	?>