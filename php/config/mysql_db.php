<?php
require('db_config.php');

class MySQL extends DBConfig{
    
    
    private $connection;
    
    public function __construct(){
        $db = new DBConfig();
        
        try{
            $this->connection = new PDO("mysql:host=$db->dbhost;dbname=$db->dbname", $db->dbuser, $db->dbpwd);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $e){
            die("Can't connect to database");
        }
        
        /*$this->connection = new mysqli($db->dbhost, $db->dbuser, $db->dbpwd, $db->dbname);
        
        if($this->connection->connect_error){
            die("Connection failed: " . $this->connection->connect_error);
        }*/
    }
    
    public function Connect(){
        return $this->connection;
    }
}

?>