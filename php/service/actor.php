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

        $result = $conn->query($query);

        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                $fetchdata = (object)$row;

                $returndata[] = array(
                    "id"=>$fetchdata->actor_id,
                    "firstname"=>$fetchdata->first_name,
                    "lastname"=>$fetchdata->last_name
                );
            }
            $conn->close();
            return json_encode($returndata);
        }
    }
    
    public function Insert($model){
        if(property_exists($model, 'firstname') && property_exists($model, 'lastname')){
            $conn = $this->db->Connect();
        
            $query = "INSERT INTO actor (first_name, last_name) VALUES (?, ?)";

            if($stmt = $conn->prepare($query)){
                $stmt->bind_param("ss", $model->firstname, $model->lastname);
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
            return "Invalid Parameters";
        }
        
    }
    
    public function Update($model){
        if(property_exists($model, 'firstname') && property_exists($model, 'lastname') && property_exists($model, 'id')){
            $conn = $this->db->Connect();
        
            $query = "UPDATE actor SET first_name=?, last_name=? WHERE actor_id=?";

            if($stmt = $conn->prepare($query)){
                $stmt->bind_param("ssi", $model->firstname, $model->lastname, $model->id);

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
            return "Invalid Parameters";
        }
    }
    
    public function Delete($model){
        if(property_exists($model, 'id')){
            $conn = $this->db->Connect();
        
            $query = "DELETE FROM actor WHERE actor_id=?";

            if($stmt = $conn->prepare($query)){
                $stmt->bind_param("i", $model->id);

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
            return "Invalid Parameters";
        }
        
    }
}

?>