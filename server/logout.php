<?php
session_start();

session_destroy();
  
    $protocollo = $_SERVER['SERVER_PROTOCOL'];
    $name = $_SERVER['HTTP_HOST'];
    $request =$_SERVER['PHP_SELF'];
    $url = "";
	
    if (preg_match("/[A-Za-z]+/", $protocollo, $matches)) 
    {
        $url .= strtolower($matches[0])."://";
    }
    
    $url .= $name.$request;
	$url = substr($url, 0, strpos($url, 'logout.php'));
	$url.="home.php";
	
	header("Location:".$url); /* Redirect browser , cerca di fralo tramire url */

exit;
?>
<!doctype html>
<html>
</html>