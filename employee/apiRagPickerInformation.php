<?php
// Include class definition
include_once "function.php";
$sign=new Signup();
$qry="select id,case_id, DATE_FORMAT(createdOn,'%d-%m-%Y') AS createdOn,scheme_doc,citizen_guid,citizen_name,DATE_FORMAT(dob,'%d-%m-%Y')dob,gender,mobile,age,family_guid,family_name,hd_id,Location from tw_ragpicker_information order by id asc;";
$format = "HTML";
$tableName ="tw_ragpicker_information";
$retVal = $sign->FunctionDataTable($qry,$format,$tableName,true,true);
echo $retVal;



?>	


