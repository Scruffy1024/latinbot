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
			
			.noun {
				background-color: rgb(180, 180, 255);
			}
		</style>
	</head>
	<body>
		<?php
			$pp1 = strtolower($_GET['pp1']);
			$pp2 = strtolower($_GET['pp2']);
			$pp3 = $_GET['pp3'];
			
			if(endsWith($pp2, "ae") || endsWith($pp2, "arum")) {
				$decl = 1;
			} else if((endsWith($pp2, "i") && !endsWith($pp2, "ei")) || endsWith($pp2, "orum")) {
				$decl = 2;
			} else if(endsWith($pp2, "is") || ((endsWith($pp2, "um") && !endsWith($pp2, "erum")) && (endsWith($pp1, "es") || endsWith($pp1, "a")))) {
				$decl = 3;
			} else if(endsWith($pp2, "us") || endsWith($pp2, "um")) {
				$decl = 4;
			} else if(endsWith($pp2, "ei") || (endsWith($pp2, "erum") && endsWith($pp1, "es"))){
				$decl = 5;
			} else {
				die("Invalid noun.");
			}
			
			if($decl == 1 && endsWith($pp2, "arum")) {
				$pluralOnly = true;
			} else if($decl == 2 && endsWith($pp2, "orum")) {
				$pluralOnly = true;
			} else if($decl == 3 && endsWith($pp2, "um")) {
				$pluralOnly = true;
			} else if($decl == 4 && endsWith($pp2, "uum")) {
				$pluralOnly = true;
			} else if($decl == 5 && endsWith($pp2, "erum")) {
				$pluralOnly = true;
			}
			
			if($decl == 1) {
				$stem = ($pluralOnly ? substr($pp2, 0, -4) : substr($pp2, 0, -2));
				
				$nom1 = $pp1;
				$acc1 = $stem . "am";
				$gen1 = $pp2;
				$dat1 = $stem . "ae";
				$abl1 = $stem . "a";
				$voc1 = $pp1;
				
				$nom2 = $stem . "ae";
				$acc2 = $stem . "as";
				$gen2 = ($pluralOnly ? $pp2 : $stem . "arum");
				$dat2 = $stem . ($pp1 == "filia" ? "abus" : "is");
				$abl2 = $stem . ($pp1 == "filia" ? "abus" : "is");
				$voc2 = $stem . "ae";
			} else if($decl == 2) {
				$stem = ($pluralOnly ? substr($pp2, 0, -4) : substr($pp2, 0, -1));
				
				$nom1 = $pp1;
				$acc1 = ($pp3 == 'n' ? $pp1 : $stem . "um");
				$gen1 = $pp2;
				$dat1 = $stem . "o";
				$abl1 = $stem . "o";
				$voc1 = ($pp3 == "m" ? (endsWith($pp1, "us") ? (endsWith($pp1, "ius") ? $stem . "i" : $stem . "e") : $pp1) : $pp1);
				
				$nom2 = $stem . ($pp3 == "n" ? "a" : "i");
				$acc2 = $stem . ($pp3 == "n" ? "a" : "os");
				$gen2 = ($pluralOnly ? $pp2 : $stem . "orum");
				$dat2 = $stem . "is";
				$abl2 = $stem . "is";
				$voc2 = $nom2;
			} else if($decl == 3) {
				$ex1 = countSyllables($pp1) == 1 && !isVowel($pp2[$pp2.length - 3]) && !isVowel($pp2[$pp2.length - 4]);
				$ex2 = $pp3 == "n" && (endsWith($pp1, "e") || endsWith($pp1, "al") || endsWith($pp1, "ar"));
				$ex3 = countSyllables($pp1) == countSyllables($pp2);
				$ex4 = $pp1 == "frater" || $pp2 == "soror" || $pp1 == "mater" || $pp1 == "pater" || $pp1 == "canis" || $pp1 == "senex" || $pp1 == "juvenis";
				echo preg_split("[aeiouy]+?\w*?[^e]", mb_strtolower($pp2));
				$gen2i = ($ex1 || ($ex3 && !$ex4));
				
				$stem = ($pluralOnly ? ($gen2i ? substr($pp2, 0, -3) : substr($pp2, 0, -2)) : substr($pp2, 0, -2));
				
				$nom1 = $pp1;
				$acc1 = ($pp3 == "n" ? $nom1 : $stem . "em");
				$gen1 = $pp2;
				$dat1 = $stem . "i";
				$abl1 = $stem . ($ex2 ? "i" : "e");
				$voc1 = $nom1;
				
				$nom2 = $stem . ($pp3 == "n" ? ($ex3 ? "ia" : "a") : "es"); // if neuter then (if ex3 then ia else a end) else es end
				$acc2 = $stem . ($pp3 == "n" ? ($ex3 ? "ia" : "a") : "es");
				$gen2 = ($pluralOnly ? $pp2 : $stem . ($gen2i ? "ium" : "um"));
				$dat2 = $stem . "ibus";
				$abl2 = $dat2;
				$voc2 = $nom2;
			} else if($decl == 4) {
				$ex1 = $pp1 == "arcus" || $pp1 == "tribus" || $pp1 == "quercus";
				
				$stem = ($pluralOnly ? substr($pp2, 0, -3) : substr($pp2, 0, -2));
				
				$nom1 = $pp1;
				$acc1 = ($pp3 == "n" ? $nom1 : $stem . "um");
				$gen1 = $pp2;
				$dat1 = $stem . ($pp3 == "n" ? "u" : "ui");
				$abl1 = $stem . "u";
				$voc1 = $nom1;
				
				$nom2 = $stem . ($pp3 == "n" ? "ua" : "us");
				$acc2 = ($pp3 == "n" ? $nom2 : $stem . "us");
				$gen2 = ($pluralOnly ? $pp2 : $stem . "uum");
				$dat2 = $stem . ($ex1 ? "ubus" : "ibus");
				$abl2 = $stem . ($ex1 ? "ubus" : "ibus");
				$voc2 = $nom2;
			} else if($decl == 5) {
				$stem = ($pluralOnly ? substr($pp2, 0, -4) : substr($pp2, 0, -2));
				
				$nom1 = $pp1;
				$acc1 = $stem . "em";
				$gen1 = $pp2;
				$dat1 = $stem . "ei";
				$abl1 = $stem . "e";
				$voc1 = $nom1;
				
				$nom2 = $stem . "es";
				$acc2 = $stem . "es";
				$gen2 = ($pluralOnly ? $pp2 : $stem . "erum");
				$dat2 = $stem . "ebus";
				$abl2 = $stem . "ebus";
				$voc2 = $nom2;
			}
			
			if($pluralOnly) {
				$nom1 = "-";
				$acc1 = "-";
				$gen1 = "-";
				$dat1 = "-";
				$abl1 = "-";
				$voc1 = "-";
			}
		?>
		<span>Declension: <?php echo $decl; ?></span>
		<hr>
		<table style="background: rgb(240, 240, 240)">
			<tbody>
				<tr class="noun">
					<td>case \ number</td>
					<td>singular</td>
					<td>plural</td>
				</tr>
				<tr>
					<td class="noun">nominative</td>
					<td><?php echo $nom1 ?></td>
					<td><?php echo $nom2 ?></td>
				</tr>
				<tr>
					<td class="noun">accusative</td>
					<td><?php echo $acc1 ?></td>
					<td><?php echo $acc2 ?></td>
				</tr>
				<tr>
					<td class="noun">genitive</td>
					<td><?php echo $gen1 ?></td>
					<td><?php echo $gen2 ?></td>
				</tr>
				<tr>
					<td class="noun">dative</td>
					<td><?php echo $dat1 ?></td>
					<td><?php echo $dat2 ?></td>
				</tr>
				<tr>
					<td class="noun">ablative</td>
					<td><?php echo $abl1 ?></td>
					<td><?php echo $abl2 ?></td>
				</tr>
				<tr>
					<td class="noun">vocative</td>
					<td><?php echo $voc1 ?></td>
					<td><?php echo $voc2 ?></td>
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
