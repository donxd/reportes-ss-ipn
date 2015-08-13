
$(document).ready( function(){
	inicializa_control_tiempo();
	inicializa_controles();
	comportamiento_dia_inicio_reporte();
	comportamiento_fecha_inicio();
	comportamiento_fecha_cierre();
	comportamiento_calculo_dias();
	comportamiento_horas_x_dia();
	comportamiento_hora_entrada();
	comportamiento_descripcion_reporte();
	comportamiento_genera_reporte();
	comportamiento_descargar_reporte();
	$('.fecha_inicio').focus();
});

function inicializa_control_tiempo (){
	var tiempo_actual = moment();
	window.informacion = {};
	window.informacion.periodo_actual = get_periodo( tiempo_actual );
	window.informacion.dias_mes_actual = get_dias_mes( tiempo_actual );
	window.informacion.periodo_siguiente = get_periodo_siguiente( tiempo_actual );
}

function get_periodo ( tiempo ){
	return tiempo.format('YYYY-MM-');
}

function get_dias_mes ( tiempo ){
	return moment( tiempo ).endOf('month').format('DD');
}

function get_periodo_siguiente ( tiempo ){
	return get_periodo( moment( tiempo ).add(1, 'month') );
}

function inicializa_controles (){
	window.datos = {};
	window.datos_reporte;
	window.informacion;
	$('input.tipo_dias:first').prop( 'checked', true );
}

function comportamiento_dia_inicio_reporte (){
	$('input.tipo_reporte').change( function (){
		inicializa_fechas();
	});
}

function inicializa_fechas (){
	switch ( $('input.tipo_reporte:checked').val() ){
		case 'pm':
			$('.fecha_inicio').val( sprintf( '%s%s', window.informacion.periodo_actual, '01' ) );
			$('.fecha_cierre').val( sprintf( '%s%s', window.informacion.periodo_actual, window.informacion.dias_mes_actual ) );
			inicializa_fechas_peticion();
			break;
		case 'mm':
			$('.fecha_inicio').val( sprintf( '%s%s', window.informacion.periodo_actual, '16' ) );
			$('.fecha_cierre').val( sprintf( '%s%s', window.informacion.periodo_siguiente, '15' ) );
			inicializa_fechas_peticion();
			break;
		case 'pp':
			if ( $('.fecha_inicio').val().length > 0 && $('.fecha_cierre').val().length > 0 ){
				inicializa_fechas_peticion();
			}
			break;
	}
}

function inicializa_fechas_peticion (){
	window.datos.fecha_inicio = cambiaFormatoFecha( 'd-m-Y', $('.fecha_inicio').val() );
	window.datos.fecha_cierre = cambiaFormatoFecha( 'd-m-Y', $('.fecha_cierre').val() );
	window.datos.tipo_reporte = $('.tipo_reporte').val();
}

function comportamiento_fecha_inicio (){
	$('.fecha_inicio').change( function(){
		inicializa_fecha_minima_cierre( $(this) );
		// obtener_entero_fecha( $(this).val() );
	});
	$('.fecha_inicio').change( function(){
		determina_tipo_reporte( $(this) );
	});
}

function inicializa_fecha_minima_cierre ( control_fecha_inicio ){
	$('.fecha_cierre').attr( 'min', control_fecha_inicio.val() );
}

