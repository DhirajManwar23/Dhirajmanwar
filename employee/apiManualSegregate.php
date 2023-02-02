<?php
session_start();
// Include class definition
include_once "function.php";
$sign=new Signup();
 $valrequesttype=$_POST['valrequesttype'];
 
$company_id = $_SESSION["company_id"];
if($valrequesttype=="edit"){



$valrequestid=$_POST['valrequestid'];

$FetchDateQry="SELECT DISTINCT entry_date,name FROM `tw_mixwaste_manual_entry` where id='".$valrequestid."'";
$FetchData=$sign->FunctionJSON($FetchDateQry);
$decodedJSON7 = json_decode($FetchData);
 $Fetchdate = $decodedJSON7->response[0]->entry_date;
 $Fetchname = $decodedJSON7->response[1]->name;

//$Fetchdate=$sign->SelectF($FetchDateQry,"entry_date");

$DetailsQry="SELECT quantity FROM `tw_mixwaste_manual_entry` where created_by='".$company_id."' and entry_date='".$Fetchdate."'";
$DetailsretVal = $sign->FunctionJSON($DetailsQry);
$decodedJSON3 = json_decode($DetailsretVal);

$qry="select swm.id,swm.name,me.quantity from tw_inward_waste_type_master swm INNER JOIN tw_mixwaste_manual_entry me ON me.waste_type=swm.id where swm.visibility='true' and me.entry_date='".$Fetchdate."'and me.name='".$Fetchname."' order by priority,id desc";
$retVal = $sign->FunctionJSON($qry);

$qry1="Select count(*) as cnt from tw_inward_waste_type_master where visibility='true'";
$retVal1 = $sign->Select($qry1);

$decodedJSON2 = json_decode($retVal);
$count = 0;
$i = 1;
$x=$retVal1;
$table="";
$data = array();
$table.="<div> 
				<div class='form-group '>
				<div class='row'>";
while($x>=$i){
	 $id = $decodedJSON2->response[$count]->id;
	$count=$count+1;
	$name = $decodedJSON2->response[$count]->name;
	$count=$count+1;
	$quantity = $decodedJSON2->response[$count]->quantity;
	$count=$count+1;
	
				$table.=   "<div class='col-lg-4 col-md-4 col-sm-4 col-xs-4 grid-margin'>
				  <label class='form-label'>".$name."</label>
					<input type='number' class='form-control' id='quantity".$i."' value='".$quantity."' />
				  </div>
				";
	$data[] = [
		'dataMaterialType' => $id,
		'dataquantity' => "quantity".$i,
	];
	
	$i=$i+1;
}



// $table.="<input type='hidden' id='quantity".$i."' value='".$id."'>";
$table.="</div>
			   </div>
		   	
	           </div>
	
	
	<button type='button' id='btnReply' class='btn btn-success' href='javascript:void(0)' onclick='addrecord();'>Save</button></tbody>";
//echo $table;

$responsearray=array();
array_push($responsearray, $table, $data);
echo json_encode($responsearray);
}

else{
	$qry="select id,name from tw_inward_waste_type_master where visibility='true' order by priority,id desc";
$retVal = $sign->FunctionJSON($qry);

$qry1="Select count(*) as cnt from tw_inward_waste_type_master where visibility='true'";
$retVal1 = $sign->Select($qry1);

$decodedJSON2 = json_decode($retVal);
$count = 0;
$i = 1;
$x=$retVal1;
$table="";
$data = array();
$table.="<div> 
				<div class='form-group '>
				<div class='row'>";
while($x>=$i){
	$id = $decodedJSON2->response[$count]->id;
	$count=$count+1;
	$name = $decodedJSON2->response[$count]->name;
	$count=$count+1;

	
				$table.=   "<div class='col-lg-4 col-md-4 col-sm-4 col-xs-4 grid-margin'>
				  <label class='form-label'>".$name."</label>
					<input type='number' class='form-control' id='quantity".$i."' value='0.00' />
				  </div>
				";
	$data[] = [
		'dataMaterialType' => $id,
		'dataquantity' => "quantity".$i,
	];
	
	$i=$i+1;
}

// $table.="<input type='hidden' id='quantity".$i."' value='".$id."'>";
$table.="</div>
			   </div>
		   	
	           </div>
	
	<div class='form-group row'>
	 
	</div>
	<button type='button' id='btnReply' class='btn btn-success' href='javascript:void(0)' onclick='addrecord();'>Save</button></tbody>";
//echo $table;

$responsearray=array();
array_push($responsearray, $table, $data);
echo json_encode($responsearray);
	
}

?>
	