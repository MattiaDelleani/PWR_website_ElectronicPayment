<?php
	
		
		include("header.php");
		
?>
  	

	<?php			
			if($logged){
				if($_SESSION['pagamento']==false){
					if(!isset($_POST['destinatario']) || !isset($_POST['importo'])) {
					echo "<p>Destinatario o importo non selezionati";
					}else{
						if(!preg_match("/(^(([0-9]+)|([0-9]+\,\d{1,2}))$)/",$_POST['importo'])){
							$err=true;
						}
						if(!$err){
							$destinatario=$_POST['destinatario'];
							$importo=str_replace(",",".",$_POST['importo']);
							$saldo=number_format($_SESSION['saldo']/100,2,".","");
							$differenza=$saldo-$importo;
							if($differenza<0){
								echo "<p>&Egrave; introdotto un importo maggiore del saldo.</p>";
							}else{
								//uso round perchè tanto non avro mail valori con virgola superiori di n,5
								$importo=round($importo*100);
								
								$saldo_compratore=$_SESSION['saldo']-$importo;								
									$con = mysqli_connect("localhost", "uReadWrite", "SuperPippo!!!", "pagamenti");           
									if (mysqli_connect_errno()) 
										echo "<p>Errore connessione al DBMS: ".mysqli_connect_error()."</p>\n";
									else
									{   
										$errore1=true;
										//DIMINUISCO IL SALDO DEL COMPRATORE
										$query="UPDATE usr SET saldo=? WHERE nick=?";
										$stmt = mysqli_prepare($con, $query);
										if($stmt==false){
											echo "<p>ERRORE 1</p>";
										}else{
											mysqli_stmt_bind_param($stmt, "is",$saldo_compratore,$_SESSION['nick']);
											$result = mysqli_stmt_execute($stmt);
											
											if($result)
												$errore1=false;
											mysqli_stmt_close($stmt);
										}
									
										
										
										$errore2=true;
										//AUMENTO IL SALDO DEL VENDITORE
										//devo rpima reperire il suo saldo
										$query="SELECT nome, saldo FROM usr WHERE nick=?";
										$stmt = mysqli_prepare($con, $query);
										if($stmt==false){
											echo "<p>ERRORE 2</p>";
										}else{
											mysqli_stmt_bind_param($stmt, "s",$destinatario);
											$result = mysqli_stmt_execute($stmt);
											mysqli_stmt_bind_result($stmt,$nome,$saldo_venditore);
											mysqli_stmt_fetch($stmt);
											if($result)
												$errore2=false;										
											mysqli_stmt_close($stmt);
											$saldo_venditore=$saldo_venditore+$importo;
										}
											
										
										$errore3=true;
										//AGGIORNO IL DBMS
										$query="UPDATE usr SET saldo=? WHERE nick=?";
										$stmt = mysqli_prepare($con, $query);
										if($stmt==false){
											echo "<p>ERRORE 3</p>";
										}
										mysqli_stmt_bind_param($stmt, "is",$saldo_venditore,$destinatario);
										$result = mysqli_stmt_execute($stmt);
										
										if($result)
											$errore3=false;
										mysqli_stmt_close($stmt);
										
										if($errore1 || $errore2 || $errore3){
											echo "<p>ERRORE NELLA GESTIONE DEI DATI</p>";
										}else{
											$_SESSION['pagamento']=true;
											$_SESSION['saldo']=$saldo_compratore;
											$date = date('Y-m-d');
											$hours = date('H:i:s');
											$data="".$date." ".$hours;
							
											?>
				<div class="riassunto">
				<?php
					if(!isset($_SESSION['logged'])){
						echo "<p>Utente: ANONIMO<br>Saldo: 0,00 &euro;</p>";
					}else{
						echo "<p>Utente:".$_SESSION['nick']."<br>Saldo:".number_format($_SESSION['saldo']/100,2,",","")." &euro;</p>";
					}
				?>
				
				</div>
				<div class="thecontent">
				  <h2><?php echo $titolo;?></h2>
		
											<p>Pagamento avvenuto con successo.<br>
											<table class="pagamento">
												<caption>Dati del pagamento:</caption>
													<tr>
														<th>Committente</th>
														<th>Destinatario</th>
														<th>Importo</th>
														<th>Data ed ora esecuzione</th>
													</tr>
											<?php
											echo "<tr><td>".$_SESSION['nome']."</td><td>".$nome."</td><td>".number_format($importo/100,2,",","")."&euro;</td>
											<td>".$data."</td></tr></table> </p>";
											
											echo "<p>Per visualizzare i tuoi movimenti clicca <a href='log.php'>qui</a></p>";
										}
										
										//AGGIUNGO IL Log
										$query="INSERT INTO log(src,dst,importo,data) VALUES (?,?,?,?)";
										$stmt = mysqli_prepare($con, $query);
										if($stmt==false){
											echo "<p>ERRORE 3</p>";
										}
										mysqli_stmt_bind_param($stmt, "ssis",$_SESSION['nick'],$destinatario,$importo,$data);
										$result = mysqli_stmt_execute($stmt);
										$erroreIns=true;
										if($result)
											$erroreIns=false;
										mysqli_stmt_close($stmt);
										
										if($erroreIns)
											echo "<p>Errore nell'inserimento record nel db.</p>";
									}
								
									mysqli_close($con);
							}					
								
						}else{
							echo "<p>&Egrave; stato introdotto un importo non valido.</p>";
						}
					}
					
				}else{
					
					echo "<p>Per visualizzare i tuoi movimenti clicca <a href='log.php'>qui</a></p>";
				}
				
			}else{
				echo "<p>Attenzione! Pagina disponibile solo per gli utenti autenticati&#58;</p>";
			}
		?>
     	
    </div>
	
       
	

     <?php 
		include('footer.php');
		?>
    </main>
  </body>
</html>