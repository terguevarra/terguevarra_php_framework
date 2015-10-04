<?php
require('db_config.php');

class MySQL extends DBConfig{
    
    
    private $connection;
    
    public function __construct(){
        $db = new DBConfig();
        $this->connection = new mysqli($db->dbhost, $db->dbuser, $db->dbpwd, $db->dbname);
        
        if($this->connection->connect_error){
            die("Connection failed: " . $this->connection->connect_error);
        }
    }
    
    public function Connect(){
        return $this->connection;
    }
}

?>