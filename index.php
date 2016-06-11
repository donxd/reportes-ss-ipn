<html>
	<head>
		<meta charset="UTF-8">
		<link href='http://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="style/formato.css">
		<link rel="stylesheet" href="lib/datetimepicker-master/jquery.datetimepicker.css">
	</head>
	<body>
		<div id="fb-root"></div>
		<script>(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = "//connect.facebook.net/es_LA/sdk.js#xfbml=1&version=v2.4";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));</script>
		<form class="formulario_reporte" action="recursos/generador_reporte.php" method="post" target="_blank">
			<div class="cabecera">
				<table class="ancho_100p">
					<tr>
						<td class="titulo">
							Generador de reporte de Servicio Social
							<div class="derecha">
								<div class="fb-like" data-href="https://www.facebook.com/reportess" data-layout="button_count"></div>
							</div>
							<div class="limpiar"></div>
						</td>
					</tr>
				</table>
			</div>
			<div class="contenedor_controles_contenido ancho_100p">
				<div class="controles_contenido">
					<input type="button" class="boton_accion ver_periodos" value="1. Período y horas"/>
					<input type="button" class="boton_accion ver_fechas_horas" value="2. Asistencias"/>
					<input type="button" class="boton_accion ver_datos_formato" value="3. Formato"/>
					<input type="button" class="boton_accion ver_datos_complementarios" value="4. Datos del reporte"/>
					<input type="submit" class="descargar" value="Descargar"/>
					<div class="derecha notas">
						<strong>NOTA : </strong> <span title="Rueda del ratón">scroll vertical = shift + scroll </span>
						<br/>
						<label title="Información del reporte"> 
							<input type="checkbox" class="preservar_informacion" checked /> Preservar información 
						</label>
					</div>
				</div>
			</div>
			<div>
				<div class="segmentacion barras">
					<div class="tabla contenido_secciones">
						<div class="celda controles_datos_reporte">
							<h4> <span class="paso_proceso"> #1. </span> PERÍODO Y HORAS </h4>
							<br/>
							<table class="informacion">		
								<tr>
									<td class="una_linea"> Día de inicio : </td>
									<td colspan="2">
										01 - 
										<input type="button" value="Mes actual" class="mes_actual" />
										<input type="button" value="Mes pasado" class="mes_pasado" />
										<br/>
										16 -
										<input type="button" value="Mes pasado - actual" class="mes_pasado_actual" />
									</td>
								</tr>
								<tr>
									<td> &nbsp; </td>
								</tr>
								<tr>
									<td> &nbsp; </td>
								</tr>
								<!-- <tr class="oculto">
									<td class="una_linea"> Día de inicio : </td>
									<td>
										<input type="button" value="Mes pasado" class="mes_pasado" />
										<label>
											<input type="radio" name="tipo_reporte" value="pm" class="principio_mes tipo_reporte" required/>
											01 - Principio del mes ( último completo )
										</label>
										<br/>
										<label>
											<input type="radio" name="tipo_reporte" value="mm" class="mitad_mes tipo_reporte"/>
											16 - Mitad del mes ( actual )
										</label>
										<br/>
										<label>
											<input type="radio" name="tipo_reporte" value="pp" class="periodo_personalizado tipo_reporte" />
											Otro
										</label>
									</td>
								</tr> -->
								<!-- <tr>
									<td></td>
									<td>
										<input type="button" value="Cerrar mes" class="cerrar_mes" />
										<input type="button" value="Cerrar quincena" class="cerrar_quincena" />
									</td>
								</tr> -->
								<tr>
									<td class="una_linea"> Fecha de inicio : </td>
									<td>
										<input type="text" class="fecha_inicio elemento control_fecha control_periodo_fecha" placeholder="dd-mm-aaaa" pattern="\d{2}-\d{2}-\d{4}" required />
									</td>
									<td rowspan="2">
										<input type="button" value="Cerrar mes" class="cerrar_mes" />
										<br/>
										<input type="button" value="Cerrar quincena" class="cerrar_quincena" />
									</td>
								</tr>
								<tr>
									<td class="una_linea"> Fecha de cierre : </td>
									<td>
										<input type="text" class="fecha_cierre elemento control_fecha control_periodo_fecha" placeholder="dd-mm-aaaa" pattern="\d{2}-\d{2}-\d{4}" required />
									</td>
								</tr>
								<tr>
									<td> &nbsp; </td>
								</tr>
								<tr>
									<td> &nbsp; </td>
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
									<td> &nbsp; </td>
								</tr>
								<tr>
									<td class="una_linea"> Horas x día : </td>
									<td>
										<input type="number" min="1" max="4" class="horas_dia" placeholder="#" pattern="\d{1,4}" value="4" required />
									</td>
								</tr>
								<tr>
									<td> &nbsp; </td>
								</tr>
								<tr>
									<td class="una_linea"> Hora de entrada : </td>
									<td class="celda_hora_entrada" colspan="2">
										<div>
											<div>
												<input type="text" class="entrada" pattern="\d{2}:\d{2}" required>
											</div>
											<div>
												<input type="range" class="entrada_rango" min="0" max="44" step="1" list="horas" value="12" />
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
								<tr>
									<td> &nbsp; </td>
								</tr>
								<tr>
									<td class="una_linea"> Tipo de servicio : </td>
									<td class="celda_hora_entrada" colspan="2">
										<label>
											<input type="radio" name="tipo_servicio" value="externo" checked /> Externo
										</label>
										<br/>
										<label>
											<input type="radio" name="tipo_servicio" value="interno" /> Interno ( IPN )
										</label>
									</td>
								</tr>
							</table>
							<br/> <br/>
							<div class="mensajes_periodos_horas oculto"></div>
						</div>
						<div class="celda contenedor_fechas_horas_reporte">
							<h4 class="sin_margen_abajo"> <span class="paso_proceso"> #2. </span> ASISTENCIAS </h4>
							<br/>
							<div class="reporte_datos">
								<div class="tabla">
									<div class="celda">
										<table class="informacion">
											<tr>
												<td> Inicio : </td>
												<td>
													<input type="text" class="periodo_inicio elemento" readonly/>
												</td>
											</tr>
											<tr>
												<td> Cierre : </td>
												<td>
													<input type="text" class="periodo_cierre elemento" readonly/>
												</td>
											</tr>
										</table>
									</div>
									<div class="celda">
										<table class="informacion">
											<tr>
												<td> Mes : </td>
												<td>
													<input type="text" class="periodo_mes elemento" readonly/>
												</td>
											</tr>
											<tr>
												<td> &nbsp; &nbsp; &nbsp; Total de horas : </td>
												<td>
													<input type="text" class="periodo_horas elemento" readonly/>
												</td>
											</tr>
										</table>
									</div>
								</div>
							</div>
							<br/>
							<div class="tabla ancho_100p">
								<div class="contenedor_tabla_horas_reporte invisible centrado_margen">
									<div class="reporte tabla centrado_margen borde_redondo"></div>
								</div>	
							</div>
						</div>
						<div class="celda controles_formato_reporte">
							<h4> <span class="paso_proceso"> #3. </span> FORMATO </h4>
							<div>
								<table class="configuracion_formato_reporte">
									<tr>
										<td colspan="3" class="advertencia_formato"> 
											<hr/>
											<br/> « Verificar que el formato de los datos sea el correcto » <br/> <br/>
											<hr/>
											<br/> <br/>
										</td>
									</tr>
									<tr>
										<td class="alineacion_vertical_medio"> Plantilla : </td>
										<td colspan="2">
											<select class="tipo_plantilla" title="Plantilla">
												<option value="escom"> ESCOM </option>
												<option value="upiicsa"> UPIICSA </option>
												<option value="generica"> Genérica </option>
											</select>
										</td>
									</tr>
									<tr>
										<td> <br/> </td>
									</tr>
									<tr>
										<td colspan="2"></td>
										<td class="texto_centrado"> EJEMPLO : </td>
									</tr>
									<tr>
										<td> Mes : </td>
										<td>
											<select class="formato_mes_reporte">
												<option value="mes_mayusculas"> MES / MES-MES </option>
												<option value="mes_normal"> Mes / Mes-Mes </option>
											</select>
										</td>
										<td class="ejemplo_formato ejemplo_formato_mes_reporte"> ENERO / ENERO-FEBRERO </td>
									</tr>
									<tr>
										<td> Fechas : </td>
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
										<td> Período del reporte : </td>
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
						<div class="celda contenedor_datos_complementarios_reporte centrado_margen tabla">
							<div>
								<h4> <span class="paso_proceso"> #4. </span> DATOS DEL REPORTE </h4>
								<br/>
								<table>
									<tr>
										<td class="alineacion_vertical_arriba">
											<table class="datos_complementarios_reporte">
												<tr>
													<td class="una_linea"> No. de reporte : </td>
													<td>
														<input type="number" min="1" max="10" class="numero_reporte" placeholder="#" pattern="\d+" required/>
													</td>
												</tr>
												<tr>
													<td class="una_linea"> Alumno : </td>
													<td>
														<input type="text" class="nombre_alumno" required/>
													</td>
												</tr>
												<tr class="control_correo">
													<td class="una_linea"> Correo : </td>
													<td>
														<input type="text" class="correo" placeholder="usuario@cuenta.xy" pattern="[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,253}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,253}[a-zA-Z0-9])?)*" required/>
													</td>
												</tr>
												<tr>
													<td class="una_linea"> Carrera : </td>
													<td>
														<input type="text" class="carrera oculto"/>
														<select class="lista_carreras" required></select>
													</td>
												</tr>
												<tr>
													<td class="una_linea"> Boleta : </td>
													<td>
														<input type="text" class="boleta" maxlength="10" placeholder="20XXPPXXXX" pattern="\d{10}" required/>
													</td>
												</tr>
												<tr class="control_telefono">
													<td class="una_linea"> Teléfono : </td>
													<td>
														<input type="text" class="telefono" />
													</td>
												</tr>
												<!-- <tr class="oculto control_alumno_egresado">
													<td class="una_linea"> Egresado : </td>
													<td>
														<label>
															<input type="radio" name="alumno_egresado" value="y" />
															Si
														</label>
														<br/>
														<label>
															<input type="radio" name="alumno_egresado" value="n" checked />
															No
														</label>
													</td>
												</tr> -->
												<tr class="oculto control_alumno_semestre">
													<td class="una_linea"> Semestre : </td>
													<td>
														<input type="number" min="1" max="12" class="semestre" placeholder="#" pattern="\d+" />
													</td>
												</tr>
												<tr class="oculto control_alumno_grupo">
													<td class="una_linea"> Grupo : </td>
													<td>
														<input type="text" class="grupo"/>
													</td>
												</tr>
											</table>
										</td>
										<td> &nbsp; &nbsp; &nbsp; </td>
										<td class="alineacion_vertical_arriba">
											<table class="datos_complementarios_reporte">
												<tr>
													<td> Dependencia : </td>
													<td>
														<input type="text" class="dependencia" title="Lugar donde se realiza el servicio social" required/>
													</td>
												</tr>
												<tr>
													<td class="una_linea"> Responsable directo : </td>
													<td>
														<input type="text" class="nombre_responsable" placeholder="Nombre" required/>
													</td>
												</tr>
												<tr>
													<td></td>
													<td>
														<input type="text" class="puesto_responsable" placeholder="Cargo" required/>
													</td>
												</tr>
											</table>
										</td>
									</tr>
									<tr>
										<td colspan="3"> &nbsp; </td>
									</tr>
									<tr>
										<td colspan="3"> 
											<hr/> 
										</td>
									</tr>
									<tr>
										<td colspan="3"> &nbsp; </td>
									</tr>
									<tr>
										<td class="alineacion_vertical_arriba">
											<table class="datos_complementarios_reporte">
												<tr>
													<td class="una_linea"> Actividades : </td>
													<td>
														1.  <input type="text" class="actividad_reporte actividad_1" required/> <br/>
														2.  <input type="text" class="actividad_reporte actividad_2" required/> <br/>
														3.  <input type="text" class="actividad_reporte actividad_3" required/> <br/>
														4.  <input type="text" class="actividad_reporte actividad_4" /> <br/>
														5.  <input type="text" class="actividad_reporte actividad_5" /> <br/>
													</td>
												</tr>
											</table>
										</td>
										<td> &nbsp; &nbsp; &nbsp; </td>
										<td class="alineacion_vertical_arriba">
											<table class="datos_complementarios_reporte">
												<tr>
													<td class="una_linea"> Fecha de emisión : </td>
													<td>
														<input type="text" class="fecha_emision control_fecha" placeholder="dd-mm-aaaa" pattern="\d{2}-\d{2}-\d{4}" required/>
													</td>
												</tr>
												<tr>
													<td> &nbsp; </td>
												</tr>
												<tr>
													<td class="una_linea"> Horas acumuladas : </td>
													<td>
														<input type="number" min="0" class="total_horas_acumuladas_anterior" placeholder="#" pattern="\d+" required/>
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
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
	<script type="text/javascript" src="js/jquery.mousewheel.js"></script>
</html>