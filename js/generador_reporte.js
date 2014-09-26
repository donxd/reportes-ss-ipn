$(document).ready(function(){
	//inicializacion
	$(":input").change( function (){
		//calculo de dias
		var fecha = $("#fecha").val();
		var tipo_reporte = $("input[name='tipo_reporte']:checked");

		if ( fecha.length > 0 && 
				tipo_reporte.length > 0){
			// fecha
			// tipo_reporte
			
			console.log("Calculando los dias...\nEnviando (0) : " +fecha+ " (1) : "+tipo_reporte.val() );
			$.ajax({
				type : "POST",
				url  : "recursos/",
				data : { fecha_inicio : fecha, tipo_reporte : tipo_reporte.val() },
			}).done( function(respuesta){
				console.log(respuesta);
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
	$("#fecha").focus();
}); //ready
var datos;