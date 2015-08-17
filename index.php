<html>
	<head>
		<meta charset="UTF-8">
		<link href='http://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="style/formato.css">
	</head>
	<body>
		<div class="cabecera">
			<table>
				<tr>
					<td class="titulo">
						Generador de contenido para el reporte de Servicio Social
					</td>
				</tr>
			</table>
		</div>
		<div class="principal">
			<div class="tabla">
				<div class="separacion_vertical celda">
					<div class="contenedor_controles contenido">
						<div class="informacion">
							<div class="instrucciones">
								<h3> INSTRUCCIONES </h3>
								<ol>
									<li>Ingrese la fecha de inicio del período del reporte.</li>
									<li>Ingrese la fecha de cierre del período del reporte.</li>
									<li>Seleccione los días que va a registrar.</li>
									<li>Ingrese las horas de servicio.</li>
									<li>Ingrese la hora de entrada.</li>
								</ol>	
							</div>
						</div>
						<div class="controles_datos_reporte">
							<hr/>
							<h3> DATOS DEL REPORTE </h3>
							<br/>
							<table class="informacion">
								<tr>
									<td colspan="2">
										
									</td>
								</tr>				
								<tr>
									<td>Día de inicio : </td>
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
										<br/>
										<label>
											<input type="radio" name="tipo_reporte" value="pp" class="periodo_personalizado tipo_reporte"/>
											Otro
										</label>
									</td>
								</tr>
								<tr>
									<td> Fecha de inicio : </td>
									<td>
										<input type="date" class="fecha_inicio elemento control_fecha" placeholder="dd/mm/aaaa"/>
									</td>
								</tr>
								<tr>
									<td> Fecha de cierre : </td>
									<td>
										<input type="date" class="fecha_cierre elemento control_fecha" placeholder="dd/mm/aaaa"/>
									</td>
								</tr>
								<tr>
									<td> Días del servicio : </td>
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
									<td> Horas x día : </td>
									<td>
										<input type="number" min="1" max="4" class="horas_dia"/>
									</td>
								</tr>
								<tr>
									<td> Hora de entrada : </td>
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
							</table>
						</div>
						<div>
							<hr/>
							<h3> CONFIGURAR FORMATO PARA DESCARGAR </h3>
						</div>
					</div>
					<div class="limpiar"></div>
				</div>
				<div class="contenedor_reporte celda ancho_100p">
					<div class="separacion_vertical"></div>
					<div class="invisible contenedor_reporte_datos ancho_100p fondo_blanco">
						<div class="tabla centrado_margen">
							<div class="celda">
								<div class="reporte_datos">
									<table class="informacion">
										<tr>
											<td> Periodo inicio : </td>
											<td>
												<input type="text" class="periodo_inicio elemento" readonly/>
											</td>
										</tr>
										<tr>
											<td> Periodo cierre : </td>
											<td>
												<input type="text" class="periodo_cierre elemento" readonly/>
											</td>
										</tr>
										<tr>
											<td> Mes del periodo : </td>
											<td>
												<input type="text" class="periodo_mes elemento" readonly/>
											</td>
										</tr>
										<tr>
											<td> Total de horas del periodo : </td>
											<td>
												<input type="text" class="periodo_horas elemento" readonly/>
											</td>
										</tr>
									</table>
								</div>	
							</div>
							<div class="celda">
								<div class="contenedor_enlace_descargar invisible">
									<div class="una_linea">
										<form class="formulario_reporte" action="recursos/generador_reporte.php" method="post" target="_blank">
											<input type="button" class="descargar" value="Descargar"/>
											<img src="https://cdn1.iconfinder.com/data/icons/material-core/20/settings-512.png" class="icono_configurar_reporte"/>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="contenedor_tabla_horas_reporte ancho_100p invisible">
						<div class="reporte tabla centrado_margen borde_redondo"></div>
					</div>
				</div>
			</div>
		</div>
	</body>
	<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
	<script type="text/javascript" src="js/moment-with-locales.js"></script>
	<script type="text/javascript" src="js/sprintf.js"></script>
	<script type="text/javascript" src="js/generador_reporte.js"></script>
</html>