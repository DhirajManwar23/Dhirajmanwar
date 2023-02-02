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
	$qry="SELECT id, entry_date,SUM(quantity) as quantity
	FROM tw_mixwaste_manual_entry GROUP BY month(entry_date), year(entry_date) order by entry_date";
	
	
	$qry1="select count(distinct(month(entry_date)),(year(entry_date))) as cnt from tw_mixwaste_manual_entry";
	}
	else{
		
	$qry="SELECT id, entry_date,SUM(quantity) as quantity
	FROM tw_mixwaste_manual_entry  where YEAR(entry_date)='".$year."' GROUP BY month(entry_date), year(entry_date) order by entry_date";
	
	
	$qry1="select count(distinct(month(entry_date)),(year(entry_date))) as cnt from tw_mixwaste_manual_entry where YEAR(entry_date)='".$year."'";
	}
	//
	
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
while($x3>=$i3){
	$FetchWasteName=$decodedJSONretValWasteName->response[$count3]->name;
	$count3=$count3+1;
	$id=$decodedJSONretValWasteName->response[$count3]->id;
	$count3=$count3+1;
	
	if($i3==$x3-0){
		
		$varComma="";
	}
	$nameWIthComma=$FetchWasteName;
	
	 $varTemp3.= "(SELECT IFNULL (sum(quantity), 00.00) FROM tw_mixwaste_manual_entry WHERE waste_type=".$id." AND entry_date = t1.entry_date) AS '".$FetchWasteName."' ".$varComma." ";
	
	$i3=$i3+1;
	}
	
	$retVal = $sign->FunctionJSON($qry);
	$retVal1 = $sign->Select($qry1);
	$decodedJSON2 = json_decode($retVal);
	$count = 0;
	$i = 1;
	$x=$retVal1;
	
	$it=1;
	$table.="<thead><tr><th>#</th><th>Month</th><th>Quantity</th><th>View</th></tr></thead><tbody>";
	
	while($x>=$i){
	$id = $decodedJSON2->response[$count]->id;
	$count=$count+1;
	$entry_date = $decodedJSON2->response[$count]->entry_date;
	$count=$count+1;
	$quantity  = $decodedJSON2->response[$count]->quantity ;
	$count=$count+1;
	$stringDate="'".$entry_date."'";
	$newDate = date('M Y', strtotime($entry_date));
		$table.="<tr>";
		$table.="<td>".$it."</td>"; 
		$table.="<td>".$newDate."</td>";
		$varTemp = "'".$id."','".$entry_date."'";
		$year=explode('-',$entry_date);
	$fetchyear=$year[0];
	$fetchmonth=$year[1];
	
	
	$qryDetails=' SELECT  entry_date,'.$varTemp3.' FROM tw_mixwaste_manual_entry AS T1 where month(entry_date)='.$fetchmonth.' and year(entry_date)='.$fetchyear.'
	GROUP By month(entry_date)='.$fetchmonth.' and year(entry_date)='.$fetchyear.'  ';	
	
	$retVal12 = $sign->FunctionJSON($qryDetails);
	
	$decodedJSON7 = json_decode($retVal12);
	
    $qryCnt="SELECT COUNT(*) as cnt FROM `tw_segregation_waste_type_master` ";
	$Cnt=$sign->Select($qryCnt);
	
			$count2 = 0;
			$count4 = 0;
			
			$i11 = 1;
			$x2=$Cnt;
		$WasteNameQry="SELECT name FROM `tw_segregation_waste_type_master`";
			$retValWasteName = $sign->FunctionJSON($WasteNameQry);
			$decodedJSONretValWasteName = json_decode($retValWasteName); 
		
		$table.="<td>".number_format($quantity,2)."</td>";
		$table.='<td><a href="javascript:void(0);" onclick="ViewMonthlyRecord('.$varTemp.')"><i class="ti-eye"></i></a></td>';
		$it++;
		$table.="</tr>";
		$i=$i+1;
}
	$table.="</tbody>";
	echo $table;
?>
	






