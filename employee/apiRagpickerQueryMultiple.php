<?php
// Include class definition
include_once("function.php");
$sign=new Signup();

$queriesJSON = file_get_contents('php://input');
$queries = json_decode($queriesJSON)->queries;
foreach ($queries as $query) {
	foreach ($query as $k => $v) {
		if($k == "valquery") $sign->FunctionQuery($v);
	}
}

echo "Success";
?>
