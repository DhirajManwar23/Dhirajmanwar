<?php
	session_start();
	// Include class definition
	include_once "function.php";
	$sign=new Signup();
	$company_id = $_SESSION["company_id"];
	
	$qry="SELECT pm.id,pm.product_name,cm.category_name,ptm.name FROM tw_product_management pm INNER JOIN tw_category_master cm ON pm.category = cm.id INNER JOIN tw_product_type_master ptm
    ON pm.product_type = ptm.ID where pm.company_id='".$company_id."' order by pm.id Desc";
	
	$retVal = $sign->FunctionJSON($qry);
	
	$qry1="Select count(*) as cnt FROM tw_product_management pm INNER JOIN tw_category_master cm ON pm.category = cm.id INNER JOIN tw_product_type_master ptm
    ON pm.product_type = ptm.ID where pm.company_id='".$company_id."'";
	$retVal1 = $sign->Select($qry1);

	$decodedJSON2 = json_decode($retVal);
	$count = 0;
	$i = 1;
	$x=$retVal1;
	$table="";
	$it=1;
	$table.="<thead><tr><th>#</th><th>Product Name</th><th>Category Name</th><th>Material Type</th><th>Edit</th><th>Delete</th></tr></thead><tbody>";
	
	while($x>=$i){
		$id = $decodedJSON2->response[$count]->id;
		$count=$count+1;
		$product_name = $decodedJSON2->response[$count]->product_name;
		$count=$count+1;
		$category_name  = $decodedJSON2->response[$count]->category_name ;
		$count=$count+1;
		$name = $decodedJSON2->response[$count]->name;
		$count=$count+1;
	
		$table.="<tr>";
		$table.="<td>".$it."</td>"; 
		$table.="<td>".$product_name."</td>";
		$table.="<td>".$category_name."</td>";
		$table.="<td>".$name."</td>";
		
		$table.="<td><a href='javascript:void(0)' onclick='editRecord(".$id.")'>Edit</a></td>";
		$table.="<td><a href='javascript:void(0)' onclick='deleteRecord(".$id.")'>Delete</a></td>";
		$it++;
		$table.="</tr>";
		

	$i=$i+1;
}
	$table.="</tbody>";
	echo $table;
?>
	




