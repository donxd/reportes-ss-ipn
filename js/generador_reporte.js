
$( document ).ready( function(){
	inicializa_valores_tiempo();
	inicializa_variables_control();
	// comportamiento_dia_inicio_reporte();
	comportamiento_controles_fechas();
	comportamiento_peticion_calculo_dias();
	comportamiento_tipo_dias();
	comportamiento_horas_x_dia();
	comportamiento_descripcion_reporte();
	comportamiento_genera_reporte();
	comportamiento_descargar_reporte();
	comportamiento_ejemplos_formato_datos();
	// comportamiento_elementos_reporte();
	// comportamiento_redimension_pagina();
	// $('.fecha_inicio').focus();
	comportamiento_presentacion();
	comportamiento_desplazamiento_secciones();
	comportamiento_almacenamiento_datos();
	comportamiento_lista_plantillas();
	comportamiento_numero_reporte();
	comportamiento_hora_entrada();
	comportamiento_tipo_servicio();
});

function inicializa_valores_tiempo (){
	moment.locale('es');
	var tiempo_mes_anterior = moment().startOf( 'month' ).subtract( 1, 'days' );
	window.tiempo = {
		  periodo_actual : get_periodo( tiempo_mes_anterior )
		, dias_mes_actual : get_dias_mes( tiempo_mes_anterior )
		, periodo_siguiente : get_periodo_siguiente( tiempo_mes_anterior )
	};
}

function get_periodo ( tiempo ){
	return tiempo.format('-MM-YYYY');
}

function get_dias_mes ( tiempo ){
	return moment( tiempo ).endOf('month').format('DD');
}

function get_periodo_siguiente ( tiempo ){
	return get_periodo( moment( tiempo ).add( 1, 'month' ) );
}

function inicializa_variables_control (){
	window.peticion = {};
	window.reporte = {};
	window.tiempo;
	window.seleccion_plantilla_anterior = '';
	window.autorizacion_peticion = false;
}

function comportamiento_dia_inicio_reporte (){
	$('input.tipo_reporte').change( function (){
		inicializa_fechas();
	});
}

function inicializa_fechas (){
	var fecha_inicio = $('.fecha_inicio');
	var fecha_cierre = $('.fecha_cierre');
	switch ( $('input.tipo_reporte:checked').val() ){
		case 'pm':
			fecha_inicio.val( sprintf( '%s%s', '01', window.tiempo.periodo_actual ) );
			fecha_cierre.val( sprintf( '%s%s', window.tiempo.dias_mes_actual, window.tiempo.periodo_actual ) );
			inicializa_fechas_peticion();
			break;
		case 'mm':
			fecha_inicio.val( sprintf( '%s%s', '16', window.tiempo.periodo_actual ) );
			fecha_cierre.val( sprintf( '%s%s', '15', window.tiempo.periodo_siguiente ) );
			inicializa_fechas_peticion();
			break;
		case 'pp':
			if ( fecha_inicio.val().length > 0 && fecha_cierre.val().length > 0 ){
				inicializa_fechas_peticion();
			}
			break;
	}
}

function inicializa_fechas_peticion (){
	window.peticion.fecha_inicio = $('.fecha_inicio').val()
	window.peticion.fecha_cierre = $('.fecha_cierre').val()
	window.peticion.tipo_reporte = $('.tipo_reporte').val();
}

function comportamiento_controles_fechas (){
	crea_controles_fecha();
	agrega_comportamiento_controles_periodo_fecha();
	agrega_comportamiento_fecha_inicio();
	agrega_comportamiento_fecha_cierre();
	agrega_comportamiento_mes_actual();
	agrega_comportamiento_mes_pasado();
	agrega_comportamiento_mes_pasado_actual();
	agrega_comportamiento_cerrar_mes();
	agrega_comportamiento_cerrar_quincena();
}

function crea_controles_fecha (){
	$('.control_fecha').datetimepicker( get_opciones_control_fecha() );
}

function get_opciones_control_fecha (){
	return {
		  timepicker : false
		, allowBlank : true
		, validateOnBlur : false
		, format : 'd-m-Y' 
		, lang : 'es'
	};
}

function agrega_comportamiento_controles_periodo_fecha (){
	$('.control_periodo_fecha').focus( function (){
		$('input.tipo_reporte[value=pp]').prop( 'checked', true );
	});
	$('.control_periodo_fecha').change( function (){
		if ( valida_fechas_no_vacias() ){
			if ( valida_fechas_orden() ){
				agrega_mensaje_periodos_horas( '' );
				window.autorizacion_peticion = true;
				inicializa_fechas_peticion();
			} else {
				agrega_mensaje_periodos_horas( 'El orden de las fechas no es valido, verifiquelo e intenta nuevamente.' );
				muestra_mensajes_periodos();
				window.autorizacion_peticion = false;
			}
		}
	});
}

function valida_fechas_no_vacias (){
	return ( $('.fecha_inicio').val().length > 0 && $('.fecha_cierre').val().length > 0 );
}

function valida_fechas_orden (){
	var tiempo_inicio = get_tiempo_fecha_texto( $('.fecha_inicio').val() );
	var tiempo_cierre = get_tiempo_fecha_texto( $('.fecha_cierre').val() );

	return tiempo_inicio.isBefore( tiempo_cierre );
}

function get_tiempo_fecha_texto ( tiempo_texto ){
	return moment( tiempo_texto, 'DD-MM-YYYY' );
}

