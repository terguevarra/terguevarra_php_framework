<?php

require('../config/mysql_db.php');
require('../lib/security.php');

class Account{
    
    private $db;
    
    public function __construct(){
        $this->db = new MySQL();
    }
    
    public function Login($model){
        if(property_exists($model, 'username') && property_exists($model, 'password')){
            $conn = $this->db->Connect();
            $auth = new Authentication();
            
            $query = "SELECT username, password, salt FROM users WHERE username = :username AND is_deleted = 0";
            
            try{
                $stmt = $conn->prepare($query);
                $params = array(
                    ":username"=>$model->username
                );
                $stmt->execute($params);

                if($stmt->rowCount() == 1){
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $result = (object)$result[0];

                    if($this->VerifyPassword($model->password, $result->password, $result->salt)){
                        return $auth->GenerateToken($result->username);
                    }else{
                        return "Invalid Password";
                    }
                }else{
                    return "Invalid Username";
                }
            }catch(PDOException $e){
                return "General Error";
            }
            
        }else{
            return "Invalid Parameters";
        }
    }
    
    public function Register($model){
        if(property_exists($model, 'username') && property_exists($model, 'password')){
            $conn = $this->db->Connect();
            
            if(!$this->CheckDuplicate($model->username)){
                //generate salt
                $security = new Security();
                $salt = $security->GenerateSalt();

                //generate hashedpassword
                $hashedpassword = $security->HashPassword($model->password, $salt);

                $query = "INSERT INTO users (username, password, salt, is_deleted) VALUES (:username, :password, :salt, 0)";
                
                try{
                    $stmt = $conn->prepare($query);
                    $params = array(
                        ":username"=>$model->username,
                        ":password"=>$hashedpassword,
                        ":salt"=>$salt
                    );

                    $stmt->execute($params);
                    return "Success";
                }catch(PDOException $e){
                    return "General Error";
                }
                
                
            }else{
               return "Username Taken"; 
            }     
        }else{
            return "Invalid Parameters";
        }
    }
    
    public function ChangePassword(){
        
    }
    
    public function ChangeUsername(){
        
    }
    
    private function CheckDuplicate($username){
        $conn = $this->db->Connect();
        
        $query = "SELECT username FROM users WHERE username = :username AND is_deleted = 0";
        
        try{
            $stmt = $conn->prepare($query);
            $params = array(
                ":username"=>$username
            );
            $stmt->execute($params);
            
            if($stmt->rowCount() >= 1){
                return true;
            }else{
                return false;
            }
        }catch(PDOException $e){
            return false;
        }
    }
    
    private function VerifyPassword($password, $hashedpassword, $salt){
        $security = new Security();
        $hashedpassword2 = $security->HashPassword($password, $salt);
        
        if($hashedpassword == $hashedpassword2){
            return true;
        }else{
            return false;
        }
    }
}

?>