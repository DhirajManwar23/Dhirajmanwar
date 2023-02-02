<?php
session_start();
	if(!isset($_SESSION["employee_id"])){
		header("Location:pgEmployeeLogIn.php");
	}
	// Include class definition
	include_once "function.php";
	$sign=new Signup();
	$employee_id = $_SESSION["employee_id"];
	$year=$_POST["year"];
	
	if($year==""){
	$qry="SELECT id,entry_date,SUM(quantity) as quantity
	FROM  tw_outward_data_entry GROUP BY month(entry_date), year(entry_date) order by entry_date";
	
	
	$qry1="select count(distinct(month(entry_date)),(year(entry_date))) as cnt from  tw_outward_data_entry";
	}
	else{
	$StartDate=$_POST["StartDate"];
	$EndDate=$_POST["EndDate"];
	$qry="SELECT id, entry_date,SUM(quantity) as quantity
	FROM  tw_outward_data_entry  where entry_date BETWEEN '".$StartDate."' AND  '".$EndDate."' GROUP BY month(entry_date), year(entry_date) order by entry_date";
	
	
	$qry1="select count(distinct(month(entry_date)),(year(entry_date))) as cnt from  tw_outward_data_entry where entry_date BETWEEN '".$StartDate."' AND  '".$EndDate."'";
	}
$table="";
$WasteNameQry="SELECT name,id FROM `tw_segregation_waste_type_master`";
$retValWasteName = $sign->FunctionJSON($WasteNameQry);
$decodedJSONretValWasteName = json_decode($retValWasteName);

$wasteCntQry="SELECT COUNT(*) as cnt FROM `tw_segregation_waste_type_master`";
$wasteCnt=$sign->Select($wasteCntQry);


$count3 = 0;
$i3 = 1;
$x3=$wasteCnt;
$varTemp3="";
$varComma=",";
$retVal1 = $sign->Select($qry1);
if($retVal1==0){
		$table.="<tr><td colspan='7' class='text-center'>No records found</td></tr>";
		echo $table;
	}
else{
	
$table.="<thead><tr><th>#</th><th>Month</th>";
$valtotalQty=array();
$first_run=true;

while($x3>=$i3){
	$FetchWasteName=$decodedJSONretValWasteName->response[$count3]->name;
	$count3=$count3+1;
	$id=$decodedJSONretValWasteName->response[$count3]->id;
	$count3=$count3+1;
	
	$table.="<th>".$FetchWasteName."</th>";
	if($i3==$x3-0){
		
		$varComma="";
	}
	$nameWIthComma=$FetchWasteName;
	
	 $varTemp3.= "(SELECT IFNULL (sum(quantity), 00.00) FROM  tw_outward_data_entry WHERE waste_type=".$id." AND month(entry_date) = month(t1.entry_date)) AS '".$FetchWasteName."' ".$varComma." ";
	
	$i3=$i3+1;
	}
	
    $table.="<th>Quantity</th><th>View</th></tr></thead><tbody>";
	
	$retVal = $sign->FunctionJSON($qry);
	
	$decodedJSON2 = json_decode($retVal);
	$count = 0;
	$i = 1;
	$x=$retVal1;

	$it=1;
	
	$grandTotal=0;
	while($x>=$i){
	$id = $decodedJSON2->response[$count]->id;
	$count=$count+1;
	$entry_date = $decodedJSON2->response[$count]->entry_date;
	$count=$count+1;
	$quantity  = $decodedJSON2->response[$count]->quantity ;
	$count=$count+1;
	$grandTotal=$grandTotal+$quantity;
	$stringDate="'".$entry_date."'";
	$newDate = date('M Y', strtotime($entry_date));
		$table.="<tr>";
		$table.="<td>".$it."</td>"; 
		$table.="<td>".$newDate."</td>";
		$varTemp = "'".$id."','".$entry_date."'";
		$year=explode('-',$entry_date);
	$fetchyear=$year[0];
	$fetchmonth=$year[1];
	
	
	$qryDetails=' SELECT  entry_date,'.$varTemp3.' FROM tw_outward_data_entry AS T1 where month(entry_date)='.$fetchmonth.' and year(entry_date)='.$fetchyear.'
	GROUP By material_name and month(entry_date)='.$fetchmonth.' and year(entry_date)='.$fetchyear.'  ';	
	
	$retVal12 = $sign->FunctionJSON($qryDetails);
	
	$decodedJSON7 = json_decode($retVal12);
	
    
	
			$count2 = 0;
			$count4 = 0;
			
			if{
				$valtotalQty[$i11-1] = $valtotalQty[$i11-1]+$qty;
			}
			 
			$table.=" <td  class='center-text'>".$qty."</td>";
			$i11=$i11+1;
				
			}
			$first_run=false;
		
		$table.="<td>".number_format($quantity,2)."</td>";
		$table.='<td><a href="javascript:void(0);" onclick="ViewMonthlyRecord('.$varTemp.')"><i class="ti-eye"></i></a></td>';
		
		$it++;
		$table.="</tr>";
		$i=$i+1;
}
//$table.="<tr><td colspan='2'  class='right-text top-align;'><b>Total Quantity</b></td>";
//$qryCnt1="SELECT COUNT(*) as cnt FROM `tw_segregation_waste_type_master` ";
		    $Cnt1=$sign->Select($qryCnt1);
			$x21=$Cnt1;
		for ($d = 0; $d <$x21; $d++) {
 

$table.="<td class='right-text' ><strong>".number_format($valtotalQty[$d],2).
"</strong></td>";
}	
	$table.="<td colspan='2'>".number_format($grandTotal,2)."</td></tr>
	
	</tbody>";
	echo $table;
}	
?>
	






