<?php
	
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
			if(!$logged){
				echo "<p>Attenzione! L’elenco dei pagamenti &egrave; disponibile solo per gli utenti autenticati&#46;</p>";
			}else{
				//UTENTE LOGGATO
				?>
				<form name="ricerca" action="movimenti.php" method="POST">
				<p>Tipologia:</p>
				<input type='radio' name='posizione' value=1 checked><label>Ordinati</label><br>
				<input type='radio' name='posizione' value=2><label>Ricevuti</label><br>
				<input type='radio' name='posizione' value=3'><label>Oridnati e ricevuti</label><br>
				<br>
				<p>Mese:</p>
				<input type='radio' name='data_pag' value=1 checked><label>Mese corrente</label><br>
				<input type='radio' name='data_pag' value=2><label>Mese corrente e i due precedenti</label><br>
				<br>
				<input type="submit" name="cerca" value="CERCA">
				</p>
				</form>
				<?php
				
			}
		?>
     	
    </div>
	
       

     <?php 
		include('footer.php');
		?>
    </main>
  </body>
</html>