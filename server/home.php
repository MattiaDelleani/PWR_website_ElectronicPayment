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
		
      <p>
		Benvenuto. Da questo sito &egrave; possibile effetuare pagamenti e vedere i propri movimenti.<br>Se non ti sei ancora autenticato puoi farlo dal men&ugrave; sotto la voce Login
      </p>
  	
    </div>
	<?php 
		include("footer.php");
	?>
    </main>
  </body>
</html>