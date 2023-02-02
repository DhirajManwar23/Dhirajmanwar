<?php
class dbConnect{
    private $host="localhost";
    private $user="ecoviwhj_leaderexim_user";
    private $pass="LeaderExim@2022";
    private $db="ecoviwhj_leaderexim";
    public function Connect(){
        $conn=mysqli_connect($this->host, $this->user, $this->pass, $this->db);
		
        if($conn){
            //echo "Connected";
        }
		else{
			echo "error";
		}
        return $conn;
    }
	
} 
?>