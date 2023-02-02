<?php
// Include class definition
session_start();
include_once "function.php";
include_once "commonFunctions.php";	
$sign=new Signup();
$commonfunction=new Common();
$employee_id=$_SESSION["employee_id"];
$po_id=$_POST["po_id"];
$settingValuePendingStatus= $commonfunction->getSettingValue("Pending Status");

$settingValueEmployeeImagePathOther=$commonfunction->getSettingValue("EmployeeImagePathOther");
$settingValueNodatafoundImage=$commonfunction->getSettingValue("NodatafoundImage");
$qry="SELECT mo.id,cd.CompanyName,mo.total_quantity,mo.final_total_amout FROM tw_material_outward mo INNER JOIN tw_company_details cd ON mo.customer_id = cd.ID WHERE mo.employee_id = '".$employee_id."'  and mo.po_id = '".$po_id."'  and mo.status='".$settingValuePendingStatus."' ORDER BY mo.id DESC";
$retVal = $sign->FunctionJSON($qry);

$qry1="Select count(*) as cnt from tw_material_outward where employee_id='".$employee_id."' and po_id='".$po_id."'and status='".$settingValuePendingStatus."'";
$retVal1 = $sign->Select($qry1);

$decodedJSON2 = json_decode($retVal);
$count = 0;
$i = 1;
$x=$retVal1;
$table="";
$it=1;
if($retVal1==0 || $retVal1==0.00){
	$table.="
				<div class='card'>
				  
					<div class='card-body text-center'>
							<img src='".$settingValueEmployeeImagePathOther."".$settingValueNodatafoundImage."' width='250px' />
						</div>
					</div>
					
				  </div><br>";	
	
}
else{

	$table.="<thead><tr><th>#</th><th>Customer Name</th><th>Total Quantity</th><th>Total Amount</th><th>Edit</th><th>Document</th></tr></thead><tbody>";
		while($x>=$i){
			
		$id = $decodedJSON2->response[$count]->id;
		$count=$count+1;
		$CompanyName = $decodedJSON2->response[$count]->CompanyName;
		$count=$count+1;
		$total_quantity = $decodedJSON2->response[$count]->total_quantity;
		$count=$count+1;
		$final_total_amout = $decodedJSON2->response[$count]->final_total_amout;
		$count=$count+1;
		
		
		$table.="<tr>";
		$table.="<td>".$it."</td>"; 
		$table.="<td>".$CompanyName."</td>";
		$table.="<td>".$total_quantity."</td>";
		$table.="<td><span>&#8377;</span> ".$final_total_amout."</td>";
		$table.="<td><a href='javascript:void(0)' onclick='editRecord(".$id.")'><i class='ti-pencil
'></i></a></td>";
		$table.="<td><a href='javascript:void(0)' onclick='DocumentRecord(".$id.")'><i class='ti-eye
'></i></a></td>";
		$it++;
		$table.="</tr>";

		$i=$i+1;
	}

		$table.="</tbody>";
}
	echo $table;
	?>