function determina_tipo_reporte ( control_fecha_inicio ){
	var tipo_reporte_seleccionando = $('input.tipo_reporte:checked'); 
	if ( tipo_reporte_seleccionando.length > 0 && tipo_reporte_seleccionando.val() != 'pp' ){
		// console.log('Inicializando periodo...');
		var fecha = control_fecha_inicio.val();
		fecha = fecha.split('-');
		//constructor yyyy, mm, dd, hh , MM, ss, ms
		// console.log("fecha-texto : "+JSON.stringify(fecha));
		fecha = new Date( fecha[0], fecha[1]-1, fecha[2]);
		console.log( sprintf(' --- cuadro : %s ', $('.fecha_inicio').val() ) );
		console.log( sprintf(' --- fecha : %s ', fecha.toString() ) );

		var tipo = (fecha.getDate() > 15) ? 1 : 0;
		// console.log("cuadro : "+$('.fecha_inicio').val()+" dia : "+fecha.getDate()+" tipo : "+tipo);
		// console.log("dia : "+fecha.getDate()+" tipo : "+tipo);
		
		$( get_selector_tipo_reporte( tipo ) ).attr('checked', true);

		datos.fecha_inicio = sprintf(
			'%s-%02d-%s'
			, fecha.getDate()
			, (fecha.getMonth()+1)
			, fecha.getFullYear() 
		);
		datos.tipo_reporte = $('input.tipo_reporte:checked').val();
	} else {
		var indice_tipo_reporte_manual = 2;
		$( get_selector_tipo_reporte( indice_tipo_reporte_manual ) ).attr('checked', true);		
	}
}

function get_selector_tipo_reporte ( tipo_reporte ){
	return sprintf( 
		'input.tipo_reporte:eq(%s)'
		, tipo_reporte
	);
}

function comportamiento_fecha_cierre (){
	$('.fecha_cierre').change( function(){
		$('.fecha_inicio').attr( 'max', $(this).val() );
		// obtener_entero_fecha( $(this).val() );
	});
	$('.fecha_cierre').change( function(){
		var fecha = $('.fecha_cierre').val();
		fecha = fecha.split("-");
		fecha = new Date( fecha[0], fecha[1]-1, fecha[2]);
		window.datos.fecha_cierre = sprintf(
			'%s-%02d-%s'
			, fecha.getDate()
			, ( fecha.getMonth()+1 )
			, fecha.getFullYear()
		);
	});
}

function comportamiento_calculo_dias (){
	$('.fecha_inicio, .fecha_cierre, input.tipo_reporte, input.tipo_dias').change( function(){
		prepara_peticion_dias_reporte();
	});
}

function prepara_peticion_dias_reporte (){
	//calculo de dias
	// var fecha = $('.fecha_inicio').val();
	// var tipo_reporte = $("input.'tipo_reporte:checked");

	// console.log("--->"+JSON.stringify(datos));
	// console.log("Calculando los dias...\nEnviando (0) : " +datos.fecha+ " (1) : "+datos.tipo_reporte );
	var parametros = get_parametros_peticion_dias_reporte();
	if ( parametros.fecha_inicio != '' && parametros.tipo_reporte != '' ){
		console.log('parametros :\n'+JSON.stringify( parametros ) );
		enviar_peticion_dias_reporte();
	}
}

function get_parametros_peticion_dias_reporte (){
	return {
		  fecha_inicio : ( window.datos.fecha_inicio != undefined ) ? window.datos.fecha_inicio : ''
		, fecha_cierre : ( window.datos.fecha_cierre != undefined ) ? window.datos.fecha_cierre : ''
		, tipo_dias    : $('input.tipo_dias:checked').val()
	};
}

function enviar_peticion_dias_reporte (){
	$.ajax({
		type : 'POST',
		url  : 'recursos/',
		data : get_parametros_peticion_dias_reporte(),
	}).done( function( respuesta ){
		procesa_respuesta_peticion_dias_reporte( respuesta );
	}).fail( function (respuesta){
		procesa_respuesta_error( respuesta );
	});
}

function procesa_respuesta_peticion_dias_reporte ( respuesta ){
	console.log(' --- procesa_respuesta_peticion_dias_reporte --- ');

	almacena_respuesta( respuesta );
	genera_reporte();

	
	console.log('---------------------');
	console.log( sprintf( 
			'%s : %s '
			, window.datos.fecha_cierre
			, window.datos_reporte.periodo.fecha_cierre 
		) 
	);
	console.log('---------------------');
	
	// if (parametros.fecha_cierre == ' || parametros.fecha_cierre.length < 1){
	if (typeof datos.fecha_cierre == 'undefined'){
		//revisar el formato que acepta el input[date]
		// console.log('---> no habia fecha cierre')
		// console.log('e ok : '+window.datos_reporte.periodo[1]+' -> '+ cambiaFormatoFecha('Y-m-d', window.datos_reporte.periodo[1] ) );
		$('.fecha_cierre').val( cambiaFormatoFecha('Y-m-d', window.datos_reporte.fecha_cierre ) );
	}
}

