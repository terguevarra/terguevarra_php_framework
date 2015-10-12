<?php
require('../lib/authentication.php');

if(isset($_POST['action'])){
    $action = $_POST['action'];
    if(isset($_POST['data'])){
        $posteddata = $_POST['data'];
        $model = (object)$posteddata;
    }else{
        $model = null;
    }
    
}else{
    $action = "get";
}

//instantiate authentication service
$auth = new Authentication();

if(!$auth->isAuthentic()){
    header("HTTP/1.1 401 Unauthorized");
}else{

}
?>
