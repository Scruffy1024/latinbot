<?php
	include 'strapis.php';
	if(!isset($_GET['textonly'])) {
?>
<!DOCTYPE html>
<html>
	<head>
		<title>LatinBot</title>
		<style type="text/css">
			th {
				font-weight: normal;
				padding: 5px;
			}
			
			table {
				background: rgb(240, 240, 240);
				margin-bottom: 5px;
			}
			
			td .indicative {
				border-color: rgb(0, 255, 0);
				border-width: 10px;
			}
			
			.indicative {
				background-color: rgb(180, 180, 255);
			}
			
			.subjunctive {
				background-color: rgb(180, 255, 180);
			}
			
			.participles {
				background-color: rgb(255, 180, 180);
			}
			
			.nonfinite {
				background-color: rgb(224, 224, 192);
			}
		</style>
	</head>
	<body>
		<?php
			$pp1 = preg_replace("/[^a-z]/", '',strtolower($_GET['pp1']));
			$pp2 = preg_replace("/[^a-z]/", '',strtolower($_GET['pp2']));
			$pp3 = preg_replace("/[^a-z]/", '',strtolower($_GET['pp3']));
			
			$decl = (endsWith($pp2, 'a') && strlen($pp3) != 0 ? '1/2' : '3');
			
			if($decl == '1/2') {
				$Pstem = substr($pp3, 0, -2);
				$Pnom = array($pp1, $pp2, $pp3, $Pstem . 'i', $Pstem . 'ae', $Pstem . 'a');
				$Pacc = array('um', 'am', 'um', 'os', 'as', 'a');
				$Pgen = array('i', 'ae', 'i', 'orum', 'arum', 'orum');
				$Pdat = array('o', 'ae', 'o', 'is', 'is', 'is');
				$Pabl = array('o', 'a', 'o', 'is', 'is', 'is');
				$Pvoc = array($Pstem . 'e', $pp2, $pp3, $Pstem . 'i', $Pstem . 'ae', $Pstem . 'a');
			} else {
				if(strlen($pp3) == 0) {
					if(endsWith($pp2, 'e')) {
						// 2-part def
						$Pstem = substr($pp2, 0, -1);
						$Pnom = array($pp1, $pp1, $pp2, $Pstem . 'es', $Pstem . 'es', $Pstem . 'ia');
						$Pacc = array($Pstem . 'em', $Pstem . 'em', $pp2, $Pstem . 'es', $Pstem . 'es', $Pstem . 'ia');
						// Pgen, Pdat, and Pabl, are constant, no matter the def. style.
						$Pvoc = $Pnom;
					} else {
						// 1-part def*
						// That's a bad name for it as we get the genitive as well, instead of the neuter.
						$Pstem = substr($pp2, 0, -2);
						$Pnom = array($pp1, $pp1, $pp1, $Pstem . 'es', $Pstem . 'es', $Pstem . 'ia');
						$Pacc = array($Pstem . 'em', $Pstem . 'em', $pp1, $Pstem . 'es', $Pstem . 'es', $Pstem . 'ia');
						// Pgen, Pdat, and Pabl, are constant, no matter the def. style.
						$Pvoc = $Pnom;
					}
				} else {
					// 3-part def
					$Pstem = substr($pp2, 0, -2);
					$Pnom = array($pp1, $pp2, $pp3, $Pstem . 'es', $Pstem . 'es', $Pstem . 'ia');
					$Pacc = array($Pstem . 'em', $Pstem . 'em', $pp3, $Pstem . 'es', $Pstem . 'es', $Pstem . 'ia');
					// Pgen, Pdat, and Pabl, are constant, no matter the def. style.
					$Pvoc  = $Pnom;
				}
				
				$Pgen = array('is', 'is', 'is', 'ium', 'ium', 'ium');
				$Pdat = array('i', 'i', 'i', 'ibus', 'ibus', 'ibus');
				$Pabl = array('i', 'i', 'i', 'ibus', 'ibus', 'ibus');
			}
			
			$Cstem = $Pstem . 'i';
			$Cnom = array('or', 'or', 'us', 'ores', 'ores', 'ora');
			$Cacc = array('orem', 'orem', 'us', 'ores', 'ores', 'ora');
			$Cgen = array('oris', 'oris', 'oris', 'orum', 'orum', 'orum');
			$Cdat = array('ori', 'ori', 'ori', 'oribus', 'oribus', 'oribus');
			$Cabl = array('ore', 'ore', 'ore', 'oribus', 'oribus', 'oribus');
			$Cvoc = array('or', 'or', 'us', 'ores', 'ores', 'ora');
			
			if(endsWith($pp1, 'er')) {
				$Sstem = $pp1 . 'rim';
			} else if(endsWith($Pstem, 'l')) {
				$Sstem = $Pstem . 'lim';
			} else {
				$Sstem = substr($pp2, 0, -2) . 'issim';
			}
			
			$Snom = array('us', 'a', 'um', 'i', 'ae', 'a');
			$Sacc = array('um', 'am', 'um', 'os', 'as', 'a');
			$Sgen = array('i', 'ae', 'i', 'orum', 'arum', 'orum');
			$Sdat = array('o', 'ae', 'o', 'is', 'is', 'is');
			$Sabl = array('o', 'a', 'o', 'is', 'is', 'is');
			$Svoc = array('e', 'a', 'um', 'i', 'ae', 'a');
		?>
		<span>Declension: <?php echo $decl; ?></span>
		<hr>
		<table>
			<tbody>
				<tr class="indicative">
					<th rowspan="2" colspan="2">adjective</th>
					<th colspan="3">singular</th>
					<th colspan="3">plural</th>
				</tr>
				<tr class="indicative" style="text-align: center;">
					<td>M</td>
					<td>F</td>
					<td>N</td>
					<td>M</td>
					<td>F</td>
					<td>N</td>
				</tr>
				<tr>
					<th rowspan="6" class="indicative">positive</th>
					<td class="indicative">nominative</td>
					<?php foreach($Pnom as $form) {echo "<td>" . $form . "</td>";} ?>
				</tr>
				<tr>
					<td class="indicative">accusative</td>
					<?php
					if($decl == '3') {
						foreach($Pacc as $form) {
							echo "<td>" . $form . "</td>";
						}
					} else {
						foreach($Pacc as $ending) {
							echo "<td>" . $Pstem . $ending . "</td>";
						}
					}
					?>
				</tr>
				<tr>
					<td class="indicative">genitive</td>
					<?php foreach($Pgen as $ending) {echo "<td>" . $Pstem . $ending . "</td>";} ?>
				</tr>
				<tr>
					<td class="indicative">dative</td>
					<?php foreach($Pdat as $ending) {echo "<td>" . $Pstem . $ending . "</td>";} ?>
				</tr>
				<tr>
					<td class="indicative">ablative</td>
					<?php foreach($Pabl as $ending) {echo "<td>" . $Pstem . $ending . "</td>";} ?>
				</tr>
				<tr>
					<td class="indicative">vocative</td>
					<?php foreach($Pvoc as $form) {echo "<td>" . $form . "</td>";} ?>
				</tr>
				<!--
				// POSITIVE
				// COMPARATIVE
				-->
				<tr>
					<th rowspan="6" class="indicative">comparative</th>
					<td class="indicative">nominative</td>
					<?php foreach($Cnom as $ending) {echo "<td>" . $Cstem . $ending . "</td>";} ?>
				</tr>
				<tr>
					<td class="indicative">accusative</td>
					<?php foreach($Cacc as $ending) {echo "<td>" . $Cstem . $ending . "</td>";} ?>
				</tr>
				<tr>
					<td class="indicative">genitive</td>
					<?php foreach($Cgen as $ending) {echo "<td>" . $Cstem . $ending . "</td>";} ?>
				</tr>
				<tr>
					<td class="indicative">dative</td>
					<?php foreach($Cdat as $ending) {echo "<td>" . $Cstem . $ending . "</td>";} ?>
				</tr>
				<tr>
					<td class="indicative">ablative</td>
					<?php foreach($Cabl as $ending) {echo "<td>" . $Cstem . $ending . "</td>";} ?>
				</tr>
				<tr>
					<td class="indicative">vocative</td>
					<?php foreach($Cvoc as $ending) {echo "<td>" . $Cstem . $ending . "</td>";} ?>
				</tr>
				<!--
				// COMPARATIVE
				// SUPERLATIVE
				-->
				<tr>
					<th rowspan="6" class="indicative">superlative</th>
					<td class="indicative">nominative</td>
					<?php foreach($Snom as $ending) {echo "<td>" . $Sstem . $ending . "</td>";} ?>
				</tr>
				<tr>
					<td class="indicative">accusative</td>
					<?php foreach($Sacc as $ending) {echo "<td>" . $Sstem . $ending . "</td>";} ?>
				</tr>
				<tr>
					<td class="indicative">genitive</td>
					<?php foreach($Sgen as $ending) {echo "<td>" . $Sstem . $ending . "</td>";} ?>
				</tr>
				<tr>
					<td class="indicative">dative</td>
					<?php foreach($Sdat as $ending) {echo "<td>" . $Sstem . $ending . "</td>";} ?>
				</tr>
				<tr>
					<td class="indicative">ablative</td>
					<?php foreach($Sabl as $ending) {echo "<td>" . $Sstem . $ending . "</td>";} ?>
				</tr>
				<tr>
					<td class="indicative">vocative</td>
					<?php foreach($Svoc as $ending) {echo "<td>" . $Sstem . $ending . "</td>";} ?>
				</tr>
			</tbody>
		</table>
	</body>
</html>
<?php
	} else {
	header('Content-Type: text/plain');
?>
Declension: <?php echo $decl; ?>
________________________________________________________________________________
<?php
	}
?>
