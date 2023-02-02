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
$qtySeg=0;
$employee_id = $_SESSION["employee_id"];
$settingValuePartner=$commonfunction->getSettingValue("Partner");
$settingKWardEMp=$commonfunction->getMRFSettingValue("ward_1");
$settingDWardEMp=$commonfunction->getMRFSettingValue("ward_2");
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

	
$dateQry="
SELECT * from(
    (SELECT   DISTINCT me.entry_date as entry_date,me.name FROM `tw_mixwaste_manual_entry` me  where  me.entry_date BETWEEN '".$StartDate."' AND  '".$EndDate."' AND me.ward='".$Ward."' and me.created_by='".$company_id."' ORDER BY  me.entry_date DESC)
              UNION ALL 

(SELECT   DISTINCT cd.created_on as entry_date,' ' FROM  tw_mix_waste_collection_details cd  where  cd.created_by='".$employee_id."' and cd.created_on BETWEEN '".$StartDate."' AND  '".$EndDate."'   ORDER BY  cd.created_on DESC)
             )t3 ORDER BY t3.entry_date ASC
";

//SELECT DISTINCT entry_date,name FROM `tw_mixwaste_manual_entry` where  entry_date BETWEEN '".$StartDate."' AND  '".$EndDate."' AND ward='".$Ward."' and created_by='".$company_id."'  ORDER BY  entry_date DESC

$DateretVal = $sign->FunctionJSON($dateQry);
$table="";


$dateCntqry="
SELECT count(*) as cnt from(
    (SELECT   DISTINCT me.entry_date as entry_date,me.name FROM `tw_mixwaste_manual_entry` me  where  me.entry_date BETWEEN '".$StartDate."' AND  '".$EndDate."' AND me.ward='".$Ward."' and me.created_by='".$company_id."' ORDER BY  me.entry_date DESC)
              UNION ALL 

(SELECT   DISTINCT cd.created_on as entry_date,' ' FROM  tw_mix_waste_collection_details cd  where  cd.created_by='".$employee_id."' and cd.created_on BETWEEN '".$StartDate."' AND  '".$EndDate."'   ORDER BY  cd.created_on DESC)
             )t3 ORDER BY t3.entry_date ASC ";
$dateCnt = $sign->Select($dateCntqry);
$decodedJSON4 = json_decode($DateretVal);

if($dateCnt==0){
		$table.="<tr><td colspan='7' class='text-center'>No records found</td></tr>";
		echo $table;
	}
	else{
	$count2 = 0;
	$i2 = 1;
	$x2=$dateCnt;
	$totalMixqty=0;
	$table.="<thead><tr><th>#</th><th>Date</th><th>Name</th><th>Total Quantity</th><th>Edit</th><th>Delete</th><th>View</th></tr></thead><tbody>";
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
		
		
		
		while($x>=$i){
			$id = $decodedJSON2->response[$count]->id;
			$count=$count+1;
			$entry_date = $decodedJSON2->response[$count]->entry_date;
			$count=$count+1;
			$quantity = (int)$decodedJSON2->response[$count]->quantity;
			$count=$count+1;
			$name = $decodedJSON2->response[$count]->name;
			$count=$count+1;
		if($designation!=$settingValuePartner){
			 $LotIdqry="SELECT DISTINCT cd.mix_waste_lot_id FROM `tw_mix_waste_collection_details` cd INNER join tw_mix_waste_lot_info li ON li.id=cd.mix_waste_lot_id where cd.created_on='".$date."' and li.status='".$settingCompletedStatus."' ";
			$LotId=(int)$sign->SelectF($LotIdqry,'mix_waste_lot_id');
			
			
			
			$qryDetailsSeg="SELECT  IFNULL(sum(quantity),00) as Segquantity FROM  tw_mix_waste_collection_details where   created_on BETWEEN '".$StartDate."' AND  '".$EndDate."'  and created_by='".$employee_id."' and mix_waste_lot_id='".$LotId."' ";
		    $qtySeg=(int)$sign->SelectF($qryDetailsSeg,'Segquantity');
			
		}
		        $totalMixqty=$quantity + $qtySeg;
			    $date2=date_create($date);
                $date1=date_format($date2,"Y_m_d");
				$table.="<tr>";
				$table.="<td>".$it."</td>"; 
				$table.="<td>".date("d-m-Y",strtotime($entry_date))."</td>";
				$table.="<td>".$name."</td>";
				$table.="<td>".$totalMixqty."</td>";
				$varTemp1 = "'".$name."','".$entry_date."'";
				$table.="<td><a href='javascript:void(0)' onclick='editRecord(".$id.",".$date1.")'><i class='ti-pencil'></i></a></td>";
				$table.='<td><a href="javascript:void(0)" onclick="DeleteRecord('.$varTemp1.')"><i class="ti-trash"></i></a></td>';
				$varTemp = "'".$id."','".$name."','".$StartDate."','".$EndDate."'";
				$table.='<td><a href="javascript:void(0);" onclick="ViewDailyRecord('.$varTemp.')"><i class="ti-eye"></i></a></td>';
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