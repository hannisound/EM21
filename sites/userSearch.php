<?php
require("../includes/db.php");
require("../includes/function.inc.php");

	$result2	= array();
	$sql 		= "SELECT username FROM ".prefix."_users WHERE active = '1' AND `username` LIKE '%".$_GET['query']."%'";
	$result = $mysqli->query($sql);
	while( $suche = mysqli_fetch_array($result))
	{
		$erg 		= "".html_entity_decode($suche['username'])."";
		array_push($result2, $erg);
	}
	$search = array('query' => $_GET['query'], 'suggestions' => $result2);
	echo json_encode($search);
?>
