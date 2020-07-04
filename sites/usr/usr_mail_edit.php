<?php
	if (isset($_POST['changeEmail'])) {
		$emailOld = trim(strtolower($_POST['emailOld']));
		$emailNew = trim(strtolower($_POST['emailNew']));

		// Alte E-Mail Adresse aus der DB auslesen
		$sql 		= "SELECT email, hash FROM ".prefix."_users WHERE username = '".$_SESSION['username']."'";
		$result 	= $mysqli->query($sql);
		$email 		= mysqli_fetch_array($result);
		$dbmail 	= trim(strtolower($email['email']));

		// Prüfen wenn die alte eingetragene E-Mail Adresse mit der in der DB übereinstimmen
		if ($dbmail === $emailOld) {
			// Prüfen wenn die neue E-Mail Adresse in der Datenbank schon vorhanden ist
			$sql = "SELECT email FROM ".prefix."_users WHERE email = '$emailNew'";
			$result = $mysqli->query($sql);
			if($result->num_rows == 0) {
					// Wenn die DB und die Old E-Mail Adresse übereinstimmen E-Mail Verschicken für das ändern der E-Mail Adresse
					$username = $_SESSION['username'];
					$oem 			= password_hash($emailOld, PASSWORD_DEFAULT);
					$link 		= "<a href=\"http://".$_SERVER['SERVER_NAME']."/index.php?site=mailedit&hash=".$email['hash']."&oem=$oem&nem=$emailNew\">E-Mail Adresse ändern</a>";

					// Template mit dem Mailbody laden und für den Versand vorbereiten
			    		$mailbody = file_get_contents( 'includes/email/ChangeMailBody.txt' );
			    		// Platzhalter mit den Benutzereingaben ersetzen
			    		$mailbody = str_replace( '###NAME###', $username, $mailbody );
			    		$mailbody = str_replace( '###LINK###', $link, $mailbody );

						// PHPMailer laden
						require ("includes/PHPMailer/PHPMailerAutoload.php");
						//Create a new PHPMailer instance
						$mail = new PHPMailer;
						//UTF-8 Kodierung festlegen
						$mail->CharSet  =  "UTF-8";
						//Set who the message is to be sent from
						$mail->setFrom('em21@bde-malygos.de', '[Fußball-Tippspiel WM 2018]');
						//Set an alternative reply-to address
						$mail->addReplyTo('em21@bde-malygos.de', '');
						//Set who the message is to be sent to
						$mail->addAddress($emailOld, $username);
						//Set the subject line
						$mail->Subject = 'Antrag auf E-Mail Adressenänderung';
						//Read an HTML message body from an external file, convert referenced images to embedded,
						//convert HTML into a basic plain-text alternative body
						$mail->msgHTML($mailbody, dirname(__FILE__));
						//Replace the plain text body with one created manually
						$mail->AltBody = 'Antrag auf E-Mail Adressenänderung';

						//send the message, check for errors
						if (!$mail->send()) {
						    echo "<p class='alert alert-danger text-center'>E-Mail Fehler: " . $mail->ErrorInfo . "</p>";
						} else {
						    echo "<div class='alert alert-success'>Wir haben Ihnen soeben eine E-Mail geschickt. Bitte bestätigen Sie die neue E-Mail Adresse mit dem Link in der E-Mail.</div>";
						}
			}
			else {
				echo "<div class='alert alert-danger'>Die neue E-Mail Adresse von Ihnen ist bereits in Benutzung. Benutzen Sie bitte eine andere neue E-Mail Adresse.</div>";
			}
		}
		else {
			echo "<div class='alert alert-danger'>Die alte E-Mail Adresse stimmt nicht mit der eingetragenen überein.</div>";
		}
	}
?>
<h2>E-Mail Adresse ändern</h2><br>
<div class="form">
<form action="" method="POST" accept-charset="UTF-8">
	<div class="field-wrap">
		<label>
			Aktuelle E-Mail Adresse<span class="req">*</span>
		</label>
		<input class="form-control" type="text" required autocomplete="off" name='emailOld'/>
	</div>
	<div class="field-wrap">
		<label>
			Neue E-Mail Adresse<span class="req">*</span>
		</label>
		<input class="form-control" type="text" required autocomplete="off" name='emailNew'/>
	</div>
	<input type="submit" class="btn btn-green" name="changeEmail" value="E-Mail Adresse ändern"></input>
</form>
</div>
