<?php
require_once 'includes/config.php';
require_once 'includes/shoutbox.class.php';

$shoutbox = new Shoutbox();
$data = $shoutbox->load();

require_once 'templates/shoutbox.php';
?>
