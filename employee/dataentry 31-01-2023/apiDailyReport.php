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
$requestid=$_POST["requestid"];
$name=$_REQUEST["name"];
$StartDate=$_POST["StartDate"];
$EndDate=$_POST["EndDate"];
$qry="SELECT DISTINCT entry_date FROM `tw_mixwaste_manual_entry` where id='".$requestid."'";
$fetchdate=$sign->SelectF($qry,"entry_date");

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

$NameQry="SELECT DISTINCT name FROM `tw_mixwaste_manual_entry` where entry_date='".$fetchdate."'";
$retValPODetails = $sign->FunctionJSON($NameQry);
$decodedJSONPODetails = json_decode($retValPODetails);


$WasteNameQry="SELECT name,id FROM `tw_inward_waste_type_master`";
$retValWasteName = $sign->FunctionJSON($WasteNameQry);
$decodedJSONretValWasteName = json_decode($retValWasteName);

$wasteCntQry="SELECT COUNT(*) as cnt FROM `tw_inward_waste_type_master` ";
$wasteCnt=$sign->Select($wasteCntQry);
$table="";
$table.=" <table>
		<table width='100%' class='printtbl' >
			 <tr>
			<th  class='center-text'>#</th>
			<th  class='center-text'>Date</th>
			
			";
$count = 0;
$i = 1;
$x=$wasteCnt;

while($x>=$i){
	$FetchWasteName=$decodedJSONretValWasteName->response[$count]->name;
	$count=$count+1;
	$id=$decodedJSONretValWasteName->response[$count]->id;
	$count=$count+1;
	
	
	
	
	
	$table.="<th  class='center-text'>".$FetchWasteName."</th>
	
	";
	$i=$i+1;
	}
	
	
$table.="</tr>";



	if($designation!=$settingValuePartner){
		$qryDetails="select swm.id,swm.name,me.quantity + cd.quantity as segqty from tw_inward_waste_type_master swm INNER JOIN tw_mixwaste_manual_entry me ON me.waste_type=swm.id INNER JOIN tw_mix_waste_collection_details cd ON cd.waste_type=me.waste_type where swm.visibility='true' and me.entry_date='".$fetchdate."'and me.name='".$name."' and cd.status=8 and cd.created_by=8 and cd.created_on BETWEEN '".$StartDate."' AND '".$EndDate."' order by priority,id desc";
		$retVal = $sign->FunctionJSON($qryDetails);	
			
	 }
	 else{
		$qryDetails="select swm.id,swm.name,me.quantity as segqty from tw_inward_waste_type_master swm INNER JOIN tw_mixwaste_manual_entry me ON me.waste_type=swm.id where swm.visibility='true' and me.entry_date='".$fetchdate."'and me.name='".$name."' order by priority,id desc";
		$retVal = $sign->FunctionJSON($qryDetails);
		$retVal = $sign->FunctionJSON($qryDetails);	
			
		 
	 }


$qry1="Select count(*) as cnt from tw_inward_waste_type_master where visibility='true'";
$retVal1 = $sign->Select($qry1);

$decodedJSON2 = json_decode($retVal);
$count = 0;
$count2 = 0;
$i = 1;
$x=$retVal1;
$table.="<tr>
<td>".$i."</td>
	<td>".date("d-m-Y",strtotime($fetchdate))."</td>";
while($x>=$i){
	$id = $decodedJSON2->response[$count]->id;
	$count=$count+1;
	$name = $decodedJSON2->response[$count]->name;
	$count=$count+1;

    $quantityseg = $decodedJSON2->response[$count]->segqty;
	$count=$count+1;
	
	// if($designation!=$settingValuePartner){
			// $qtySeg =(int)$decodedJSONseg->response[$count2]->quantity;
			// $count2=$count2+1;
	// }
	
	$i=$i+1;
	$table.="
	
	<td>".$quantityseg ."</td>
	
	
	";


}	
		
		
	$table.="</tr></table>";
echo $table;
?>