<?php
 require_once('../../../../wp-config.php');
	global $wpdb;
	$na_table_name = $wpdb->prefix . 'na_covid19Nepal';
	?>
<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="UTF-8">
		<title>COVID-19 Nepalis Abroad</title>
	</head>

	<body>
		<span>
			COVID-19 Nepalis Abroad
		</span>

		<table id="nepalis-abroad" border=1px>
			<tr>
				<td>country</td>
				<td>region</td>
				<td>infected</td>
				<td>fatality</td>
				<td>recovered</td>
			</tr>
		</table>

	<?php
		echo '<script type="text/javascript">';
		//START
		$naSql = "SELECT *
								FROM $na_table_name
								WHERE groupDate = (SELECT MAX(groupDate) FROM $na_table_name)
						ORDER BY region, country ASC";

		$naResults= $wpdb->get_results($naSql);

		echo 'var covidNepalisAbroad=[];';

		foreach ( $naResults as $result ) {
			echo 'covidNepalisAbroad.push(' . json_encode($result) . ');';
		}
		//END
		echo "</script>";
	?>
		<script>
			const table = document.getElementById('nepalis-abroad');

			for(let i=0; i<covidNepalisAbroad.length; i++) {
				const trEl = document.createElement('tr');
				const oneRow = covidNepalisAbroad[i];

				const tdCtry = document.createElement('td');
				tdCtry.innerHTML = oneRow.country;
				trEl.appendChild(tdCtry);

				const tdRgn = document.createElement('td');
				tdRgn.innerHTML = oneRow.region;
				trEl.appendChild(tdRgn);

				const tdInf = document.createElement('td');
				tdInf.innerHTML = oneRow.infected;
				trEl.appendChild(tdInf);

				const tdFat = document.createElement('td');
				tdFat.innerHTML = oneRow.fatality;
				trEl.appendChild(tdFat);

				const tdRec = document.createElement('td');
				tdRec.innerHTML = oneRow.recovered;
				trEl.appendChild(tdRec);

				table.appendChild(trEl);
			}
		</script>
	</body>
</html>