<?php
if(!isset($_GET['username']) || !isset($_GET['message'])) exit;

require_once 'config.php';
require_once 'shoutbox.class.php';

$shoutbox = new Shoutbox();
$result = $shoutbox->save($_GET['username'], $_GET['message']);

echo json_encode($result);
exit;