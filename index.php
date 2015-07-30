<html>
	<head>
		<meta charset="UTF-8">
		<link href='http://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="style/formato.css">
	</head>
	<body>
		<div class="cabecera">
			<h3> Generador de contenido para el reporte de Servicio Social </h3>
		</div>
		<div class="principal">
			<div>
				<div class="contenedor_controles">
					<div class="contenido sombra">
						<table class="informacion">
							<tr>
								<td colspan="2">
									<div class="instrucciones">
										<details open>
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
									<label>
										<input type="radio" name="tipo_reporte" value="pm" class="principio_mes tipo_reporte" />
										01 - Principio del mes
									</label>
									<br/>
									<label>
										<input type="radio" name="tipo_reporte" value="mm" class="mitad_mes tipo_reporte"/>
										16 - Mitad del mes
									</label>
								</td>
							</tr>
							<tr>
								<td>Fecha de inicio</td>
								<td>
									<input type="date" class="fecha_inicio elemento"/>
								</td>
							</tr>
							<tr>
								<td>Fecha de cierre</td>
								<td>
									<input type="date" class="fecha_cierre elemento"/>
								</td>
							</tr>
							<tr>
								<td>Días del servicio</td>
								<td>
									<label>
										<input type="radio" name="tipo_dias" class="entre_semana tipo_dias" value="es"/>
										Lunes a Viernes
									</label>
									<br/>
									<label>
										<input type="radio" name="tipo_dias" class="fines_semana tipo_dias" value="fs"/>
										Sábado y Domingo
									</label>
								</td>
							</tr>
							<tr>
								<td>Horas x día</td>
								<td>
									<input type="number" min="1" max="4" class="horas_dia"/>
								</td>
							</tr>
							<tr>
								<td>Hora de entrada</td>
								<td class="celda_hora_entrada">
									<div>
										<div>
											<input type="range" class="entrada_rango" min="0" max="47" step="1" list="horas"/>
											<datalist id="horas">
												<option>0</option>
												<option>12</option>
												<option>24</option>
												<option>36</option>
												<option>47</option>
											</datalist>
										</div>
										<div>
											<span class="entrada"></span>
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<td colspan="2"> &nbsp; </td>
							</tr>
							<tr>
								<td colspan="2">
									<hr/>
								</td>
							</tr>
							<tr>
								<td colspan="2"> &nbsp; </td>
							</tr>
							<tr>
								<td>Periodo inicio</td>
								<td>
									<input type="text" class="periodo_inicio elemento" readonly/>
								</td>
							</tr>
							<tr>
								<td>Periodo cierre</td>
								<td>
									<input type="text" class="periodo_cierre elemento" readonly/>
								</td>
							</tr>
							<tr>
								<td>Mes del periodo</td>
								<td>
									<input type="text" class="periodo_mes elemento" readonly/>
								</td>
							</tr>
							<tr>
								<td>Total de horas del periodo</td>
								<td>
									<input type="text" class="periodo_horas elemento" readonly/>
								</td>
							</tr>
						</table>
					</div>
				</div>
				<div class="contenedor_reporte">
					<div class="reporte sombra oculto"></div>
				</div>
			</div>
		</div>
	</body>
	<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
	<script type="text/javascript" src="js/moment-with-locales.js"></script>
	<script type="text/javascript" src="js/generador_reporte.js"></script>
</html>