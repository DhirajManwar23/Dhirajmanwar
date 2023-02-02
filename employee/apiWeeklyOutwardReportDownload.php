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
	
	$StartDate=$_POST["StartDate"];
	$EndDate=$_POST["EndDate"];
    $qry="SELECT id, WEEK(entry_date) AS week,entry_date,SUM(quantity) as quantity
	FROM tw_outward_data_entry  where entry_date BETWEEN '".$StartDate."' AND  '".$EndDate."' GROUP BY WEEK(entry_date) order by WEEK(entry_date)";
	
	
	$qry1="select count(distinct(WEEK(entry_date))) as cnt from tw_outward_data_entry where entry_date BETWEEN '".$StartDate."' AND  '".$EndDate."' ";
	
	$table="";
	$WasteNameQry="SELECT DISTINCT material_name as name FROM `tw_outward_data_entry`";
	$retValWasteName = $sign->FunctionJSON($WasteNameQry);
	$decodedJSONretValWasteName = json_decode($retValWasteName);

	$wasteCntQry="SELECT COUNT(DISTINCT(material_name)) as cnt FROM `tw_outward_data_entry`";
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
	
	$table.="<thead><tr><th>#</th><th>Week No</th>";
	$valtotalQty=array();
	$first_run=true;

	while($x3>=$i3){
		$FetchWasteName=$decodedJSONretValWasteName->response[$count3]->name;
		$count3=$count3+1;
		$customerNameQry="SELECT name FROM tw_inward_waste_type_master where id='".$FetchWasteName."'";
		$customerName = $sign->selectF($customerNameQry,"name");
	
		$table.="<th>".$customerName."</th>";
	if($i3==$x3-0){
		
		$varComma="";
	}
	$nameWIthComma=$FetchWasteName;
	
	 $varTemp3.= "(SELECT IFNULL (sum(quantity), 00.00) FROM tw_outward_data_entry WHERE material_name='".$FetchWasteName."' AND WEEK(entry_date) = WEEK(t1.entry_date)) AS '".$FetchWasteName."' ".$varComma." ";
	
	$i3=$i3+1;
	}
	
    $table.="<th>Quantity</th></tr></thead><tbody>";
	
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
	$week = $decodedJSON2->response[$count]->week;
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
	$table.="<td>".$week."</td>";
	$varTemp = "'".$id."','".$entry_date."'";
	$year=explode('-',$entry_date);
	$fetchyear=$year[0];
	$fetchmonth=$year[1];
	
	$qryDetails=' SELECT  week(entry_date) as entry_date,'.$varTemp3.' FROM tw_outward_data_entry AS t1 where  entry_date BETWEEN "'.$StartDate.'" AND  "'.$EndDate.'"
	GROUP By  WEEK(entry_date) AND week(entry_date)='.$week.'';	
    $retVal12 = $sign->FunctionJSON($qryDetails);
	
	$decodedJSON7 = json_decode($retVal12);
	$count2 = 0;
	$count4 = 0;
			
	$qryCnt="SELECT COUNT(DISTINCT(material_name)) as cnt FROM tw_outward_data_entry";
	$Cnt=$sign->Select($qryCnt);
	$x2=$Cnt;
	$WasteNameQry="SELECT DISTINCT material_name as name FROM tw_outward_data_entry";
	$retValWasteName = $sign->FunctionJSON($WasteNameQry);
	$decodedJSONretValWasteName = json_decode($retValWasteName); 
			
	$i11 = 1;
	while($x2>=$i11){
		$FetchWasteName=$decodedJSONretValWasteName->response[$count2]->name;
		$count2=$count2+1;
		
		$qty =(int) $decodedJSON7->response[$count2]->$FetchWasteName;
			if ($first_run)
			{
				//$valtotalQty=array($Cnt);
				array_push($valtotalQty,$qty);
				
			}
			else{
				$valtotalQty[$i11-1] = $valtotalQty[$i11-1]+$qty;
			}
			 
			$table.=" <td  class='center-text'>".round($qty)."</td>";
			$i11=$i11+1;;
				
			}
			$first_run=false;
		
		$table.="<td>".round($quantity)."</td>";
		$it++;
		$table.="</tr>";
		$i=$i+1;
}
	$table.="<tr><td colspan='2'  class='right-text top-align;'><b>Total Quantity</b></td>";
	$qryCnt1="SELECT COUNT(DISTINCT(material_name)) as cnt FROM tw_outward_data_entry";
	$Cnt1=$sign->Select($qryCnt1);
	$x21=$Cnt1;
	for ($d = 0; $d <$x21; $d++) {
	$table.="<td  class='text-center' ><strong>".round($valtotalQty[$d]).
	"</strong></td>";
	}	
    $I1=1;
	$table.="<td colspan=3>".round($grandTotal)."</td></tr>
	</tbody>";
	echo $table;
}	
?>
	






