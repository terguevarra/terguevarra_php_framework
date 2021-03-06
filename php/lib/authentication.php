<?php
class Authentication{
    
    private $is_authentic;
    private $key = 'tergie10052015';
    
    /*check if token is authentic
    returns true or false*/
    public function isAuthentic(){
        $token = $this->GetToken();
        
        if(!$token){
            return false; //no token
        }else{
            return $this->is_authentic = $this->VerifyToken($token);
        }
    }
    
    /*generate token for login*/
    public function GenerateToken($username){

		$today = time();

		$exp = strtotime('+1 day', $today);

		$domain = $_SERVER['SERVER_NAME'];

		$header = array(
				"typ"=>"JWT",
				"alg"=>"HS256"
			);

		$payload =  array(
				"iss"=>$domain,
				"iat"=>$today,
				"exp"=>$exp,
				"username"=>$username
			);

		$header = base64_encode(json_encode($header));
		$payload = base64_encode(json_encode($payload));

		$encodedstring = $header . $payload;

		$signature = hash_hmac('sha256', $encodedstring, $this->key);

		$token = $header . "." . $payload . "." . $signature;

		return $token;
    }
    
    /*get token from http header*/
    private function GetToken(){
        $headers = getallheaders();
        foreach ($headers as $key => $value) {
            if($key == 'Authorization'){
                $token = $value;
            }
        }
        if(isset($token)){
            return $token;
        }else{
            return false;
        }
        
    }
    
    /*verify token*/
    private function VerifyToken($token){
        $token = explode(".", $token);

		$header = $token[0];
		$payload = $token[1];
		$signature = $token[2];

		$encodedstring = $header . $payload;

		$signature2 = hash_hmac('sha256', $encodedstring, $this->key);

		if($signature2 == $signature){
			$payloadObject = $this->ToObject($payload);
            if($this->CheckExpiration($payloadObject->exp)){
                return true; //token is valid
            }else{
                return false; //token is expired
            }
		}else{
			return false; //invalid token
		}
    }
    
    /*parse part of the token to object*/
    private function ToObject($load){
        return (object)json_decode(base64_decode($load));
    }
    
    /*check if token is expired*/
    private function CheckExpiration($date){
        $today = time();
        if($today > $date){
            return false;
        }else{
            return true;
        }
    }
    
}
?>