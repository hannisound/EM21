<section id="anmeldung">
	<div class="container-md">
    	<div class="row">
        <div class="col-lg-12 mx-auto">
                <?php
                ?>
                <h2>Willkommen <?php echo html_entity_decode($_SESSION['username']);?>!</h2></br>

								<?php
									echo EmClock($mysqli);
									echo EndofWM($_SESSION['user_id'], $mysqli);
									echo nextGames($mysqli, $_SESSION['user_id']);

								?>
								<br />
                <div class="card-deck">
                  <div class="card">
                    <a class="image" href="index.php?site=<?php echo gftipps($mysqli); ?>" /><img class="card-img-top" src="images/features/carousel_tipps.png" alt="Stehts alle Tipps im Überblick behalten"></a>
                    <div class="card-body">
                      <h5 class="card-title">Tipps</h5>
                      <p class="card-text text-danger font-weight-bold"><?php open_group_tipps($_SESSION['user_id'], $mysqli); open_final_tipps($_SESSION['user_id'], $mysqli); ?></p>
                    </div>
                  </div>
                  <div class="card">
                    <a class="image" href="index.php?site=special" /><img class="card-img-top" src="images/features/carousel_special.png" alt="Spezialtipps"></a>
                    <div class="card-body">
                      <h5 class="card-title">Spezialtipps</h5>
                      <p class="card-text"><?php open_special_tipps($_SESSION['user_id'], $mysqli);?></p>
                      <!--<p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>-->
                    </div>
                  </div>
                  <div class="card">
                    <a class="image" href="index.php?site=chat" /><img class="card-img-top" src="images/features/carousel_chat.png" alt="WM Chat"></a>
                    <div class="card-body">
                      <h5 class="card-title">WM Chat</h5>
                      <p class="card-text">Hier kannst du mit den anderen Diskutieren, Meinungen austauschen oder einfach nur über Fußball unterhalten</p>
                    </div>
                  </div>
                </div><br>
                <div class="card-deck">
                  <div class="card">
                    <a class="image" href="index.php?site=points" /><img class="card-img-top" src="images/features/carousel_points.png" alt="Behalte deine Punkte stehts im Blick"></a>
                    <div class="card-body">
                      <h5 class="card-title">Deine Punkte</h5>
                      <p class="card-text">Habe stehts deine Konkurrenz und deine Punkte im Blick</p>
                    </div>
                  </div>
                  <div class="card">
                    <a class="image" href="index.php?site=points" /><img class="card-img-top" src="images/features/carousel_privat_group.png" alt="Private Gruppen erstellen"></a>
                    <div class="card-body">
                      <h5 class="card-title">Private Gruppen</h5>
                      <p class="card-text">Habe stehts deine Private Gruppen im Überblick</p>
                    </div>
                  </div>
                  <div class="card">
                    <a class="image" href="index.php?site=faq" /><img class="card-img-top" src="images/features/carousel_faq.png" alt="Du brauchst Hilfe, hier findest du Sie"></a>
                    <div class="card-body">
                      <h5 class="card-title">Hilfe</h5>
                      <p class="card-text">Hier kannst du mit den anderen Diskutieren, Meinungen austauschen oder einfach nur über Fußball unterhalten</p>
                    </div>
                  </div>
                </div>
        	</div>
        </div>
    </div>
</section>
