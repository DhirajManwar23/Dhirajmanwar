<?php
// Include class definition
include_once "function.php";
include_once "commonFunctions.php";
$sign=new Signup();
$commonfunction=new Common();
session_start();
$company_id = $_SESSION["company_id"]; 
$txtsuppliername=$_POST["txtsuppliername"];
$txtDate=$_POST["txtDate"];
$txtremark=$_POST["txtremark"];
$inward_id = $_REQUEST["inward_id"];
$hdnOrderID=$_POST["hdnOrderID"];
$employee_id=$_SESSION["employee_id"];


$ip_address= $commonfunction->getIPAddress();
date_default_timezone_set("Asia/Kolkata");
$cur_date=date("Y-m-d h:i:sa");
/* $qry="insert into tw_material_inward_grn_temp(employee_id,description,quantity,rate,uom,tax,total,created_by,created_on,created_ip) values('".$employee_id."','".$txtMaterialName."','".$txtQuantity."','".$txtRate."','".$txtUOM."','".$txtTax."','".$txtTotal."','".$employee_id."','".$cur_date."','".$ip_address."')"; */
/* $qry="Select count(*) as cnt from tw_material_inward_grn_temp where employee_id = '".$employee_id."'";
$retVal = $sign->Select($qry);

if($retVal==0){
	echo "Blank";
}
else{ */
	$qry1="Delete from tw_material_inward_grn_individual where material_inward_grn_id='".$hdnOrderID."' ";
	$retVal1 = $sign->FunctionQuery($qry1);
	if($retVal1=="Success"){
		$qry2="Update tw_material_inward_grn set supplier_id='".$txtsuppliername."',date='".$txtDate."',remark='".$txtremark."' where id='".$hdnOrderID."'";
		$retVal2 = $sign->FunctionQuery($qry2);
		if($retVal2=="Success"){
		$query="Select id,description,quantity,rate,tax,uom,total from tw_material_inward_grn_temp where employee_id = '".$employee_id."' ORDER BY id DESC";
		$retVal4 = $sign->FunctionJSON($query);
		
		$qry3="Select count(*) as cnt from tw_material_inward_grn_temp where employee_id = '".$employee_id."' ORDER BY id DESC";
		$retVal3 = $sign->Select($qry3);

		$decodedJSON2 = json_decode($retVal4);
		$count = 0;
		$i = 1;
		$x=$retVal3;
		$table="";
		$it=1;
		$valtotalamt=0;
		$table.="<thead><tr><th>#SR.NO</th><th>Description</th><th>Quantity</th><th>Rate</th><th>Tax</th><th>UOM</th><th>Total</th><th>Remove</th></tr></thead><tbody>";
			while($x>=$i){
			
			
			$id = $decodedJSON2->response[$count]->id;
			$count=$count+1;
			$description = $decodedJSON2->response[$count]->description;
			$count=$count+1;
			$quantity = $decodedJSON2->response[$count]->quantity;
			$count=$count+1;
			$rate = $decodedJSON2->response[$count]->rate;
			$count=$count+1;
			$tax = $decodedJSON2->response[$count]->tax;
			$count=$count+1;
			$uom = $decodedJSON2->response[$count]->uom;
			$count=$count+1;
			$total = $decodedJSON2->response[$count]->total;
			$count=$count+1;
			
			
			$qry5="Insert into tw_material_inward_grn_individual(material_inward_grn_id,employee_id,description,quantity,rate,tax,uom,total,created_by,created_on,created_ip) values('".$hdnOrderID."','".$employee_id."','".$description."','".$quantity."','".$rate."','".$tax."','".$uom."','".$total."','".$employee_id."','".$cur_date."','".$ip_address."')" ;
			$retVal5 = $sign->FunctionQuery($qry5);
			
			$i=$i+1;
			}
			$qry6="delete from tw_material_inward_grn_temp where employee_id = '".$employee_id."'";
			$retVal6 = $sign->FunctionQuery($qry6);

			if($retVal6=="Success"){
				echo "Success";
			}
			else{
				echo "error";
			}

			}
			else{
				echo "error";
			}
	}
	else{
		echo "error";
	}
//}

?>