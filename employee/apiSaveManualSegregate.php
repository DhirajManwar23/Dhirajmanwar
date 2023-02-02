<?php
session_start();
include("function.php");
include("commonFunctions.php");
include("mailFunction.php");

$commonfunction=new Common();
$sign=new Signup();
$materialtype=$_POST['materialtype'];
$quantityvalue=$_POST['quantityvalue'];
$valmix_waste_lot_id=$_POST['valmix_waste_lot_id'];
$EntryDate=$_POST['EntryDate'];
$Name=$_POST['Name'];
$valrequesttype=$_POST['valrequesttype'];
$valrequestid=$_POST['valrequestid'];
$Ward=$_POST['Ward'];
$settingValuePendingStatus= $commonfunction->getSettingValue("Pending Status");
$settingValueCompletedStatus= $commonfunction->getSettingValue("Completed Status");
date_default_timezone_set("Asia/Kolkata");
$date=date("Y-m-d h:i:sa");
$ip_address= $commonfunction->getIPAddress();
$company_id = $_SESSION["company_id"];

$FetchDateQry="SELECT DISTINCT entry_date FROM tw_mixwaste_manual_entry where entry_date='".$EntryDate."' ";
$Fetchdate=$sign->SelectF($FetchDateQry,'entry_date');
$datecheckqry="SELECT COUNT(*) as cnt FROM tw_mixwaste_manual_entry where entry_date='".$Fetchdate."' and name='".$Name."'";
$datecheck = $sign->Select($datecheckqry);
 
 
if($valrequesttype=="add"  ){
	if($datecheck>1){
      echo "error";
	}
	else{

	$i = 0;
	$x=count($_POST['materialtype']);
	$x = $x-1;
	$valquery = "";
	$valTotalquantity = 0;


	
		while($x>=$i){
		  
			    $qry3="Insert into tw_mixwaste_manual_entry(mix_waste_lot_id,entry_date,waste_type,quantity,status,name,ward,created_by,created_on,created_ip) values ('".$valmix_waste_lot_id."','".$EntryDate."','".$materialtype[$i]."','".$quantityvalue[$i]."','".$settingValuePendingStatus."','".$Name."','".$Ward."','".$company_id."','".$date."','".$ip_address."')";
							$retVal3 = $sign->FunctionQuery($qry3); 
						if($retVal3=="Success"){
							$valquery = "Success";
						}
						else{
							$valquery = "error";
						}
						
						$i=$i+1;
			  
		   
	            }
				echo $valquery;		
	        
	  }
}	
		   else{
			   
			    $i = 0;
				$x=count($_POST['materialtype']);
				$x = $x-1;
				$valquery = "";
				$valTotalquantity = 0;
			   while($x>=$i){
				  $qry3="Update tw_mixwaste_manual_entry set entry_date='".$EntryDate."',waste_type='".$materialtype[$i]."',quantity='".$quantityvalue[$i]."',status='".$settingValuePendingStatus."',modified_by='".$company_id."',modified_on='".$date."',modified_ip='".$ip_address."'where waste_type='".$materialtype[$i]."' and entry_date='".$Fetchdate."' ";
					$retVal3 = $sign->FunctionQuery($qry3); 
					if($retVal3=="Success"){
						$valquery = "Success";
					}
					else{
						$valquery = "error";
					}
					
					 $i=$i+1;	 

			  		
			   }
               echo $valquery;			   
			 }
		
		
	
	

?>
