<?php
session_start();
if(!isset($_SESSION["employee_id"])){
	header("Location:pgEmployeeLogIn.php");
}
include_once "function.php";
include_once "commonFunctions.php";	
$sign=new Signup();
$commonfunction=new Common();

$employee_id=$_SESSION["employee_id"];
$startDate=$_POST["startdate"];
$endDate=$_POST["enddate"];

$settingValueEmployeeImagePathOther=$commonfunction->getSettingValue("EmployeeImagePathOther");
$settingValueNodatafoundImage=$commonfunction->getSettingValue("NodatafoundImage");


$qry="SELECT employee_id,cp_id,agent_id,geo_location,drop_geo_location,quantity,collection_date_time FROM `tw_mix_waste_collection` where collection_date_time BETWEEN '".$startDate."' AND '".$endDate."' and employee_id='".$employee_id."'";
$qry1="SELECT COUNT(*) as cnt FROM `tw_mix_waste_collection` where  collection_date_time BETWEEN '".$startDate."' AND '".$endDate."' and employee_id='".$employee_id."'";

$retVal = $sign->FunctionJSON($qry);
$qryCnt = $sign->Select($qry1);

$decodedJSON2 = json_decode($retVal);

$decodedJSON2 = json_decode($retVal);
$count = 0;
$i = 1;
$x=$qryCnt;

$table="";

$it=1;

if($qryCnt==0 ){

		
	
	
}
else{
	$table.="<thead><tr><th>#</th><th>Collection Date</th><th>Collection Point Name</th><th>Location</th><th>Agent Name</th><th>Quantity</th></thead><tbody>";
	while($x>=$i){
      $employee_id = $decodedJSON2->response[$count]->employee_id;
		$count=$count+1;
		$cp_id = $decodedJSON2->response[$count]->cp_id;
		$count=$count+1;
		$agent_id = $decodedJSON2->response[$count]->agent_id;
		$count=$count+1;
		$geo_location = $decodedJSON2->response[$count]->geo_location;
		$count=$count+1;
		$drop_geo_location = $decodedJSON2->response[$count]->drop_geo_location;
		$count=$count+1;
		$quantity = $decodedJSON2->response[$count]->quantity;
		$count=$count+1;
		$collection_date_time = $decodedJSON2->response[$count]->collection_date_time;
		$count=$count+1;

        $qry2="Select collection_point_name,collection_point_logo,mobile_number, location from tw_collection_point_master where id='".$cp_id."'";
		$retVal = $sign->FunctionJSON($qry2);
		$decodedJSON = json_decode($retVal);
		$collection_point_name = $decodedJSON->response[0]->collection_point_name;	
		$collection_point_logo = $decodedJSON->response[1]->collection_point_logo;	
		$mobile_number = $decodedJSON->response[2]->mobile_number;	
		$colllocation = $decodedJSON->response[3]->location;
		
		
		$qry3="Select agent_name,agent_photo,mobilenumber, location from tw_agent_details where id='".$agent_id."'";
		$retVal1 = $sign->FunctionJSON($qry3);
		$decodedJSON1= json_decode($retVal1);
		$agent_name = $decodedJSON1->response[0]->agent_name;	
		$agent_photo = $decodedJSON1->response[1]->agent_photo;	
		$mobilenumber = $decodedJSON1->response[2]->mobilenumber;	
		$agtlocation = $decodedJSON1->response[3]->location;	
   
		$table.="<tr>";
												$table.="<td class='center-text'>".$it."</td>
												<td class='center-text'>".date("d-m-Y",strtotime($collection_date_time))." </td>
												<td class='center-text'>".$collection_point_name."</td>
												<td class='center-text'>".$colllocation."</td>
												<td class='center-text' >".$agent_name."</td>
												<td class='right-text'>".$quantity."</td>";
												
												$table.="</tr> 
												
				      ";
   
   
       	$it++;
    	$i=$i+1;
	}
	$table.="<tr><button type='button' id='btnAddrecord'  class='btn btn-success' onclick='genrateReport()'>Genrate Report</button> </tr> ";
  	
	echo $table;
}
?>