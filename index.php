<html>
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="style/formato.css">
	</head>
	<body>
		<h3>Generador de reporte mensual</h3>
		<table>
			<tr>
				<td>Tipo reporte</td>
				<td>
					<input type="radio" name="tipo_reporte" id="principio_mes" value="pm"/>
					<label for="principio_mes">Principio del mes</label>
					<br/>
					<input type="radio" name="tipo_reporte" id="mitad_mes" value="mm"/>
					<label for="mitad_mes">Mitad del mes</label>
				</td>
			</tr>
			<tr>
				<td>Fecha de inicio</td>
				<td>
					<input type="date" id="fecha_inicio"/>
				</td>
			</tr>
			<tr>
				<td>Fecha de cierre</td>
				<td>
					<input type="date" id="fecha_cierre"/>
				</td>
			</tr>
			<tr>
				<td>Días</td>
				<td>
					<input type="radio" name="tipo_dias" id="entre_semana" value="es"/>
					<label for="principio_mes">Lunes a Viernes</label>
					<br/>
					<input type="radio" name="tipo_dias" id="fines_semana" value="fs"/>
					<label for="mitad_mes">Sábado y Domingo</label>
				</td>
			</tr>
			<tr>
				<td>Hora de entrada</td>
				<td>
					<input type="time" id="entrada"/>
				</td>
			</tr>
			<tr>
				<td>Horas x día</td>
				<td>
					<input type="number" min="1" max="4" id="horas_dia"/>
				</td>
			</tr>
		</table>
	</body>
	<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
	<script type="text/javascript" src="js/generador_reporte.js"></script>
</html>