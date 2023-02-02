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
$StartDate=$_POST["StartDate"];
$EndDate=$_POST["EndDate"];
$Ward=$_POST["Ward"];
$settingValuePartner=$commonfunction->getSettingValue("Partner");
$settingCompletedStatus=$commonfunction->getSettingValue("Completed Status");
$settingKWardEMp=$commonfunction->getMRFSettingValue("ward_1");
$settingDWardEMp=$commonfunction->getMRFSettingValue("ward_2");	
$designationQry="SELECT employee_designation FROM `tw_employee_registration` where id='".$settingValuePartner."'";
$designation = $sign->SelectF($designationQry,'employee_designation'); 

$dateQry="SELECT DISTINCT entry_date,name FROM `tw_mixwaste_manual_entry` where  entry_date BETWEEN '".$StartDate."' AND  '".$EndDate."' AND ward='".$Ward."' and created_by='".$company_id."'  ORDER BY  entry_date DESC";
$DateretVal = $sign->FunctionJSON($dateQry);
$table="";
$dateCntqry="SELECT COUNT(DISTINCT(entry_date),name) as cnt FROM `tw_mixwaste_manual_entry` where  entry_date BETWEEN '".$StartDate."' AND  '".$EndDate."' AND ward='".$Ward."' and created_by='".$company_id."' ";
$dateCnt = $sign->Select($dateCntqry);
$decodedJSON4 = json_decode($DateretVal);
$qtySeg=0;
	if($Ward==1){
		$employee_id=$settingKWardEMp;
	}
	else if($Ward==2){
		$employee_id=$settingDWardEMp;
	}
	else{
	$employee_id = $_SESSION["employee_id"];
	}

if($dateCnt==0){
		$table.="<tr><td colspan='7' class='text-center'>No records found</td></tr>";
		echo $table;
	}
	else{
	$count2 = 0;
	$i2 = 1;
	$x2=$dateCnt;
	
	$table.="<thead><tr><th>#</th><th>Date</th><th>Name</th><th>Total Quantity</th></tr></thead><tbody>";
	$it=1;
while($x2>=$i2){
  $date = $decodedJSON4->response[$count2]->entry_date;
$count2=$count2+1; 
$Disname = $decodedJSON4->response[$count2]->name;
$count2=$count2+1;




	 $qry="SELECT id,entry_date,sum(quantity) as quantity,name FROM `tw_mixwaste_manual_entry` where  entry_date='".$date."' and name='".$Disname."' and entry_date BETWEEN '".$StartDate."' AND  '".$EndDate."' AND ward='".$Ward."' and created_by='".$company_id."' ";

		$retVal = $sign->FunctionJSON($qry);

		$qry1="Select count(distinct(month(entry_date)),(name)) as cnt from tw_mixwaste_manual_entry where entry_date='".$date."' and name='".$Disname."' and entry_date BETWEEN '".$StartDate."' AND  '".$EndDate."' AND ward='".$Ward."' and created_by='".$company_id."' ";
		 $retVal1 = $sign->Select($qry1);

		$decodedJSON2 = json_decode($retVal);
		$count = 0;
		$i = 1;
		$x=$retVal1;
		
	if($designation==$settingValuePartner){
	$varTempseg.= "(SELECT sum(quantity) FROM tw_mix_waste_collection_details  WHERE waste_type=".$id." AND month(created_on) = month(t1.created_on) AND year(created_on)=year(t1.created_on)  and created_by='".$employee_id."' status='".$settingCompletedStatus."') AS '".$FetchWasteName."' ".$varComma." ";
	 }else{
	 $varTempseg="";
	 }
		
		while($x>=$i){
			$id = $decodedJSON2->response[$count]->id;
			$count=$count+1;
			$entry_date = $decodedJSON2->response[$count]->entry_date;
			$count=$count+1;
			$quantity = $decodedJSON2->response[$count]->quantity;
			$count=$count+1;
			$name = $decodedJSON2->response[$count]->name;
			$count=$count+1;
			
			if($designation!=$settingValuePartner){
			$qryDetailsSeg="SELECT  IFNULL(sum(quantity),00) as Segquantity FROM  tw_mix_waste_collection_details where  created_on BETWEEN '".$StartDate."' AND  '".$EndDate."'  and created_by='".$employee_id."' and status='".$settingCompletedStatus."' ";
		   $qtySeg=$sign->SelectF($qryDetailsSeg,'Segquantity'); 
		}
			
			    $date2=date_create($date);
                $date1=date_format($date2,"Y_m_d");
				$table.="<tr>";
				$table.="<td>".$it."</td>"; 
				$table.="<td>".date("d-m-Y",strtotime($entry_date))."</td>";
				$table.="<td>".$name."</td>";
				$table.="<td>".$quantity + $qtySeg."</td>";
				$varTemp1 = "'".$name."','".$entry_date."'";
				
				$it++;
				$table.="</tr>";
				

			$i=$i+1;
	    }
		$i2=$i2+1;
}
	$table.="</tbody>";
	echo $table;
}
?>										