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
				background-color: rgb(192, 228, 192);
			}
			
			.participles {
				background-color: rgb(255, 180, 180);
			}
			
			.imperative {
				background-color: rgb(224, 224, 192);
			}
			
			.infinitive {
				background-color: rgb(224, 212, 192);
			}
			
			.skullxbones {
				background-image: url('/assets/SkullXBones.svg');
				background-size: contain;
			}
			
			.beta {
				background: repeating-linear-gradient(-45deg, #FF0000, #FFFFFF 10px, #465298 15px, #FF0000 10px);
			}
		</style>
	</head>
	<body>
		<?php
			if(isset($_GET['cfg'])) {
				$cfg = json_decode($_GET['cfg']);
			}
			$participleNotFoundTD = "<td";
			
			if($cfg->{'noParticipleMode'} == 0) {
				$participleNotFoundTD .= ">X";
			} elseif($cfg->{'noParticipleMode'} == 1) {
				$participleNotFoundTD .= " class=\"skullxbones\">";
			} else {
				$participleNotFoundTD .= ">&#x2620;";
			}
			$participleNotFoundTD .= "</td>";
			
			$essePre = array("sum", "es", "est", "sumus", "estis", "sunt");
			$esseImp = array("eram", "eras", "erat", "eramus", "eratis", "erant");
			$esseFut = array("ero", "eris", "erit", "erimus", "eritis", "erunt");
			
			$pp1 = preg_replace("/[^A-Za-z]/", '',strtolower($_GET['pp1']));
			$pp2 = preg_replace("/[^A-Za-z]/", '',strtolower($_GET['pp2']));
			$pp3 = preg_replace("/[^A-Za-z]/", '',strtolower($_GET['pp3']));
			$pp4 = preg_replace("/[^A-Za-z]/", '',strtolower($_GET['pp4']));
			
			if(strlen($pp4) == 0) {
				if(endsWith($pp1, "r")) {
					die("That's a typo or a deponent verb. Neither is supported.");
				} elseif(endsWith($pp2, "ri")) {
					die("That's a typo or a semi-deponent verb. Neither is supported.");
				}
			}
			
			if(endsWith($pp2, "are")) {
				$conj = 1;
			} else if(endsWith($pp2, "ere")) {
				if(endsWith($pp1, "eo")) {
					$conj = 2;
				} else if(endsWith($pp1, "io")) {
					$conj = 3.1;
				} else {
					$conj = 3;
				}
			} else if(endsWith($pp2, "ire")) {
				$conj = 4;
			}
			
			$supineStem = substr($pp4, 0, -2);
			$perfStem = substr($pp3, 0, -1);
			$presStem = substr($pp2, 0, -3);
			
			$presVowel = $conj == 2 ? 'e' : ($conj > 2 ? 'i' : 'a');
			
			switch($conj) {
			case 1: $presPartVowel = 'a'; break;
			case 2: $presPartVowel = 'e'; break;
			case 3: $presPartVowel = 'e'; break;
			case 3.1: $presPartVowel = 'ie'; break;
			case 4: $presPartVowel = 'ie'; break;
			}
			
			$imperfStem = ($conj > 3 ? substr($pp2, 0, -3) . "ie" : substr($pp2, 0, -2)) . "ba";
			
			$futStem = $conj <= 2 ? substr($pp2, 0, -2) : substr($pp2, 0, -3) . ($conj == 3 ? "" : "i");
			
			$fupEnds = array("ero", "eris", "erit", "erimus", "eritis", "erint");
			$perEnds = array("i", "isti", "it", "imus", "istis", "erunt");
			$pluEnds = array("eram", "eras", "erat", "eramus", "eratis", "erant");
			
			$IApreEnds = array("s", "t", "mus", "tis", $conj <= 2 ? "nt" : "unt");
			$IAfutEnds = $conj < 3 ? array("bo", "bis", "bit", "bimus", "bitis", "bunt") : array("am", "es", "et", "emus", "etis", "ent");
			$IAimpEnds = array("m", "s", "t", "mus", "tis", "nt");
			
			
			$presStemVowel = $presStem . $presVowel;
			$IPpreEnds = array("ris", "tur", "mur", "mini", "ntur");
			$IPimpEnds = array("r", "ris", "tur", "mur", "mini", "ntur");
			$IPfutEnds = $conj <= 2 ? array("bor", "beris", "bitur", "bimur", "bimini", "buntur") : array("ar", "eris", "etur", "emur", "emini", "entur");
			
			$infinIApres = $pp2;
			$infinIPpres = ($conj == 3 || $conj == 3.1) ? substr($pp2, 0, -3) . 'i' : substr($pp2, 0, -1) . 'i';
			$infinIAperf = substr($pp3, 0, -1) . 'isse';
			if($cfg->{'participlePrinciplePartMode'} == 0) {
				$infinIPperf = $supineStem . "us, " . $supineStem . "a, " . $supineStem . "um esse";
				$infinIAfut = $supineStem . "urus, " . $supineStem . "ura, " . $supineStem . "urum esse";
			} else {
				$infinIPperf = $supineStem . "us, -a, -um esse";
				$infinIAfut = $supineStem . "urus, -a, -um esse";
			}
			$infinIPfut = $pp4 . ' iri';
			
			// Finalisation
			// I
			//  A
			$IApre = array($pp1); foreach($IApreEnds as $end) {array_push($IApre, $presStem . (($end == "unt" && $conj == 3) ? "" : $presVowel) . $end);}
			$IAimp = array(); foreach($IAimpEnds as $end) {array_push($IAimp, $imperfStem . $end);}
			$IAfut = array(); foreach($IAfutEnds as $end) {array_push($IAfut, $futStem . $end);}
			$IAper = array(); foreach($perEnds as $end) {array_push($IAper, $perfStem . $end);}
			$IAplu = array(); foreach($pluEnds as $end) {array_push($IAplu, $perfStem . $end);}
			$IAfup = array(); foreach($fupEnds as $end) {array_push($IAfup, $perfStem . $end);}
			//  P
			$IPpre = array($pp1 . "r"); foreach($IPpreEnds as $end) {array_push($IPpre, $presStemVowel . $end);}
			$IPimp = array(); foreach($IPimpEnds as $end) {array_push($IPimp, $imperfStem . $end);}
			$IPfut = array(); foreach($IPfutEnds as $end) {array_push($IPfut, $futStem . $end);}
			$IPper = array(); for($i = 0; $i < 6; $i++) {array_push($IPper, $supineStem . ($i < 3 ? "us " : "i ") . $essePre[$i]);}
			$IPplu = array(); for($i = 0; $i < 6; $i++) {array_push($IPplu, $supineStem . ($i < 3 ? "us " : "i ") . $esseImp[$i]);}
			$IPfup = array(); for($i = 0; $i < 6; $i++) {array_push($IPfup, $supineStem . ($i < 3 ? "us " : "i ") . $esseFut[$i]);}
			// S
			//  A
			$SApre = array();
			$SAimp = array();
			$SAfut = array();
			$SAper = array();
			$SAplu = array();
			$SAfup = array();
			//  P
			$SPpre = array();
			$SPimp = array();
			$SPfut = array();
			$SPper = array();
			$SPplu = array();
			$SPfup = array();
		?>
		<span>Conjugation: <?php echo $conj == 3.1 ? "3-io" : $conj;?></span>
		<hr>
		<table>
			<tbody>
				<tr class="indicative">
					<th rowspan="2" colspan="2">indicative</th>
					<th colspan="3">singular</th>
					<th colspan="3">plural</th>
				</tr>
				<tr class="indicative">
					<td>first</td>
					<td>second</td>
					<td>third</td>
					<td>first</td>
					<td>second</td>
					<td>third</td>
				</tr>
				<tr>
					<th rowspan="6" class="indicative">active</th>
					<td class="indicative">present</td>
					<?php foreach($IApre as $form) {echo "<td>" . $form . "</td>";}?>
				</tr>
				<tr>
					<td class="indicative">imperfect</td>
					<?php foreach($IAimp as $form) {echo "<td>" . $form . "</td>";}?>
				</tr>
				<tr>
					<td class="indicative">future</td>
					<?php foreach($IAfut as $form) {echo "<td>" . $form . "</td>";}?>
				</tr>
				<tr>
					<td class="indicative">perfect</td>
					<?php foreach($IAper as $form) {echo "<td>" . $form . "</td>";}?>
				</tr>
				<tr>
					<td class="indicative">pluperfect</td>
					<?php foreach($IAplu as $form) {echo "<td>" . $form . "</td>";}?>
				</tr>
				<tr>
					<td class="indicative">future perfect</td>
					<?php foreach($IAfup as $form) {echo "<td>" . $form . "</td>";}?>
				</tr>
				
				<tr>
					<th rowspan="6" class="indicative">passive</th>
					<td class="indicative">present</td>
					<?php foreach($IPpre as $form) {echo "<td>" . $form . "</td>";}?>
				</tr>
				<tr>
					<td class="indicative">imperfect</td>
					<?php foreach($IPimp as $form) {echo "<td>" . $form . "</td>";}?>
				</tr>
				<tr>
					<td class="indicative">future</td>
					<?php foreach($IPfut as $form) {echo "<td>" . $form . "</td>";}?>
				</tr>
				<tr>
					<td class="indicative">past perfect</td>
					<?php foreach($IPper as $form) {echo "<td>" . $form . "</td>";}?>
				</tr>
				<tr>
					<td class="indicative">pluperfect</td>
					<?php foreach($IPplu as $form) {echo "<td>" . $form . "</td>";}?>
				</tr>
				<tr>
					<td class="indicative">future perfect</td>
					<?php foreach($IPfup as $form) {echo "<td>" . $form . "</td>";}?>
				</tr>
			</tbody>
		</table>
		<!--
		// INDICATIVE
		// SUBJUNCTIVE
		-->
		<!--
		<table>
			<tbody>
				<tr class="subjunctive">
					<th rowspan="2" colspan="2">subjunctive</th>
					<th colspan="3">singular</th>
					<th colspan="3">plural</th>
				</tr>
				<tr class="subjunctive">
					<td>first</td>
					<td>second</td>
					<td>third</td>
					<td>first</td>
					<td>second</td>
					<td>third</td>
				</tr>
				<tr>
					<th rowspan="6" class="subjunctive">active</th>
					<td class="subjunctive">present</td>
					<?php foreach($SApre as $form) {echo "<td>" . $form . "</td>";}?>
				</tr>
				<tr>
					<td class="subjunctive">imperfect</td>
					<?php foreach($SAimp as $form) {echo "<td>" . $form . "</td>";}?>
				</tr>
				<tr>
					<td class="subjunctive">future</td>
					<?php foreach($SAfut as $form) {echo "<td>" . $form . "</td>";}?>
				</tr>
				<tr>
					<td class="subjunctive">perfect</td>
					<?php foreach($SAper as $form) {echo "<td>" . $form . "</td>";}?>
				</tr>
				<tr>
					<td class="subjunctive">pluperfect</td>
					<?php foreach($SAplu as $form) {echo "<td>" . $form . "</td>";}?>
				</tr>
				<tr>
					<td class="subjunctive">future perfect</td>
					<?php foreach($SAfup as $form) {echo "<td>" . $form . "</td>";}?>
				</tr>
				
				<tr>
					<th rowspan="6" class="subjunctive">passive</th>
					<td class="subjunctive">present</td>
					<?php foreach($SPpre as $form) {echo "<td>" . $form . "</td>";}?>
				</tr>
				<tr>
					<td class="subjunctive">imperfect</td>
					<?php foreach($SPimp as $form) {echo "<td>" . $form . "</td>";}?>
				</tr>
				<tr>
					<td class="subjunctive">future</td>
					<?php foreach($SPfut as $form) {echo "<td>" . $form . "</td>";}?>
				</tr>
				<tr>
					<td class="subjunctive">past perfect</td>
					<?php foreach($SPper as $form) {echo "<td>" . $form . "</td>";}?>
				</tr>
				<tr>
					<td class="subjunctive">pluperfect</td>
					<?php foreach($SPplu as $form) {echo "<td>" . $form . "</td>";}?>
				</tr>
				<tr>
					<td class="subjunctive">future perfect</td>
					<?php foreach($SPfup as $form) {echo "<td>" . $form . "</td>";}?>
				</tr>
			</tbody>
		</table>
		-->
		<table>
			<tbody>
				<tr class="participles">
					<th rowspan="2">participle</th>
					<th rowspan="2">active</th>
					<th rowspan="2">passive</th>
				</tr>
				<tr></tr>
				<tr>
					<th class="participles">present</th>
					<td><?php
						if($cfg->{'participlePrinciplePartMode'} == 0) {
							echo $presStem . $presPartVowel . "ns, " . $presStem . $presPartVowel . "ntis";
						} else {
							echo $presStem . $presPartVowel . "ns, -" . $presPartVowel . "ntis";
						}
					?></td>
					<?php echo $participleNotFoundTD; ?>
				</tr>
				<tr>
					<th class="participles">perfect</th>
					<?php echo $participleNotFoundTD; ?>
					<td><?php 
						if($cfg->{'participlePrinciplePartMode'} == 0) {
							echo $supineStem . "us, " . $supineStem . "a, " . $supineStem . "um";
						} else {
							echo $supineStem . "us, -a, -um";
						}
					?></td>
				</tr>
				<tr>
					<th class="participles">future</th>
					<td><?php 
						if($cfg->{'participlePrinciplePartMode'} == 0) {
							echo $supineStem . "urus, " . $supineStem . "ura, " . $supineStem . "urum";
						} else {
							echo $supineStem . "urus, -a, -um";
						}
					?></td>
					<?php echo $participleNotFoundTD; ?>
				</tr>
			</tbody>
		</table>
		<table>
			<tbody>
				<tr>
					<th rowspan="2" colspan="2" class="imperative">imperative</th>
					<th class="imperative">singular</th>
					<th class="imperative">plural</th>
				</tr>
				<tr></tr>
				<tr>
					<th rowspan="2" class="imperative">active</th>
					<th class="imperative">positive</th>
					<td><?php echo $presStem . ($conj == 1 ? 'a' : ($conj == 4 ? 'i' : 'e')); ?></td>
					<td><?php echo $presStem . ($conj == 1 ? 'a' : ($conj == 2 ? 'e' : 'i')) . 'te'; ?></td>
				</tr>
				<tr>
					<th class="imperative">negative</th>
					<td><?php echo "noli " . $infinIApres; ?></td>
					<td><?php echo "nolite " . $infinIApres; ?></td>
				</tr>
				<?php
				if(isset($cfg)) {
					if(isset($cfg->{'beta'})) {
						if($cfg->{'beta'} == 1) { ?>
				<tr class="beta">
					<th rowspan="2">passive</th>
					<th>positive</th>
					<td>?</td>
					<td>?</td>
				</tr>
				<tr class="beta">
					<th>negative</th>
					<td><?php echo "noli " . $infinIPpres; ?>?</td>
					<td><?php echo "nolite " . $infinIPpres; ?>?</td>
				</tr>
				<?php }}} ?>
			</tbody>
		</table>
		<table>
			<tbody>
				<tr>
					<th rowspan="2" class="infinitive">infinitive</th>
					<th rowspan="2" class="infinitive">active</th>
					<th rowspan="2" class="infinitive">passive</th>
				</tr>
				<tr></tr>
				<tr>
					<th class="infinitive">present</th>
					<td><?php echo $infinIApres; ?></td>
					<td><?php echo $infinIPpres; ?></td>
				</tr>
				<tr>
					<th class="infinitive">perfect</th>
					<td><?php echo $infinIAperf; ?></td>
					<td><?php echo $infinIPperf; ?></td>
				</tr>
				<tr>
					<th class="infinitive">future</th>
					<td><?php echo $infinIAfut; ?></td>
					<td><?php echo $infinIPfut; ?></td>
				</tr>
			</tbody>
		</table>
	</body>
</html>
<?php
	} else {
	header('Content-Type: text/plain');
?>
Conjugation: <?php echo $conj == 3.1 ? "3-io" : $conj; ?>
________________________________________________________________________________
<?php
	}
?>