<?php
require('../lib/authentication.php');
require('../service/user.php');

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
    header("HTTP/1.1 403 Forbidden");
}

//instatiate actor class
$user = new User();

if($action == "register"){
    echo $user->Register($model);
}else{
    header("HTTP/1.1 403 Forbidden");
}
?>
