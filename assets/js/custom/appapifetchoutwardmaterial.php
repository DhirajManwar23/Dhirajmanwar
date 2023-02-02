<?php 
	// array for JSON response
	$response = array();
	
	if (isset($_REQUEST['vendor_id']) && isset($_REQUEST['status'])){
		
		$vendor_id = $_REQUEST["vendor_id"];
		$status = $_REQUEST["status"];
		// Include class definition
		require "function.php";
		$sign=new Signup();
		if($status==""){
			$query="SELECT id,rp_id,geo_coordinate,image,quantity,status,created_on FROM tw_collect_material WHERE vendor_id = '".$vendor_id."'";	
			$qry3="SELECT count(*) as cnt FROM tw_collect_material WHERE vendor_id = '".$vendor_id."'";
		}
		else{
			$query="SELECT id,rp_id,geo_coordinate,image,quantity,status,created_on FROM tw_collect_material WHERE vendor_id = '".$vendor_id."' and status='".$status."'";		
			$qry3="SELECT count(*) as cnt FROM tw_collect_material WHERE vendor_id = '".$vendor_id."' and status='".$status."'";
		}
		$retVal2 = $sign->FunctionJSON($query);
		$decodedJSON2 = json_decode($retVal2);
		$retVal3 = $sign->Select($qry3);
								
			$count = 0;
			$i = 1;
			$x=$retVal3;
			while($x>=$i){
					
					$id = $decodedJSON2->response[$count]->id;
					$count=$count+1;
					$rp_id = $decodedJSON2->response[$count]->rp_id;
					$count=$count+1;
					$geo_coordinate = $decodedJSON2->response[$count]->geo_coordinate;
					$count=$count+1;
					$image = $decodedJSON2->response[$count]->image;
					$count=$count+1;
					$quantity = $decodedJSON2->response[$count]->quantity;
					$count=$count+1;
					$status = $decodedJSON2->response[$count]->status;
					$count=$count+1;
					$created_on = $decodedJSON2->response[$count]->created_on;
					$count=$count+1;
					
					
					 $qry4="SELECT name FROM tw_ragpicker_registration where id='".$rp_id."'";
					 $name= $sign->SelectF($qry4,"name");
					
				array_push($response,array('id'=>$id,'rp_id'=>$name,'geo_coordinate'=>$geo_coordinate,
				'image'=>$image,'quantity'=>$quantity,'status'=>$status,'created_on'=>$created_on));
					$i=$i+1;
		}
		echo json_encode(array('response'=>$response));
	}
	else{
		// required field is missing
		$response["success"] = 0;
		$response["message"] = "Required field is missing.";
		echo json_encode($response);
	}
	
	
	


?>