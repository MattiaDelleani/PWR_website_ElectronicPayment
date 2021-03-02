<?php
	$titolo=ucfirst(basename($_SERVER['PHP_SELF'],'.php'));
	$session=true;
		if( session_status() === PHP_SESSION_DISABLED  )
		$session = false;
		elseif( session_status() !== PHP_SESSION_ACTIVE )
		session_start();
		if(isset($_SESSION["logged"])){
			$logged=true;
			//nel caso di due browser connessi contemporaneamente devo aggiornare un eventuale cambio di saldo quindi accedo al DB
			$con = mysqli_connect("localhost", "uReadWrite", "SuperPippo!!!", "pagamenti");           
			if (mysqli_connect_errno()) 
				echo "<p>Errore connessione al DBMS: ".mysqli_connect_error()."</p>\n";
			else
			{   $query="SELECT saldo FROM usr WHERE nick=?";
				$stmt = mysqli_prepare($con, $query);
				if($stmt){
					mysqli_stmt_bind_param($stmt, "s",$_SESSION['nick']);
					$result = mysqli_stmt_execute($stmt);
					mysqli_stmt_bind_result($stmt,$saldo);
					mysqli_stmt_fetch($stmt);
														
					mysqli_stmt_close($stmt);
					$_SESSION['saldo']=$saldo;
					}
			}
			mysqli_close($con);
			
			
		}
		
	
	echo "<!doctype html>
			<html lang='it'>
				<head>
					<meta charset='utf-8'/>
					<link rel='stylesheet' type='text/css' href='stile.css'>
					<title>Pagamenti elettronici - PWR</title>
					<meta name='author' content='Mattia Delleani'>
					<meta name='keywords' content='html'>
					<meta name='keywords' content='Progettazzione di servizi web e reti di calcolatori'>
					<meta name='viewport' content='width=display-width; initial-scale=1.0'>
				</head>	
				  <body>
					<main class='grid-container'>
				<div class='theheader'>
					<h1>Pagamenti elettronici</h1>
				</div>
				
				<div class='thenavigation'>
					  <h2>Men&ugrave;</h2>

					  <ul>
						<li><a href='home.php'>Home</a></li>
						<li><a href='paga.php'>Pagamenti</a></li>
						<li><a href='log.php'>Movimenti</a></li>";
							if($logged){
								echo "<li class='log'>Login</li>";
								echo "<li><a href='logout.php'>Logout</a></li></ul>";				
							}else{
								echo "<li><a href='login.php'>Login</a></li>";
								echo "<li class='log'>Logout</li></ul>";
							}
echo "</div>";							
					 /*Metto anche il menu che è uguale per tutti ma non il riassunto perche nella pagina riepilogo viene chiamato dopo*/
?>