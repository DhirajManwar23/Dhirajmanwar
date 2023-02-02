<?php
session_start();
	if(!isset($_SESSION["employee_id"])){
		header("Location:pgEmployeeLogIn.php");
	}
	// Include class definition
	include_once "function.php";
	$sign=new Signup();
	include_once "commonFunctions.php";	
	$commonfunction=new Common();
	$company_id = $_SESSION["company_id"];
	$year=$_POST["year"];
	$Ward=$_POST["Ward"];
	$settingKWardEMp=$commonfunction->getMRFSettingValue("ward_1");
	$settingDWardEMp=$commonfunction->getMRFSettingValue("ward_2");
	$employee_id = $_SESSION["employee_id"];
	$settingValuePartner=$commonfunction->getSettingValue("Partner");
	$settingCompletedStatus=$commonfunction->getSettingValue("Completed Status");
	$designationQry="SELECT employee_designation FROM `tw_employee_registration` where id='".$settingValuePartner."'";
	$designation = $sign->SelectF($designationQry,'employee_designation'); 
		if($Ward==1){
		$employee_id=$settingKWardEMp;
	}
	else if($Ward==2){
		$employee_id=$settingDWardEMp;
	}
	else{
	$employee_id = $_SESSION["employee_id"];
	}
	
	
	
	if($year==""){
	$qry="SELECT id, entry_date,SUM(quantity) as quantity
	FROM tw_mixwaste_manual_entry GROUP BY month(entry_date), year(entry_date) order by entry_date";
	
	
	$qry1="select count(distinct(month(entry_date)),(year(entry_date))) as cnt from tw_mixwaste_manual_entry";
	}
	else{
	$StartDate=$_POST["StartDate"];
	$Startyear=explode('-',$StartDate);
	$fetchyear=$Startyear[0];
	
	$EndDate=$_POST["EndDate"];
	$Endyear=explode('-',$EndDate);
	$Endyear=$Endyear[0];
	
    $qry="SELECT id, WEEK(entry_date) AS week,entry_date,SUM(quantity) as quantity
	FROM tw_mixwaste_manual_entry  where entry_date BETWEEN '".$StartDate."' AND  '".$EndDate."' AND ward='".$Ward."' and created_by='".$company_id."' GROUP BY WEEK(entry_date) order by WEEK(entry_date)";
	
	
	$qrySeg="SELECT sum(quantity) as Qty FROM `tw_mix_waste_collection_details` where created_on BETWEEN '".$StartDate."' AND '".$EndDate."' and created_by='".$employee_id."' and status='".$settingCompletedStatus."' order by week(created_on)";
	$SegTotalqty=$sign->SelectF($qrySeg,"Qty");
	
	$qry1="select count(distinct(WEEK(entry_date))) as cnt from tw_mixwaste_manual_entry where entry_date BETWEEN '".$StartDate."' AND  '".$EndDate."' AND ward='".$Ward."' and created_by='".$company_id."' ";
	}
$table="";
$WasteNameQry="SELECT name,id FROM `tw_inward_waste_type_master`";
$retValWasteName = $sign->FunctionJSON($WasteNameQry);
$decodedJSONretValWasteName = json_decode($retValWasteName);

$wasteCntQry="SELECT COUNT(*) as cnt FROM `tw_inward_waste_type_master`";
$wasteCnt=$sign->Select($wasteCntQry);


$count3 = 0;
$i3 = 1;
$x3=$wasteCnt;
$qtySeg=0;
$varTemp3="";
$varTempSeg="";
$varComma=",";
$retVal1 = $sign->Select($qry1);
if($retVal1==0){
		$table.="<tr><td colspan='7' class='text-center'>No records found</td></tr>";
		echo $table;
	}
