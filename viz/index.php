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
			<tr style="background-color:DeepSkyBlue; color: white">
				<td>country</td>
				<td>region</td>
				<td>infected</td>
				<td>fatality</td>
				<td>recovered</td>
			</tr>
		</table>

		<script type="text/javascript" src="/wp-content/plugins/C19nepalisAbroadData/js/nepalisAbroad.js"></script>

		<script>
			const table = document.getElementById('nepalis-abroad');

			for(let i=0; i<covidNepalisAbroad.countries.length; i++) {
				const trEl = document.createElement('tr');
				const oneRow = covidNepalisAbroad.countries[i];

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

			const trEl = document.createElement('tr');
			const totRow = covidNepalisAbroad.total;

			const tdCtry = document.createElement('td');
			tdCtry.innerHTML = "TOTAL";
			trEl.appendChild(tdCtry);

			const tdRgn = document.createElement('td');
			trEl.appendChild(tdRgn);

			const tdInf = document.createElement('td');
			tdInf.innerHTML = totRow.infected;
			trEl.appendChild(tdInf);

			const tdFat = document.createElement('td');
			tdFat.innerHTML = totRow.fatality;
			trEl.appendChild(tdFat);

			const tdRec = document.createElement('td');
			tdRec.innerHTML = totRow.recovered;
			trEl.appendChild(tdRec);

			trEl.style.backgroundColor="grey"
			trEl.style.color="white"

			table.appendChild(trEl);
		</script>
	</body>
</html>