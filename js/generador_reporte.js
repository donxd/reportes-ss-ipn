
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
		window.datos.fecha_inicio = cambiaFormatoFecha( 'd-m-Y', $('.fecha_inicio').val() );
		window.datos.fecha_cierre = cambiaFormatoFecha( 'd-m-Y', $('.fecha_cierre').val() );
		window.datos.tipo_reporte = $(this).val();
	});
}

function inicializa_fechas (){
	switch ( $('input.tipo_reporte:checked').val() ){
		case 'pm':
			$('.fecha_inicio').val( window.informacion.periodo_actual + '01' );
			$('.fecha_cierre').val( window.informacion.periodo_actual + window.informacion.dias_mes_actual );
			break;
		case 'mm':
			$('.fecha_inicio').val( window.informacion.periodo_actual + '16' );
			$('.fecha_cierre').val( window.informacion.periodo_siguiente + '15' );
			break;
	}
}

function comportamiento_fecha_inicio (){
	$('.fecha_inicio').change( function(){
		$('.fecha_cierre').get(0).min = $(this).val();
		obtener_int_fecha ($(this).val());
	});
	$('.fecha_inicio').change( function(){
		// console.log('Inicializando periodo...');
		var fecha = $(this).val();
		fecha = fecha.split('-');
		//constructor yyyy, mm, dd, hh , MM, ss, ms
		// console.log("fecha-texto : "+JSON.stringify(fecha));
		fecha = new Date( fecha[0], fecha[1]-1, fecha[2]);
		console.log('cuadro : '+$('.fecha_inicio').val()+' fecha : '+fecha.toString());

		var tipo = (fecha.getDate() > 15) ? 1 : 0;
		// console.log("cuadro : "+$('.fecha_inicio').val()+" dia : "+fecha.getDate()+" tipo : "+tipo);
		// console.log("dia : "+fecha.getDate()+" tipo : "+tipo);
		
		
		$('input.tipo_reporte:eq('+tipo+')').attr('checked', true);
		

		// if (datos.fecha_inicio == undefined){
			datos.fecha_inicio = fecha.getDate()+'-'+(fecha.getMonth()+1)+'-'+fecha.getFullYear();
			datos.tipo_reporte = $('input.tipo_reporte:checked').val();
		// }

		// datos.fecha = fecha;
		// datos.tipo_reporte = tipo;
	});
}

function comportamiento_fecha_cierre (){
	$('.fecha_cierre').change( function(){
		$('.fecha_inicio').get(0).max = $(this).val();
		obtener_int_fecha ($(this).val());
	});
	$('.fecha_cierre').change( function(){
		var fecha = $('.fecha_cierre').val();
		fecha = fecha.split("-");
		fecha = new Date( fecha[0], fecha[1]-1, fecha[2]);
		// if (datos.fecha_cierre == undefined){
			datos.fecha_cierre = fecha.getDate()+'-'+(fecha.getMonth()+1)+'-'+fecha.getFullYear();
		// }
	});
}

function comportamiento_calculo_dias (){
	$('.fecha_inicio, .fecha_cierre, input.tipo_reporte, input.tipo_dias').change( function(){
		enviar_peticion_dias_asueto_rango();
	});
}

function enviar_peticion_dias_asueto_rango (){
	//calculo de dias
	// var fecha = $('.fecha_inicio').val();
	// var tipo_reporte = $("input.'tipo_reporte:checked");

	// console.log("--->"+JSON.stringify(datos));
	// console.log("Calculando los dias...\nEnviando (0) : " +datos.fecha+ " (1) : "+datos.tipo_reporte );

	var parametros = {};
	parametros.fecha_inicio = ( window.datos.fecha_inicio != undefined ) ? window.datos.fecha_inicio : '';
	parametros.fecha_cierre = ( window.datos.fecha_cierre != undefined ) ? window.datos.fecha_cierre : '';
	parametros.tipo_reporte = ( window.datos.tipo_reporte != undefined ) ? window.datos.tipo_reporte : '';

	if ( parametros.fecha_inicio != '' && parametros.tipo_reporte != '' ){
		// fecha_inicio
		// tipo_reporte
		// fecha_cierre - opcional

		console.log('parametros :\n'+JSON.stringify({ fecha_inicio : parametros.fecha_inicio, fecha_cierre : parametros.fecha_cierre, tipo_reporte : parametros.tipo_reporte, tipo_dias : $('input.tipo_dias:checked').val() }));
		
		$.ajax({
			type : 'POST',
			url  : 'recursos/',
			data : { fecha_inicio : parametros.fecha_inicio, fecha_cierre : parametros.fecha_cierre, tipo_reporte : parametros.tipo_reporte, tipo_dias : $('input.tipo_dias:checked').val() },
		}).done( function(respuesta){
			console.log('RES : '+respuesta);

			window.datos_reporte = JSON.parse(respuesta);
			genera_reporte();

			//agregando los valores obtenidos en los campos vacios
			console.log('---------------------\n'+datos.fecha_cierre+' : '+window.datos_reporte.periodo[1]+'\n---------------------\n');
			// if (parametros.fecha_cierre == ' || parametros.fecha_cierre.length < 1){
			if (typeof datos.fecha_cierre == 'undefined'){
				//revisar el formato que acepta el input[date]
				// console.log('---> no habia fecha cierre')
				// console.log('e ok : '+window.datos_reporte.periodo[1]+' -> '+ cambiaFormatoFecha('Y-m-d', window.datos_reporte.periodo[1] ) );
				$('.fecha_cierre').val( cambiaFormatoFecha('Y-m-d', window.datos_reporte.periodo[1] ) );
			}
		});
	}
}