function almacena_respuesta ( respuesta ){
	window.datos_reporte = ( typeof( respuesta ) == 'string' ) ? JSON.parse(  respuesta ) : respuesta;
}

function comportamiento_horas_x_dia (){
	$('.horas_dia').change( function (){
		calcula_hora_maxima_entrada( $(this) );
		verifica_hora_entrada( $(this) );
	});
}

function calcula_hora_maxima_entrada ( control_horas_dia ){
	var tiempo_maximo_entrada = 47;
	var tiempo_maximo_salida = 48;
	var horas_dia = control_horas_dia.val();
	if ( horas_dia.length > 0 ){
		tiempo_maximo_entrada = tiempo_maximo_salida - (horas_dia * 2);
	}
	$('.entrada_rango').attr('max', tiempo_maximo_entrada);
}

function verifica_hora_entrada ( control_horas_dia ){
	// console.log( '--- verifica_hora_entrada ---' );
	var control_hora_entrada = $('.entrada_rango');
	var horas_dia = parseInt( control_horas_dia.val() );
	var hora_entrada = parseInt( control_hora_entrada.val() );
	var tiempo_entrada = hora_entrada + ( horas_dia * 2 );
	var tiempo_maximo_salida = 48;
	// console.log( '--- entrada : ', tiempo_entrada );
	if ( tiempo_entrada >= tiempo_maximo_salida ){
		var diferencia_tiempo = tiempo_entrada - tiempo_maximo_salida;
		var ajuste_tiempo = hora_entrada - diferencia_tiempo;
		control_hora_entrada.val( ajuste_tiempo );
		calcula_hora_entrada( control_hora_entrada );
	}
}

function comportamiento_hora_entrada (){
	$('.entrada_rango').on( 'input', function (){
		calcula_hora_entrada( $(this) );
	});
}

function calcula_hora_entrada ( control_hora_entrada ){
	var rango = parseInt( control_hora_entrada.val() );
	genera_hora_entrada(rango);
}

function comportamiento_descripcion_reporte (){
	$('.periodo_inicio, .periodo_cierre, .periodo_mes, .periodo_horas').click( function(){
		$(this).select();
	});
}

function comportamiento_genera_reporte (){
	$('.entrada_rango, .horas_dia').on('input', function(){
		genera_reporte();
	});
	$('.horas_dia').change( function(){
		genera_reporte();
	});
}

function obtener_entero_fecha(tFecha){
	var numero_fecha = parseInt( tFecha.replace(/\-/g,'') );
	// console.log("fecha ",numero_fecha);
	return numero_fecha;
}

function genera_hora_entrada (rango){
	// console.log('genera_hora_entrada : '+rango);
	var entrada = agregaCeros( parseInt(rango/2).toString() );
	if (rango%2	!= 0){
		entrada += ':30';
	} else {
		entrada += ':00';
	}
	// console.log('hora_entrada ('+rango+')->'+entrada);
	$('.entrada').html(entrada);
}

function genera_reporte (){
	asigna_datos_cabecera_reporte();
	crea_tabla_reporte();
	muestra_reporte();
	mostrar_opcion_descargar_reporte();
}

function asigna_datos_cabecera_reporte (){
	window.datos_reporte.total_horas_reporte = get_total_horas_reporte();
	window.datos_reporte.horas_dia = get_horas_dia_reporte();
	$('.periodo_inicio').val( window.datos_reporte.periodo.fecha_inicio );
	$('.periodo_cierre').val( window.datos_reporte.periodo.fecha_cierre );
	$('.periodo_mes').val( window.datos_reporte.mes );
	$('.periodo_horas').val( window.datos_reporte.total_horas_reporte );
}

