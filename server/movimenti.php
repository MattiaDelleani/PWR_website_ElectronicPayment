<?php
	$form_received=false;
	if(isset($_POST["posizione"])&& isset($_POST["data_pag"])){
		$form_received=true;
	}
	
	
	$_SESSION['pagamento']=false;
		include("header.php");
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
	  
	  <?php
	  
	  //PAGIAN DI LOGG
		 if(!$form_received)
		 {
			 echo "<p>Selezionare delle condizioni di ricerca nella sezione <a href='log.php'>Movimenti</a></p>";
		 }else{
		
			$errore_SQL=true;
			$errore_query=true;
			$errore_conn=true;
			$valido=false;
            $con = mysqli_connect("localhost", "uReadOnly", "posso_solo_leggere", "pagamenti");
             
                if (!mysqli_connect_errno()){
					$errore_conn=false;
					$anno=date('Y');
					$mese=date('n');
					$mesi_prec=$mese-2;
					$posizione=$_POST['posizione'];
					$data_pag=$_POST['data_pag'];
					$query="";
					switch($posizione){
						case 1:
							if($data_pag==1)
								$query="SELECT * FROM log WHERE src=? AND MONTH(data)=? AND YEAR(data)=?";
							else
								$query="SELECT * FROM log WHERE src=? AND MONTH(data)>=?  AND MONTH(data)<=? AND YEAR(data)=? ";
						
						break;
						
						case 2:
							if($data_pag==1)
								$query="SELECT * FROM log WHERE dst=? AND MONTH(data)=? AND YEAR(data)=?";
							else
								$query="SELECT * FROM log WHERE dst=? AND MONTH(data)>=? AND MONTH(data)<=? AND YEAR(data)=?";
						
						break;
						
						case 3:
							if($data_pag==1)
								$query="SELECT * FROM log WHERE (src=? OR dst=?) AND MONTH(data)=? AND YEAR(data)=?";
							else
								$query="SELECT * FROM log WHERE (src=? OR dst=?) AND MONTH(data)>=? AND MONTH(data)<=? AND YEAR(data)=?";
						
						break;
						
					}
					
					//echo "QUERY: ".$query."<br>MEse: ".$mese." prec:".$mesi_prec;
					
					$stmt_log=mysqli_prepare($con, $query);
					if($stmt_log){
						$errore_SQL=false;
						//SWI
						
						switch($posizione){
						case 1:
							if($data_pag==1)
								mysqli_stmt_bind_param($stmt_log, "sii", $_SESSION['nick'], $mese,$anno);
							else
								mysqli_stmt_bind_param($stmt_log, "siii", $_SESSION['nick'], $mesi_prec, $mese,$anno);
						
						break;
						
						case 2:
							if($data_pag==1)
								mysqli_stmt_bind_param($stmt_log, "sii", $_SESSION['nick'], $mese,$anno);
							else
								mysqli_stmt_bind_param($stmt_log, "siii", $_SESSION['nick'], $mesi_prec, $mese,$anno);
						
						break;
						
						case 3:
							if($data_pag==1)
								mysqli_stmt_bind_param($stmt_log, "ssii", $_SESSION['nick'],$_SESSION['nick'], $mese,$anno);
							else
								mysqli_stmt_bind_param($stmt_log, "ssiii", $_SESSION['nick'], $_SESSION['nick'],$mesi_prec, $mese,$anno);
						
						break;
						
						default:
							echo "<p>data: .".$data_pag."  pos".$posizione."</p>";
						
					}
						$result_log = mysqli_stmt_execute($stmt_log);
						
						if($result_log){
							$errore_query=false;
							mysqli_stmt_bind_result($stmt_log, $id, $src, $dst, $importo,$d);
							?>
							<p>
								<table class="movimenti">
													<tr>
														<th>ID Operazione</th>
														<th>Committente</th>
														<th>Destinatario</th>
														<th>Importo</th>
														<th>Data ed ora esecuzione</th>
													</tr>
								<?php
                                    while($row = mysqli_stmt_fetch($stmt_log)){
                                        echo "<tr>\n<td>".$id."</td>\n<td>".$src."</td>\n<td>".$dst."</td>\n<td>".number_format($importo/100,2,',','')."&euro;</td>\n<td>".$d."</td>\n</tr>\n";
									}
									?>
								</table>
							</p>
							<p>Per tornare alle modalit&agrave; di ricerca clicca <a href='log.php'>qui</a></p>
						<?php
                                    mysqli_stmt_close($stmt_log);
						}
					}
					
				}
				mysqli_close($con);
	 
				if($errore_SQL ||  $errore_conn || $errore_query){
					echo "<p>Errore di connessione DB</p>";
				}
		 }
	  ?>
    </div>
	
       

      <?php 
		include('footer.php');
		?>
    </main>
  </body>
</html>