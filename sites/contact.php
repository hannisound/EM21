<section id="anmeldung">
	<div class="container">
    	<div class="row">
        	<div class="col-lg-8 mx-auto">
            <?php
              if(login_check($mysqli)) {
                $email = $_SESSION['email'];
                $username = $_SESSION['username'];
                $hidden = "hidden";
              }
              else {
                $email = "";
                $username = "";
                $hidden = "";
              }

            	if (isset($_POST['contact'])) {
    		    		$name 	= $_POST['name'];
    		    		$betreff 	= $_POST['betreff'];
    		    		$email 		= $_POST['email'];
    		    		$message 	= nl2br($_POST['message']);

	              if (!empty($name) AND !empty($betreff) AND !empty($message) AND !empty($email) AND filter_var($email, FILTER_VALIDATE_EMAIL))
								{
									// Captcha Code auf Richtigkeit prüfen
			    				if (isset($_POST['g-recaptcha-response']) && empty($_POST['g-recaptcha-response']) == FALSE)
									{
										//your site secret key
						        	$secret 	= '6LfYeF0UAAAAAEEXLiXng3p55Ts7q0ChgspO61h0';
						        	$remoteip	= $_SERVER['REMOTE_ADDR'];
						        	$captcha 	= $_POST['g-recaptcha-response'];
						        	//get verify response data
						        	$rsp 		= file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$captcha.'&remoteip='.$remoteip.'');
						        	$responseData = json_decode($rsp);
						        	if($responseData->success) {

		      							// Template mit dem Mailbody laden und für den Versand vorbereiten
		      			    		$mailbody = file_get_contents( 'includes/email/ContactBody.txt' );
		      			    		// Platzhalter mit den Benutzereingaben ersetzen
		      			    		$mailbody = str_replace( '###NAME###', $name, $mailbody );
		      			    		$mailbody = str_replace( '###EMAIL###', $email, $mailbody );
		      			    		$mailbody = str_replace( '###NACHRICHT###', $message, $mailbody );
		      			    		$mailbody = str_replace( '###BETREFF###', $betreff, $mailbody );

			      						// PHPMailer laden
			      						require ("includes/PHPMailer/PHPMailerAutoload.php");
			      						//Create a new PHPMailer instance
			      						$mail = new PHPMailer;
			      						$mail->CharSet  =  "UTF-8"; 																						//UTF-8 Kodierung festlegen
			      						$mail->setFrom ('em21@bde-malygos.de', ''.$name.''); 										// Von wem kommt die E-Mail Adresse
			      						$mail->addReplyTo($email);																							// Antwort geht an
			      						$mail->addAddress('em21@bde-malygos.de');																// Wohin soll die Mail gehen
			      						$mail->Subject = '[Kontaktformular] Nachricht von: '.$name.'';					// Betreff festlegen
			      						//Read an HTML message body from an external file, convert referenced images to embedded,
			      						//convert HTML into a basic plain-text alternative body
			      						$mail->msgHTML($mailbody, dirname(__FILE__));
			      						//Replace the plain text body with one created manually
			      						$mail->AltBody = 'Sie haben eine Nachricht vom Kontaktformular erhalten';

			      						//send the message, check for errors
			      						if (!$mail->send()) {
			      						    echo "<div class='alert alert-danger text-center'>E-Mail Fehler: " . $mail->ErrorInfo, "</div>";
			      						} else {
			      						    echo "<div class='alert alert-success text-center'>Ihre Nachricht wurde Erfolgreich versendet</div>"; // Ausgabe formatieren die Optik!
			      						}
											}
											else {
												// Wenn reCapatcha false zurückgibt
												$_SESSION['message'] = "Der Sicherheitscode war nicht korrekt.";
			                  echo "<div class='alert alert-danger text-center'>".$_SESSION['message']."</div>";
											}
	      					}
									else {
										// Wenn reCapatcha false zurückgibt
										$_SESSION['message'] = "Der Sicherheitscode war nicht korrekt.";
										echo "<div class='alert alert-danger text-center'>".$_SESSION['message']."</div>";
									}
								}
								else {
									$_SESSION['message'] = "Es müssen alle Felder ausgefüllt sein.";
									echo "<div class='alert alert-danger text-center'>".$_SESSION['message']."</div>";
								}
							}
      		  ?>

          <div id="login" class="form">
                <p class="lead text-center"> Nutzen Sie dieses Formular um mit uns in Kontakt zu treten.</p>
              </h2>
              <form action="index.php?site=contact" method="post" autocomplete="off">

                <div class="field-wrap">
                  <label>
                    Name<span class="req">*</span>
                  </label>
                  <input class="form-control" type="text" <?php echo $hidden; ?> value="<?php echo $username; ?>" required autocomplete="off" name="name"/>
                </div>

              <div class="field-wrap">
                <label>
                  E-Mail Adresse<span class="req">*</span>
                </label>
                <input class="form-control" type="email" <?php echo $hidden; ?>  value="<?php echo $email; ?>" required autocomplete="off" name="email"/>
              </div>

              <div class="field-wrap">
                <label>
                  Betreff<span class="req">*</span>
                </label>
                <input class="form-control" type="text" required autocomplete="off" name="betreff"/>
              </div>

              <div class="field-wrap">
                <label>
                  Nachricht<span class="req">*</span>
                </label>
                <textarea class="form-control" type="text" required autocomplete="off" name="message" rows="5"/></textarea>
              </div>
							<!-- ReCapatcha von Google für Bots -->
							<div class="g-recaptcha" data-sitekey="6LfYeF0UAAAAAH8yxdfCULkGkB86lir_oDUtj8-a"></div>
              <button class="button button-block" name="contact" />Nachricht versenden</button>

              </form>

            </div>
        	</div>
        </div>
    </div>
</section>
