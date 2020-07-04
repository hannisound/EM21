<?php
require("../../includes/db.php");
require("../../includes/function.inc.php");

	$result2	= array();
	$sql 		= "SELECT sp.ID, sp.vorname, sp.name, sp.posi,  t.name AS team FROM `".prefix."_spieler` sp
    				INNER JOIN ".prefix."_team t ON (sp.team = t.id) WHERE
                    sp.vorname LIKE '%".$_GET['query']."%' OR
                    sp.name LIKE '%".$_GET['query']."%' OR
                    sp.posi LIKE '%".$_GET['query']."%' OR
                    t.name LIKE '%".$_GET['query']."%'";
	$result = $mysqli->query($sql);
	while( $suche = mysqli_fetch_array($result))
	{
		$erg 		= "".$suche['vorname']." ".$suche['name']." || Position: ".$suche['posi']." || Team: ".$suche['team']." ";
		array_push($result2, $erg);
	}
	$search = array('query' => $_GET['query'], 'suggestions' => $result2);
	echo json_encode($search);
?>
