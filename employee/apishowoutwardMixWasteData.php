<?php
session_start();
	if(!isset($_SESSION["employee_id"])){
		header("Location:pgEmployeeLogIn.php");
	}
	// Include class definition
	include_once "function.php";
	$sign=new Signup();
	$employee_id = $_SESSION["employee_id"];
	echo $date=$_POST['entry_date'];
	$year=explode('-',$date);
	$fetchyear=$year[0];
	$fetchmonth=$year[1];
	
    $qry="SELECT id, entry_date,SUM(quantity) as quantity
	FROM tw_outward_data_entry  where month(entry_date)='".$fetchmonth."' AND YEAR(entry_date) ='".$fetchyear."' GROUP BY entry_date, month(entry_date) order by entry_date ";
	$retVal = $sign->FunctionJSON($qry);
	
	$qry1="select count(distinct(entry_date),(month(entry_date))) as cnt from tw_outward_data_entry where month(entry_date)='".$fetchmonth."' AND YEAR(entry_date) ='".$fetchyear."'";
	$retVal1 = $sign->Select($qry1);

	$decodedJSON2 = json_decode($retVal);
	$count = 0;
	$i = 1;
	$x=$retVal1;
	$table="";
	$it=1;
	$table.="<thead><tr><th>SR.NO</th><th>Date</th><th>Quantity</th><th>View</th></tr></thead><tbody>";
	
	while($x>=$i){
	$id = $decodedJSON2->response[$count]->id;
	$count=$count+1;
	$entry_date = $decodedJSON2->response[$count]->entry_date;
	$count=$count+1;
	$quantity  = $decodedJSON2->response[$count]->quantity ;
	$count=$count+1;
	
		$newDate = date('d-m-Y', strtotime($entry_date));
		$table.="<tr>";
		$table.="<td>".$it."</td>"; 
		$table.="<td>".$newDate."</td>";
		$varTemp = "'".$id."','".$entry_date."'";
		$table.="<td>".Round($quantity)."</td>";
		$table.='<td><a href="javascript:void(0);" onclick="ViewMonthlyRecord('.$varTemp.')"><i class="ti-eye"></i></a></td>';
		//$table.="<td><a href='javascript:void(0)' onclick='showDataEntry(".$id.")'><i class='ti-files'></a></td>";
		$it++;
		$table.="</tr>";
		$i=$i+1;
}
	$table.="</tbody>";
	echo $table;
?>
	






