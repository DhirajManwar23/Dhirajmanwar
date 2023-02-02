<?php
include_once "function.php";	
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();
$po_id=$_POST['po_id'];
 $responsearray=array();

echo $qry2 = "Select po.id,pm.product_name from tw_temp_po_details po INNER JOIN tw_product_management pm ON po.material_id=pm.id where po.po_id='".$po_id."'";
$retVal2 = $sign->FunctionJSON($qry2);
$qry3="Select count(*) as cnt from tw_temp_po_details  where po_id='".$po_id."'";
$retVal3 = $sign->Select($qry3);
	
$decodedJSON2 = json_decode($retVal2);
$count = 0;
$i1 = 1;
$x1=$retVal3;
while($x1>=$i1){
	$id = $decodedJSON2->response[$count]->id;
	$count=$count+1;
	$product_name = $decodedJSON2->response[$count]->product_name;
	$count=$count+1; 

	echo $option = "<option value='".$id."'>".$product_name."</option>";
		  array_push($responsearray,$option);
		 		$i1=$i1+1;


}
		
 echo json_encode($responsearray); 

?>