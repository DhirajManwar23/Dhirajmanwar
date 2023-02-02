<?php
session_start();
if(!isset($_SESSION["employee_id"])){
	header("Location:pgEmployeeLogIn.php");
}
// Include class definition
include_once "function.php";
$sign=new Signup();
$requestid=$_POST["requestid"];
$date=$_REQUEST["date"];
$year=explode('-',$date);
$fetchyear=$year[0];
$fetchmonth=$year[1];
$NameQry="SELECT DISTINCT material_name FROM `tw_outward_data_entry` where entry_date='".$date."'";
$retValPODetails = $sign->FunctionJSON($NameQry);
$decodedJSONPODetails = json_decode($retValPODetails);


$WasteNameQry="SELECT DISTINCT material_name FROM `tw_outward_data_entry`";
$retValWasteName = $sign->FunctionJSON($WasteNameQry);
$decodedJSONretValWasteName = json_decode($retValWasteName);

$wasteCntQry="SELECT COUNT(DISTINCT(material_name)) as cnt FROM `tw_outward_data_entry` ";
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
	$FetchWasteName=$decodedJSONretValWasteName->response[$count]->material_name;
	$count=$count+1;

	$customerNameQry="SELECT name FROM `tw_inward_waste_type_master` where id='".$FetchWasteName."'";
    $customerName = $sign->selectF($customerNameQry,"name");
	
	
	
	
	$table.="<th  class='center-text'>".$customerName."</th>
	
	";
	$i=$i+1;
	}
	
	
$table.=" </tr> ";




$WasteNameQry="SELECT DISTINCT material_name FROM `tw_outward_data_entry`";
$retValWasteName = $sign->FunctionJSON($WasteNameQry);
$decodedJSONretValWasteName = json_decode($retValWasteName);

$wasteCntQry="SELECT COUNT(DISTINCT(material_name)) as cnt FROM `tw_outward_data_entry`";
$wasteCnt=$sign->Select($wasteCntQry);

$count = 0;
$i = 1;
$x=$wasteCnt;
$varTemp="";
$varComma=",";
  //$qryDetails=' ';
while($x>=$i){
	$FetchWasteName=$decodedJSONretValWasteName->response[$count]->material_name;
	$count=$count+1;
	
	
	
	if($i==$x-0){
		
		$varComma="";
	}
	$nameWIthComma=$FetchWasteName;
	
	 $varTemp.= "(SELECT sum(quantity) FROM tw_outward_data_entry WHERE material_name='".$FetchWasteName."' AND entry_date = t1.entry_date) AS '".$i."' ".$varComma." ";
	
	$i=$i+1;
	}
	
	
	$qryDetails=' SELECT  entry_date,'.$varTemp.' FROM tw_outward_data_entry AS t1 where month(entry_date)='.$fetchmonth.' and year(entry_date)='.$fetchyear.'
	GROUP BY entry_date  ';	
	$table.="</tr></table>";
	$retVal = $sign->FunctionJSON($qryDetails);
	
	$decodedJSON7 = json_decode($retVal);
	
     $qryCnt="SELECT COUNT( DISTINCT entry_date) cnt FROM `tw_outward_data_entry` where month(entry_date)='".$fetchmonth."' and year(entry_date)='".$fetchyear."' ";
	 $Cnt=$sign->Select($qryCnt);
	
	$count2 = 0;
	
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
	 $count2=$count2+1;
     if(empty($qty)){
		$qty=0;
	}
	 $table.=	"
	<td  class='center-text'>".Round($qty)."</td>
	
	
	";
	 
	$i11=$i11+1;;
	 }
	
	

	$i2=$i2+1;
	}
echo $table;
?>