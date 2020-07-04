<section id="anmeldung">
	<div class="container">
    	<div class="row">
        	<div class="col-lg-10 mx-auto">
            <?php
            // Make sure email and hash variables aren't empty
            if (isset($_POST['pw_reset'])) {
              require 'includes/pwReset.inc.php';
            }
            elseif( isset($_GET['id']) && !empty($_GET['id']) AND isset($_GET['hash']) && !empty($_GET['hash']) )
            {
                $id = $mysqli->escape_string($_GET['id']);
                $hash = $mysqli->escape_string($_GET['hash']);

                // Make sure user email with matching hash exist
                $result = $mysqli->query("SELECT * FROM ".prefix."_users WHERE id='$id' AND hash='$hash'");

                if ( $result->num_rows == 0 )
                {
                    $_SESSION['message'] = "Sie haben eine ungültige URL für das Zurücksetzen des Passworts eingegeben!";
                    echo "<div class='alert alert-danger text-center'>".$_SESSION['message']."</div>";
                }
                else {
                  ?>
                  <div class="form">

                        <h1>Wählen Sie Ihr neues Passwort</h1>

                        <form action="index.php?site=reset" method="post">

                        <div class="field-wrap">
                          <label>
                            Neues Passwort<span class="req">*</span>
                          </label>
                          <input type="password" required name="newpassword" autocomplete="off"/>
                        </div>

                        <div class="field-wrap">
                          <label>
                            Wiederholen Sie Ihr neues Passwort Passwort<span class="req">*</span>
                          </label>
                          <input type="password" required name="confirmpassword" autocomplete="off"/>
                        </div>

                        <!-- This input field is needed, to get the email of the user -->
                        <input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">
                        <input type="hidden" name="hash" value="<?php echo $_GET['hash'] ?>">

                        <button class="button button-block" name="pw_reset"/>Bestätigen</button>

                        </form>

                  </div>
                  <?php
                }
            }
            else {
                $_SESSION['message'] = "Sorry, die Überprüfung ist fehlgeschlagen, versuchen Sie es erneut!";
                echo "<div class='alert alert-danger text-center'>".$_SESSION['message']."</div>";
            }
            ?>
          </div>
        </div>
    </div>
</section>
