<section id="faq">
	<div class="container">
    	<div class="row">
        	<div class="col-lg-10 mx-auto">
            <div id="accordion">
            <?php
            // Fragen & Antworten aus der Datenbank auslesen
            $sql = $mysqli->query("SELECT id, sort_id, frage, antwort FROM ".prefix."_faq ORDER BY sort_id ASC");
            while($faq = $sql->fetch_object())
            {
              echo "
              <div class='card'>
                <div class='card-header' id='heading".$faq->id."'>
                  <h5 class='mb-0 text-center'>
                    <button class='btn btn-link' data-toggle='collapse' data-target='#collapse".$faq->id."' aria-expanded='true' aria-controls='collapse".$faq->id."'>
                      ".$faq->frage."
                    </button>
                  </h5>
                </div>

                <div id='collapse".$faq->id."' class='collapse' aria-labelledby='heading".$faq->id."' data-parent='#accordion'>
                  <div class='card-body text-center'>
                    ".nl2br($faq->antwort)."
                  </div>
                </div>
              </div>";
            }
            ?>
            </div>
          </div>
        </div>
    </div>
</section>

<!-- <div class='column' align='left'>
    <div class='portlet'>
      <div class='portlet-header'></div>
      <div class='portlet-content'></div>
    </div>
  </div>";-->
