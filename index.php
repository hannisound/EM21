<?php
        //include_once 'includes/ChatFunction.inc.php';
        ob_start();
        header("Content-Type: text/html; charset=utf-8");
        require_once 'includes/db.php';
        require_once 'includes/function.inc.php';
        sec_session_start();
        require_once 'includes/whitelist.php';
        // Head
        require_once 'sites/head.php';
  ?>

<!-- Head End -->

<!-- Navigation -->
  <?php
  // Prüfen wenn GET = Chat ist und die Function aufrufen um die Badge in der Nav zu aktualisieren für den Chat
  if($_GET['site'] == "chat") {
    saveLastChatID($_SESSION['user_id'], $mysqli);
  }
  require_once 'sites/nav.php'; ?>

<!-- Navigation End -->

<!-- Header -->

  <?php require_once 'sites/header.php'; ?>

<!-- Header End -->

<!-- Content -->

  <?php
        $erlaubteSeite = array('home', 'login', 'faq', 'impressum', 'contact', 'datenschutz', 'verify', 'forgot', 'error', 'reset');
        if (!in_array($site, $erlaubteSeite))
        {
          require("includes/check.php");
          require("sites/".$site.".php"); // Vorher Code einfügen zum überprüfen von home etc. für die Weitergabe der Informationen
        }
        else {
          include("sites/".$site.".php"); // Vorher Code einfügen zum überprüfen von home etc. für die Weitergabe der Informationen
        } ?>

<!-- Content End -->

<!-- Footer -->

  <?php require_once 'sites/footer.php'; ?>

<!-- Footer End -->
