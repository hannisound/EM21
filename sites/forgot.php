<section id="anmeldung">
	<div class="container">
    	<div class="row">
        	<div class="col-lg-10 mx-auto">
                <?php
                    // Check if form submitted with method="post"
                    if ( $_SERVER['REQUEST_METHOD'] == 'POST' )
                    {
                        $email = $mysqli->escape_string($_POST['email']);
                        $result = $mysqli->query("SELECT id, username, email, hash FROM ".prefix."_users WHERE email='$email'");

                        if ( $result->num_rows == 0 ) // User doesn't exist
                        {
                            $_SESSION['message'] = "Uns ist kein User bekannt mit dieser E-Mail Adresse. Überprüfen Sie bitte die E-Mail Adresse.";
                            echo "<div class='alert alert-danger text-center'>".$_SESSION['message']."</div>";
                        }
                        else { // User exists (num_rows != 0)

                            $user       = $result->fetch_assoc(); // $user becomes array with user data

                            $id		      = $user['id'];
                            $hash    		= $user['hash'];
														$username   = $user['username'];
														$email 			= $user['email'];

														// Links vorbereiten für E-Mail
										    		$link	= '<a href="http://'.$_SERVER['SERVER_NAME'].'/index.php?site=reset&id='.$id.'&hash='.$hash.'">Passwort zurücksetzen</a>';
										    		$link2  = ''.$_SERVER['SERVER_NAME'].'/index.php?site=reset&id='.$id.'&hash='.$hash.'';

										    		// Template mit dem Mailbody laden und für den Versand vorbereiten
											    	$mailbody = file_get_contents( 'includes/email/PwResetBody2.txt' );
											    	// Platzhalter mit den Benutzereingaben ersetzen
														$mailbody = str_replace('###USERNAME###', $username, $mailbody);
											    	$mailbody = str_replace('###LINK###', $link, $mailbody);
											    	$mailbody = str_replace('###LINK2###', $link2, $mailbody);

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
													$mail->addAddress($email);
													//Set the subject line
													$mail->Subject = 'Sie haben soeben das Passwort vergessen Formular benutzt.';
													//Read an HTML message body from an external file, convert referenced images to embedded,
													//convert HTML into a basic plain-text alternative body
													$mail->msgHTML($mailbody, dirname(__FILE__));
													//Replace the plain text body with one created manually
													$mail->AltBody = 'Sie haben Ihr Passwort vergessen - Wir helfen Ihnen';
														//send the message, check for errors
													if (!$mail->send()) {
													    echo "<div class='alert alert-danger text-center'>E-Mail Fehler: ",$mail->ErrorInfo, "</div>";
													} else {
													    echo "<div class='alert alert-success text-center'>Wir haben Ihnen soeben eine E-Mail mit weiteren Anweisungen geschickt. Kontrollieren Sie bitte Ihre E-Mails und folgen Sie den Anweisungen um Ihr Passwort zurückzusetzen.</div>";
													}
                      }
                    }
                ?>

        	<div class="form">
              <form action="index.php?site=forgot" method="post">
                <div class="field-wrap">
                  <label>
                    E-Mail Adresse<span class="req">*</span>
                  </label>
                  <input type="email"required autocomplete="off" name="email"/>
                </div>
                <button class="button button-block"/>Zurücksetzen</button>
              </form>
            </div>
        </div>
    </div>
</div>
</section>
