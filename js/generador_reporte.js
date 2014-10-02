var datos;
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

		datos = { fecha : fecha.getDate()+"-"+(fecha.getMonth()+1)+"-"+fecha.getFullYear(), tipo_reporte : $("input[name='tipo_reporte']:checked").val() };

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
				data : { fecha_inicio : datos.fecha, tipo_reporte : datos.tipo_reporte },
			}).done( function(respuesta){
				console.log("RES : "+respuesta);

				respuesta = JSON.parse(respuesta);

				// console.log("fc : "+respuesta.periodo[1].replace(/-/g,"\/") );

				try {
					// $("#fecha_cierre").setAttribute("value", respuesta.periodo[1].replace(/-/g,"\/") );
					// $("#fecha_cierre").val( respuesta.periodo[1].replace(/-/g,"\/") );
					$("#fecha_cierre").val( respuesta.periodo[1] );
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
	$("#fecha_inicio").focus();
}); //ready