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
			
			.pronoun {
				background-color: rgb(180, 180, 255);
			}
		</style>
	</head>
	<body>
		<?php
			$pp1 = $_GET['pp1'];
			$isPersonal = startsWith($pp1, "pers");
			
			switch($pp1) {
				case "demo1": $type = "Demonstrative Pronoun: is, ea, id"; break;
				case "demo2": $type = "Demonstrative Pronoun: hic, haec, hoc"; break;
				case "demo3": $type = "Demonstrative Pronoun: ille, illa, illud"; break;
				case "demo4": $type = "Demonstrative Pronoun: iste, ista, istud"; break;
				case "pers1": $type = "Personal Pronoun: ego, mei"; break;
				case "pers2": $type = "Personal Pronoun: tu, tui"; break;
				case "pers3": $type = "Personal Pronoun: -, sui"; break;
				case "ident": $type = "Identifying Pronoun: idem, eadem, idem"; break;
				case "intens": $type = "Intensive Pronoun: ipse, ipsa, ipsum"; break;
				case "interrog": $type = "Interrogative Pronoun: quis, quis, quid"; break;
				case "rel": $type = "Relative Pronoun: qui, quae, quod"; break;
				default: "Realative Pronoun: qui, quae, quod";
			}
		?>
		<span><?php echo $type; ?></span>
		<hr>
		<table style="background: rgb(240, 240, 240)">
			<tbody>
				<?php
					$file = explode("\n", file_get_contents("./pronouns/" . $pp1 . ".txt"));
					if(!$isPersonal) {
				?>
				<tr class="pronoun">
					<td style="text-align: right;">number</td>
					<th colspan="3">singular</th>
					<th colspan="3">plural</th>
				</tr>
				<tr class="pronoun">
					<td>case</td>
					<td>M</td>
					<td>F</td>
					<td>N</td>
					<td>M</td>
					<td>F</td>
					<td>N</td>
				</tr>
				<tr>
					<td class="pronoun">nominative</td>
					<td><?php echo $file[0] ?></td>
					<td><?php echo $file[1] ?></td>
					<td><?php echo $file[2] ?></td>
					<td><?php echo $file[15] ?></td>
					<td><?php echo $file[16] ?></td>
					<td><?php echo $file[17] ?></td>
				</tr>
				<tr>
					<td class="pronoun">accusative</td>
					<td><?php echo $file[3] ?></td>
					<td><?php echo $file[4] ?></td>
					<td><?php echo $file[5] ?></td>
					<td><?php echo $file[18] ?></td>
					<td><?php echo $file[19] ?></td>
					<td><?php echo $file[20] ?></td>
				</tr>
				<tr>
					<td class="pronoun">genitive</td>
					<td><?php echo $file[6] ?></td>
					<td><?php echo $file[7] ?></td>
					<td><?php echo $file[8] ?></td>
					<td><?php echo $file[21] ?></td>
					<td><?php echo $file[22] ?></td>
					<td><?php echo $file[23] ?></td>
				</tr>
				<tr>
					<td class="pronoun">dative</td>
					<td><?php echo $file[9] ?></td>
					<td><?php echo $file[10] ?></td>
					<td><?php echo $file[11] ?></td>
					<td><?php echo $file[24] ?></td>
					<td><?php echo $file[25] ?></td>
					<td><?php echo $file[26] ?></td>
				</tr>
				<tr>
					<td class="pronoun">ablative</td>
					<td><?php echo $file[12] ?></td>
					<td><?php echo $file[13] ?></td>
					<td><?php echo $file[14] ?></td>
					<td><?php echo $file[27] ?></td>
					<td><?php echo $file[28] ?></td>
					<td><?php echo $file[29] ?></td>
				</tr>
				<?php
					echo $rows;
					} else {
				?>
				<tr class="pronoun">
					<td>case \ number</td>
					<td>singular</td>
					<td>plural</td>
				</tr>
				<tr>
					<td class="pronoun">nominative</td>
					<td><?php echo $file[0] ?></td>
					<td><?php echo $file[5] ?></td>
				</tr>
				<tr>
					<td class="pronoun">accusative</td>
					<td><?php echo $file[1] ?></td>
					<td><?php echo $file[6] ?></td>
				</tr>
				<tr>
					<td class="pronoun">genitive</td>
					<td><?php echo $file[2] ?></td>
					<td><?php echo $file[7] ?></td>
				</tr>
				<tr>
					<td class="pronoun">dative</td>
					<td><?php echo $file[3] ?></td>
					<td><?php echo $file[8] ?></td>
				</tr>
				<tr>
					<td class="pronoun">ablative</td>
					<td><?php echo $file[4] ?></td>
					<td><?php echo $file[9] ?></td>
				</tr>
				<?php
					}
				?>
			</tbody>
		</table>
	</body>
</html>
<?php
	} else {
	header('Content-Type: text/plain');
?>
Conjugation: <?php echo $type; ?>
________________________________________________________________________________
<?php
	}
?>
