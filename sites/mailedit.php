<section id="mailedit">
	<div class="container">
    	<div class="row">
        	<div class="col-lg-8 mx-auto">
            <h1>Antrag auf E-Mail Adresse ändern<br>
              <p class="lead text-center">Schnell und einfach Ihre E-Mail Adresse ändern</p>
            </h1>
            <?php
            	if (isset($_REQUEST['hash']) && isset($_REQUEST['oem']) && isset($_REQUEST['nem'])){
                // Übergebene Daten in Variablen schreiben
            		$hash		= $_REQUEST['hash'];
            		$oem 		= $_REQUEST['oem'];
            		$nem 		= $_REQUEST['nem'];

                // E-Mail Adresse aus der Datenbank auslesen mittels Hash
            		$sql 		    = "SELECT email FROM ".prefix."_users WHERE hash = '$hash'";
            		$result 	  = $mysqli->query($sql);
            		$emailOld 	= mysqli_fetch_array($result);
            		$emailOld	  = trim(strtolower($emailOld['email']));

                // Prüfen wenn alte E-Mail mit $oem übereinstimmt
            		if (password_verify($emailOld, $oem)) {
                  // Neue E-Mail Adresse in Datenbank eintragen
            			$sql1 = "UPDATE `".prefix."_users` SET  `email` = '$nem' WHERE `hash` = '$hash'";
            			if(!$mysqli->query($sql1)) {
            				printf("<p class='alert alert-danger text-center'>DB Fehler #0007: %s\n</p>", $mysqli->error);
            			}
            			else {
            				echo "<p class='alert alert-success text-center'>Ihre E-Mail Adresse wurde Erfolgreich geändert.</p>";
            			}
            		}
            		else {
            			echo "<p class='alert alert-danger'>Ihre Daten stimmen leider nicht überein, bitte prüfen Sie wenn Sie den kompletten Link kopiert haben. Sollte es weiterhin nicht funktionieren führen Sie bitte die E-Mail Änderung erneut aus.</p>";
            		}
            	}
            	else {
            		echo "	<p class='alert alert-danger'>Es ist leider ein Fehler aufgetreten. Es konnten leider nicht alle benötigten Daten empfangen werden.<br>
            				Überprüfen Sie bitte wenn der Link welchen wir Ihnen geschickt haben vollständig im Browser angekommen ist. <br>
            				Sollte es weiterhin Probleme geben wenden Sie sich bitte über das Kontaktformular an uns. Vielen Dank für Ihr Verständnis</p>";
            	}
            ?>
        </div>
    </div>
  </div>
</section>
