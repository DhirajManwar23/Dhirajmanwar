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
$requestid=$_POST["requestid"];
$date=$_REQUEST["date"];
$ward=$_REQUEST["Ward"];

$employee_id = $_SESSION["employee_id"];
$settingValuePartner=$commonfunction->getSettingValue("Partner");
$settingCompletedStatus=$commonfunction->getSettingValue("Completed Status");
$settingKWardEMp=$commonfunction->getMRFSettingValue("ward_1");
$settingDWardEMp=$commonfunction->getMRFSettingValue("ward_2");
$designationQry="SELECT employee_designation FROM `tw_employee_registration` where id='".$settingValuePartner."'";
$designation = $sign->SelectF($designationQry,'employee_designation'); 

$designationQry="SELECT employee_designation FROM `tw_employee_registration` where id='".$settingValuePartner."'";
$designation = $sign->SelectF($designationQry,'employee_designation'); 

if($ward==1){
		$employee_id=$settingKWardEMp;
	}
	else if($ward==2){
		$employee_id=$settingDWardEMp;
	}
	else{
	$employee_id = $_SESSION["employee_id"];
	}


$year=explode('-',$date);
$fetchyear=$year[0];
$fetchmonth=$year[1];
$NameQry="SELECT DISTINCT name FROM `tw_mixwaste_manual_entry` where entry_date='".$date."'";
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
$varTempseg="";
$qtySeg=0;
while($x>=$i){
	$FetchWasteName=$decodedJSONretValWasteName->response[$count]->name;
	$count=$count+1;
	$id=$decodedJSONretValWasteName->response[$count]->id;
	$count=$count+1;
	
	
	
	
	
	$table.="<th  class='center-text'>".$FetchWasteName."</th>
	
	";
	$i=$i+1;
	}
	
	
$table.=" </tr> ";




$WasteNameQry="SELECT name,id FROM `tw_inward_waste_type_master`";
$retValWasteName = $sign->FunctionJSON($WasteNameQry);
$decodedJSONretValWasteName = json_decode($retValWasteName);

$wasteCntQry="SELECT COUNT(*) as cnt FROM `tw_inward_waste_type_master`";
$wasteCnt=$sign->Select($wasteCntQry);

$count = 0;
$i = 1;
$x=$wasteCnt;
$varTemp="";
$varComma=",";
  //$qryDetails=' ';
while($x>=$i){
	$FetchWasteName=$decodedJSONretValWasteName->response[$count]->name;
	$count=$count+1;
	$id=$decodedJSONretValWasteName->response[$count]->id;
	$count=$count+1;
	
	
	if($i==$x-0){
		
		$varComma="";
	}
	$nameWIthComma=$FetchWasteName;
	
	 $varTemp.= "(SELECT sum(quantity) FROM tw_mixwaste_manual_entry WHERE waste_type='".$id."' AND entry_date = t1.entry_date) AS '".$i."' ".$varComma." ";
	
	
	 if($designation!=$settingValuePartner){
	$varTempseg.= "(SELECT IFNULL( sum(quantity),00 )FROM tw_mix_waste_collection_details WHERE waste_type='".$id."' AND created_on = t1.created_on and created_by='".$employee_id."' and status='".$settingCompletedStatus."') AS '".$i."' ".$varComma." ";
	 }else{
	 $varTempseg="";
	 }
	
	$i=$i+1;
	}
	
	
	$qryDetails=' SELECT  entry_date,'.$varTemp.' FROM tw_mixwaste_manual_entry AS t1 where month(entry_date)='.$fetchmonth.' and year(entry_date)='.$fetchyear.' and ward='.$ward.' and created_by='.$company_id.'
	GROUP BY entry_date  ';	
	$table.="</tr></table>";
	$retVal = $sign->FunctionJSON($qryDetails);
	
	$decodedJSON7 = json_decode($retVal);
	
	 if($designation!=$settingValuePartner){
		$qryDetailsSeg=' SELECT  created_on,'.$varTempseg.' FROM tw_mix_waste_collection_details  AS t1 
		where status='.$settingCompletedStatus.' and created_by='.$employee_id.' group by     month(created_on)='.$fetchmonth.' and year(created_on)='.$fetchyear.'   ';	
		
		$retVal1Seg = $sign->FunctionJSON($qryDetailsSeg);
		$decodedJSONseg = json_decode($retVal1Seg);
		
	}
	
     $qryCnt="SELECT COUNT( DISTINCT entry_date) cnt FROM `tw_mixwaste_manual_entry` where month(entry_date)='".$fetchmonth."' and year(entry_date)='".$fetchyear."' and ward='".$ward."' and created_by='".$company_id."' ";
	 $Cnt=$sign->Select($qryCnt);
	
	$count2 = 0;
	$count0=0;
	$i2 = 1;
	$x2=$Cnt;
	$valtotal="00.00";
	while($x2>=$i2){
	
	 $entry_date = $decodedJSON7->response[$count2]->entry_date;
	 $count2=$count2+1;
	 $i11=1;
	 $table.="<tr>
	<td>".$i2."</td>
	<td  class='center-text'>".date("d-m-Y",strtotime($entry_date))."</td>";
	
	 while($x>=$i11){
    
	 $qty = (int)$decodedJSON7->response[$count2]->$i11;
	 

	if($designation!=$settingValuePartner){
		$qtySeg =(int)$decodedJSONseg->response[$count2]->$i11;
	}
      $count2=$count2+1;
	 $table.=	"
	<td  class='center-text'>".round($qty + $qtySeg)."</td>
	
	
	";
	 
	$i11=$i11+1;;
	 }
	
	

	$i2=$i2+1;
	}
echo $table;
?>