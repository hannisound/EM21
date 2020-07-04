<section id="chat_beta">
	<div class="container">
	  <div class="row">
	    <div class="col-lg-10 mx-auto">
        <?php
          require_once 'includes/shoutbox/includes/config.php';
          require_once 'includes/shoutbox/includes/shoutbox.class.php';

          $shoutbox = new Shoutbox();
          $data = $shoutbox->load();

          require_once 'includes/shoutbox/templates/shoutbox.php';
        ?>

      </div>
    </div>
  </div>
</section
