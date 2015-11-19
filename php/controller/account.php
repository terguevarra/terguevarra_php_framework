<?php
require('../lib/authentication.php');
require('../service/account.php');

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
$account = new Account();

if($action == "register"){
    echo $account->Register($model);
}elseif($action == "login"){
    echo $account->Login($model);
}else{
    header("HTTP/1.1 403 Forbidden");
}
?>
