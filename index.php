<?
	require_once("recursos/funciones.php");
	$funciones = new funciones();
	$informacion = $funciones->informacion_mes();
?>
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
					<td colspan="2">
						<div id="instrucciones">
							<!-- <details open> -->
							<details>
								<summary>
									<strong>Instrucciones</strong>
								</summary>
								<ol>
									<li>Ingrese la fecha de inicio del período del reporte.</li>
									<li>Ingrese la fecha de cierre del período del reporte.</li>
									<li>Seleccione los días que va a registrar.</li>
									<li>Ingrese las horas de servicio.</li>
									<li>Ingrese la hora de entrada.</li>
								</ol>	
							</details>
						</div>
						<hr/>
					</td>
				</tr>				
				<tr>
					<td>Día de inicio del reporte</td>
					<td>
						<input type="radio" id="principio_mes" value="pm" name="tipo_reporte" />
						<label for="principio_mes">01 - Principio del mes</label>
						<br/>
						<input type="radio" id="mitad_mes" value="mm" name="tipo_reporte"/>
						<label for="mitad_mes">16 - Mitad del mes</label>
					</td>
				</tr>
				<tr>
					<td>Fecha de inicio</td>
					<td>
						<input type="date" id="fecha_inicio" class="elemento"/>
					</td>
				</tr>
				<tr>
					<td>Fecha de cierre</td>
					<td>
						<input type="date" id="fecha_cierre" class="elemento"/>
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
					<td>Horas x día</td>
					<td>
						<input type="number" min="1" max="4" id="horas_dia"/>
					</td>
				</tr>
				<tr>
					<td>Hora de entrada</td>
					<td id="celda_hora_entrada">
						<div>
							<div>
								<input type="range" id="entrada_rango" min="0" max="47" step="1" list="horas"/>
								<datalist id="horas">
									<option>0</option>
									<option>12</option>
									<option>24</option>
									<option>36</option>
									<option>47</option>
								</datalist>
							</div>
							<div>
								<span id="entrada"></span>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>Periodo inicio</td>
					<td>
						<input type="text" id="periodo_inicio" class="elemento" readonly/>
					</td>
				</tr>
				<tr>
					<td>Periodo cierre</td>
					<td>
						<input type="text" id="periodo_cierre" class="elemento" readonly/>
					</td>
				</tr>
				<tr>
					<td>Mes del periodo</td>
					<td>
						<input type="text" id="periodo_mes" class="elemento" readonly/>
					</td>
				</tr>
				<tr>
					<td>Total de horas del periodo</td>
					<td>
						<input type="text" id="periodo_horas" class="elemento" readonly/>
					</td>
				</tr>
			</table>
		</div>
		<div id="reporte"></div>
	</body>
	<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
	<script type="text/javascript" src="js/generador_reporte.js"></script>
	<script type="text/javascript"><?= "informacion = ['".$informacion[0]."', '".$informacion[1]."', '".$informacion[2]."'];" ?></script>
</html>