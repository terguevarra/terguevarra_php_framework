<?php

include('../config/mysql_db.php');

$query = "SELECT * FROM actor";

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
    
    echo json_encode($returndata);
}

?>