<br>
<div class="container-fluid">
	<div class="row justify-content-xl-center text-center">

	<!-- Neue Struktur mit DIV - Test -->
	<?php /*
	$sql = "SELECT ID, name FROM ".prefix."_grp LIMIT 8";
		$result = $mysqli->query($sql);
		while ($grp = mysqli_fetch_array($result)) {
			if ($grp['name'] == "A" OR $grp['name'] == "C" OR $grp['name'] == "E" OR $grp['name'] == "G") {
				echo "<div class='row'>
						<div class='col-12 col-lg-6'>
							<div class='row'>
								<div class='col-6 text-center'><strong>Gruppe ".$grp['name']."</strong></div>
							</div>";
				$ende = "";
			}
			else {
				echo "<div class='col-12 col-lg-6'>
						<div class='row'>
							<div class='text-center bg-warning'><strong>Gruppe ".$grp['name']."</strong></div>
						</div>";
				$ende = "</div>";
			}
			echo "<div class='row'>";
			echo "<div class='col'>Platz</div>";
			echo "<div class='col'>Team</div>";
			echo "<div class='col'>Sp.</div>";
			echo "<div class='col'>S/U/N</div>";
			echo "<div class='col'>Tore</div>";
			echo "<div class='col'>Diff</div>";
			echo "<div class='col'>Punkte</div>
					</div>";
			$place = 1;
			$sql2 = "SELECT name, grp, punkte, differenz, tore, sun, games FROM ".prefix."_team WHERE grp = ".$grp['ID']." ORDER BY `punkte` DESC, `differenz` DESC";
			$result2 = $mysqli->query($sql2);
			while ($team = mysqli_fetch_array($result2)) {
				echo "	<div class='row'>
						<div class='col'>$place.</div>
						<div class='col'>".flag($team['name'], 30, 1)." ".$team['name']."</div>
						<div class='col'>".$team['games']."</div>
						<div class='col'>".$team['sun']."</div>
						<div class='col'>".$team['tore']."</div>
						<div class='col'>".$team['differenz']."</div>
						<div class='col'>".$team['punkte']."</div></div>";
				$place++;
			}
			echo "</div>";
			echo $ende;
		}*/

	?>

	<!-- Alte Tabellenstruktur -->
	<?php
		$sql = "SELECT ID, name FROM ".prefix."_grp LIMIT 6";
		$result = $mysqli->query($sql);
		while ($grp = mysqli_fetch_array($result)) {
			if ($grp['name'] == "A" OR $grp['name'] == "C" OR $grp['name'] == "E") {
				echo "<div class='row'>
						<div class='col'>
							<div class='text-center'><strong>Gruppe ".$grp['name']."</strong></div>";
				$ende = "";
			}
			else {
				echo "<div class='col'>
						<div class='text-center'><strong>Gruppe ".$grp['name']."</strong></div>";
				$ende = "</div>";
			}
			echo "<table class='table table-striped table-sm text-center'>
					<tr>
						<th>Rang</th>
						<th colspan='2' class='text-center' width=150px>Team</th>
						<th>Sp.</th>
						<th>S/U/N</th>
						<th>Tore</th>
						<th>Diff.</th>
						<th>Pkt.</th>
					</tr>";
			$place = 1;
			$sql2 = "SELECT name, short_name, grp, punkte, differenz, tore, sun, games FROM ".prefix."_team WHERE grp = ".$grp['ID']." ORDER BY `punkte` DESC, `differenz` DESC";
			$result2 = $mysqli->query($sql2);
			while ($team = mysqli_fetch_array($result2)) {
				echo "	<tr>
							<td data-label='Platz'>$place.</td>
							<td data-label='Team'>".flag($team['name'], 30, 1)."</td>
							<td class='text-left'><span class='long_name'>".$team['name']."</span> <span class='short_name'>".$team['short_name']."</span></td>
							<td data-label='Sp.'>".$team['games']."</td>
							<td data-label='S/U/N'>".$team['sun']."</td>
							<td data-label='Tore'>".$team['tore']."</td>
							<td data-label='Diff.'>".$team['differenz']."</td>
							<td data-label='Pkt.'>".$team['punkte']."</td>
						</tr>";
				$place++;
			}
			echo "</table>";
			echo "</div>";
			echo $ende;
		}
	?>
	</div>
</div>
