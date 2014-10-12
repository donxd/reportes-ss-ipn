<html>
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="style/formato.css">
	</head>
	<body>
		<div id="contenido">
			<h3>Generador de contenido para el reporte mensual</h3>
			<table id="informacion">
				<tr>
					<td>Día de inicio del reporte</td>
					<td>
						<input type="radio" name="tipo_reporte" id="principio_mes" value="pm"/>
						<label for="principio_mes">01 - Principio del mes</label>
						<br/>
						<input type="radio" name="tipo_reporte" id="mitad_mes" value="mm"/>
						<label for="mitad_mes">16 - Mitad del mes</label>
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
					<td>Días del servicio</td>
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
						<input type="time" id="entrada" step="1800"/>
					</td>
				</tr>
				<tr>
					<td>Horas x día</td>
					<td>
						<input type="number" min="1" max="4" id="horas_dia"/>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>Periodo inicio</td>
					<td>
						<input type="text" id="periodo_inicio" readonly/>
					</td>
				</tr>
				<tr>
					<td>Periodo cierre</td>
					<td>
						<input type="text" id="periodo_cierre" readonly/>
					</td>
				</tr>
				<tr>
					<td>Mes del periodo</td>
					<td>
						<input type="text" id="periodo_mes" readonly/>
					</td>
				</tr>
			</table>
		</div>
		<div id="reporte"></div>
	</body>
	<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
	<script type="text/javascript" src="js/generador_reporte.js"></script>
</html>