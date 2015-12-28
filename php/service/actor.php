<?php

require('../config/mysql_db.php');

class Actor{
    
    private $db;
    
    public function __construct(){
        $this->db = new MySQL();
    }
    
    public function Get(){
        $conn = $this->db->Connect();
        
        $query = "SELECT actor_id, first_name, last_name FROM actor ORDER BY last_update DESC";
        
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if(sizeof($result) > 0){
            foreach($result as $row){
                $fetchdata = (object)$row;
                
                $returndata[] = array(
                    "id"=>$fetchdata->actor_id,
                    "firstname"=>$fetchdata->first_name,
                    "lastname"=>$fetchdata->last_name
                );
            }
            return json_encode($returndata);
        }else{
            return "null";
        }
    }
    
    public function Insert($model){
        if(property_exists($model, 'firstname') && property_exists($model, 'lastname')){
            $conn = $this->db->Connect();
        
            $query = "INSERT INTO actor (first_name, last_name) VALUES (:firstname, :lastname)";
            
            try{
                $stmt = $conn->prepare($query);
                $params = array(
                    ":firstname"=>$model->firstname,
                    ":lastname"=>$model->lastname
                );
                
                $stmt->execute($params);
                return "Success";
            }catch(PDOExceptio $e){
                return $e->getMessage();
            }
        }else{
            return "Invalid Parameters";
        }
        
    }
    
    public function Update($model){
        if(property_exists($model, 'firstname') && property_exists($model, 'lastname') && property_exists($model, 'id')){
            $conn = $this->db->Connect();
        
            $query = "UPDATE actor SET first_name=:firstname, last_name=:lastname WHERE actor_id=:id";
            
            try{
                $stmt = $conn->prepare($query);
                $params = array(
                    ":firstname"=>$model->firstname,
                    ":lastname"=>$model->lastname,
                    ":id"=>$model->id
                );
                
                $stmt->execute($params);
                return "Success";
            }catch(PDOExceptio $e){
                return $e->getMessage();
            }
        }else{
            return "Invalid Parameters";
        }
    }
    
    public function Delete($model){
        if(property_exists($model, 'id')){
            $conn = $this->db->Connect();
        
            $query = "DELETE FROM actor WHERE actor_id=:id";

            try{
                $stmt = $conn->prepare($query);
                $params = array(
                    ":id"=>$model->id
                );
                
                $stmt->execute($params);
                return "Success";
            }catch(PDOExceptio $e){
                return $e->getMessage();
            }
        }else{
            return "Invalid Parameters";
        }
        
    }
}

?>