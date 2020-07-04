<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <title><?php echo $title; ?> | Tippspiel für die UEFA EM 2021</title>
    <meta name="author" content="Hannes Bittins">
    <link rel="apple-touch-icon" sizes="180x180" href="images/Icon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/Icon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/Icon/favicon-16x16.png">
    <link rel="manifest" href="images/Icon/site.webmanifest">
    <link rel="mask-icon" href="images/Icon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">



    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    
    <!-- Place your Fontawesome (Icons) kit's code here -->
    <script src="https://kit.fontawesome.com/ae307d7062.js" crossorigin="anonymous"></script>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">!-->
    <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">!-->

    <!-- Custom styles for this template -->
    <link href="css/scrolling-nav.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">

    <!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
    <link rel="stylesheet" href="css/jquery.fileupload.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

    <!-- Jquery UI für FAQ Design und Sortable Custom (ADM-Bereich)!-->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>

    <!-- Jquery Libarys für die Shoutbox !-->
    <?php if ($site == "chat") {
      echo '<script type="text/javascript" src="includes/shoutbox/js/libs/modernizr-2.0.6.min.js"></script>';
      echo '<script type="text/javascript" defer src="includes/shoutbox/js/plugins.js"></script>';
      echo '<script type="text/javascript" defer src="includes/shoutbox/js/script.js"></script>';
      echo '<link rel="stylesheet" href="includes/shoutbox/css/style.css">';

      echo '<!-- Scrollbar anpassen für Shoutbox!-->';
      echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simplebar@latest/dist/simplebar.css"/>';
      echo '<script src="https://cdn.jsdelivr.net/npm/simplebar@latest/dist/simplebar.min.js"></script>';
      //echo '<script defer src="js/jquery.custom-scrollbar.js"></script>';
      //echo '<link rel="stylesheet" href="css/jquery.custom-scrollbar.css">';
    }
    ?>

    <!-- Datetime Picker nur laden wenn die Seite adm-create gesetzt ist -->
    <?php if ($site == "adm_create") {
      echo "<!-- Jquery DateTime Picker für die Eintragung der Spiele !-->
              <link rel='stylesheet' type='text/css' href='css/jquery.datetimepicker.min.css'/>";
    }?>

    <!-- ReCapatcha v2 Google -->
    <script src='https://www.google.com/recaptcha/api.js'></script>

    <!-- Prüfen wenn Cookies verboten wurden -->
    <?php
      if (!isset($_COOKIE['cookieconsent_status']) OR $_COOKIE['cookieconsent_status'] === "dismiss") {
    ?>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script>
      var gaProperty = 'UA-44228421-4';
      var disableStr = 'ga-disable-' + gaProperty;
      if (document.cookie.indexOf(disableStr + '=true') > -1) {
          window[disableStr] = true;
      }
      function gaOptout() {
          document.cookie = disableStr + '=true; expires=Thu, 31 Dec 2099 23:59:59 UTC; path=/';
          window[disableStr] = true;
          alert('Das Tracking ist jetzt deaktiviert');
      }
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
              (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-44228421-4', 'auto');
      ga('set', 'anonymizeIp', true);
      ga('send', 'pageview');
    </script>
    <?php
      }
     ?>

    <link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet">

    <!-- Cookie Text -->
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.css" />
    <script src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.js"></script>
    <script>
      window.addEventListener("load", function(){
      window.cookieconsent.initialise({
        "palette": {
          "popup": {
            "background": "#000"
          },
          "button": {
            "background": "#f1d600"
          }
        },
        "type": "opt-out",
        "content": {
          "message": "Diese Website benutzt Cookies, um sicherzustellen, dass Sie die beste Erfahrung auf unserer Website genießen können. Wenn Sie dies nicht wollen, deaktivieren Sie bitte rechts diese Funktion.",
          "dismiss": "Verstanden",
          "deny": "Deaktivieren",
          "link": "Lesen Sie mehr",
          "href": "http://em21.bde-malygos.de/index.php?site=datenschutz"
        },

        onInitialise: function (status) {
          var type = this.options.type;
          var didConsent = this.hasConsented();
          if (type == 'opt-in' && didConsent) {
            // enable cookies
          }
          if (type == 'opt-out' && !didConsent) {
            // disable cookies
          }
        },

      onStatusChange: function(status, chosenBefore) {
        var type = this.options.type;
        var didConsent = this.hasConsented();
        if (type == 'opt-in' && didConsent) {
          // enable cookies
        }
        if (type == 'opt-out' && !didConsent) {
          // disable cookies
        }
      },

      onRevokeChoice: function() {
        var type = this.options.type;
        if (type == 'opt-in') {
          // disable cookies
        }
        if (type == 'opt-out') {
          // enable cookies
        }
      }
    });
  });
    </script>
  </head>
  <body id="page-top">
    <div id="wrapper">
