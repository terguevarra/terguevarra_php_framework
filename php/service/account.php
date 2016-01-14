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
    
    public function ChangePassword($model){
        if(property_exists($model, 'id') && property_exists($model, 'password')){
            $conn = $this->db->Connect();
            
            $security = new Security();
            
            //generate salt
            $salt = $security->GenerateSalt();
            
            //generate hashedpassword
            $hashedpassword = $security->HashPassword($model->password, $salt);
            
            $query = "UPDATE users SET password = :password, salt = :salt WHERE id = :id";
            
            try{
                $stmt = $conn->prepare($query);
                $params = array(
                    ":password"=>$hashedpassword,
                    ":salt"=>$salt,
                    ":id"=>$model->id
                );
                $stmt->execute($params);
                $response = "Success";
            }catch(PDOException $e){
                $response = "General Error";
            }
            
        }else{
            $response = "Invalid Parameters";
        }
        
        return $response;
    }
    
     public function Delete($model){
        if(property_exists($model, 'id')){
            $conn = $this->db->Connect();
            
            $query = "UPDATE users SET is_deleted = 1 WHERE id = :id";
            
            try{
                $stmt = $conn->prepare($query);
                $params = array(
                    ":id"=>$model->id
                );
                $stmt->execute($params);
                $response = "Success";
            }catch(PDOException $e){
                $response = "General Error";
            }
            
        }else{
            $response = "Invalid Parameters";
        }
        return $response;
    }
    
    public function RestoreUser($model){
        if(property_exists($model, 'id')){
            $conn = $this->db->Connect();
            
            $query = "UPDATE users SET is_deleted = 0 WHERE id = :id";
            
            try{
                $stmt = $conn->prepare($query);
                $params = array(
                    ":id"=>$model->id
                );
                $stmt->execute($params);
                $response = "Success";
            }catch(PDOException $e){
                $response = "General Error";
            }
        }else{
            $response = "Invalid Parameters";
        }
        return $response;
    }
    
    
    public function ChangeUsername($model){
        if(property_exists($model, 'id') && property_exists($model, 'username')){
            $conn = $this->db->Connect();
            if(!$this->CheckDuplicate($model->username)){
                $query = "UPDATE users SET username = :username WHERE id = :id";
                try{
                    $stmt = $conn->prepare($query);
                    $params = array(
                        ":id"=>$model->id,
                        ":username"=>$model->username
                    );
                    $stmt->execute($params);
                    $response = "Success";
                }catch(PDOException $e){
                    $response = "General Error";
                }
            }else{
                $response = "Username Taken"; 
            }
            
        }else{
            $response = "Invalid Parameters";
        }
        return $response;
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
