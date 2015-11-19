<?php

require('../config/mysql_db.php');
require('../lib/security.php');

class User{
    
    private $db;
    
    public function __construct(){
        $this->db = new MySQL();
    }
    
    public function Login(){
        
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

                $query = "INSERT INTO users (username, password, salt, is_deleted) VALUES (?,?,?,0)";

                if($stmt = $conn->prepare($query)){
                    $stmt->bind_param("sss", $model->username, $hashedpassword, $salt);
                    $stmt->execute();
                    $conn->commit();
                    $stmt->close();
                    $conn->close();
                    return "Success";
                }else{
                    $conn->close();
                    return $conn->error;
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
        
        $query = "SELECT username FROM users WHERE username = ? AND is_deleted = 0";
        
        if($stmt = $conn->prepare($query)){
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();
            if($stmt->num_rows >= 1){
                return true;
            }else{
                return false;
            }
        }
    }
}

?>