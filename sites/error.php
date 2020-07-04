<section id="anmeldung">
	<div class="container">
    	<div class="row">
        	<div class="col-lg-10 mx-auto">
        		<?php
        			// echo nl2br(print_r($_SESSION,true));
        			echo "<div class='alert alert-danger text-center'>".$_SESSION['message']."</div>";
        			$_SESSION['message'] = "";
        		?>
        	</div>
        </div>
    </div>
</section>
