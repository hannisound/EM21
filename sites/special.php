<section id="special">
	<div class="container">
    	<div class="row">
        	<div class="col-lg-10 mx-auto text-center">
						<h2>Spezialtipps<br>
							<p class="lead">Hole dir nochmal richtig fette Punkte mit den Spezialtipps</p>
						</h2>
						<hr>
						<?php
							// Turniermeistertipp laden
							include 'sites/special/sp_meister.php';

							echo "<hr>";

							// Gruppensiegertipps odbc_longreadlen
							include 'sites/special/sp_group.php';

							echo "<hr>";

							// Endstationtipp laden
							include 'sites/special/sp_endstation.php';

							echo "<hr>";

							// Torschützenkönigtipp laden
							include 'sites/special/sp_torkoenig.php';
						?>
					</div>
			</div>
	</div>
</section>
