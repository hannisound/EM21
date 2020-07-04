<section id="adm_faq">
	<div class="container">
		<div class="row">
			<div class="col-lg-10 mx-auto">
        <?php
    	if (empty($_SESSION['status']) AND $_SESSION['status'] <= 2)
    	{
    		echo "<p class='error'>Sie haben keine Berechtigung diese Seite zu öffnen</p>";
    	}
    	else
    	{
	    ?>
	    	<div class="row">
		    	<div class="col-xs-6 .col-sm-6">
		    		<?php include ("faq/new_faq.html"); ?>
		    	</div>
		    	<div class="col-xs-6 .col-sm-6">
		            <?php include ("faq/edit_faq.html"); ?>
		        </div>
	    	</div>
	        <ul id="faq_sort">
	   		<?php
	  		// Fragen & Antworten aus der Datenbank auslesen
			$sql = "SELECT ID, frage, antwort FROM ".prefix."_faq ORDER BY sort_id ASC";
			$result	= $mysqli->query($sql);
			while($faq = mysqli_fetch_array($result))
			{
				echo "	<li class='faq_edit' id='faq_".$faq['ID']."'>
				<a style='position:relative; right: -95%;' id='faq_edit' name='".$faq['ID']."' href='#' class='btn btn-green btn-sm'><i class='fa fa-pencil'></i></a>
				<a style='position:relative; right: -95%;' id='faq_delete' name='".$faq['ID']."' href='#'class='btn btn-green btn-sm'><i class='fa fa-times'></i></a>
				<b style='position:relative; right: 40px;'><span id='faq_frage_".$faq['ID']."'>".$faq['frage']."</span></b><br /><br />
				<span id='faq_antwort_".$faq['ID']."'>".nl2br($faq['antwort'])."</span>
				</li>
				";
			}
		}
		?>
		</ul>
      </div>
    </div>
  </div>
</section>
