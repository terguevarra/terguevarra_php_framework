<?php

function GetToken(){
	$headers = getallheaders();
	foreach ($headers as $key => $value) {
		if($key == 'Authorization'){
			$token = $value;
		}
	}
	return $token;
}


?>