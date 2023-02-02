<?php
class Signup{
	private $conn;
	function __construct()
	{
		include_once "DB_Connect.php";	
		$dbConn=new dbConnect();
		$this->conn = $dbConn->Connect();
		
	}
    public function Select($value){
  
            //FOLLING STATEMENT IS NOW CORRECT
            $queryrun=mysqli_query($this->conn, $value);
			$result=mysqli_fetch_array($queryrun);
			return $result["cnt"];
			
    }
	public function SelectF($query,$value1){
            //FOLLING STATEMENT IS NOW CORRECT
			//echo $query;
            $queryrun=mysqli_query($this->conn, $query);
			$result=mysqli_fetch_array($queryrun);
			$cntRows = mysqli_num_rows($queryrun);
			if ($cntRows>0)
			{
				return $result[$value1];
			}
			else
			{
				return "";
			}
    }
	public function FunctionQuery($query,$return_id = false){
  
            //FOLLING STATEMENT IS NOW CORRECT
			if($runi=mysqli_query($this->conn,$query)){
				$id = $this->conn->insert_id;
				
				if($return_id==true){
					return $id;
				}
				else{
					return "Success";
				}
				
			}
			else{
				   return "error";
			}
			
    }
	
	public function escapeString($value)
	{
		$value=strip_tags(trim($value));
		return mysqli_real_escape_string($this->conn,$value);
	}		
	 public function FunctionDataTable($query,$type,$tableName,$editFlag,$deleteFlag)
	{
		$queryrun=mysqli_query($this->conn, $query);
		$i=0;
		$table="";
		$table.="<thead><tr><th>#</th>";
		// Get field information for all fields
		while ($fieldinfo = mysqli_fetch_field($queryrun)) {
			$fieldName = $fieldinfo->name;
			$table.="<th>".$this->getColumnComment($fieldName,$tableName)."</th>"; 
		}
		if ($editFlag)
		{
			$table.="<th>Edit</th>"; 
		}
		if ($deleteFlag)
		{
			$table.="<th>Delete</th>"; 
		}
		$table.="</tr></thead><tbody>";
		$columnCount = mysqli_num_fields($queryrun);
		//Fetch values from Table
		while($result=mysqli_fetch_array($queryrun)){
			$i=$i+1;
			$table.="<tr>";
			$table.="<td>".$i."</td>";
			$id="";
			for ($x = 0; $x < $columnCount; $x++) {
				if ($x==0)
				{
					$id = $result[$x];
					$table.="<td id='ID".$id."'>".$id."</td>";
				}
				else
				{
					$table.="<td>".$result[$x]."</td>";
				}
				
			}
			if ($editFlag)
			{
				$table.="<td><a href='javascript:void(0)' onclick='editRecord(".$id.");'><i class='ti-pencil'></i></a></td>";
			}
			if ($deleteFlag)
			{
				$table.="<td><a href='javascript:void(0)' onclick='deleteRecord(".$id.");'><i class='ti-trash'></i></a></td>";
			}
			$table.="</tr>";
		}
		
		if($i==0)
		{
			$table.="<tr><td colspan=".($columnCount+1).">No record found</td></tr>";
		}
		$table.="</tbody>";
		return $table;
	}
	
	public function getColumnComment($columnName, $tableName)
	{
		$colComment="";
		$qry = "SELECT a.COLUMN_COMMENT as Comment FROM information_schema.COLUMNS a WHERE a.TABLE_NAME='".$tableName."' and a.COLUMN_NAME='".$columnName."'";
		$queryrun=mysqli_query($this->conn, $qry);
		while($result=mysqli_fetch_array($queryrun))
		{
			$colComment=$result["Comment"];
		}
		return $colComment;
	}
	 public function FunctionJSON($value){
			// array for JSON response
			$response = array();
            $queryrun=mysqli_query($this->conn, $value);
			$cntRows = mysqli_num_rows($queryrun);
			if ($cntRows>0)
			{
				while($row = mysqli_fetch_assoc($queryrun)){
					foreach($row as $column => $value) {
						if($value==null){
							$value="";
						}
						array_push($response,array($column=>$value));
					}
				}
			}
			else
			{
				$value="";
				for($i = 0; $i < mysqli_num_fields($queryrun); $i++) {
					$field_info = mysqli_fetch_field($queryrun);
					$col = "{$field_info->name}";
					array_push($response,array($col=>$value));
				}
			}
			return json_encode(array('response'=>$response));
			
    } 

	public function FunctionOption($qry,$Status,$field,$id){
			// array for JSON response
			$option="";
			$valSel="";
            //FOLLING STATEMENT IS NOW CORRECT
			$queryrun=mysqli_query($this->conn, $qry);
			while($result=mysqli_fetch_array($queryrun)){
				//echo $Status;
				//echo $result[$id];
				if($Status==$result[$id]){
					$valSel="selected";
				}
				else{
					$valSel="";
				}
				$option.="<option value='".$result[$id]."' ".$valSel." >".$result[$field]." </option>";
			}
			return $option;
			
    }
	
	public function FunctionData($value){
			// array for JSON response
			$response = array();
            $queryrun=mysqli_query($this->conn, $value);
			$cntRows = mysqli_num_rows($queryrun);
			if ($cntRows>0)
			{
				while($row = mysqli_fetch_assoc($queryrun)){
					$record = array();
					foreach($row as $column => $value) {
						if($value==null){
							$value="";
						}
						$record[$column]=$value;
					}
					array_push($response,$record);
				}
			}
			
			return $response;
			
    } 
}


?>