function get_total_horas_reporte (){
	var horas_dia = get_horas_dia_reporte();
	return ( window.datos_reporte.dias.length * parseInt( horas_dia ) );
}

function get_horas_dia_reporte (){
	return $('.horas_dia').val().length > 0 ? $('.horas_dia').val() : 0;
}

function crea_tabla_reporte (){
	fija_horas_reporte();
	$('.reporte').html( construye_tabla_reporte() );
}

function fija_horas_reporte (){
	var hora_salida = '';
	var hora_entrada = $('.entrada').html();
	var horas_dia = $('.horas_dia').val();

	if (hora_entrada.length > 0 && horas_dia.length > 0){
		horas_dia = parseInt( $('.horas_dia').val() );

		hora_salida = hora_entrada.split(':');
		horas_dia = parseInt(hora_salida[0])+horas_dia;
		horas_dia = horas_dia.toString();
		hora_salida = agregaCeros(horas_dia)+':'+hora_salida[1];
		
		horas_dia = $('.horas_dia').val();
	}
	window.reporte = {
		  hora_entrada : hora_entrada
		, hora_salida  : hora_salida
		, horas_dia    : horas_dia
	};
	// console.log( '--- datos_reporte : ', JSON.stringify( window.reporte ) );
}

function construye_tabla_reporte (){
	var columnas_reporte = constantes_columnas();	

	return sprintf(
		'<table> \
			<tr> %s </tr> %s \
		</table>'
		, get_columnas_tabla_reporte( columnas_reporte )
		, get_registros_tabla_reporte( columnas_reporte )
	);
}

function constantes_columnas (){
	return {
		  NUMERO       : 0
		, FECHA        : 1
		, HORA_ENTRADA : 2
		, HORA_SALIDA  : 3
		, HORAS_DIA    : 4
	}
}

function get_titulos_columnas_reporte (){
	return [ 'No.', 'Fecha', 'Hora de entrada', 'Hora de salida', 'Horas por día' ];	
}

function get_columnas_tabla_reporte ( columnas_reporte ){
	var columnas_tabla = [];
	var titulo_columnas = get_titulos_columnas_reporte();
	for ( var columna in columnas_reporte ){
		columnas_tabla.push( sprintf( '<td> %s </td>', titulo_columnas[ columnas_reporte[ columna ] ] ) )
	}
	return columnas_tabla.join('');
}

function get_titulos_columnas (){
	return [ 'No.', 'Fecha', 'Hora de entrada', 'Hora de salida', 'Horas por día' ];
}

function get_registros_tabla_reporte ( columnas_reporte ){
	var registros = [];
	window.contador_registro_reporte = 1;
	for ( var contador_dias in window.datos_reporte.dias ){
		var celdas = [];
		window.dia_reporte = window.datos_reporte.dias[ contador_dias ];
		for ( var columna in columnas_reporte ){
			celdas.push( get_contenido_celda_reporte( columnas_reporte[ columna ], dia_reporte ) );
		}
		window.contador_registro_reporte++;
		registros.push( sprintf( '<tr> %s </tr>', celdas.join('') ) );
	}
	return registros.join('');
}

function get_contenido_celda_reporte ( tipo_columna_reporte ){
	var columnas_reporte = constantes_columnas();
	var contenido = '';
	switch ( tipo_columna_reporte ){
		case columnas_reporte.NUMERO:
			contenido = window.contador_registro_reporte;
			break;
		case columnas_reporte.FECHA:
			contenido = window.dia_reporte.fecha;
			break;
		case columnas_reporte.HORA_ENTRADA:
			if ( !window.dia_reporte.festivo )
				contenido = get_reporte_hora_entrada();
			break;
		case columnas_reporte.HORA_SALIDA:
			if ( !window.dia_reporte.festivo )
				contenido = get_reporte_hora_salida();
			else 
				contenido = 'DIA FESTIVO';
			break;
		case columnas_reporte.HORAS_DIA:
			if ( !window.dia_reporte.festivo )
				contenido = get_reporte_horas_dia();
			break;
	}
	return sprintf(
		'<td> %s </td>'
		, contenido
	);
}

