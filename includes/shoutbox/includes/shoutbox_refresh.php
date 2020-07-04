<?php
require_once '../../function.inc.php';
sec_session_start();
require_once 'config.php';
require_once 'shoutbox.class.php';

if(!isset($_GET['lastEntry'])) exit;

$shoutbox = new Shoutbox();
$result = $shoutbox->getNewEntries($_GET['lastEntry']);

echo json_encode($result);
exit;
