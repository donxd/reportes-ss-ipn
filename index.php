<html>
	<head>
		<meta charset="UTF-8">
		<link href='http://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="style/formato.css">
		<link rel="stylesheet" href="lib/datetimepicker-master/jquery.datetimepicker.css">
	</head>
	<body>
		<form class="formulario_reporte" action="recursos/generador_reporte.php" method="post" target="_blank">
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
										<li> Periodo y horas </li>
										<li> Control de asistencias </li>
										<li> Datos del reporte </li>
										<li> Formato </li>
										<li> Plantilla </li>
										<li> Descargar  </li>
									</ol>	
								</div>
							</div>
							<div class="controles_datos_reporte">
								<!-- <hr/> -->
								<h3> <span class="paso_proceso"> #1. </span> PERIODO Y HORAS DEL REPORTE </h3>
								<!-- <h3> FECHAS Y HORAS DEL REPORTE </h3> -->
								<br/>
								<table class="informacion">				
									<tr>
										<td class="una_linea"> Día de inicio : </td>
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
												<input type="radio" name="tipo_reporte" value="pp" class="periodo_personalizado tipo_reporte" checked />
												Otro
											</label>
										</td>
									</tr>
									<tr>
										<td class="una_linea"> Fecha de inicio : </td>
										<td>
											<input type="text" class="fecha_inicio elemento control_fecha control_periodo_fecha" placeholder="dd-mm-aaaa" pattern="\d{2}-\d{2}-\d{4}" required />
										</td>
									</tr>
									<tr>
										<td class="una_linea"> Fecha de cierre : </td>
										<td>
											<input type="text" class="fecha_cierre elemento control_fecha control_periodo_fecha" placeholder="dd-mm-aaaa" pattern="\d{2}-\d{2}-\d{4}" required />
										</td>
									</tr>
									<tr>
										<td class="una_linea"> Días del servicio : </td>
										<td>
											<label>
												<input type="radio" name="tipo_dias" class="entre_semana tipo_dias" value="es" checked />
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
										<td class="una_linea"> Horas x día : </td>
										<td>
											<input type="number" min="1" max="4" class="horas_dia" placeholder="#" pattern="\d{1,4}" required />
										</td>
									</tr>
									<tr>
										<td class="una_linea"> Hora de entrada : </td>
										<td class="celda_hora_entrada">
											<div>
												<div>
													<input type="text" class="entrada" pattern="\d{2}:\d{2}" required></span>
												</div>
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
											</div>
										</td>
									</tr>
								</table>
							</div>
							<div>
								<hr/>
								<h3> <span class="paso_proceso"> #4. </span> CONFIGURAR FORMATO PARA DESCARGAR </h3>
								<div>
									<table class="configuracion_formato_reporte">
										<tr>
											<td colspan="2"></td>
											<td class="texto_centrado"> Ejemplo: </td>
										</tr>
										<tr>
											<td> Mes del reporte : </td>
											<td>
												<select class="formato_mes_reporte">
													<option value="mes_mayusculas"> MES / MES-MES </option>
													<option value="mes_normal"> Mes / Mes-Mes </option>
												</select>
											</td>
											<td class="ejemplo_formato ejemplo_formato_mes_reporte"> ENERO / ENERO-FEBRERO </td>
										</tr>
										<tr>
											<td> Fecha horas : </td>
											<td>
												<select class="formato_fechas_horas">
													<option value="dd/mm/aa"> dd/mm/aa </option>
													<option value="dd/mm/aaaa"> dd/mm/aaaa </option>
													<option value="dd-mm-aa"> dd-mm-aa </option>
													<option value="dd-mm-aaaa"> dd-mm-aaaa </option>
													<option value="aaaa-mm-dd"> aaaa-mm-dd </option>
													<option value="aa-mm-dd"> aa-mm-dd </option>
												</select>
											</td>
											<td class="ejemplo_formato ejemplo_formato_fechas_horas"> 01/01/15 </td>
										</tr>
										<tr>
											<td> Horas : </td>
											<td>
												<select class="formato_horas_reporte">
													<option value="horas_simple"> 0:00 </option>
													<option value="horas_formato"> 00:00 </option>
												</select>
											</td>
											<td class="ejemplo_formato ejemplo_formato_horas_reporte"> 8:00 </td>
										</tr>
										<tr>
											<td> Periodo del reporte : </td>
											<td>
												<select class="formato_periodo_reporte">
													<option value="dMESaaaa"> d MES aaaa </option>
													<option value="dMESaaaa*"> d MES aaaa* </option>
													<option value="dMesaaaa"> d Mes aaaa </option>
													<option value="dMesaaaa*"> d Mes aaaa* </option>
													<option value="ddMESaaaa"> dd MES aaaa </option>
													<option value="ddMESaaaa*"> dd MES aaaa* </option>
													<option value="ddMesaaaa"> dd Mes aaaa </option>
													<option value="ddMesaaaa*"> dd Mes aaaa* </option>
													<option value="dMESaa"> d MES aa </option>
													<option value="dMESaa*"> d MES aa* </option>
													<option value="dMesaa"> d Mes aa </option>
													<option value="dMesaa*"> d Mes aa* </option>
													<option value="ddMESaa"> dd MES aa </option>
													<option value="ddMESaa*"> dd MES aa* </option>
													<option value="ddMesaa"> dd Mes aa </option>
													<option value="ddMesaa*"> dd Mes aa* </option>
													<option value="dmaaaa"> d m aaaa </option>
													<option value="dmmaaaa"> d mm aaaa </option>
													<option value="ddmaaaa"> dd m aaaa </option>
													<option value="ddmmaaaa"> dd mm aaaa </option>
													<option value="dmaa"> d m aa </option>
													<option value="dmmaa"> d mm aa </option>
													<option value="ddmaa"> dd m aa </option>
													<option value="ddmmaa"> dd mm aa </option>
												</select>
											</td>
											<td class="ejemplo_formato ejemplo_formato_periodo_reporte"> 1 ENE 2015 </td>
										</tr>
										<tr>
											<td colspan="3"> &nbsp; <br/> &nbsp; </td>
										</tr>
										<tr>
											<td> Fecha de emisión : </td>
											<td>
												<select class="formato_fecha_emision">
													<option value="dMESaaaa*"> d MES aaaa* </option>
													<option value="ddMESaaaa*"> dd MES aaaa* </option>
													<option value="ddMesaaaa*"> dd Mes aaaa* </option>
													<option value="dMesaaaa*"> d Mes aaaa* </option>
													<option value="dMESaaaa"> d MES aaaa </option>
													<option value="ddMESaaaa"> dd MES aaaa </option>
													<option value="dMesaaaa"> d Mes aaaa </option>
													<option value="ddMesaaaa"> dd Mes aaaa </option>
													<option value="ddmmaaaa"> dd mm aaaa </option>
													<option value="ddmaaaa"> dd m aaaa </option>
													<option value="ddmaa"> dd m aa </option>
													<option value="ddmmaa"> dd mm aa </option>
													<option value="dmmaaaa"> d mm aaaa </option>
													<option value="dmmaa"> d mm aa </option>
													<option value="dmaaaa"> d m aaaa </option>
													<option value="dmaa"> d m aa </option>
												</select>
											</td>
											<td class="ejemplo_formato ejemplo_formato_fecha_emision"> 1 ENERO 2015 </td>
										</tr>
									</table>
								</div>
							</div>
						</div>
						<div class="limpiar"></div>
					</div>
					<div class="contenedor_reporte celda ancho_100p">
						<div class="separacion_vertical"></div>
						<div class="invisible contenedor_reporte_datos ancho_100p fondo_blanco">
							<div class="tabla centrado_margen">
								<div class="celda">
									<div class="reporte_datos invisible">
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
												<td> Total de horas <br/> del periodo : </td>
												<td>
													<input type="text" class="periodo_horas elemento" readonly/>
												</td>
											</tr>
										</table>
										<br/>
										<h3 class="sin_margen_abajo"> Control de asistencias </h3>
									</div>
								</div>
								<div class="celda">
									<div class="contenedor_enlace_descargar invisible">
										<input type="button" class="boton_accion ver_horas_reporte" value=" 2. Control de asistencias"/>
										<input type="button" class="boton_accion ver_datos_formato" value="3. Datos del reporte"/> <br/> <br/>
										<div class="una_linea">
											<select class="tipo_plantilla" title="Plantilla">
												<option value="upiicsa"> UPIICSA </option>
												<option value="generica"> Genérica </option>
											</select>
											&nbsp;
											<input type="submit" class="descargar" value="Descargar"/>
											<!-- <img src="https://cdn1.iconfinder.com/data/icons/material-core/20/settings-512.png" class="icono_configurar_reporte"/> -->
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="contenedor_tabla_horas_reporte ancho_100p invisible">	
							<div class="reporte tabla centrado_margen borde_redondo"></div>
						</div>
						<div class="contenedor_datos_complementarios_reporte centrado_margen borde_redondo tabla oculto">
							<div>
								<h3> DATOS DEL REPORTE </h3>
								<br/>
								<div class="tabla">
									<div class="celda alineacion_vertical_arriba">
										<table class="datos_complementarios_reporte">
											<tr>
												<td class="una_linea"> Número de reporte : </td>
												<td>
													<input type="number" min="1" class="numero_reporte" placeholder="#" pattern="\d+" />
												</td>
											</tr>
											<tr>
												<td class="una_linea"> Carrera : </td>
												<td>
													<input type="text" class="carrera"/>
												</td>
											</tr>
											<tr>
												<td class="una_linea"> Nombre del alumno: </td>
												<td>
													<input type="text" class="nombre_alumno"/>
												</td>
											</tr>
											<tr>
												<td class="una_linea"> Boleta : </td>
												<td>
													<input type="text" class="boleta" placeholder="20XXPPXXXX" pattern="\d{10}"/>
												</td>
											</tr>
											<tr>
												<td class="una_linea"> Correo electrónico : </td>
												<td>
													<input type="text" class="correo" placeholder="usuario@cuenta.xy" pattern="[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,253}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,253}[a-zA-Z0-9])?)*"/>
												</td>
											</tr>
											<tr>
												<td class="una_linea"> Teléfono : </td>
												<td>
													<input type="text" class="telefono"/>
												</td>
											</tr>
										</table>
									</div>
									<div class="celda">
										<table class="datos_complementarios_reporte">
											<tr>
												<td> &nbsp; </td>
											</tr>
											<tr>
												<td> &nbsp; </td>
											</tr>
											<tr>
												<td> Dependencia donde <br/> se realiza el servicio social : </td>
												<td>
													<input type="text" class="dependencia"/>
												</td>
											</tr>
											<tr>
												<td class="una_linea"> Responsable directo : </td>
												<td>
													<input type="text" class="nombre_responsable" placeholder="Nombre"/>
												</td>
											</tr>
											<tr>
												<td></td>
												<td>
													<input type="text" class="puesto_responsable" placeholder="Puesto"/>
												</td>
											</tr>
										</table>
									</div>
								</div>
								<br/>
								<hr/>
								<br/>
								<table class="datos_complementarios_reporte">
									<tr>
										<td class="una_linea"> Actividades : </td>
										<td>
											<input type="text" class="actividad_reporte actividad_1" placeholder="1"/> <br/>
											<input type="text" class="actividad_reporte actividad_2" placeholder="2"/> <br/>
											<input type="text" class="actividad_reporte actividad_3" placeholder="3"/> <br/>
										</td>
										<td class="alineacion_vertical_arriba">
											<input type="text" class="actividad_reporte actividad_4" placeholder="4"/> <br/>
											<input type="text" class="actividad_reporte actividad_5" placeholder="5"/> <br/>
										</td>
									</tr>
								</table>
								<br/>
								<hr/>
								<br/>
								<div class="tabla">
									<div class="celda alineacion_vertical_medio">
										<table class="datos_complementarios_reporte">
											<tr>
												<td class="una_linea"> Fecha de emisión : </td>
												<td>
													<input type="text" class="fecha_emision control_fecha" placeholder="dd-mm-aaaa" pattern="\d{2}-\d{2}-\d{4}"/>
												</td>
											</tr>
										</table>
									</div>
									<div class="celda">
										<table class="datos_complementarios_reporte">
											<tr>
												<td class="una_linea"> Total de horas <br/> acumuladas anterior : </td>
												<td>
													<input type="number" min="0" class="total_horas_acumuladas_anterior" placeholder="#" pattern="\d+" />
												</td>
											</tr>
										</table>
									</div>
								</div>
								<br/>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</body>
	<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
	<script type="text/javascript" src="js/moment-with-locales.js"></script>
	<script type="text/javascript" src="js/sprintf.js"></script>
	<script type="text/javascript" src="lib/datetimepicker-master/jquery.datetimepicker.js"></script>
	<script type="text/javascript" src="js/generador_reporte.js"></script>
</html>