function get_reporte_hora_entrada (){
	return get_reporte_hora( window.reporte.hora_entrada );
}

function get_reporte_hora ( hora ){
	return ( hora.length > 0 ) ? hora : '';
}

function get_reporte_hora_salida (){
	return get_reporte_hora( window.reporte.hora_salida );
}

function get_reporte_horas_dia (){
	return get_reporte_hora( window.reporte.horas_dia );
}

function muestra_reporte (){
	$('.reporte').removeClass('oculto');
}

function mostrar_opcion_descargar_reporte (){
	$('.contenedor_enlace_descargar').removeClass('oculto');
}

function procesa_respuesta_error ( respuesta ){
	console.log( sprintf( '--- error_respuesta : %s ', JSON.stringify( respuesta ) ) );
}

function cambiaFormatoFecha (formato_salida, fecha){
	var fecha_salida = fecha.split('-');

	switch (formato_salida){
		case 'Y-m-d':
			return get_fecha_anio_mes_dia(  )
			break;
		case 'd-m-Y':
			//constructor yyyy, mm, dd, hh , MM, ss, ms
			// console.log('y :'+fecha_salida[0]+' m : '+fecha_salida[1]+' d: '+fecha_salida[2])
			fecha_salida = new Date( fecha_salida[0], fecha_salida[1]-1, fecha_salida[2]);
			fecha_salida = agregaCeros( fecha_salida.getDate() )+'-'+agregaCeros( fecha_salida.getMonth()+1 )+'-'+fecha_salida.getFullYear();
			// fecha_salida = fecha_salida.getFullYear()+'-'+agregaCeros( fecha_salida.getMonth()+1 )+'-'+agregaCeros( fecha_salida.getDate() );
			break;
	}
	// console.log('entrada : '+fecha+'\nsalida : '+fecha_salida);
	return fecha_salida;
}

function get_fecha_anio_mes_dia (){
	fecha_salida = new Date( fecha_salida[2], fecha_salida[1]-1, fecha_salida[0]);
	return sprintf(
		'%s-%s-%s'
		, fecha_salida.getFullYear()
		, agregaCeros( fecha_salida.getMonth()+1 )
		, agregaCeros( fecha_salida.getDate() )
	);
}

function agregaCeros ( numero_parametro ){
	var numero = parseInt( numero_parametro );
	return sprintf( '%02d', numero );
}

function comportamiento_descargar_reporte (){
	$('.formulario_reporte').submit( function (){
		agrega_datos_reporte();
	});
}

function agrega_datos_reporte (){
	var formulario = $('.formulario_reporte');
	formulario.append( campo_dato_reporte( 'fecha_inicio', window.datos_reporte.periodo.fecha_inicio ) );
	formulario.append( campo_dato_reporte( 'fecha_cierre', window.datos_reporte.periodo.fecha_cierre ) );
	formulario.append( campo_dato_reporte( 'mes', window.datos_reporte.mes ) );
	formulario.append( campo_dato_reporte( 'total_horas', window.datos_reporte.total_horas_reporte ) );
	formulario.append( campo_dato_reporte( 'horas_dia', window.datos_reporte.horas_dia ) );
	formulario.append( campo_dato_reporte( 'hora_entrada', get_reporte_hora_entrada() ) );
	formulario.append( campo_dato_reporte( 'hora_salida', get_reporte_hora_salida() ) );
	formulario.append( campo_dato_reporte( 'dias', JSON.stringify( window.datos_reporte.dias ) ) );
	setTimeout( limpiar_formulario, 2000 );
}

function campo_dato_reporte ( nombre, valor ){
	return sprintf( 
		"<input type='hidden' name='%s' value='%s'/>"
		, nombre
		, valor
	);
}

function limpiar_formulario (){
	$('.formulario_reporte input[type=hidden]').remove();
	console.log( '--- formulario limpio ');
}