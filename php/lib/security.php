<?php
class Security{
    public function GenerateSalt(){
        $length = 100;
        $salt = base64_encode(mcrypt_create_iv($length, MCRYPT_DEV_URANDOM));
        $salt = preg_replace("/[^A-Za-z0-9 ]/", '', $salt);
        $salt = substr($salt, 0, 64);
        return $salt;
    }

    public function HashPassword($password, $salt){
        $key = 'gieter10052015';
        $passwordSalt = $password . $salt;
        $hashedPassword = hash_hmac('sha256', $passwordSalt, $key);
        return $hashedPassword;
    }
}

?>