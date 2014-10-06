var datos;
var datos_reporte;
$(document).ready(function(){
	//inicializacion
	$("#fecha_inicio").change( function (){
		// console.log("Inicializando periodo...");
		var fecha = $("#fecha_inicio").val();
		fecha = fecha.split("-");
		//constructor yyyy, mm, dd, hh , MM, ss, ms
		// console.log("fecha-texto : "+JSON.stringify(fecha));
		fecha = new Date( fecha[0], fecha[1]-1, fecha[2]);

		// console.log("cuadro : "+$("#fecha_inicio").val()+" fecha : "+fecha.toString());

		var tipo = (fecha.getDate() > 15) ? 1 : 0;
		// console.log("cuadro : "+$("#fecha_inicio").val()+" dia : "+fecha.getDate()+" tipo : "+tipo);
		// console.log("dia : "+fecha.getDate()+" tipo : "+tipo);
		
		
		// $("input[name='tipo_reporte']").removeAttr('checked');
		var tipos = $("input[name='tipo_reporte']");
		for (var i = 0; i < tipos.size(); i++){
			$("input[name='tipo_reporte']")[i].removeAttribute('checked');
		}
		$("input[name='tipo_reporte']")[tipo].setAttribute("checked","checked");
		// $("input[name='tipo_reporte']")[tipo].attr("checked","checked");

		datos = { 
			fecha : fecha.getDate()+"-"+(fecha.getMonth()+1)+"-"+fecha.getFullYear(), 
			tipo_reporte : $("input[name='tipo_reporte']:checked").val() 
		};

		// datos.fecha = fecha;
		// datos.tipo_reporte = tipo;
	});
	$(":input").change( function (){
		//calculo de dias
		// var fecha = $("#fecha_inicio").val();
		// var tipo_reporte = $("input[name='tipo_reporte']:checked");

		// console.log("--->"+JSON.stringify(datos));
		// console.log("Calculando los dias...\nEnviando (0) : " +datos.fecha+ " (1) : "+datos.tipo_reporte );

		if ( datos.fecha != "" && datos.tipo_reporte != null && 
				datos.fecha.length > 0 ){
			// fecha
			// tipo_reporte
			
			$.ajax({
				type : "POST",
				url  : "recursos/",
				data : { fecha_inicio : datos.fecha, tipo_reporte : datos.tipo_reporte, tipo_dias : $("input[name='tipo_dias']:checked").val() },
			}).done( function(respuesta){
				console.log("RES : "+respuesta);

				datos_reporte = JSON.parse(respuesta);
				generaReporte();

				// console.log("fc : "+respuesta.periodo[1].replace(/-/g,"\/") );

				try {
					// $("#fecha_cierre").setAttribute("value", respuesta.periodo[1].replace(/-/g,"\/") );
					// $("#fecha_cierre").val( respuesta.periodo[1].replace(/-/g,"\/") );
					$("#fecha_cierre").val( datos_reporte.periodo[1] );
					// $("#fecha_cierre").setAttribute("value", respuesta.periodo[1] );
				} catch (error) {
					console.log("Error ingresando fecha de cierre");
				}

				//datos = JSON.parse(respuesta);
			});
		} //if
		//construccion de la tabla
		if ( $("#entrada").val().length > 0 && 
				$("#horas_dia").val().length > 0){
			// entrada
			// horas_dia
			console.log("Construyendo la tabla...");	
		} //if
	});
	$("#periodo_inicio, #periodo_cierre, #periodo_mes").click( function (){
		$(this).select();
	});
	$("#fecha_inicio").focus();
	$("input[name='tipo_dias']")[0].setAttribute("checked","checked");
}); //ready

function generaReporte (){

	var encabezados = ['No.', 'Fecha', 'Hora de entrada', 'Hora de salida', 'Horas por día'];

	//ingresando la información

	$("#periodo_inicio").val( datos_reporte.periodo[0]);
	$("#periodo_cierre").val( datos_reporte.periodo[1]);
	$("#periodo_mes").val( datos_reporte.mes);

	$("#reporte").html("<table></table>");
	var reporte = $("#reporte table").get(0);
	
	var hora_entrada = $("#entrada").val();
	var horas_dia = $("#horas_dia").val();
	
	var hora_salida = "";
	if (hora_entrada.length > 0 && horas_dia.length > 0){
		horas_dia = parseInt( $("#horas_dia").val() );

		hora_salida = hora_entrada.split(":");
		horas_dia = parseInt(hora_salida[0])+horas_dia;
		horas_dia = horas_dia.toString();
		hora_salida = ( (horas_dia.length < 2) ? "0"+horas_dia : horas_dia )+":"+hora_salida[1];
		
		horas_dia = $("#horas_dia").val();
	}

	reporte.insertRow(0);
	for (var i = 0; i < encabezados.length; i++){
		reporte.rows[0].insertCell(i);
		reporte.rows[0].cells[i].innerHTML = encabezados[i];
	}
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
		}
	}
}  //generaReporte