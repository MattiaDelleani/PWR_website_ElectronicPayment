<?php
	$_SESSION['pagamento']=false;
	
	include("header.php");
?>

  
	<script type="application/javascript">
            function validateForm(nickname,pswd){
            if(nickname == "" || pswd == ""){
                   window.alert("Il nickname e/o la password non sono impostati.");
                   return false;
				   }
               return true;
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
	 <?php if($_SESSION['errato']==true){
				echo "<script type='text/javascript'> window.alert('Sono stati introdotti dati di autenticazione errati.');</script>";
				$_SESSION['errato']=false;
				
				}
	?>
      <h2><?php echo $titolo;?></h2>
	  <p>Inserire le informazioni richieste:</p>
	 
		<form name="f" action="paga.php" method="POST" onSubmit="return validateForm(nickname.value,pswd.value);">
			<p>
				<?php
					if(isset($_COOKIE["nickname"]))						
					{
						$temp=$_COOKIE["nickname"];
						echo "Nickname: <input type='text' name='nickname' value='".$temp."'>";
						
					}else{
						echo "Nickname: <input type='text' name='nickname'>";
					}
				?><br>
               Password: <input type="password" name="pswd">
            </p>
            <p><input type="submit" value="OK" ><input type="reset" value="PULISCI"></p>
        </form>
  	
    </div>
	
       
		<?php 
		include('footer.php');
		?>
    </main>
  </body>
</html>