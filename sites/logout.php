<section id="anmeldung">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
            <?php
        		if (resetUser())
        		{
        			echo "<div class='alert alert-success text-center' role='alert'><strong>Sie wurden Erfolgreich vom System abgemeldet! Auf Wiedersehen.<br>";
        			echo "Sie werden in kürze auf die Startseite weitergeleitet.</strong></div>";
        			echo "<meta http-equiv='refresh' content='4; URL=http:index.php?site=home'>";
        		}
        	?>
	       </div>
        </div>
    </div>
</section>
