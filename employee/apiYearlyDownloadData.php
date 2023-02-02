<?php
session_start();
	
	
	// Include class definition
	include_once "function.php";
	$sign=new Signup();
	include_once "commonFunctions.php";	
	$commonfunction=new Common();
	
	$company_id = $_SESSION["company_id"];
	$employee_id = $_SESSION["employee_id"];
	$settingValuePartner=$commonfunction->getSettingValue("Partner");
	$settingKWardEMp=$commonfunction->getMRFSettingValue("ward_1");
	$settingDWardEMp=$commonfunction->getMRFSettingValue("ward_2");
	$settingCompletedStatus=$commonfunction->getSettingValue("Completed Status");
	$designationQry="SELECT employee_designation FROM `tw_employee_registration` where id='".$settingValuePartner."'";
	$designation = $sign->SelectF($designationQry,'employee_designation'); 
	$Ward = $_POST["Ward"];

	if($Ward==1){
	$employee_id=$settingKWardEMp;
	}
	else if($Ward==2){
		$employee_id=$settingDWardEMp;
	}
	else{
	$employee_id = $_SESSION["employee_id"];
	}
	
	$StartDate=$_POST["StartDate"];
	
	$Startyear = explode('-', $StartDate);
	
	$Startfetchmonth = $Startyear[0];
	
	$EndDate=$_POST["EndDate"];
	$ENDtyear = explode('-', $EndDate);
	
	$ENdfetchmonth = $ENDtyear[0];
	
	$table="";
	$qry="SELECT entry_date,SUM(quantity) quantity
	FROM tw_mixwaste_manual_entry where entry_date BETWEEN '".$StartDate."' AND  '".$EndDate."' AND ward='".$Ward."' GROUP BY year(entry_date) order by entry_date";
	$retVal = $sign->FunctionJSON($qry);
	
	$qrySeg="SELECT sum(quantity) as Qty FROM `tw_mix_waste_collection_details`  where created_on BETWEEN '".$StartDate."' AND  '".$EndDate."' and status='".$settingCompletedStatus."' and created_by='".$employee_id."' GROUP BY year(created_on) order by created_on";
	 $SegTotalqty=$sign->SelectF($qrySeg,"Qty");
	
	$qry1="select count(distinct(year(entry_date))) as cnt from tw_mixwaste_manual_entry where entry_date BETWEEN '".$StartDate."' AND  '".$EndDate."' AND ward='".$Ward."' and created_by='".$company_id."' ";
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
	$qtySeg=0;
	$it=1;
	$table.="<thead><tr><th>#</th><th>Year</th>";
	
	$WasteNameQry="SELECT name,id FROM `tw_inward_waste_type_master`";
	$retValWasteName = $sign->FunctionJSON($WasteNameQry);
	$decodedJSONretValWasteName = json_decode($retValWasteName);
	
	$wasteCntQry="SELECT COUNT(*) as cnt FROM `tw_inward_waste_type_master`";
	$wasteCnt=$sign->Select($wasteCntQry);
	
	$count3 = 0;
	$i3 = 1;
	$x3=$wasteCnt;
	$varTemp3="";
	$varTempSeg="";
	$varComma=",";
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
	
	 $varTemp3.= "(SELECT IFNULL (sum(quantity), 00.00) FROM tw_mixwaste_manual_entry WHERE waste_type=".$id." AND year(entry_date) = year(t1.entry_date) AND year(entry_date) BETWEEN '".$Startfetchmonth."' and '".$ENdfetchmonth."' AND ward='".$Ward."' and created_by='".$company_id."') AS '".$FetchWasteName."' ".$varComma." ";
	 
	 if($designation!=$settingValuePartner){
	$varTempSeg.= "(SELECT IFNULL (sum(cd.quantity), 00.00) FROM tw_mix_waste_collection_details cd INNER JOIN tw_mix_waste_lot_info li ON cd.mix_waste_lot_id=li.id WHERE cd.waste_type=".$id." AND year(cd.created_on) = year(t1.created_on) AND year(cd.created_on) BETWEEN '".$Startfetchmonth."' and '".$ENdfetchmonth."' and cd.created_by='".$employee_id."' and li.status='".$settingCompletedStatus."') AS '".$FetchWasteName."' ".$varComma."  ";
	 }
	 else{
	 $varTempseg="";
	 }
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
	$valtotalQtySeg=array();
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
	
	
   $qryDetails=' SELECT  year(entry_date),'.$varTemp3.' FROM tw_mixwaste_manual_entry AS t1 where  year(entry_date)='.$fetchyear.'
	GROUP By year(entry_date)  ';	
	
	$retVal12 = $sign->FunctionJSON($qryDetails);
	
	$decodedJSON7 = json_decode($retVal12);
	
		if($designation!=$settingValuePartner){
			$qryDetailsSeg=' SELECT  year(created_on),'.$varTempSeg.' FROM tw_mix_waste_collection_details AS t1 group by year(created_on)='.$fetchyear.'
			 ';	
			$retVal1Seg = $sign->FunctionJSON($qryDetailsSeg);
			$decodedJSONseg = json_decode($retVal1Seg);
		}	
			$count2 = 0;
			$count4 = 0;
	
	$qryCnt="SELECT COUNT(*) as cnt FROM `tw_inward_waste_type_master` ";
		    $Cnt=$sign->Select($qryCnt);
			$x2=$Cnt;
			$WasteNameQry="SELECT name FROM `tw_inward_waste_type_master`";
			$retValWasteName = $sign->FunctionJSON($WasteNameQry);
			$decodedJSONretValWasteName = json_decode($retValWasteName); 
			
			$i11 = 1;
		
	
	$table.="<tr>";
	$table.="<td>".$it."</td>"; 
	$table.="<td>".$newDate."</td>";

	  while($x2>=$i11){
		 $FetchWasteName=$decodedJSONretValWasteName->response[$count2]->name;
		    $count2=$count2+1;

			 $qty =(int)$decodedJSON7->response[$count2]->$FetchWasteName;
				if($designation!=$settingValuePartner){
					echo $qtySeg =(int)$decodedJSONseg->response[$count2]->$FetchWasteName;
				}
				
				
				
				if ($first_run)
				{
					array_push($valtotalQty,$qty);
					array_push($valtotalQtySeg,$qtySeg);
				}
				else{
					$valtotalQty[$i11-1] = $valtotalQty[$i11-1]+$qty;
					$valtotalQtySeg[$i11-1] = $valtotalQtySeg[$i11-1]+$qtySeg;
				}
				
             $table.=	"
	        <td  class='center-text'>".Round($qty + $qtySeg)."</td>";
			$i11=$i11+1;;
			}			  
		  	$first_run=false;
	
	$table.="<td>".Round((int)$quantity + (int)$SegTotalqty)."</td>";
	
		
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
$table.="<td  class='center-text' ><strong>".Round($valfinalqty).
"</strong></td>";
		}
		$finaltotalQuantity=(int)$SegTotalqty+(int)$grandTotal;
	$table.="<td colspan='2' >".Round($finaltotalQuantity)."</td></tr></tbody>";
	echo $table;
	
	}
?>
	






