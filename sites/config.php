<section id="config">
	<div class="container">
    	<div class="row">
        	<div class="col-lg-10 mx-auto text-center">
				<h2>Einstellungen<br>
					<p class="lead">Ändere hier dein Profilbild, ändere deine E-Mail Adresse oder vergib ein neues Passort</p>
				</h2>
				<hr>
				<?php
				// Admincenter laden wenn Status korrekt ist
				if ($_SESSION['status'] == 3) {
					echo "<h2>Admincenter</h2>";
					echo "<center><div class=''>";
					echo "<a class='btn btn-green' role='button' href='index.php?site=adm_news'>News</a>";
					echo "<a class='btn btn-green' role='button' href='index.php?site=adm_faq_edit'>FAQ</a>";
					echo "<a class='btn btn-green' role='button' href='index.php?site=adm_create'>Spiele erstellen</a>";
					echo "<a class='btn btn-green' role='button' href='index.php?site=adm_user'>User bearbeiten</a>";
					echo "<a class='btn btn-green' role='button' href='index.php?site=adm_final'>Finalrunde bearbeiten</a>";
					echo "<a class='btn btn-green' role='button' href='index.php?site=adm_specials'>Specials eintragen</a>";
					echo "<a class='btn btn-green' role='button' href='index.php?site=adm_site_title'>Seitentitel</a>";
					echo "</div></center>";
					echo "<hr />";
				}
				
				// E-Mail Adresse ändern laden
				include 'sites/usr/usr_mail_edit.php';
				echo "<hr>";
				// Passwort ändern laden
				include 'sites/usr/usr_pw_edit.php';
				echo "<hr>";
				// Newsletter ändern laden
				include 'sites/usr/usr_newsletter.php';
				echo "<hr>";
				// Profilbild ändern laden
				include 'sites/usr/usr_profilpic.php';
				?>
			</div>
		</div>
	</div>
</section>
