var datos = {};
var datos_reporte;
var informacion = [];
$(document).ready( function(){
	//inicializacion
	$('input[name=tipo_reporte]').change( function (){
		switch ( $("input[name=tipo_reporte]:checked").val() ){
			case "pm":
				$("#fecha_inicio").val( informacion[0] + "01" );
				$("#fecha_cierre").val( informacion[0] + informacion[1] );
				break;
			case "mm":
				$("#fecha_inicio").val( informacion[0] + "16" );
				$("#fecha_cierre").val( informacion[2] + "15" );
				break;
		} //switch
		// $(this).attr('checked', true);
		// console.log("e ?1 : "+$("#fecha_inicio").val()+" -> "+ cambiaFormatoFecha("Y-m-d", $("#fecha_inicio").val() ) );
		datos.fecha_inicio = cambiaFormatoFecha("d-m-Y", $("#fecha_inicio").val());
		// console.log("e ?2 : "+$("#fecha_cierre").val()+" -> "+ cambiaFormatoFecha("Y-m-d", $("#fecha_cierre").val() ) );
		datos.fecha_cierre = cambiaFormatoFecha("d-m-Y", $("#fecha_cierre").val());
		datos.tipo_reporte = $(this).val();
	});
	$("#fecha_inicio").change( function(){
		// console.log("Inicializando periodo...");
		var fecha = $(this).val();
		fecha = fecha.split("-");
		//constructor yyyy, mm, dd, hh , MM, ss, ms
		// console.log("fecha-texto : "+JSON.stringify(fecha));
		fecha = new Date( fecha[0], fecha[1]-1, fecha[2]);
		console.log("cuadro : "+$("#fecha_inicio").val()+" fecha : "+fecha.toString());

		var tipo = (fecha.getDate() > 15) ? 1 : 0;
		// console.log("cuadro : "+$("#fecha_inicio").val()+" dia : "+fecha.getDate()+" tipo : "+tipo);
		// console.log("dia : "+fecha.getDate()+" tipo : "+tipo);
		
		
		$("input[name=tipo_reporte]:eq("+tipo+")").attr('checked', true);
		

		// if (datos.fecha_inicio == undefined){
			datos.fecha_inicio = fecha.getDate()+"-"+(fecha.getMonth()+1)+"-"+fecha.getFullYear();
			datos.tipo_reporte = $("input[name='tipo_reporte']:checked").val();
		// }

		// datos.fecha = fecha;
		// datos.tipo_reporte = tipo;
	});
	$("#fecha_cierre").change( function(){
		var fecha = $("#fecha_cierre").val();
		fecha = fecha.split("-");
		fecha = new Date( fecha[0], fecha[1]-1, fecha[2]);
		// if (datos.fecha_cierre == undefined){
			datos.fecha_cierre = fecha.getDate()+"-"+(fecha.getMonth()+1)+"-"+fecha.getFullYear();
		// }
	});
	$("#fecha_inicio, #fecha_cierre, input[name=tipo_reporte], input[name=tipo_dias]").change( function(){
		//calculo de dias
		// var fecha = $("#fecha_inicio").val();
		// var tipo_reporte = $("input[name='tipo_reporte']:checked");

		// console.log("--->"+JSON.stringify(datos));
		// console.log("Calculando los dias...\nEnviando (0) : " +datos.fecha+ " (1) : "+datos.tipo_reporte );

		var parametros = {};
		parametros.fecha_inicio = (datos.fecha_inicio != undefined) ? datos.fecha_inicio : "";
		parametros.fecha_cierre = (datos.fecha_cierre != undefined) ? datos.fecha_cierre : "";
		parametros.tipo_reporte = (datos.tipo_reporte != undefined) ? datos.tipo_reporte : "";

		if ( parametros.fecha_inicio != "" && parametros.tipo_reporte != "" ){
			// fecha_inicio
			// tipo_reporte
			// fecha_cierre - opcional

			console.log("parametros :\n"+JSON.stringify({ fecha_inicio : parametros.fecha_inicio, fecha_cierre : parametros.fecha_cierre, tipo_reporte : parametros.tipo_reporte, tipo_dias : $("input[name='tipo_dias']:checked").val() }));
			
			$.ajax({
				type : "POST",
				url  : "recursos/",
				data : { fecha_inicio : parametros.fecha_inicio, fecha_cierre : parametros.fecha_cierre, tipo_reporte : parametros.tipo_reporte, tipo_dias : $("input[name='tipo_dias']:checked").val() },
			}).done( function(respuesta){
				console.log("RES : "+respuesta);

				datos_reporte = JSON.parse(respuesta);
				generaReporte();

				//agregando los valores obtenidos en los campos vacios
				console.log("---------------------\n"+datos.fecha_cierre+" : "+datos_reporte.periodo[1]+"\n---------------------\n")
				// if (parametros.fecha_cierre == "" || parametros.fecha_cierre.length < 1){
				if (typeof datos.fecha_cierre == "undefined"){
					//revisar el formato que acepta el input[date]
					// console.log("---> no habia fecha cierre")
					// console.log("e ok : "+datos_reporte.periodo[1]+" -> "+ cambiaFormatoFecha('Y-m-d', datos_reporte.periodo[1] ) );
					$("#fecha_cierre").val( cambiaFormatoFecha('Y-m-d', datos_reporte.periodo[1] ) );
				}
			});
		} //if
	});
	$("#entrada_rango").change( function (){
		var rango = parseInt($(this).val());
		generaHoraEntrada(rango);
	});
	$("#horas_dia").change( function (){
		var maximo = 47;
		var horas_dia = $(this).val();
		if ( horas_dia.length > 0){
			maximo = 47 - (horas_dia * 2);
		}
		$("#entrada_rango").attr("max", maximo);
	});
	$("#entrada_rango, #horas_dia").change( function(){
		generaReporte();
	});
	$("#periodo_inicio, #periodo_cierre, #periodo_mes, #periodo_horas").click( function(){
		$(this).select();
	});
	$("#fecha_inicio").focus();
	$("input[name='tipo_dias']")[0].setAttribute("checked","checked");
}); //ready