function comportamiento_horas_x_dia (){
	$('.horas_dia').change( function (){
		var maximo = 47;
		var horas_dia = $(this).val();
		if ( horas_dia.length > 0){
			maximo = 47 - (horas_dia * 2);
		}
		$('.entrada_rango').attr('max', maximo);
	});
}

function comportamiento_hora_entrada (){
	$('.entrada_rango').on( 'input', function (){
		var rango = parseInt( $(this).val() );
		genera_hora_entrada(rango);
	});
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

function obtener_int_fecha (tFecha){
	var numero_fecha = parseInt( tFecha.replace(/\-/g,'') );
	// console.log("fecha ",numero_fecha);
	return numero_fecha;
}

function genera_hora_entrada (rango){
	// console.log('genera_hora_entrada : '+rango);
	var entrada = agregaCeros( parseInt(rango/2).toString() );
	if (rango%2	!= 0){
		//parametros
		entrada += ':30';
	} else {
		entrada += ':00';
	}
	// console.log('hora_entrada ('+rango+')->'+entrada);
	$('.entrada').html(entrada);
}

function genera_reporte (){

	var encabezados = ['No.', 'Fecha', 'Hora de entrada', 'Hora de salida', 'Horas por día'];

	//ingresando la información

	$('.periodo_inicio').val( window.datos_reporte.periodo[0] );
	$('.periodo_cierre').val( window.datos_reporte.periodo[1] );
	$('.periodo_mes').val( window.datos_reporte.mes);

	$('.reporte').html('<table></table>');
	var reporte = $('.reporte table').get(0);
	
	var hora_entrada = $('.entrada').html();
	var horas_dia = $('.horas_dia').val().length > 0 ? $('.horas_dia').val() : 0;
	
	var hora_salida = '';
	if (hora_entrada.length > 0 && horas_dia.length > 0){
		horas_dia = parseInt( $('.horas_dia').val() );

		hora_salida = hora_entrada.split(':');
		horas_dia = parseInt(hora_salida[0])+horas_dia;
		horas_dia = horas_dia.toString();
		hora_salida = agregaCeros(horas_dia)+':'+hora_salida[1];
		
		horas_dia = $('.horas_dia').val();
	}

	reporte.insertRow(0);
	for (var i = 0; i < encabezados.length; i++){
		reporte.rows[0].insertCell(i);
		reporte.rows[0].cells[i].innerHTML = encabezados[i];
	}
	window.datos_reporte.dias_totales = 0;
	for (var i = 0; i < window.datos_reporte.numero_dias_reporte; i++){
		reporte.insertRow(i+1);
		for (var j = 0; j < encabezados.length; j++){
			reporte.rows[i+1].insertCell(j);
		}
		reporte.rows[i+1].cells[0].innerHTML = i+1;
		reporte.rows[i+1].cells[1].innerHTML = window.datos_reporte.dias[i][0];
		if (window.datos_reporte.dias[i][1]){
			reporte.rows[i+1].cells[2].setAttribute('colspan','3');
			reporte.rows[i+1].cells[2].innerHTML = 'DIA FESTIVO';
			reporte.rows[i+1].cells[3].setAttribute('class','oculto');
			reporte.rows[i+1].cells[4].setAttribute('class','oculto');
		} else {
			reporte.rows[i+1].cells[2].innerHTML = (hora_entrada.length > 0) ? hora_entrada : '';
			reporte.rows[i+1].cells[3].innerHTML = (hora_salida.length > 0) ? hora_salida : '';
			reporte.rows[i+1].cells[4].innerHTML = (horas_dia.length > 0) ? horas_dia : '';
			window.datos_reporte.dias_totales++;
		}
	}
	$('.periodo_horas').val( window.datos_reporte.dias_totales * parseInt(horas_dia) );
	$('.reporte').removeClass('oculto');
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
	return fecha_salida.getFullYear()+'-'+agregaCeros( fecha_salida.getMonth()+1 )+'-'+agregaCeros( fecha_salida.getDate() );
}

function agregaCeros (numero){
	switch (typeof numero){
		case 'number':
			return ( numero < 10 ) ? '0'+numero : numero;
		case 'string':
			return ( numero.length < 2 ) ? '0'+numero : numero;
	}	
}