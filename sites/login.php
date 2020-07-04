<section id="anmeldung">
	<div class="container">
    	<div class="row">
        	<div class="col-lg-10 mx-auto">
          	<?php
							// Fehlervariablen initalisieren
 							$usernameError = $emailError = $passwordError =  $capatchaError = "";
							// Aktiven Tab bestimmen und als Aktiv markieren
							$lactive 	= "active"; 						// Logintab als Active markieren
							$lshow		= "show active";					// Logincontent als Active markieren
							$ractive 	= "";								// Regtab als Inactive markieren
							$rshow		= "";								// Regcontent als Inactive markieren
							if ($_SERVER['REQUEST_METHOD'] == 'POST')
							{
							    if (isset($_POST['login'])) { //user logging in
							        require 'includes/login.inc.php';
							    }
							    elseif (isset($_POST['register'])) { //user registering
							        require 'includes/register.inc.php';

											$lactive 	= ""; 						// Logintab als Inactive markieren
											$lshow		= "";							// Logincontent als Inactive markieren
											$ractive 	= "active";				// Regtab als Active markieren
											$rshow		= "show active";	// Regcontent als Active markieren
							    }
							}
  					?>
  				<div class="form">
						<nav>
						  <div class="nav nav-tabs nav-justified" id="nav-tab" role="tablist">
						    <a class="nav-item nav-link <?php echo $lactive; ?>" id="nav-login-tab" data-toggle="tab" href="#nav-login" role="tab" aria-controls="nav-login" aria-selected="true">Login</a>
						    <a class="nav-item nav-link <?php echo $ractive; ?>" id="nav-reg-tab" data-toggle="tab" href="#nav-reg" role="tab" aria-controls="nav-reg" aria-selected="false">Registrieren</a>
						  </div>
						</nav>
						<div class="tab-content" id="nav-tabContent">
						  <div class="tab-pane fade <?php echo $lshow; ?>" id="nav-login" role="tabpanel" aria-labelledby="nav-login-tab">
							  	<div id="login">
						          <h2>Willkommen zurück!</h2>

						          <form action="index.php?site=login" method="post" autocomplete="off">

						            <div class="field-wrap">
						            <label>
						              E-Mail Adresse<span class="req">*</span>
						            </label>
						            <input class="form-control" type="email" required autocomplete="off" name="email"/>
						          </div>

						          <div class="field-wrap">
						            <label>
						              Password<span class="req">*</span>
						            </label>
						            <input class="form-control" type="password" required autocomplete="off" name="password"/>
						          </div>

						          <p class="forgot"><a href="index.php?site=forgot">Passwort vergessen?</a></p>

						          <button class="button button-block" name="login" />Login</button>

						          </form>

						        </div>
						  </div>
				  <div class="tab-pane fade <?php echo $rshow; ?>" id="nav-reg" role="tabpanel" aria-labelledby="nav-reg-tab">
					  	<div id="signup">
				          <h2>Registrieren Sie sich Kostenlos</h2>

				          <form action="index.php?site=login" method="post" autocomplete="off">
										<?php
											if (wmStart($mysqli) < time()) {
												echo "<fieldset disabled>";
												$disabled = "</fieldset disabled>";
											}
											else {
												$disabled = "";
											}
										?>
				            <div class="field-wrap">
				              <label>Username<span class="req">*</span></label>
				              <input class="form-control <?php echo $usernameError;?>" type="text" required autocomplete="off" name='username'/>
				            </div>

							<div class="field-wrap">
								<label>E-Mail Adresse<span class="req">*</span></label>
								<input class="form-control <?php echo $emailError;?>" type="email" required autocomplete="off" name='email' />
							</div>

							<div class="field-wrap">
								<label>Passwort<span class="req">*</span></label>
								<input class="form-control <?php echo $passwordError;?>" type="password" required autocomplete="off" name='password'/>
								<small id="passwordHelpBlock" class="form-text text-muted">
									Dein Passwort muss mind. 8 Zeichen lang sein sowie Zahlen, Groß- & Kleinbuchstaben enthalten.
								</small>
							</div>

							<div class="field-wrap">
								<label>Passwort wiederholen<span class="req">*</span></label>
								<input class="form-control <?php echo $passwordError;?>" type="password" required autocomplete="off" name='replay_password' />
								<small id="passwordHelpBlock" class="form-text text-muted">
									Dein Passwort muss mind. 8 Zeichen lang sein sowie Zahlen, Groß- & Kleinbuchstaben enthalten.
								</small>
							</div>

							<div class=field-wrap">
								<div class="custom-control custom-switch">
									<input type="checkbox" class="custom-control-input" id="customSwitch1" name="newsletter">
									<label class="custom-control-label" for="customSwitch1">Benachrichtigungen erhalten (z.B. Erinnerung für ungetippte anstehende Spiele)</label>
								</div>
							</div>

							<!--<div class="g-recaptcha" data-sitekey="6LfoeF0UAAAAADXEaDcHuBNgueik6VJYfiB8VGKz"></div>-->

				          	<button type="submit" class="button button-block" name="register" />Registrieren</button>
									<?php
										echo $disabled;
									?>
				        </form>
				      </div>
				    </div><!-- tab-content -->
				  </div>
				</div>
			</div>
    </div> <!-- class col-lg-10 mx-auto Ende -->
  </div>
</section>
