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
			
			$esseIAPre = array("sum", "es", "est", "sumus", "estis", "sunt");
			$esseIAImp = array("eram", "eras", "erat", "eramus", "eratis", "erant");
			$esseIAFut = array("ero", "eris", "erit", "erimus", "eritis", "erunt");
			
			$esseSAPre = array("sim", "sis", "sit", "simus", "sitis", "sint");
			$esseSAImp = array("essem", "esses", "esset", "essemus", "essetis", "essent");
			
			$pp1 = preg_replace("/[^A-Za-z]/", '',strtolower($_GET['pp1']));
			$pp2 = preg_replace("/[^A-Za-z]/", '',strtolower($_GET['pp2']));
			$pp3 = preg_replace("/[^A-Za-z ]/", '',strtolower($_GET['pp3']));
			$pp4 = preg_replace("/[^A-Za-z]/", '',strtolower($_GET['pp4']));
			
			$deponency = 0;
			if(endsWith($pp3, " sum")) {
				if(endsWith($pp1, "r")) {
					$deponency = 1;
					//die("That's a typo or a deponent verb. Neither is supported.");
				} else {
					$deponency = 0.5;
					die("That's a typo or a semi-deponent verb. Neither is supported.");
				}
			}
			
			if($deponency == 0) {
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
			} else {
				// This is susceptible to verbaror, verbari (3rd conj, marked as 1st)
				if(endsWith($pp2, "ari")) {
					$conj = 1;
				} else if(endsWith($pp2, "eri")) {
					$conj = 2;
				} else if(endsWith($pp2, "iri")) {
					$conj = 4;
				} else {
					$conj = 3;
				}
			}
			
			if($deponency == 0) {
				$supineStem = substr($pp4, 0, -2);
			} else {
				$supineStem = substr($pp3, 0, -6);
			}
			
			$perfStem = substr($pp3, 0, -1);
			$presStem = substr($pp2, 0, -3);
			
			$IpresVowel = $conj == 2 ? 'e' : ($conj > 2 ? 'i' : 'a');
			switch($conj) {
				case 1: $SpresVowel = 'e'; break;
				case 2: $SpresVowel = 'ea'; break;
				case 3: $SpresVowel = 'a'; break;
				default: $SpresVowel = 'ia'; break;
			}
			
			$IpresStemVowel = $presStem . $IpresVowel;
			$SpresStemVowel = $presStem . $SpresVowel;
			
			$SAimpVowel = substr($pp2, -3, 1);
			
			switch($conj) {
			case 1: $presPartVowel = 'a'; break;
			case 2: $presPartVowel = 'e'; break;
			case 3: $presPartVowel = 'e'; break;
			case 3.1: $presPartVowel = 'ie'; break;
			case 4: $presPartVowel = 'ie'; break;
			}
			
			$imperfStem = ($conj > 3 ? substr($pp2, 0, -3) . "ie" : substr($pp2, 0, -2)) . "ba";
			$futStem = $conj <= 2 ? substr($pp2, 0, -2) : substr($pp2, 0, -3) . ($conj == 3 ? "" : "i");
			
			$passiveEnds = array("r", "ris", "tur", "mur", "mini", "ntur");
			
			$fupEnds = array("ero", "eris", "erit", "erimus", "eritis", "erint");
			$perEnds = array("i", "isti", "it", "imus", "istis", "erunt");
			$pluEnds = array("eram", "eras", "erat", "eramus", "eratis", "erant");
			
			$IApreEnds = array("s", "t", "mus", "tis", $conj <= 2 ? "nt" : "unt");
			$IAfutEnds = $conj < 3 ? array("bo", "bis", "bit", "bimus", "bitis", "bunt") : array("am", "es", "et", "emus", "etis", "ent");
			$IAimpEnds = array("m", "s", "t", "mus", "tis", "nt");
			
			$IPpreEnds = array("ris", "tur", "mur", "mini", "ntur");
			$IPimpEnds = $passiveEnds;
			$IPfutEnds = $conj <= 2 ? array("bor", "beris", "bitur", "bimur", "bimini", "buntur") : array("ar", "eris", "etur", "emur", "emini", "entur");
			
			$subjuntiveEnds = array("m", "s", "t", "mus", "tis", "nt");
			$SApreEnds = $subjuntiveEnds;
			$SAimpEnds = $subjuntiveEnds;
			$SAperEnds = array("erim", "eris", "erit", "erimus", "eritis", "erint");
			$SApluEnds = $subjuntiveEnds;
			
			$SPpreEnds = $passiveEnds;
			$SPimpEnds = $passiveEnds;
			
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
			$infinIPfut = $supineStem . 'um iri';
			
			// Finalisation - Indicative & Subjunctive
			// I
			//  A
			$IApre = array($pp1); foreach($IApreEnds as $end) {array_push($IApre, $presStem . (($end == "unt" && $conj == 3) ? "" : $IpresVowel) . $end);}
			$IAimp = array(); foreach($IAimpEnds as $end) {array_push($IAimp, $imperfStem . $end);}
			$IAfut = array(); foreach($IAfutEnds as $end) {array_push($IAfut, $futStem . $end);}
			$IAper = array(); foreach($perEnds as $end) {array_push($IAper, $perfStem . $end);}
			$IAplu = array(); foreach($pluEnds as $end) {array_push($IAplu, $perfStem . $end);}
			$IAfup = array(); foreach($fupEnds as $end) {array_push($IAfup, $perfStem . $end);}
			//  P
			$IPpre = array($pp1 . ($deponency == 1 ? "" : "r")); foreach($IPpreEnds as $end) {array_push($IPpre, $IpresStemVowel . $end);}
			$IPimp = array(); foreach($IPimpEnds as $end) {array_push($IPimp, $imperfStem . $end);}
			$IPfut = array(); foreach($IPfutEnds as $end) {array_push($IPfut, $futStem . $end);}
			$IPper = array(); for($i = 0; $i < 6; $i++) {array_push($IPper, $supineStem . ($i < 3 ? "us " : "i ") . $esseIAPre[$i]);}
			$IPplu = array(); for($i = 0; $i < 6; $i++) {array_push($IPplu, $supineStem . ($i < 3 ? "us " : "i ") . $esseIAImp[$i]);}
			$IPfup = array(); for($i = 0; $i < 6; $i++) {array_push($IPfup, $supineStem . ($i < 3 ? "us " : "i ") . $esseIAFut[$i]);}
			// S
			//  A
			$SApre = array(); foreach($SApreEnds as $end) {array_push($SApre, $SpresStemVowel . $end);}
			$SAimp = array(); foreach($SAimpEnds as $end) {array_push($SAimp, $infinIApres . $end);}
			$SAper = array(); foreach($SAperEnds as $end) {array_push($SAper, $perfStem . $end);}
			$SAplu = array(); foreach($SApluEnds as $end) {array_push($SAplu, $infinIAperf . $end);}
			//  P
			$SPpre = array(); foreach($SPpreEnds as $end) {array_push($SPpre, $SpresStemVowel . $end);}
			$SPimp = array(); foreach($SPimpEnds as $end) {array_push($SPimp, $presStem . $SAimpVowel . "re" . $end);}
			$SPper = array(); for($i = 0; $i < 6; $i++) {array_push($SPper, $supineStem . ($i < 3 ? "us " : "i ") . $esseSAPre[$i]);}
			$SPplu = array(); for($i = 0; $i < 6; $i++) {array_push($SPplu, $supineStem . ($i < 3 ? "us " : "i ") . $esseSAImp[$i]);}
			
			if($deponency == 1) {
				$IApre = $IPpre;
				$IAimp = $IPimp;
				$IAfut = $IPfut;
				$IAper = $IPper;
				$IAplu = $IPplu;
				$IAfup = $IPfup;
				
				$SApre = $SPpre;
				$SAimp = $SPimp;
				$SAfut = $SPfut;
				$SAper = $SPper;
				$SAplu = $SPplu;
				$SAfup = $SPfup;
			}
			
			// Finalisation - Other Moods
			// Im
			//  Pre
			//   A
			//    +
			$ImPreAPos1 = $presStem . ($conj == 1 ? 'a' : ($conj == 4 ? 'i' : 'e'));
			$ImPreAPos2 = $presStem . ($conj == 1 ? 'a' : ($conj == 2 ? 'e' : 'i')) . 'te';
			//    -
			$ImPreANeg1 = "noli " . $infinIApres;
			$ImPreANeg2 = "nolite " . $infinIApres;
			//   P
			//    +
			$ImPrePPos1 = substr($infinIApres, 0, -1) . 'e';
			$ImPrePPos2 = $IpresStemVowel . "mini";
			//    -
			$ImPrePNeg1 = "noli " . $infinIPpres;
			$ImPrePNeg2 = "nolite " . $infinIPpres;
			
			if($deponency == 1) {
				$ImPreAPos1 = $ImPrePPos1;
				$ImPreAPos2 = $ImPrePPos2;
				$ImPreANeg1 = $ImPrePNeg1;
				$ImPreANeg2 = $ImPrePNeg2;
			}
			
			// For inline usage
			$tdo = "<td>"; 
			$tdc = "</td>";
		?>
		<span>Conjugation: <?php echo $conj == 3.1 ? "3-io" : $conj; if($deponency != 0) {echo ", " . ($deponency == 0.5 ? "semi-" : "") . "deponent";}?></span>
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
				<?php if($deponency == 0) { ?>
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
				<?php } ?>
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
					<th rowspan="4" class="subjunctive">active</th>
					<td class="subjunctive">present</td>
					<?php foreach($SApre as $form) {echo "<td>" . $form . "</td>";}?>
				</tr>
				<tr>
					<td class="subjunctive">imperfect</td>
					<?php foreach($SAimp as $form) {echo "<td>" . $form . "</td>";}?>
				</tr>
				<tr>
					<td class="subjunctive">perfect</td>
					<?php foreach($SAper as $form) {echo "<td>" . $form . "</td>";}?>
				</tr>
				<tr>
					<td class="subjunctive">pluperfect</td>
					<?php foreach($SAplu as $form) {echo "<td>" . $form . "</td>";}?>
				</tr>
				<?php if($deponency == 0) { ?>
				<tr>
					<th rowspan="4" class="subjunctive">passive</th>
					<td class="subjunctive">present</td>
					<?php foreach($SPpre as $form) {echo "<td>" . $form . "</td>";}?>
				</tr>
				<tr>
					<td class="subjunctive">imperfect</td>
					<?php foreach($SPimp as $form) {echo "<td>" . $form . "</td>";}?>
				</tr>
				<tr>
					<td class="subjunctive">past perfect</td>
					<?php foreach($SPper as $form) {echo "<td>" . $form . "</td>";}?>
				</tr>
				<tr>
					<td class="subjunctive">pluperfect</td>
					<?php foreach($SPplu as $form) {echo "<td>" . $form . "</td>";}?>
				</tr>
				<?php } ?>
			</tbody>
		</table>
		
		
		<table>
			<tbody>
				<tr>
					<th class="imperative" rowspan="2" colspan="3">imperative</th>
					<th class="imperative">singular</th>
					<th class="imperative">plural</th>
				</tr>
				<tr></tr>
				<tr>
					<th class="imperative" rowspan="<?php echo $deponency == 0 ? 4: 2; ?>">present</th>
					<th class="imperative" rowspan="2">active</th>
					<th class="imperative">positive</th>
					<td><?php echo $ImPreAPos1; ?></td>
					<td><?php echo $ImPreAPos2; ?></td>
				</tr>
				<tr>
					<th class="imperative">negative</th>
					<td><?php echo $ImPreANeg1; ?></td>
					<td><?php echo $ImPreANeg2; ?></td>
				</tr>
				<?php if($deponency == 0) { ?>
				<tr>
					<th class="imperative" rowspan="2">passive</th>
					<th class="imperative">positive</th>
					<td><?php echo $ImPrePPos1; ?></td>
					<td><?php echo $ImPrePPos2; ?></td>
				</tr>
				<tr>
					<th class="imperative">negative</th>
					<td><?php echo $ImPrePNeg1; ?></td>
					<td><?php echo $ImPrePNeg2; ?></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
		
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
					<?php if($deponency == 0) {echo $participleNotFoundTD;} ?>
					<td><?php 
						if($cfg->{'participlePrinciplePartMode'} == 0) {
							echo $supineStem . "us, " . $supineStem . "a, " . $supineStem . "um";
						} else {
							echo $supineStem . "us, -a, -um";
						}
					?></td>
					<?php if($deponency != 0) {echo $participleNotFoundTD;} ?>
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
				
				<tr>
					<th rowspan="2" class="infinitive">infinitive</th>
					<th rowspan="2" class="infinitive">active</th>
					<th rowspan="2" class="infinitive">passive</th>
				</tr>
				<tr></tr>
				<tr>
					<th class="infinitive">present</th>
					<?php
						if($deponency == 0) {
							echo $tdo . $infinIApres . $tdc . $tdo . $infinIPpres . $tdc;
						} else {
							echo $tdo . $infinIPpres . $tdc . $participleNotFoundTD;
						}
					?>
				</tr>
				<tr>
					<th class="infinitive">perfect</th>
					<?php
						if($deponency == 0) {
							echo $tdo . $infinIAperf . $tdc . $tdo . $infinIPperf . $tdc;
						} else {
							echo $tdo . $infinIPperf . $tdc . $participleNotFoundTD;
						}
					?>
				</tr>
				<tr>
					<th class="infinitive">future</th>
					<?php
						if($deponency == 0) {
							echo $tdo . $infinIAfut . $tdc . $tdo . $infinIPfut . $tdc;
						} else {
							echo $tdo . $infinIAfut . $tdc . $participleNotFoundTD;
						}
					?>
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

