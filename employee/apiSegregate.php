<?php
session_start();
// Include class definition
include_once "function.php";
$sign=new Signup();

$qry="select id,name from tw_segregation_waste_type_master where visibility='true' order by priority,id desc";
$retVal = $sign->FunctionJSON($qry);

$qry1="Select count(*) as cnt from tw_segregation_waste_type_master where visibility='true'";
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
	  <label class='col-sm-3 col-form-label'>Comment</label>
	  <div class='col-sm-9'>
		<textarea class='form-control' id='txtComment' maxlength='5000'  placeholder='Comment'></textarea>
	  </div>
	</div>
	<button type='button' id='btnReply' class='btn btn-success' href='javascript:void(0)' onclick='addrecord();'>Save</button></tbody>";
//echo $table;

$responsearray=array();
array_push($responsearray, $table, $data);
echo json_encode($responsearray);


?>
	