<?php
	echo "<div class='thefooter'>
        <p>Autore: Mattia Delleani</p>
        <address>e-mail:    <a href='mailto:s248900@studenti.polito.it'>s248900@studenti.polito.it</a></address>
        <p>
		<a id='css' href='http://jigsaw.w3.org/css-validator/check/referer'>
			<img style='border:0;width:88px;height:31px'
            src='http://jigsaw.w3.org/css-validator/images/vcss'
            alt='CSS Valido!' align='right' />
		</a>
		</p>
		<p>Nome file:".basename($_SERVER['PHP_SELF'])."</p>"; //.basename($_SERVER['PHP_SELF'],'.php'); con basename tolgo il .php
    echo "</div>";
?>