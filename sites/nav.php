    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
      <div class="container">
        <a class="navbar-brand js-scroll-trigger" href="#page-top"><img alt="Fifa World Cup Logo 2018" src="images/Logo/fifa_logo_klein.png">EM21 Tippspiel BDE-Malygos.de</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
            <?php
              if (isset($_SESSION['logged_in'])) {
            ?>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="index.php?site=start"><i class="fa fa-home"></i> Start</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="index.php?site=gtipps" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-pencil-square-o"></i>
                Tipps
              </a>
              <div class="dropdown-menu bg-dark" aria-labelledby="navbarDropdown">
                <a class="dropdown-item js-scroll-trigger text-secondary" href="index.php?site=gtipps">Gruppenphase</a>
                <a class="dropdown-item js-scroll-trigger text-secondary" href="index.php?site=ftipps">Finalrunden</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item js-scroll-trigger text-secondary" href="index.php?site=special">Spezialtipps</a>
              </div>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="index.php?site=points"><i class="fa fa-line-chart"></i> Punkte</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="index.php?site=chat"><i class="fa fa-comments"></i> Chat <?php echo newMessages($_SESSION['user_id'], $mysqli); ?></a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="index.php?site=faq"><i class="fa fa-question-circle"></i> Hilfe</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="index.php?site=config"><i class="fa fa-cog"></i> Einstellungen</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="index.php?site=contact"><i class="fa fa-envelope-o"></i> Kontakt</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="index.php?site=logout"><i class="fa fa-power-off"></i> Logout</a>
            </li>
            <?php
              }
              else
              {
                // Navi für ausgeloggte/ nicht authorizierte User
            ?>
                <li class="nav-item">
                  <a class="nav-link js-scroll-trigger" href="index.php?site=home"><i class="fa fa-home"></i> Home</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link js-scroll-trigger" href="index.php?site=login"><i class="fa fa-user"></i> Login / Registrierung</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link js-scroll-trigger" href="index.php?site=faq"><i class="fa fa-question-circle"></i> Fragen & Antwort</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link js-scroll-trigger" href="index.php?site=contact"><i class="fa fa-envelope-o"></i> Kontakt</a>
                </li>
            <?php
            }
            ?>
          </ul>
        </div>
      </div>
    </nav>
