<?php 
// Include class definition
include_once("function.php");
session_start();
$inward_id=$_POST["inward_id"];
$company_id = $_SESSION["company_id"];          
$valrequesttype = $_POST["valrequesttype"];          
$request_id = $_POST["request_id"];

$qry="SELECT id,description,norms FROM tw_test_report_designation_master where company_id='".$company_id."' order by priority";
$sign=new Signup();
$retVal = $sign->FunctionJSON($qry);

$qry1="Select count(*) as cnt from tw_test_report_designation_master where company_id = '".$company_id."' ";
$retVal1 = $sign->Select($qry1);

$decodedJSON2 = json_decode($retVal);
$count = 0;
$i = 1;
$x=$retVal1;
$table="";
$it=1;
$descriptionEdit="";
$normsEdit="";
$firstEdit="";
$secondEdit="";
$thirdEdit="";
$totalEdit="";
$averageEdit="";


$table.="<thead><tr><th>#</th><th>Description</th><th>Norms</th><th>1st</th><th>2nd</th><th>3rd</th><th>Total</th><th>Average %</th></tr></thead><tbody>";
	while($x>=$i){
		
	$id = $decodedJSON2->response[$count]->id;
	$count=$count+1;
	$description = $decodedJSON2->response[$count]->description;
	$count=$count+1;
	$norms = $decodedJSON2->response[$count]->norms;
	$count=$count+1;
	
	$var1="";
	$var2="";
	$var3="";
	$var4="";
	$var5="";
	if($valrequesttype=="edit"){
		echo $editquery = "select first,second,third,total,average from tw_material_inward_qc_individual where description='".$id."' and material_inward_qc_id='".$request_id."'";
		
		$edit = $sign->FunctionJSON($editquery);
		$decodedJSON3 = json_decode($edit);
	
		
		if (isset($decodedJSON3->response[0]->first))
		{
			$var1 = $decodedJSON3->response[0]->first;
		}
		if (isset($decodedJSON3->response[1]->second))
		{
			$var2 = $decodedJSON3->response[1]->second;
		}
		if (isset($decodedJSON3->response[2]->third))
		{
			$var3 = $decodedJSON3->response[2]->third;
		}
		if (isset($decodedJSON3->response[3]->total))
		{
			$var4 = $decodedJSON3->response[3]->total;
		}
		if (isset($decodedJSON3->response[4]->average))
		{
			$var5 = $decodedJSON3->response[4]->average;
		}
	}

	$table.="<tr>";
 	$table.="<td>".$it."</td>"; 
	$table.="<td id='".$id."'>".$description."</td>";
	$table.="<td id='txtNorms".$id."'>".$norms."</td>";
	$table.="<td><input type='number' id='txtFirst".$id."' placeholder='1st'  class='form-control' onkeyup=work(".$id.") value='".$var1."'/></td>";
	
	$table.="<td><input type='number' id='txtSecond".$id."' placeholder='2nd'  class='form-control' onkeyup=work(".$id.")   value='".$var2."' /></td>";
	
	$table.="<td><input type='number' id='txtThird".$id."' placeholder='3rd'  class='form-control' onkeyup=work(".$id.") value='".$var3."' /></td>";
	
	$table.="<td><input type='number' id='txtTotal".$id."' disabled placeholder='Total'  class='form-control' value='".$var4."' /></td>";
	
	$table.="<td><input type='number' id='txtAverage".$id."' disabled placeholder='Average'  class='form-control' value='".$var5."' /></td>"; 
	
	
	$it++;
	$table.="</tr>";

	$i=$i+1;
}

	$table.="</tbody>";
	echo $table;

?>