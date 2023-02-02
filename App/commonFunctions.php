<?php
class Common {
	
	public function CommonEnc($pass){
    $cipKey="AES-256-CBC";
    $eKey = "Dhiraj";
    $options = 0;
    $eIV = '1234567891012345';
    $varEnc = openssl_encrypt($pass, $cipKey, $eKey, $options, $eIV);
    return $varEnc; 

    }
    // return $encryption;
    public function CommonDec($pass){
    
        $dIv = '1234567891012345';
        $dKey = "Dhiraj";
        $cipKey="AES-256-CBC";
        $options=0;
        $varDec = openssl_decrypt($pass, $cipKey, $dKey, $options, $dIv);
        return $varDec;
    }
	public function getIPAddress(){
		
		if (!empty($_SERVER['HTTP_CLIENT_IP'])){
			$ip_address = $_SERVER['HTTP_CLIENT_IP'];
		}
		//whether ip is from proxy
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))  {
			$ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		//whether ip is from remote address
		else{
			$ip_address = $_SERVER['REMOTE_ADDR'];
		}
		date_default_timezone_set("Asia/Kolkata");
		$date=date("Y-m-d h:i:sa");
		
		if (!empty($_SERVER['HTTP_CLIENT_IP'])){
			$ip_address = $_SERVER['HTTP_CLIENT_IP'];
		}
		//whether ip is from proxy
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		//whether ip is from remote address
		else{
			$ip_address = $_SERVER['REMOTE_ADDR'];
		}
		return $ip_address;
 }

}
?>