else{
	
$table.="<thead><tr><th>#</th><th>Week No</th>";
$valtotalQty=array();
$valtotalQtySeg=array();
$first_run=true;
$rowQty=0;
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
	
	 $varTemp3.= "(SELECT sum(quantity) FROM tw_mixwaste_manual_entry WHERE waste_type=".$id." AND WEEK(entry_date) = WEEK(t1.entry_date) AND year(entry_date) between '".$fetchyear."' AND '".$Endyear."' and ward='".$Ward."' and created_by='".$company_id."') AS '".$FetchWasteName."' ".$varComma." "; 
	 
	 if($designation!=$settingValuePartner){
	 
	 $varTempSeg.= "(SELECT sum(cd.quantity) FROM tw_mix_waste_collection_details cd INNER JOIN tw_mix_waste_lot_info li ON cd.mix_waste_lot_id=li.id WHERE waste_type=".$id." AND WEEK(cd.created_on) = WEEK(t1.created_on) AND year(cd.created_on) between '".$fetchyear."' AND '".$Endyear."'  and cd.created_by='".$employee_id."' and li.status='".$settingCompletedStatus."') AS '".$FetchWasteName."' ".$varComma." ";
	 }
	 else{
	 $varTempseg="";
	 }
	 
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
			
			$count2 = 0;
			$count4 = 0;
			
	$qryDetails=' SELECT  week(entry_date) as entry_date,'.$varTemp3.' FROM tw_mixwaste_manual_entry AS t1 where  entry_date BETWEEN "'.$StartDate.'" AND  "'.$EndDate.'"  AND week(entry_date)='.$week.' 
	GROUP By  WEEK(entry_date) ';	
	
	$retVal12 = $sign->FunctionJSON($qryDetails);
	
	$decodedJSON7 = json_decode($retVal12);
	
		 if($designation!=$settingValuePartner){
		$qryDetailsSeg=' SELECT  week(created_on) as entry_date,'.$varTempSeg.' FROM tw_mix_waste_collection_details AS t1 where  created_on BETWEEN "'.$StartDate.'" AND  "'.$EndDate.'" GROUP by week(created_on)='.$week.' 
			 ';
			$retVal1Seg = $sign->FunctionJSON($qryDetailsSeg);
			$decodedJSONseg = json_decode($retVal1Seg);
		 }		
		 
			$qryCnt="SELECT COUNT(*) as cnt FROM `tw_inward_waste_type_master` ";
		    $Cnt=$sign->Select($qryCnt);
			$x2=$Cnt;
			$WasteNameQry="SELECT name FROM `tw_inward_waste_type_master`";
			$retValWasteName = $sign->FunctionJSON($WasteNameQry);
			$decodedJSONretValWasteName = json_decode($retValWasteName); 
			
			
			$i11 = 1;
			
		  while($x2>=$i11){
		       
				$FetchWasteName=$decodedJSONretValWasteName->response[$count2]->name;
				$count2=$count2+1;
                $qty =(int)$decodedJSON7->response[$count2]->$FetchWasteName;
				
				if($designation!=$settingValuePartner){
				$qtySeg =(int)$decodedJSONseg->response[$count2]->$FetchWasteName;
				}
				
				if ($first_run)
				{
					//$valtotalQty=array($Cnt);
					array_push($valtotalQty,$qty);
					array_push($valtotalQtySeg,$qtySeg);
					
				}
				else{
					//print_r($valtotalQty);
					$valtotalQty[$i11-1] = $valtotalQty[$i11-1]+$qty;
					$valtotalQtySeg[$i11-1] = $valtotalQtySeg[$i11-1]+$qtySeg;
				}
				 
				$table.=" <td  class='center-text'>".round($qty + $qtySeg)."</td>";
				$i11=$i11+1;;
			 
			}
			
			
		$table.="<td>".round($quantity + $SegTotalqty)."</td>";
		
		$first_run=false;
		$it++;
		$table.="</tr>";
		$i=$i+1;
}

$table.="<tr><td colspan='2'  class='right-text top-align;'><b>Total Quantity</b></td>";
$qryCnt1="SELECT COUNT(*) as cnt FROM `tw_inward_waste_type_master` ";
		    $Cnt1=$sign->Select($qryCnt1);
			$x21=$Cnt1;
		for ($d = 0; $d <$x21; $d++) {
 
$valfinalqty=$valtotalQty[$d] +  $valtotalQtySeg[$d];
$table.="<td  class='text-center' ><strong>".round((int)$valfinalqty).
"</strong></td>";
}	
$finaltotalQuantity=$SegTotalqty+$grandTotal;
	$table.="<td colspan='2'>".round($finaltotalQuantity)."</td></tr>
	
	</tbody>";
	echo $table;
}	
?>
	






