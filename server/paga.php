<?php
	$form_received=false;
	if(isset($_POST["nickname"])&& $_POST["pswd"]!=""){
		$form_received=true;
	}
	$session=true;
	if( session_status() === PHP_SESSION_DISABLED  )
		$session = false;
	elseif( session_status() !== PHP_SESSION_ACTIVE )
		session_start();
	if(isset($_SESSION["logged"]))
		$logged=true;
	
	$_SESSION['pagamento']=false;
	
	
	
	$nickname="";
		 if($form_received)
		 {
			 $nickname=$_POST["nickname"];
			$errore_SQL=true;
			$errore_query=true;
			$errore_conn=true;
			$valido=false;
            $con = mysqli_connect("localhost", "uReadOnly", "posso_solo_leggere", "pagamenti");
             
                if (!mysqli_connect_errno()){
					$errore_conn=false;
					$query_log="SELECT nome, saldo, negozio FROM usr WHERE nick=? and pwd=?";
					$stmt_log=mysqli_prepare($con, $query_log);
					if($stmt_log){
						$errore_SQL=false;
						$pswd=$_POST["pswd"];
						$nick=$nickname;
						mysqli_stmt_bind_param($stmt_log, "ss", $nick, $pswd);
						$result_log = mysqli_stmt_execute($stmt_log);
						mysqli_stmt_bind_result($stmt_log, $nome, $saldo,$negozio);
						mysqli_stmt_fetch($stmt_log);
						
						if($result_log){
							$errore_query=false;
							if($nome && $saldo){
								$valido=true;
								$scadenza = time()+(3600*24*5); 
								setcookie("nickname",$nickname,$scadenza,"","",FALSE);
								
										$logged=true;
										$_SESSION['logged']=true;
										$_SESSION['nick']=$nick;
										$_SESSION['nome']=$nome;
										$_SESSION['saldo']=$saldo;
										$_SESSION['negozio']=$negozio;
								
							}else{
								
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
								$url.="login.php";
								$_SESSION['errato']=true;
								header("Location:".$url); /* Redirect browser , cerca di fralo tramire url */
							}							
						}
					}
					mysqli_stmt_close($stmt_log);
				}
			mysqli_close($con);
		}
		
		include("header.php");
	  ?>

	<script type="application/javascript">
	
		function validateForm(input){
		var regex=/(^(([0-9]+)|([0-9]+\,\d{1,2}))$)/;
		if(input==""){
			alert("Attenzione: e' necessario inserire un importo!");
          return false;
		}
		else if(!regex.test(input)){
				alert("Valore inserito non conforme. Inserire importo intero oppure intero con una o due cifre dopo la virgola (es. 5,34 o 5,3 o 5)");
				return false;
		}else{
			var s="<?php echo number_format($_SESSION['saldo']/100,2,",",""); ?>";
			var saldo=parseFloat(s.replace(/\,/g,"."));
			var importo=parseFloat(input.replace(/\,/g,"."));
			var diff=0.0;
			//alert("importo: "+ importo+ " "+ typeof(importo)+ "| saldo "+saldo+" "+typeof(saldo)+" \ diff: "+ (saldo-importo));
			if(importo>saldo){
				alert("Errore. Importo maggiore del saldo attuale.");
				return false;
			}else{
				diff=(saldo-importo);
				var dest=document.getElementsByName('destinatario');
				var valido=false;
				 for(var i = 0; i < dest.length; i++) { 
					if(dest[i].checked) {
						return true;
					}
				}
				alert("Selezionare un destinatario.");
				return false;
				}	
			}
		}
		
     

	
		
		
	</script>

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
		if($errore_SQL ||  $errore_conn || $errore_query){
			echo "<p>Errore di connessione DB</p>";
		}else{
	  
		//numero di negozi e venditori
		 $conn = mysqli_connect("localhost", "uReadOnly", "posso_solo_leggere", "pagamenti");
             
                if (!mysqli_connect_errno()){
					$query="SELECT negozio, COUNT(*) as c FROM `usr` GROUP BY negozio";
					$result=mysqli_query($conn, $query);
					if($result){
						while($row=mysqli_fetch_assoc($result)){
							if($row['negozio']==0){
								$individui=$row['c'];
							}else{
								$negozi=$row['c'];
							}
						}
						mysqli_free_result($result);
						
					}else{
						echo "<p>ERROR SQL</p>";
					}
				}else{
					echo "<p>Errore connessione al DBMS: ".mysqli_connect_error()."</p>";
				}
			
	  
		if(!isset($_SESSION["logged"]) && !$form_received ){
			//caso in cui non ce sessione e non si è passati dal'Login
			echo "<p class='n_dati'>Numero di negozi registrati&#58; ".$negozi."<br>Numero di individui registrati&#58; ".$individui."</p>";
			echo "<p>Ciao, non ti sei ancora autenticato puoi farlo dal men&ugrave; sotto la voce Login, oppure puoi cliccare <a href='login.php'>qui</a></p>";
		}elseif(!$valido && $form_received){     //caso in cui si è passati dal login ma ho introdotto dati errati
			echo "<p class='n_dati'>Numero di negozi registrati&#58; ".$negozi."<br>Numero di individui registrati&#58; ".$individui."</p>";
			echo "<p>Nickname o password non validi per accedere. <br>Torna al <a href='login.php'>Login</a></p>";
				  	
		}
		if($logged){
			echo "<p>Utente cllegato correttamente. </p>";
			?>
			<form name="form_paga" action="riepilogo.php" method="POST" onSubmit="return validateForm(importo.value);" >
            <table name="elenco_dest">
                <caption>Elenco destinatari:</caption>
                <tr>
                    <th colspan='2'>Destinatario</th>
                    <th>Seleziona</th>
                </tr>
			
			<?php
			
			//se è negozio
			if($_SESSION['negozio']==1){
					$query="SELECT nick, nome FROM `usr` WHERE nick!=?";
					$stmt=mysqli_prepare($conn, $query);
					mysqli_stmt_bind_param($stmt, "s", $_SESSION['nick']);
					$result=mysqli_stmt_execute($stmt);
					if($result){
						mysqli_stmt_bind_result($stmt,$nick, $nome);
						while($row=mysqli_stmt_fetch($stmt)){
							 echo "<tr>\n<td colspan='2'>".$nome."</td>\n<td><input type='radio' name='destinatario' value='".$nick."'></td>\n</tr>\n";  
						}
					}else{
						echo "<tr>\n<td colspan='3'>Errore query fallita: ".mysqli_error($con)."</td>\n</tr>\n";
					}		
					
			}else{//se non è negozio
				$query="SELECT * FROM `usr` WHERE negozio=1";
					$result=mysqli_query($conn, $query);
					if($result){
						while($row=mysqli_fetch_assoc($result)){
							 echo "<tr>\n<td colspan='2'>".$row['nome']."</td>\n<td><input type='radio' name='destinatario' value='".$row['nick']."'></td>\n</tr>\n";  
						}
					}else{
						echo "<tr>\n<td colspan='3'>Errore query fallita: ".mysqli_error($con)."</td>\n</tr>\n";
					}		
					
			}
			 ?>
				<tr>
                    <td colspan="3">Inserire importo e cliccare il bottone "Procedi"</td>
                </tr>
				<tr>
					<td colspan="2">Importo: <input type="text" name="importo" class="number"></td>
					<td><input type="submit" name="procedi" value="PROCEDI"></td>
				</tr>
            </table>
        </form>
		<?php
		}
		mysqli_close($conn);
		}
		?>
	  
    </div>
	
       <?php 
		include('footer.php');
		?>
    </main>
  </body>
</html>