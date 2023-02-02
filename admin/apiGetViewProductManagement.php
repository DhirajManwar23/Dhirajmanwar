<?php
	session_start();
	// Include class definition
	include_once "function.php";
	$sign=new Signup();
	
	$qry="select id,product_name,product_type,public_visible from tw_product_management order by id asc";
	$retVal = $sign->FunctionJSON($qry);
	
	$qry1="Select count(*) as cnt from tw_product_management";
	$retVal1 = $sign->Select($qry1);

	$decodedJSON2 = json_decode($retVal);
	$count = 0;
	$i = 1;
	$x=$retVal1;
	$table="";
	$it=1;
	$table.="<thead><tr><th>#</th><th>Product Name</th><th>Material Type</th><th>Visibility</th><th>Edit</th><th>Delete</th></tr></thead><tbody>";
	
	while($x>=$i){
		$id = $decodedJSON2->response[$count]->id;
		$count=$count+1;
		
		$product_name = $decodedJSON2->response[$count]->product_name;
		$count=$count+1;
		$product_type = $decodedJSON2->response[$count]->product_type;
		$count=$count+1;
		$public_visible = $decodedJSON2->response[$count]->public_visible;
		$count=$count+1;
		
		$qry3="select name from tw_product_type_master where id='".$product_type."' ";
		$product_type= $sign->SelectF($qry3,"name");
	
		$table.="<tr>";
		$table.="<td>".$it."</td>"; 
		$table.="<td>".$product_name."</td>";
		$table.="<td>".$product_type."</td>";
		$table.="<td>".$public_visible."</td>";
		
		$table.="<td><a href='javascript:void(0)' onclick='editRecord(".$id.")'>Edit</a></td>";
		$table.="<td><a href='javascript:void(0)' onclick='deleteRecord(".$id.")'>Delete</a></td>";
		$it++;
		$table.="</tr>";
		

	$i=$i+1;
}
	$table.="</tbody>";
	echo $table;
?>