function generaHoraEntrada (rango){
	// console.log("generaHoraEntrada : "+rango);
	var entrada = agregaCeros( parseInt(rango/2).toString() );
	if (rango%2	!= 0){
		//parametros
		entrada += ":30";
	} else {
		entrada += ":00";
	}
	// console.log("hora_entrada ("+rango+")->"+entrada);
	$("#entrada").html(entrada);
} //generaHoraEntrada

function generaReporte (){

	var encabezados = ['No.', 'Fecha', 'Hora de entrada', 'Hora de salida', 'Horas por día'];

	//ingresando la información

	$("#periodo_inicio").val( datos_reporte.periodo[0]);
	$("#periodo_cierre").val( datos_reporte.periodo[1]);
	$("#periodo_mes").val( datos_reporte.mes);

	$("#reporte").html("<table></table>");
	var reporte = $("#reporte table").get(0);
	
	var hora_entrada = $("#entrada").html();
	var horas_dia = $("#horas_dia").val();
	
	var hora_salida = "";
	if (hora_entrada.length > 0 && horas_dia.length > 0){
		horas_dia = parseInt( $("#horas_dia").val() );

		hora_salida = hora_entrada.split(":");
		horas_dia = parseInt(hora_salida[0])+horas_dia;
		horas_dia = horas_dia.toString();
		hora_salida = agregaCeros(horas_dia)+":"+hora_salida[1];
		
		horas_dia = $("#horas_dia").val();
	}

	reporte.insertRow(0);
	for (var i = 0; i < encabezados.length; i++){
		reporte.rows[0].insertCell(i);
		reporte.rows[0].cells[i].innerHTML = encabezados[i];
	}
	datos_reporte.dias_totales = 0;
	for (var i = 0; i < datos_reporte.numero_dias_reporte; i++){
		reporte.insertRow(i+1);
		for (var j = 0; j < encabezados.length; j++){
			reporte.rows[i+1].insertCell(j);
		}
		reporte.rows[i+1].cells[0].innerHTML = i+1;
		reporte.rows[i+1].cells[1].innerHTML = datos_reporte.dias[i][0];
		if (datos_reporte.dias[i][1]){
			reporte.rows[i+1].cells[2].setAttribute("colspan","3");
			reporte.rows[i+1].cells[2].innerHTML = "DIA FESTIVO";
			reporte.rows[i+1].cells[3].setAttribute("class","oculto");
			reporte.rows[i+1].cells[4].setAttribute("class","oculto");
		} else {
			reporte.rows[i+1].cells[2].innerHTML = (hora_entrada.length > 0) ? hora_entrada : "";
			reporte.rows[i+1].cells[3].innerHTML = (hora_salida.length > 0) ? hora_salida : "";
			reporte.rows[i+1].cells[4].innerHTML = (horas_dia.length > 0) ? horas_dia : "";
			datos_reporte.dias_totales++;
		}
	}
	$("#periodo_horas").val( datos_reporte.dias_totales * parseInt(horas_dia) );
}  //generaReporte

function cambiaFormatoFecha (formato_salida, fecha){
	var fecha_salida = fecha.split("-");

	switch (formato_salida){
		case "Y-m-d":
			fecha_salida = new Date( fecha_salida[2], fecha_salida[1]-1, fecha_salida[0]);
			fecha_salida = fecha_salida.getFullYear()+"-"+agregaCeros( fecha_salida.getMonth()+1 )+"-"+agregaCeros( fecha_salida.getDate() );
			break;
		case "d-m-Y":
			//constructor yyyy, mm, dd, hh , MM, ss, ms
			// console.log("y :"+fecha_salida[0]+" m : "+fecha_salida[1]+" d: "+fecha_salida[2])
			fecha_salida = new Date( fecha_salida[0], fecha_salida[1]-1, fecha_salida[2]);
			fecha_salida = agregaCeros( fecha_salida.getDate() )+"-"+agregaCeros( fecha_salida.getMonth()+1 )+"-"+fecha_salida.getFullYear();
			// fecha_salida = fecha_salida.getFullYear()+"-"+agregaCeros( fecha_salida.getMonth()+1 )+"-"+agregaCeros( fecha_salida.getDate() );
			break;
	}
	// console.log("entrada : "+fecha+"\nsalida : "+fecha_salida);
	return fecha_salida;
} //cambiaFormatoFecha

function agregaCeros (numero){
	switch (typeof numero){
		case "number":
			numero = (numero < 10) ? "0"+numero : numero;
			break;
		case "string":
			numero = (numero.length < 2) ? "0"+numero : numero;
			break;
	}	
	return numero;
} //agregaCeros