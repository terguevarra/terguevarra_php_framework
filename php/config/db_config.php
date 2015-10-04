<?php
class DBConfig{
    
    protected $dbhost;
    protected $dbuser;
    protected $dbpwd;
    protected $dbname;
    
    function DBCOnfig(){
        $this->dbhost = "localhost";
        $this->dbuser = "root";
        $this->dbpwd = "killerlook";
        $this->dbname = "sakila";
    }

}
?>