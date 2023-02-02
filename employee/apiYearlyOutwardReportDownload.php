<?php
session_start();
	if(!isset($_SESSION["employee_id"])){
		header("Location:pgEmployeeLogIn.php");
	}
	// Include class definition
	include_once "function.php";
	$sign=new Signup();
	$employee_id = $_SESSION["employee_id"];
	$StartDate=$_POST["StartDate"];
	$EndDate=$_POST["EndDate"];
	$Ward = $_POST["Ward"];
	$table="";
	
	$qry="SELECT entry_date,SUM(quantity) quantity
	FROM tw_outward_data_entry where entry_date BETWEEN '".$StartDate."' AND  '".$EndDate."' AND ward='".$Ward."' GROUP BY year(entry_date) order by entry_date";
	$retVal = $sign->FunctionJSON($qry);
	
	$qry1="select count(distinct(year(entry_date))) as cnt from tw_outward_data_entry where entry_date BETWEEN '".$StartDate."' AND  '".$EndDate."' AND ward='".$Ward."' and created_by='".$employee_id."'";
	$retVal1 = $sign->Select($qry1);
	$decodedJSON2 = json_decode($retVal);
	if($retVal1==0){
		$table.="<tr><td colspan='7' class='text-center'>No records found</td></tr>";
		echo $table;
	}
	else{
	$count = 0;
	$i = 1;
	$x=$retVal1;
	
	$it=1;
	$table.="<thead><tr><th>#</th><th>Year</th>";
	
	$WasteNameQry="SELECT DISTINCT material_name as name FROM tw_outward_data_entry";
	$retValWasteName = $sign->FunctionJSON($WasteNameQry);
	$decodedJSONretValWasteName = json_decode($retValWasteName);
	
	$wasteCntQry="SELECT COUNT(DISTINCT(material_name)) as cnt FROM `tw_outward_data_entry`";
	$wasteCnt=$sign->Select($wasteCntQry);
	
	$count3 = 0;
	$i3 = 1;
	$x3=$wasteCnt;
	$varTemp3="";
	$varComma=",";
	while($x3>=$i3){
	$FetchWasteName=$decodedJSONretValWasteName->response[$count3]->name;
	$count3=$count3+1;
	
	$customerNameQry="SELECT name FROM `tw_inward_waste_type_master` where id='".$FetchWasteName."'";
    $customerName = $sign->selectF($customerNameQry,"name");
	
	$table.="<th class='center-text'>".$customerName."</th>";
	
	if($i3==$x3-0){
		
		$varComma="";
	}
	$nameWIthComma=$FetchWasteName;
	
	 $varTemp3.= "(SELECT IFNULL (sum(quantity), 00.00) FROM tw_outward_data_entry WHERE material_name='".$FetchWasteName."' AND year(entry_date) = year(t1.entry_date) AND ward='".$Ward."' and created_by='".$employee_id."') AS '".$FetchWasteName."' ".$varComma." ";
	
	$i3=$i3+1;
	}
	
	$retVal = $sign->FunctionJSON($qry);
	$retVal1 = $sign->Select($qry1);
	$decodedJSON2 = json_decode($retVal);
	$count = 0;
	$i = 1;
	$x=$retVal1;

	$it=1;
	
	$table.="<th>Quantity</th></tr></thead><tbody>";
	$valtotalQty=array();
	$first_run=true;
	$grandTotal=0;
	while($x>=$i){
	
	$entry_date = $decodedJSON2->response[$count]->entry_date;
	$count=$count+1;
	$quantity  = $decodedJSON2->response[$count]->quantity ;
	$count=$count+1;
	$grandTotal=$grandTotal+$quantity;
	$stringDate=$StartDate;
	$stringDate1=$EndDate;
	$strDate="'".$StartDate."','".$EndDate."','".$Ward."'";
	$newDate = date('Y', strtotime($entry_date));
	$newDate1 = date('Y-m-d', strtotime($entry_date));
	
	$year=explode('-',$newDate1);
	$fetchyear=$year[0];
	$fetchmonth=$year[1];
	
	
    $qryDetails=' SELECT  entry_date,'.$varTemp3.' FROM tw_outward_data_entry AS T1 where year(entry_date)='.$fetchyear.'
	GROUP By year(entry_date)='.$fetchyear.'  ';	
	
	$retVal12 = $sign->FunctionJSON($qryDetails);
	
	$decodedJSON7 = json_decode($retVal12);
			$count2 = 0;
			$count4 = 0;
	
	$qryCnt="SELECT COUNT(DISTINCT(material_name)) as cnt FROM tw_outward_data_entry";
		    $Cnt=$sign->Select($qryCnt);
			$x2=$Cnt;
			$WasteNameQry="SELECT DISTINCT material_name as name FROM `tw_outward_data_entry`";
			$retValWasteName = $sign->FunctionJSON($WasteNameQry);
			$decodedJSONretValWasteName = json_decode($retValWasteName); 
			
			$i11 = 1;
	$table.="<tr>";
	$table.="<td>".$it."</td>"; 
	$table.="<td>".$newDate."</td>";

	  while($x2>=$i11){
		 $FetchWasteName=$decodedJSONretValWasteName->response[$count2]->name;
		    $count2=$count2+1;
			  $qty = (int)$decodedJSON7->response[$count2]->$FetchWasteName;
				if ($first_run)
			{
				array_push($valtotalQty,$qty);
				
			}
			else{
				$valtotalQty[$i11-1] = $valtotalQty[$i11-1]+$qty;
			}
			
             $table.=	"
	        <td  class='center-text'>".Round($qty)."</td>";
			$i11=$i11+1;;
			}			  
		  	$first_run=false;
	
	$table.="<td>".Round($quantity)."</td>";
	
		$it++;
		$table.="</tr>";
		$i=$i+1;
}
$table.="<tr><td colspan='2'  class='right-text top-align;'><b>Total Quantity</b></td>";
$qryCnt1="SELECT COUNT(DISTINCT(material_name)) as cnt FROM `tw_outward_data_entry`  ";
		    $Cnt1=$sign->Select($qryCnt1);
			$x21=$Cnt1;
		for ($d = 0; $d <$x21; $d++) {
 

$table.="<td  class='center-text' ><strong>".Round($valtotalQty[$d]).
"</strong></td>";
		}
	$table.="<td colspan='2' >".number_format($grandTotal,2)."</td></tr></tbody>";
	echo $table;
	
	}
?>
	