function agrega_comportamiento_fecha_inicio (){
	$( '.fecha_inicio' ).change( function(){
		inicializa_fecha_minima_cierre( $(this) );
		// obtener_entero_fecha( $(this).val() );
	// });
	// $('.fecha_inicio').change( function(){
		determina_tipo_reporte( $(this) );
	});

	$( '.fecha_inicio' ).focus( function(){
		$( '.fecha_cierre' ).val( '' );
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
		console.log( sprintf(' --- cuadro : %s ', control_fecha_inicio.val() ) );
		console.log( sprintf(' --- fecha : %s ', fecha.toString() ) );

		var tipo = (fecha.getDate() > 15) ? 1 : 0;
		// console.log("cuadro : "+control_fecha_inicio.val()+" dia : "+fecha.getDate()+" tipo : "+tipo);
		// console.log("dia : "+fecha.getDate()+" tipo : "+tipo);
		
		$( get_selector_tipo_reporte( tipo ) ).attr('checked', true);

		window.peticion.fecha_inicio = sprintf(
			'%s-%02d-%s'
			, fecha.getDate()
			, (fecha.getMonth()+1)
			, fecha.getFullYear() 
		);
		window.peticion.tipo_reporte = $('input.tipo_reporte:checked').val();
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

function agrega_comportamiento_fecha_cierre (){
	$( '.fecha_cierre' ).change( function(){
		inicializa_fecha_maxima_inicio( $(this) );
		// obtener_entero_fecha( $(this).val() );
	// });
	// $('.fecha_cierre').change( function(){
		inicializa_fecha_cierre_peticion( $(this) );
	});
}

function agrega_comportamiento_mes_actual (){
	$( '.mes_actual' ).click( function (){
		var tiempo_inicio_mes = get_tiempo_inicio_mes_actual();
		cerrar_mes_completo( tiempo_inicio_mes );
	});
}

function agrega_comportamiento_mes_pasado (){
	$( '.mes_pasado' ).click( function (){
		var tiempo_inicio_mes = get_tiempo_inicio_mes_anterior();
		cerrar_mes_completo( tiempo_inicio_mes );
	});
}

function agrega_comportamiento_mes_pasado_actual (){
	$( '.mes_pasado_actual' ).click( function (){
		var tiempo_inicio_mes = get_tiempo_inicio_quincena_mes_anterior_actual();
		cerrar_mes_quincena_actual( tiempo_inicio_mes );
	});
}

function cerrar_mes_completo ( tiempo_inicio ){
	var tiempo_cierre_mes = get_tiempo_cierre_mes( tiempo_inicio );
	$('.fecha_inicio').val( get_fecha_tiempo( tiempo_inicio ) );
	$('.fecha_cierre').val( get_fecha_tiempo( tiempo_cierre_mes ) );

	verifica_periodo_tiempo();
}

function cerrar_mes_quincena_actual ( tiempo_inicio ){
	var tiempo_inicio_mes_actual = get_tiempo_inicio_mes_actual();
	var tiempo_cierre_mes = get_tiempo_cierre_quincena_mes( tiempo_inicio_mes_actual );
	$('.fecha_inicio').val( get_fecha_tiempo( tiempo_inicio ) );
	$('.fecha_cierre').val( get_fecha_tiempo( tiempo_cierre_mes ) );

	verifica_periodo_tiempo();
}

function cerrar_mes_quincena ( tiempo_inicio ){
	var tiempo_cierre = get_tiempo_cierre_quincena_mes( tiempo_inicio );
	$('.fecha_inicio').val( get_fecha_tiempo( tiempo_inicio ) );
	$('.fecha_cierre').val( get_fecha_tiempo( tiempo_cierre ) );

	verifica_periodo_tiempo();
}

function cerrar_mes_quincena_siguiente ( tiempo_inicio ){
	var tiempo_cierre = get_tiempo_cierre_siguiente_quincena( tiempo_inicio );
	$('.fecha_inicio').val( get_fecha_tiempo( tiempo_inicio ) );
	$('.fecha_cierre').val( get_fecha_tiempo( tiempo_cierre ) );

	verifica_periodo_tiempo();
}

function get_tiempo_cierre_siguiente_quincena ( tiempo ){
	return get_tiempo_cierre_quincena_mes(
		get_tiempo_cierre_mes( tiempo )
			.add( 1, 'd' )
	);
}

function verifica_periodo_tiempo (){
	$('.fecha_cierre').trigger( 'change' );
}

function agrega_comportamiento_cerrar_mes (){
	$( '.cerrar_mes' ).click( function(){
		var fecha_inicio = $('.fecha_inicio').val();
		var tiempo_inicio_mes;
		if ( fecha_inicio.length > 0 ){
			tiempo_inicio_mes = get_tiempo_fecha_texto( fecha_inicio );
		} else {
			tiempo_inicio_mes = get_tiempo_inicio_mes_actual();
		}

		cerrar_mes_completo( tiempo_inicio_mes );
	});
}

function agrega_comportamiento_cerrar_quincena (){
	$( '.cerrar_quincena' ).click( function(){
		var fecha_inicio = $('.fecha_inicio').val();
		if ( fecha_inicio.length > 0 ){
			var tiempo_inicio_mes = get_tiempo_fecha_texto( fecha_inicio );
			if ( tiempo_inicio_mes.date() < 15 ){
				cerrar_mes_quincena( tiempo_inicio_mes );
			} else {
				cerrar_mes_quincena_siguiente( tiempo_inicio_mes );
			}
		}
	});
}

function get_tiempo_inicio_mes_actual (){
	return moment().date( 1 );
}

function get_tiempo_inicio_mes_anterior (){
	return moment().subtract( 1, 'M' ).date( 1 );
}

function get_tiempo_inicio_quincena_mes_anterior_actual (){
	return moment().subtract( 1, 'M' ).date( 16 );
}

function get_fecha_tiempo ( tiempo ){
	return tiempo.format( 'DD-MM-YYYY' );
}

function get_tiempo_cierre_mes ( tiempo ){
	var mes = tiempo.month();
	var anio = tiempo.year();
	if ( mes != 11 ){
		mes++;
	} else {
		mes = 0;
		anio++;
	}

	return moment( { year : anio, month : mes, date: 1 } ).subtract( 1, 'd' );

	// return tiempo.clone().add( 1, 'M' ).subtract( 1, 'd' );
}

function get_tiempo_cierre_quincena_mes ( tiempo ){
	return tiempo.clone().date( 15 );
}

function inicializa_fecha_maxima_inicio( control_fecha_cierre ){
	$('.fecha_inicio').attr( 'max', control_fecha_cierre.val() );
}

function inicializa_fecha_cierre_peticion ( control_fecha_cierre ){
	window.peticion.fecha_cierre = control_fecha_cierre.val();
}

function comportamiento_peticion_calculo_dias (){
	$('.control_periodo_fecha, input.tipo_reporte, input.tipo_dias').change( function(){
		if ( window.autorizacion_peticion ){
			prepara_peticion_dias_reporte();
		}
	});
}

function prepara_peticion_dias_reporte (){
	console.log('--- prepara_peticion_dias_reporte ---');
	var parametros = get_parametros_peticion_dias_reporte();
	if ( parametros.fecha_inicio != '' && parametros.tipo_reporte != '' ){
		console.log('parametros :\n'+JSON.stringify( parametros ) );
		enviar_peticion_dias_reporte();
	}
}

function get_parametros_peticion_dias_reporte (){
	return {
		  fecha_inicio : ( window.peticion.fecha_inicio != undefined ) ? window.peticion.fecha_inicio : ''
		, fecha_cierre : ( window.peticion.fecha_cierre != undefined ) ? window.peticion.fecha_cierre : ''
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

function agrega_mensaje_periodos_horas ( mensaje ){
	$( '.mensajes_periodos_horas' ).html( mensaje );
}

function muestra_mensajes_periodos (){
	muestra_elemento( '.mensajes_periodos_horas' );
}

function oculta_mensajes_periodos (){
	if ( $( '.mensajes_periodos_horas' ).html().length == 0 ){
		oculta_elemento( '.mensajes_periodos_horas' );
	}
}

function procesa_respuesta_peticion_dias_reporte ( respuesta ){

	almacena_respuesta( respuesta );
	if ( window.respuesta_peticion_dias_reporte.consulta ){
		respalda_repuesta_peticion_dias_reporte();
		genera_reporte();
		oculta_mensajes_periodos();
	} else {
		agrega_mensaje_periodos_horas( 'El período que ha seleccionado no pudo ser procesado, verifiquelo e intenta nuevamente.' );
		muestra_mensajes_periodos();
	}

}

function almacena_respuesta ( respuesta ){
	window.respuesta_peticion_dias_reporte = ( typeof( respuesta ) == 'string' ) ? JSON.parse( respuesta ) : respuesta;
}

function respalda_repuesta_peticion_dias_reporte (){
	window.respaldo_respuesta_peticion_dias_reporte = window.respuesta_peticion_dias_reporte;
}

function comportamiento_tipo_dias (){
	$('.tipo_dias').change( function (){
		calcula_maximo_horas_dia();
	});
}

function calcula_maximo_horas_dia (){
	var horas_dia = $('.horas_dia');
	switch ( $('.tipo_dias:checked').val() ){
		case 'es':
			horas_dia.attr( 'max', 4 );
			verifica_restriccion_maximo_horas_dia( horas_dia );
			break;
		case 'fs':
			horas_dia.attr( 'max', 10 );
			horas_dia.val( 10 );
			calcula_hora_maxima_entrada( horas_dia );
			verifica_hora_entrada( horas_dia );
			break;
	}
}

function verifica_restriccion_maximo_horas_dia ( control_horas_dia ){
	if ( control_horas_dia.val() > parseInt( control_horas_dia.attr('max') ) ){
		control_horas_dia.val( control_horas_dia.attr('max') );
	}
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
	$('.entrada_rango').trigger( 'input' );
}

function comportamiento_tipo_servicio (){
	$( '[name=tipo_servicio]' ).change( function(){
		genera_reporte();
	});
}

function calcula_hora_entrada ( control_hora_entrada ){
	var rango = parseInt( control_hora_entrada.val() );
	genera_hora_entrada( rango );
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
	$('.configuracion_formato_reporte select').on('change', function (){
		genera_reporte();
	})
}

function obtener_entero_fecha(tFecha){
	var numero_fecha = parseInt( tFecha.replace(/\-/g,'') );
	// console.log("fecha ",numero_fecha);
	return numero_fecha;
}

function genera_hora_entrada (rango){
	// console.log('genera_hora_entrada : '+rango);
	var entrada = agregaCeros( parseInt(rango/2).toString() );
	if ( ( rango % 2 ) != 0 ){
		entrada += ':30';
	} else {
		entrada += ':00';
	}
	// console.log('hora_entrada ('+rango+')->'+entrada);
	$('.entrada').val( entrada );
}

function genera_reporte (){
	asigna_datos_cabecera_reporte();
	crea_tabla_reporte();
	// muestra_opcion_descargar_reporte();
}

function asigna_datos_cabecera_reporte (){
	if ( window.respuesta_peticion_dias_reporte !== undefined ){
		window.reporte.total_horas_reporte = get_total_horas_reporte();
		window.reporte.horas_dia = get_horas_dia_reporte();
		if ( window.respuesta_peticion_dias_reporte.periodo != null ){
			$('.periodo_inicio').val( aplica_formato_periodo_reporte( window.respuesta_peticion_dias_reporte.periodo.fecha_inicio ) );
			$('.periodo_cierre').val( aplica_formato_periodo_reporte( window.respuesta_peticion_dias_reporte.periodo.fecha_cierre ) );
			$('.periodo_mes').val( aplica_formato_mes_reporte( window.respuesta_peticion_dias_reporte.mes ) );
			$('.periodo_horas').val( window.reporte.total_horas_reporte );
		}
		muestra_reporte();
	}
}

function get_total_horas_reporte (){
	var horas_dia = get_horas_dia_reporte();
	var numero_dias_laborales = get_numero_dias_laborales();
	return ( numero_dias_laborales * parseInt( horas_dia ) );
}

function get_horas_dia_reporte (){
	var horas_dia = $('.horas_dia').val();
	return horas_dia.length > 0 ? horas_dia : 0;
}

function get_numero_dias_laborales (){
	if ( window.respuesta_peticion_dias_reporte != null && window.respuesta_peticion_dias_reporte.dias != null ){
		var dias_asistencia = 0;
		// console.group();
		for ( var dia in window.respuesta_peticion_dias_reporte.dias ){
			// console.log( ' festivo : ', window.respuesta_peticion_dias_reporte.dias[ dia ].festivo );
			if ( !window.respuesta_peticion_dias_reporte.dias[ dia ].festivo ) 
				dias_asistencia++;
		}
		// console.groupEnd();
		return dias_asistencia;
	}
	return 0;
}

function aplica_formato_periodo_reporte ( fecha ){
	return get_formato_fecha( fecha, $('.formato_periodo_reporte').val() );
}

function get_formato_fecha ( fecha, formato ){
	switch ( formato ){
		case 'dMESaaaa'   : return formato_fecha( fecha, 'D MMM YYYY' ).toUpperCase().replace( '.', '', 'g');
		case 'dMESaaaa*'  : return formato_fecha( fecha, 'D MMMM YYYY' ).toUpperCase();
		case 'dMesaaaa'   : return formato_fecha( fecha, 'D MMM YYYY' ).replace( '.', '', 'g');
		case 'dMesaaaa*'  : return formato_fecha( fecha, 'D MMMM YYYY' );
		case 'ddMESaaaa'  : return formato_fecha( fecha, 'DD MMM YYYY' ).toUpperCase().replace( '.', '', 'g');
		case 'ddMESaaaa*' : return formato_fecha( fecha, 'DD MMMM YYYY' ).toUpperCase();
		case 'ddMesaaaa'  : return formato_fecha( fecha, 'DD MMM YYYY' ).replace( '.', '', 'g');
		case 'ddMesaaaa*' : return formato_fecha( fecha, 'DD MMMM YYYY' );
		case 'dMESaa'     : return formato_fecha( fecha, 'D MMM YY' ).toUpperCase().replace( '.', '', 'g');
		case 'dMESaa*'    : return formato_fecha( fecha, 'D MMMM YY' ).toUpperCase();
		case 'dMesaa'     : return formato_fecha( fecha, 'D MMM YY' ).replace( '.', '', 'g');
		case 'dMesaa*'    : return formato_fecha( fecha, 'D MMMM YY' );
		case 'ddMESaa'    : return formato_fecha( fecha, 'DD MMM YY' ).toUpperCase().replace( '.', '', 'g');
		case 'ddMESaa*'   : return formato_fecha( fecha, 'DD MMMM YY' ).toUpperCase();
		case 'ddMesaa'    : return formato_fecha( fecha, 'DD MMM YY' ).replace( '.', '', 'g');
		case 'ddMesaa*'   : return formato_fecha( fecha, 'DD MMMM YY' );
		case 'dmaaaa'     : return formato_fecha( fecha, 'D M YYYY' );
		case 'dmmaaaa'    : return formato_fecha( fecha, 'D MM YYYY' );
		case 'ddmaaaa'    : return formato_fecha( fecha, 'DD M YYYY' );
		case 'ddmmaaaa'   : return formato_fecha( fecha, 'DD MM YYYY' );
		case 'dmaa'       : return formato_fecha( fecha, 'D M YY' );
		case 'dmmaa'      : return formato_fecha( fecha, 'D MM YY' );
		case 'ddmaa'      : return formato_fecha( fecha, 'DD M YY' );
		case 'ddmmaa'     : return formato_fecha( fecha, 'DD MM YY' );
	}
}

function aplica_formato_mes_reporte ( mes_reporte ){	
	switch ( $('.formato_mes_reporte').val() ){
		case 'mes_mayusculas' : return mes_reporte.toUpperCase();
		case 'mes_normal'     : return mes_reporte;
	}
}

function aplica_formato_fecha_emision ( fecha ){
	return get_formato_fecha( fecha, $('.formato_fecha_emision').val() );
}

function crea_tabla_reporte (){
	fija_horas_reporte();
	$('.reporte').html( construye_tabla_reporte() );
	agrega_comportamiento_cambio_tipo_dia_asueto();
}

function fija_horas_reporte (){
	set_horas_reporte();
	aplica_formato_datos_horas_reporte();
}

function set_horas_reporte (){
	var hora_salida  = '';
	var hora_entrada = $('.entrada').val();
	var horas_dia    = $('.horas_dia').val();

	if ( hora_entrada.length > 0 && horas_dia.length > 0 ){
		horas_dia = parseInt( $('.horas_dia').val() );

		hora_salida = hora_entrada.split(':');
		horas_dia   = parseInt( hora_salida[ 0 ] ) + horas_dia;
		horas_dia   = horas_dia.toString();
		hora_salida = agregaCeros( horas_dia ) +' : '+ hora_salida[1];
		
		horas_dia = $('.horas_dia').val();
	}
	window.reporte.hora_entrada = hora_entrada;
	window.reporte.hora_salida  = hora_salida;
	window.reporte.horas_dia    = horas_dia;
}

function aplica_formato_datos_horas_reporte (){
	window.reporte.hora_entrada = aplica_formato_horas_reporte( window.reporte.hora_entrada );
	window.reporte.hora_salida  = aplica_formato_horas_reporte( window.reporte.hora_salida );
}

function aplica_formato_horas_reporte ( hora_sin_formato ){
	if ( hora_sin_formato != null && hora_sin_formato != '' ){
		switch ( $('.formato_horas_reporte').val() ){
			case 'horas_simple'  : return formato_horas_moment( hora_sin_formato, 'H:mm' );
			case 'horas_formato' : return formato_horas_moment( hora_sin_formato, 'HH:mm' );
		}
	}
}

function formato_horas_moment ( hora_sin_formato, formato ){
	return moment( hora_sin_formato, 'HH:mm' ).format( formato );
}

function construye_tabla_reporte (){
	var columnas_reporte = constantes_columnas();	

	return sprintf(
		'<table class="borde_redondo"> \
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
	if ( window.respuesta_peticion_dias_reporte != null ){
		var dias = window.respuesta_peticion_dias_reporte.dias.slice( 0 );
		for ( var dia in dias ){
			var celdas = [];
			var dia_reporte = dias[ dia ];
			for ( var columna in columnas_reporte ){
				celdas.push( get_contenido_celda_reporte( columnas_reporte[ columna ], dia_reporte ) );
			}
			window.contador_registro_reporte++;
			registros.push( sprintf( '<tr> %s </tr>', celdas.join('') ) );
		}
	}
	return registros.join('');
}

function get_contenido_celda_reporte ( tipo_columna_reporte, dia_reporte ){
	var columnas_reporte = constantes_columnas();
	var contenido = '';

	switch ( tipo_columna_reporte ){
		case columnas_reporte.NUMERO       : contenido = window.contador_registro_reporte; break;
		case columnas_reporte.FECHA        : contenido = aplica_formato_fecha_horas( dia_reporte.fecha ); break;
		case columnas_reporte.HORA_ENTRADA : contenido = determina_hora_entrada( dia_reporte ); break;
		case columnas_reporte.HORA_SALIDA  : contenido = determina_hora_salida( dia_reporte ); break;
		case columnas_reporte.HORAS_DIA    : contenido = determina_horas_dia( dia_reporte ); break;
	}

	return sprintf(
		'<td> %s </td>'
		, contenido
	);
}

function determina_hora_entrada ( dia_reporte ){
	var hora_entrada = get_reporte_hora_entrada();
	var dia_festivo_suspencion = '';

	if ( dia_reporte.festivo == false ){
		
		return hora_entrada;
	} else {
		
		return determina_valor_x_dia_festivo( 
			  dia_reporte
			, dia_festivo_suspencion
			, dia_festivo_suspencion
			, hora_entrada
			, dia_festivo_suspencion
		);
	}
}

function determina_valor_x_dia_festivo ( dia_reporte, valor_dia_festivo_oficial, valor_dia_festivo_no_oficial, 
		valor_dia_no_festivo, valor_dia_suspencion_escuela ){

	if ( dia_reporte.festivo == 'o' ){
		
		return valor_dia_festivo_oficial;
	} else {
		var tipo_servicio = $('[name=tipo_servicio]:checked').val();
		switch ( tipo_servicio ){
			case 'externo':
				if ( texto_contiene( dia_reporte.festivo, 'n' ) ){
					
					return valor_dia_festivo_no_oficial;	
				} else {

					return valor_dia_no_festivo;
				}
				break;
			case 'interno':
				if ( texto_contiene( dia_reporte.festivo, 'v' ) || 
						texto_contiene( dia_reporte.festivo, 'e' ) ){
					
					return valor_dia_suspencion_escuela;
				} else {

					return valor_dia_no_festivo;
				}
				break;
		}
	}
}

function determina_hora_salida ( dia_reporte ){
	var hora_salida = get_reporte_hora_salida();
	var dia_festivo_suspencion = '';

	if ( dia_reporte.festivo == false ){
		
		return hora_salida;
	} else {
		
		return determina_valor_x_dia_festivo( 
			  dia_reporte
			, dia_festivo_suspencion
			, dia_festivo_suspencion
			, hora_salida
			, dia_festivo_suspencion
		);
	}
}

function determina_horas_dia ( dia_reporte ){
	var horas_dia = get_reporte_horas_dia();

	if ( dia_reporte.festivo == false ){
		
		return horas_dia;
	} else {
		
		return determina_valor_x_dia_festivo( 
			  dia_reporte
			, 'DIA FESTIVO'
			, 'SUSPENCION DE LABORES'
			, horas_dia
			, 'SUSPENCION DE LABORES'
		);
	}
}

function texto_contiene ( texto, segmento ){
	return ( texto.indexOf( segmento ) != -1 );
}

function aplica_formato_fecha_horas ( fecha ){
	switch ( $('.formato_fechas_horas').val() ){
		case 'dd/mm/aa'   : return formato_fecha( fecha, 'DD/MM/YY' );
		case 'dd/mm/aaaa' : return formato_fecha( fecha, 'DD/MM/YYYY' );
		case 'dd-mm-aa'   : return formato_fecha( fecha, 'DD-MM-YY' );
		case 'dd-mm-aaaa' : return formato_fecha( fecha, 'DD-MM-YYYY' );
		case 'aaaa-mm-dd' : return formato_fecha( fecha, 'YYYY-MM-DD' );
		case 'aa-mm-dd'   : return formato_fecha( fecha, 'YY-MM-DD' );
	}
}

function formato_fecha ( fecha, formato ){
	return moment( fecha, 'DD-MM-YYYY' ).format( formato );
}

function get_reporte_hora_entrada (){
	return get_reporte_hora( window.reporte.hora_entrada );
}

function get_reporte_hora ( hora ){
	return ( hora != null && hora.length > 0 ) ? hora : '';
}

function get_reporte_hora_salida (){
	return get_reporte_hora( window.reporte.hora_salida );
}

function get_reporte_horas_dia (){
	return get_reporte_hora( window.reporte.horas_dia );
}

function muestra_reporte (){
	// ajusta_altura_tabla_horas_reporte();
	$('.contenedor_tabla_horas_reporte').removeClass( 'invisible' );
	// $('.contenedor_reporte_datos').removeClass( 'invisible' );
	// mostrar_datos_reporte();
}

function ajusta_altura_tabla_horas_reporte (){
	var altura_pagina              = $(document).height();
	var altura_cabecera            = $('.cabecera').height();
	var altura_separador           = 30;
	var altura_reporte_datos       = $('.contenedor_reporte_datos').height();
	var altura_reporte_tabla_horas = $('.contenedor_tabla_horas_reporte').height();

	var altura_elementos = altura_cabecera + altura_separador + altura_reporte_datos + altura_reporte_tabla_horas;
	var diferencia_altura = altura_pagina - ( altura_elementos );

	// if ( diferencia_altura > 0 ){
	// 	console.log( sprintf('--- diferencia altura : %d - ( %d + %d + %d + %d )', altura_pagina, altura_cabecera, altura_separador, altura_reporte_datos, altura_reporte_tabla_horas ) );
	// 	console.log( '--- diferencia altura : ', diferencia_altura );
	// 	$('.contenedor_tabla_horas_reporte').height( altura_reporte_tabla_horas + diferencia_altura );
	// } else {
	// 	$('.contenedor_tabla_horas_reporte').removeAttr('style');
	// }
}

function muestra_opcion_descargar_reporte (){
	$('.contenedor_enlace_descargar').removeClass( 'invisible' );
}

function procesa_respuesta_error ( respuesta ){
	console.log( sprintf( '--- error_respuesta : %s ', JSON.stringify( respuesta ) ) );
}

function cambiaFormatoFecha (formato_salida, fecha){
	var fecha_salida = fecha.split('-');

	switch (formato_salida){
		case 'Y-m-d':
			
			return get_fecha_anio_mes_dia()
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
	$('.entrada').focus( function (){
		$('.entrada_rango').focus();
	});
	$('.formulario_reporte').submit( function (){
		try {
			valida_hora_entrada();
			verifica_validacion_datos_reporte();
			agrega_datos_reporte();
			// muestra_informacion_reporte( $(this) );
		} catch ( error ){
			console.log( 'Error :', error );
			return false;
		}
	});
}

function valida_hora_entrada (){
	if ( $('.entrada').val().length == 0 ){
		$('.entrada_rango').focus();
		
		throw true;
	}
}

function verifica_validacion_datos_reporte (){
	var dependencia = $('.numero_reporte').prop( 'required' );
	// console.log( 'dependencia : ', dependencia );
	if ( !dependencia ){
		agrega_dependencia_campos_datos_reporte( get_campos_datos_reporte() );
		setTimeout( revalida_datos_reporte, 250 );
		
		throw true;
	}
}

function revalida_datos_reporte (){
	$('.ver_datos_formato').click();
	$('.descargar').click();
}

function get_campos_datos_reporte (){
	switch ( $('.tipo_plantilla').val() ){
		case 'escom':
			get_campos_escom();
			break;
		case 'upiicsa':
		default:
			get_campos_upiicsa();
			break;
	}
}

function get_campos_upiicsa (){
	return new Array(
		  '.numero_reporte'
		, '.carrera'
		, '.nombre_alumno'
		, '.boleta'
		, '.correo'
		, '.telefono'
		, '.dependencia'
		, '.nombre_responsable'
		, '.puesto_responsable'
		, '.actividad_1'
		, '.actividad_2'
		, '.actividad_3'
		, '.fecha_emision'
		, '.total_horas_acumuladas_anterior'
	);
}

function get_campos_escom (){
	return new Array(
		  '.numero_reporte'
		, '.carrera'
		, '.nombre_alumno'
		, '.boleta'
		// , '.correo'
		// , '.telefono'
		, '.dependencia'
		, '.nombre_responsable'
		, '.puesto_responsable'
		, '.actividad_1'
		, '.actividad_2'
		, '.actividad_3'
		, '.fecha_emision'
		, '.total_horas_acumuladas_anterior'
	);
}

function agrega_dependencia_campos_datos_reporte ( campos ){
	for ( var campo in campos ){
		agrega_dependencia_campo( campos[ campo ] );
	}
}

function agrega_dependencia_campo ( selector ){
	$( selector ).prop( 'required', true );
}

function remueve_dependencia_campo ( selector ){
	$( selector ).prop( 'required', false );
}

function agrega_datos_reporte (){
	var formulario = $('.formulario_reporte');
	var carrera = $('.tipo_plantilla').val() != 'generica' ? $('.lista_carreras').val() : $('.carrera').val();
	
	formulario.append( campo_dato_reporte( 'fecha_inicio', $('.periodo_inicio').val() ) );
	formulario.append( campo_dato_reporte( 'fecha_cierre', $('.periodo_cierre').val() ) );
	formulario.append( campo_dato_reporte( 'mes', $('.periodo_mes').val() ) );
	formulario.append( campo_dato_reporte( 'total_horas', window.reporte.total_horas_reporte ) );
	formulario.append( campo_dato_reporte( 'horas_dia', window.reporte.horas_dia ) );
	formulario.append( campo_dato_reporte( 'hora_entrada', get_reporte_hora_entrada() ) );
	formulario.append( campo_dato_reporte( 'hora_salida', get_reporte_hora_salida() ) );
	formulario.append( campo_dato_reporte( 'dias', JSON.stringify( aplica_formato_fecha_horas_dias() ) ) );
	formulario.append( campo_dato_reporte( 'numero_reporte', $('.numero_reporte').val() ) );
	formulario.append( campo_dato_reporte( 'carrera', carrera ) );
	formulario.append( campo_dato_reporte( 'nombre_alumno', $('.nombre_alumno').val() ) );
	formulario.append( campo_dato_reporte( 'boleta', $('.boleta').val() ) );
	formulario.append( campo_dato_reporte( 'correo', $('.correo').val() ) );
	formulario.append( campo_dato_reporte( 'telefono', $('.telefono').val() ) );
	formulario.append( campo_dato_reporte( 'dependencia', $('.dependencia').val() ) );
	formulario.append( campo_dato_reporte( 'responsable_nombre', $('.nombre_responsable').val() ) );
	formulario.append( campo_dato_reporte( 'responsable_puesto', $('.puesto_responsable').val() ) );
	formulario.append( campo_dato_reporte( 'fecha_emision', aplica_formato_fecha_emision( $('.fecha_emision').val() ) ) );
	formulario.append( campo_dato_reporte( 'total_horas_acumuladas_anterior', $('.total_horas_acumuladas_anterior').val() ) );
	formulario.append( campo_dato_reporte( 'plantilla', $('.tipo_plantilla').val() ) );
	formulario.append( campo_dato_reporte( 'fecha_inicio_estandar', $('.fecha_inicio').val() ) );
	formulario.append( campo_dato_reporte( 'fecha_cierre_estandar', $('.fecha_cierre').val() ) );
	formulario.append( campo_dato_reporte( 'fecha_emision_estandar', $('.fecha_emision').val() ) );

	// formulario.append( campo_dato_reporte( 'egresado', $('[name=alumno_egresado]:checked').val() ) );
	formulario.append( campo_dato_reporte( 'semestre', $('.semestre').val() ) );
	formulario.append( campo_dato_reporte( 'grupo'   , $('.grupo').val() ) );

	agrega_actividades_reporte();	
	setTimeout( limpiar_formulario, 2000 );
}

function aplica_formato_fecha_horas_dias (){
	// console.log( 'dias : ', JSON.stringify( window.respuesta_peticion_dias_reporte.dias ) );
	
	var valor_dia_festivo;
	var contador_registros = 0;
	var columnas_reporte = constantes_columnas();
	var dias_reporte = $('.reporte > table tr:gt(0)');

	var dias = JSON.parse( JSON.stringify( window.respuesta_peticion_dias_reporte.dias ) );

	for ( var dia in dias ){
		// console.log( 'dia : ', JSON.stringify( window.respuesta_peticion_dias_reporte.dias[ dia ].fecha ) );
		// dias[ dia ].fecha = aplica_formato_fecha_horas( dias[ dia ].fecha );
		// console.log( 'dia : ', JSON.stringify( window.respuesta_peticion_dias_reporte.dias[ dia ].fecha ) );
		
		valor_dia_festivo   = $( dias_reporte[ contador_registros ] ).find( 'td:eq( '+ columnas_reporte.HORAS_DIA +' )' ).text();
		dias[ dia ].fecha   = $( dias_reporte[ contador_registros ] ).find( 'td:eq( '+ columnas_reporte.FECHA +' )' ).text();
		dias[ dia ].festivo = get_tipo_dia_festivo( valor_dia_festivo.trim() );
		
		contador_registros++;
	}

	return dias;
}

function get_tipo_dia_festivo ( valor_dia_festivo ){
	if ( valor_dia_festivo == 'DIA FESTIVO' || valor_dia_festivo == 'SUSPENCION DE LABORES' ){

		return valor_dia_festivo;
	}

	return false;
}

function agrega_actividades_reporte (){
	var formulario = $('.formulario_reporte');
	agrega_actividad_reporte( formulario, '.actividad_1' );
	agrega_actividad_reporte( formulario, '.actividad_2' );
	agrega_actividad_reporte( formulario, '.actividad_3' );
	agrega_actividad_reporte( formulario, '.actividad_4' );
	agrega_actividad_reporte( formulario, '.actividad_5' );
}

function agrega_actividad_reporte ( formulario, selector_actividad ){
	var actividad = $( selector_actividad ).val();
	if ( actividad.length > 0 ){
		formulario.append( campo_dato_reporte( 'actividad[]', actividad ) );
	}
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
	// console.log( '--- formulario limpio ');
}

function comportamiento_redimension_pagina (){
	$( window ).resize( function (){
		ajusta_altura_tabla_horas_reporte();
	});
}

function comportamiento_ejemplos_formato_datos (){
	comportamiento_ejemplos_formato_mes_reporte();
	comportamiento_ejemplos_formato_fechas_horas();
	comportamiento_ejemplos_formato_horas_reporte();
	comportamiento_ejemplos_formato_periodo_reporte();
	comportamiento_ejemplos_formato_fecha_emision();
}

function comportamiento_ejemplos_formato_mes_reporte (){
	$('.formato_mes_reporte').change( function (){
		presenta_ejemplo_formato( 'formato_mes_reporte' );
	});
}

function comportamiento_ejemplos_formato_fechas_horas (){
	$('.formato_fechas_horas').change( function (){
		presenta_ejemplo_formato( 'formato_fechas_horas' );
	});
}

function comportamiento_ejemplos_formato_horas_reporte (){
	$('.formato_horas_reporte').change( function (){
		presenta_ejemplo_formato( 'formato_horas_reporte' );
	});
}

function comportamiento_ejemplos_formato_periodo_reporte (){
	$('.formato_periodo_reporte').change( function (){
		presenta_ejemplo_formato( 'formato_periodo_reporte' );
	});
}

function comportamiento_ejemplos_formato_fecha_emision (){
	$('.formato_fecha_emision').change( function (){
		presenta_ejemplo_formato( 'formato_fecha_emision' );
	});
}

function presenta_ejemplo_formato ( clase_elemento ){
	// console.log( 'selector : ', get_selector_ejemplo_formato( clase_elemento ) );
	// console.log( 'valor : ', get_ejemplo_formato( clase_elemento ) );
	$( get_selector_ejemplo_formato( clase_elemento ) ).html( get_ejemplo_formato( clase_elemento ) );
}

function get_selector_ejemplo_formato ( clase_elemento ){
	return sprintf( '.ejemplo_%s', clase_elemento );
}

function get_ejemplo_formato ( clase_elemento ){
	var selector_elemento = get_selector_clase_elemento( clase_elemento );
	switch ( clase_elemento ){
		case 'formato_mes_reporte'     : return get_ejemplo_formato_mes_reporte( selector_elemento );
		case 'formato_fechas_horas'    : return get_ejemplo_formato_fechas_horas( selector_elemento );
		case 'formato_horas_reporte'   : return get_ejemplo_formato_horas_reporte( selector_elemento );
		case 'formato_periodo_reporte' : return get_ejemplo_formato_periodo_reporte( selector_elemento );
		case 'formato_fecha_emision'   : return get_ejemplo_formato_fecha_emision( selector_elemento );
	}
}

function get_selector_clase_elemento ( clase_elemento ){
	return sprintf( '.%s', clase_elemento );
}

function get_ejemplo_formato_mes_reporte ( selector_elemento ){
	switch ( $( selector_elemento ).val() ){
		case 'mes_mayusculas' : return 'ENERO / ENERO-FEBRERO';
		case 'mes_normal'     : return 'Enero / Enero-Febrero';
	}
}

function get_ejemplo_formato_fechas_horas ( selector_elemento ){
	switch ( $( selector_elemento ).val() ){
		case 'dd/mm/aa'   : return '01/01/15';
		case 'dd/mm/aaaa' : return '01/01/2015';
		case 'dd-mm-aa'   : return '01-01-15';
		case 'dd-mm-aaaa' : return '01-01-2015';
		case 'aaaa-mm-dd' : return '2015-01-01';
		case 'aa-mm-dd'   : return '15-01-01';
	}
}

function get_ejemplo_formato_horas_reporte ( selector_elemento ){
	switch ( $( selector_elemento ).val() ){
		case 'horas_simple'  : return '8:00';
		case 'horas_formato' : return '08:00';
	}
}

function get_ejemplo_formato_periodo_reporte ( selector_elemento ){
	switch ( $( selector_elemento ).val() ){
		case 'dMESaaaa'   : return '1 ENE 2015';
		case 'dMESaaaa*'  : return '1 ENERO 2015';

		case 'dMesaaaa'   : return '1 Ene 2015';
		case 'dMesaaaa*'  : return '1 Enero 2015';

		case 'ddMESaaaa'  : return '01 ENE 2015';
		case 'ddMESaaaa*' : return '01 ENERO 2015';

		case 'ddMesaaaa'  : return '01 Ene 2015';
		case 'ddMesaaaa*' : return '01 Enero 2015';

		case 'dMESaa'     : return '1 ENE 15';
		case 'dMESaa*'    : return '1 ENERO 15';

		case 'dMesaa'     : return '1 Ene 15';
		case 'dMesaa*'    : return '1 Enero 15';

		case 'ddMESaa'    : return '01 ENE 15';
		case 'ddMESaa*'   : return '01 ENERO 15';

		case 'ddMesaa'    : return '01 Ene 15';
		case 'ddMesaa*'   : return '01 Enero 15';

		case 'dmaaaa'     : return '1 1 2015';
		case 'dmmaaaa'    : return '1 01 2015';
		case 'ddmaaaa'    : return '01 1 2015';
		case 'ddmmaaaa'   : return '01 01 2015';

		case 'dmaa'       : return '1 1 15';
		case 'dmmaa'      : return '1 01 15';
		case 'ddmaa'      : return '01 1 15';
		case 'ddmmaa'     : return '01 01 15';
	}
}

function get_ejemplo_formato_fecha_emision ( selector_elemento ){
	switch ( $( selector_elemento ).val() ){
		case 'dMESaaaa*'  : return '1 ENERO 2015';
		case 'ddMESaaaa*' : return '01 ENERO 2015';
		
		case 'dMesaaaa*'  : return '1 Enero 2015';
		case 'ddMesaaaa*' : return '01 Enero 2015';
		
		case 'dMESaaaa'   : return '1 ENE 2015';
		case 'ddMESaaaa'  : return '01 ENE 2015';
		
		case 'dMesaaaa'   : return '1 Ene 2015';
		case 'ddMesaaaa'  : return '01 Ene 2015';
		
		case 'ddmmaaaa'   : return '01 01 2015';
		case 'ddmmaa'     : return '01 01 15';
		
		case 'ddmaaaa'    : return '01 1 2015';
		case 'ddmaa'      : return '01 1 15';
		
		case 'dmmaaaa'    : return '1 01 2015';
		case 'dmmaa'      : return '1 01 15';
		
		case 'dmaaaa'     : return '1 1 2015';
		case 'dmaa'       : return '1 1 15';
	}
}

function comportamiento_elementos_reporte (){
	// comportamiento_ver_horas_reporte();
	// comportamiento_ver_datos_formato();
}

function comportamiento_ver_horas_reporte (){
	$('.ver_horas_reporte').click( function (){
		ocultar_datos_formato();
		mostrar_datos_reporte();
		mostrar_horas_reporte();
	});
}

function ocultar_datos_formato (){
	oculta_elemento( '.contenedor_datos_complementarios_reporte' );
}

function mostrar_datos_reporte (){
	$('.reporte_datos').removeClass( 'invisible' );
}

function mostrar_horas_reporte (){
	$('.contenedor_tabla_horas_reporte').removeClass( 'oculto invisible' );
}

function comportamiento_ver_datos_formato (){
	$('.ver_datos_formato').click( function (){
		ocultar_datos_reporte();
		ocultar_horas_reporte();
		mostrar_datos_formato();
	});
}

function ocultar_datos_reporte (){
	$('.reporte_datos').addClass('invisible');
}

function ocultar_horas_reporte (){
	$('.contenedor_tabla_horas_reporte').addClass('oculto invisible');
}

function mostrar_datos_formato (){
	muestra_elemento( '.contenedor_datos_complementarios_reporte' );
}

function muestra_informacion_reporte ( formulario ){
	console.log( JSON.stringify( formulario.serializeArray() ) );
}

function enfoca_seccion ( elemento ){
	// console.log( 'posicion : ', window.posiciones_secciones[ elemento ] );
	$( '.segmentacion' ).animate( 
		{ scrollLeft : window.posiciones_secciones[ elemento ] }
		, 300 
	);
}

function comportamiento_presentacion (){
	$( window ).resize( function (){
		ajusta_tamanio_principal();
	});
	setTimeout( ajusta_tamanio_principal, 200 );
}

function ajusta_tamanio_principal (){
	// $('.separador_cabecera').height( $('.cabecera').height() );
	// $('.separador_controles').height( $('.controles_contenido').height() + 50 );
	// $('.segmentacion').height( $('.controles_contenido').height() );
	// console.log( sprintf(' wh : %d, ch : %d, sch : %d', window.innerHeight, $('.cabecera').height(), $('.contenedor_controles_contenido').height() ) );
	$('.segmentacion').width( $( window ).width() );
	$('.segmentacion').height( window.innerHeight - $('.cabecera').height() - $('.contenedor_controles_contenido').height() );
}

function comportamiento_desplazamiento_secciones (){
	window.posiciones_secciones = {
		  'controles_datos_reporte'         		 : $('.controles_datos_reporte').offset().left
		, 'contenedor_fechas_horas_reporte' 		 : $('.contenedor_fechas_horas_reporte').offset().left
		, 'controles_formato_reporte'       		 : $('.controles_formato_reporte').offset().left
		, 'contenedor_datos_complementarios_reporte' : $('.contenedor_datos_complementarios_reporte').offset().left
	}
	$('.ver_periodos').click( function (){
		enfoca_seccion( 'controles_datos_reporte' );
	});

	$('.ver_fechas_horas').click( function (){
		enfoca_seccion( 'contenedor_fechas_horas_reporte' );
	});

	$('.ver_datos_formato').click( function (){
		enfoca_seccion( 'controles_formato_reporte' );
	});

	$('.ver_datos_complementarios').click( function (){
		enfoca_seccion( 'contenedor_datos_complementarios_reporte' );
	});

	$('.controles_contenido').mousewheel( function( event ){
		controla_desplazamiento( event );
	});
}

function controla_desplazamiento ( event ){
	var cambio_desplazamiento = 0;
	if ( event.deltaY != 0 ){
		cambio_desplazamiento = event.deltaY;
	}

	if ( event.deltaX != 0 ){
		cambio_desplazamiento = event.deltaX * -1;
	}

	$('.segmentacion').scrollLeft( $('.segmentacion').scrollLeft() - ( cambio_desplazamiento * 2 ) );
	event.preventDefault();
}

function comportamiento_almacenamiento_datos (){
	if ( verificacion_almacenamiento() ){
		agrega_comportamiento_preservar_informacion();
		agrega_eventos_almacenamiento();
		verifica_datos_almacenados();
	}
}

function verificacion_almacenamiento (){
	var test = 'test';
	try {
		localStorage.setItem( test, test );
		localStorage.removeItem( test );
		
		return true;
	} catch ( error ) {
		
		return false;
	}
}

function agrega_eventos_almacenamiento (){
	var elementos = get_lista_elementos_almacenamiento();

	for ( var elemento in elementos ){
		var clase = elementos[ elemento ];
		var selector = get_selector( clase );
		// console.log( sprintf( 'clase : %s, selector : %s ', clase, selector) );
		crea_evento_almacenamiento_datos( selector, clase );
	}
}

function get_lista_elementos_almacenamiento () {
	return [
		  'numero_reporte'
		, 'nombre_alumno'
		, 'correo'
		, 'carrera'
		, 'boleta'
		, 'telefono'
		, 'dependencia'
		, 'nombre_responsable'
		, 'puesto_responsable'
		, 'actividad_1'
		, 'actividad_2'
		, 'actividad_3'
		, 'actividad_4'
		, 'actividad_5'
		, 'fecha_emision'
		, 'total_horas_acumuladas_anterior'
		, 'lista_carreras'
		, 'tipo_plantilla'
		, 'semestre'
		, 'grupo'
		, 'horas_dia'
		, 'entrada_rango'
	];
}

function get_selector ( clase ){
	return sprintf( '.%s', clase );
}

function crea_evento_almacenamiento_datos ( selector, clase ){
	$( selector ).change( function (){
		if ( $('.preservar_informacion').prop( 'checked' ) ){
			almacena_dato( clase, $( selector ).val() );
		}
	});
}

function almacena_dato ( nombre, valor ){
	if ( valor.length > 0 ){
		localStorage.setItem( nombre, valor );
	} else {
		localStorage.removeItem( nombre );
	}
}

function agrega_comportamiento_preservar_informacion (){
	$( '.preservar_informacion' ).change( function(){
		if ( !$( this ).prop( 'checked' ) && confirmar_no_almacenar() ){
			eliminar_informacion_almacenada();
			almacena_olivido_informacion();
		} else {
			eliminar_informacion_almacenada();
			$( this ).prop( 'checked', true );
		}
	});
}

function confirmar_no_almacenar (){
	return confirm( 'Se eliminará la información almacenada y no se respaldarán los datos actuales, ¿Está seguro?' );
}

function eliminar_informacion_almacenada (){
	localStorage.clear();
}

function almacena_olivido_informacion (){
	if ( verificacion_almacenamiento() ){
		almacena_dato( 'preservar_informacion', 'false' );
	}
}

function verifica_datos_almacenados (){
	for ( var elemento in localStorage ){
		switch ( elemento ){
			case 'preservar_informacion':
				$( get_selector( elemento ) ).prop( 
					  'checked'
					, ( localStorage[ elemento ] === "true" )
				);
				break;
			default:
				$( get_selector( elemento ) ).val( localStorage[ elemento ] );
				break;
		}
	}
}

function comportamiento_numero_reporte (){
	$('.numero_reporte').change( function (){
		if ( $( this ).val() == '1' ){
			$('.total_horas_acumuladas_anterior').val( 0 );
			localStorage.setItem( 'total_horas_acumuladas_anterior', 0 );
		}
	});
}

function comportamiento_lista_plantillas (){
	$( '.tipo_plantilla' ).change( function (){
		cambia_plantilla();
	});
	cambia_plantilla();
}

function cambia_plantilla (){
	var plantilla = $('.tipo_plantilla').val();
	if ( plantilla != 'generica' ){
		if ( window.seleccion_plantilla_anterior == '' || plantilla != window.seleccion_plantilla_anterior ){
			switch( plantilla ){
				case 'upiicsa':
					agrega_opciones_carreras_lista( get_lista_carreras_upiicsa() );
					desactiva_campos_plantilla_escom();
					activa_campos_plantilla_upiicsa_generica();
					break;
				case 'escom':
					agrega_opciones_carreras_lista( get_lista_carreras_escom() );
					desactiva_campos_plantilla_upiicsa_generica();
					activa_campos_plantilla_escom();
					break;
			}
			window.seleccion_plantilla_anterior = plantilla;
		}
		desactiva_campos_plantilla_generica();
	} else {
		activa_campos_plantilla_generica();
		desactiva_campos_plantilla_escom();
	}
}

function get_lista_carreras_upiicsa (){
	return [
		  'INGENIERIA EN INFORMATICA'
		, 'INGENIERIA INDUSTRIAL'
		, 'INGENIERIA EN TRANSPORTE'
		, 'ADMINISTRACION INDUSTRIAL'
		, 'CIENCIAS DE LA INFORMATICA'
	];
}

function get_lista_carreras_escom (){
	return [
		  'ING EN SISTEMAS COMPUTACIONALES'
	];
}

function agrega_opciones_carreras_lista ( carreras ){
	$('.lista_carreras').html( genera_opciones_carreras( carreras ) );
}

function activa_campos_plantilla_escom (){
	// agrega_dependencia_campo( '[name=alumno_egresado]:first' );
	agrega_dependencia_campo( '.semestre' );
	agrega_dependencia_campo( '.grupo' );

	// muestra_elemento( '.control_alumno_egresado' );
	muestra_elemento( '.control_alumno_semestre' );
	muestra_elemento( '.control_alumno_grupo' );
}

function muestra_elemento ( selector ){
	$( selector ).removeClass( 'oculto' );
}

function oculta_elemento ( selector ){
	$( selector ).addClass( 'oculto' );
}

function desactiva_campos_plantilla_escom (){
	// remueve_dependencia_campo( '[name=alumno_egresado]:first' );
	remueve_dependencia_campo( '.semestre' );
	remueve_dependencia_campo( '.grupo' );

	// oculta_elemento( '.control_alumno_egresado' );
	oculta_elemento( '.control_alumno_semestre' );
	oculta_elemento( '.control_alumno_grupo' );
}

function activa_campos_plantilla_upiicsa_generica (){
	agrega_dependencia_campo( '.telefono' );
	agrega_dependencia_campo( '.correo' );

	muestra_elemento( '.control_telefono' );
	muestra_elemento( '.control_correo' );
}

function desactiva_campos_plantilla_upiicsa_generica (){
	remueve_dependencia_campo( '.telefono' );
	remueve_dependencia_campo( '.correo' );

	oculta_elemento( '.control_telefono' );
	oculta_elemento( '.control_correo' );
}

function activa_campos_plantilla_generica (){
	ocultar_lista_carreras();
	remueve_dependencia_lista_carreras();
	mostrar_control_carrera_manual();
	agrega_dependencia_carrera_manual();
}

function desactiva_campos_plantilla_generica (){
	ocultar_control_carrera_manual();
	remueve_dependencia_carrera_manual();
	mostrar_lista_carreras();
	agrega_dependencia_lista_carreras();
}

function genera_opciones_carreras ( carreras ){
	var opciones = [];
	for ( carrera in  carreras ){
		opciones.push( sprintf( 
			'<option value="%s"> %s </opction>'
				, carreras[ carrera ]
				, carreras[ carrera ]
			) 
		);
	}
	return opciones.join('');
}

function ocultar_control_carrera_manual (){
	oculta_elemento( '.carrera' );
}

function remueve_dependencia_carrera_manual (){
	remueve_dependencia_campo( '.carrera' );
}

function agrega_dependencia_lista_carreras (){
	agrega_dependencia_campo( '.lista_carreras' );
}

function mostrar_lista_carreras (){
	muestra_elemento( '.lista_carreras' );
}

function ocultar_lista_carreras (){
	oculta_elemento( '.lista_carreras' );
}

function remueve_dependencia_lista_carreras (){
	remueve_dependencia_campo( '.lista_carreras' );
}

function agrega_dependencia_carrera_manual (){
	agrega_dependencia_campo( '.carrera' );
}

function mostrar_control_carrera_manual (){
	muestra_elemento( '.carrera' );
}

function agrega_comportamiento_cambio_tipo_dia_asueto (){

	var columnas_reporte = constantes_columnas();
	var dias_reporte = $( '.reporte > table tr:gt(0)' );
	var horas_dia = $( '.horas_dia' ).val();
	var valor_horas_dia_registro;
	var dia_registro;

	dias_reporte.each( function( indice ){
		dia_registro = $( this ).find( 'td:eq( '+ columnas_reporte.HORAS_DIA +' )' );
		
		valor_horas_dia_registro = dia_registro.text().trim();

		if ( horas_dia != valor_horas_dia_registro ){
			dia_registro.addClass( 'dia_fecha_asueto' );
		}

	});

	$( '.dia_fecha_asueto' ).click( function(){
		cambia_tipo_dia_asueto( $(this) );
	});

}

function cambia_tipo_dia_asueto ( celda_registro_tipo_dia ){

	var horas_dia = $( '.horas_dia' ).val();
	var valor_tipo_dia = celda_registro_tipo_dia.text().trim();

	if ( valor_tipo_dia == 'DIA FESTIVO' ){
		celda_registro_tipo_dia.text( 'SUSPENCION DE LABORES' );
	}

	if ( valor_tipo_dia == 'SUSPENCION DE LABORES' ){
		celda_registro_tipo_dia.text( horas_dia );
		agrega_hora_entrada_salida_registro( 
			  celda_registro_tipo_dia.parent()
			, get_reporte_hora_entrada()
			, get_reporte_hora_salida()
		);
	}

	if ( valor_tipo_dia == horas_dia ){
		celda_registro_tipo_dia.text( 'DIA FESTIVO' );
		agrega_hora_entrada_salida_registro( 
			  celda_registro_tipo_dia.parent()
			, ''
			, ''
		);
	}
}

function agrega_hora_entrada_salida_registro ( registro, hora_entrada, hora_salida ){
	var columnas_reporte = constantes_columnas();

	registro.find( 'td:eq( '+ columnas_reporte.HORA_ENTRADA +' )' ).text( sprintf( ' %s ', hora_entrada ) );
	registro.find( 'td:eq( '+ columnas_reporte.HORA_SALIDA +' )' ).text( sprintf( ' %s ', hora_salida ) );
}
