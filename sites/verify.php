<section id="anmeldung">
	<div class="container">
    	<div class="row">
        	<div class="col-lg-10 mx-auto">
            <?php
              // Make sure id and hash variables aren't empty
              if(isset($_GET['id']) && !empty($_GET['id']) AND isset($_GET['hash']) && !empty($_GET['hash']))
              {
								$time = time();
								// Prüfen wenn die Registrierungszeit schon vorbei ist
								$sql 	= "SELECT wert FROM ".prefix."_config WHERE eigenschaft = 'wmStart'";
								$result = $mysqli->query($sql);
								// Prüfen wenn WM noch nicht gestartet ist
								$wmStart = mysqli_fetch_array($result);

								if ($time <= $wmStart['wert']) {
                  $ID = $mysqli->escape_string($_GET['id']);
                  $hash = $mysqli->escape_string($_GET['hash']);

                  // Select user with matching email and hash, who hasn't verified their account yet (active = 0)
                  $result = $mysqli->query("SELECT username FROM ".prefix."_users WHERE id='$ID' AND hash='$hash' AND active='0'");

                  if ( $result->num_rows == 0 )
                  {
                      $_SESSION['message'] = "Der Account ist bereits Aktiviert oder die URL ist nicht korrekt!";
                      echo "<div class='alert alert-success text-center'>".$_SESSION['message']."</div>";
                  }
                  else {
                      // Set the user status to active (active = 1)
                      if ($mysqli->query("UPDATE ".prefix."_users SET active='1' WHERE id='$ID'") or die($mysqli->error)) {
                        $_SESSION['message'] = "Ihr Account wurde Erfolgreich aktivert!";
												// Spieler in die Toplist eintragen
												reg_eintrag_toplist($ID, $mysqli);
                        echo "<div class='alert alert-success text-center'>".$_SESSION['message']."</div>";
                      }
                      else {
                        $_SESSION['message'] = "Etwas ist bei Ihrer Aktivierung schiefgangen. Versuchen Sie es bitte erneut";
                        echo "<div class='alert alert-danger text-center'>".$_SESSION['message']."</div>";
                      }
                  }
								}
								else {
									$_SESSION['message'] = "Die Fußballweltmeisterschaft hat bereits begonnen. Damit ist keine Registrierung mehr möglich";
									echo "<div class='alert alert-danger text-center'>".$_SESSION['message']."</div>";
								}
              }
              else {
                  $_SESSION['message'] = "Etwas ist bei Ihrer Aktivierung schiefgangen. Prüfen Sie bitte den Link mit den Parametern!";
                  echo "<div class='alert alert-danger text-center'>".$_SESSION['message']."</div>";
              }
            ?>
          </div> <!-- class col-lg-10 mx-auto Ende -->
      	</div>
    </div>
</section>
