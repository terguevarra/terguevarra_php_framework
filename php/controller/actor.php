<?php
require('../lib/authentication.php');
require('../service/actor.php');

if(isset($_POST['action'])){
    $action = $_POST['action'];
    if(isset($_POST['data'])){
        $posteddata = $_POST['data'];
        $posteddata = json_decode($posteddata);
        $model = (object)$posteddata;
    }else{
        $model = null;
    }
    
}else{
    $action = "get";
}

//instantiate authentication service
$auth = new Authentication();

//instatiate actor class
$actor = new Actor();

if(!$auth->isAuthentic()){
    header("HTTP/1.1 401 Unauthorized");
}else{
    if($action == "get"){
	    echo $actor->Get();
    }else if($action == "insert"){
        echo $actor->Insert($model);
    }else if($action == "update"){
        echo $actor->Update($model);
    }else if($action == "delete"){
        echo $actor->Delete($model);
    }
}
?>
